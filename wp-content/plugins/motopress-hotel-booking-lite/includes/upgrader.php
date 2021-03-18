<?php

namespace MPHB;

use MPHB\iCal\BackgroundProcesses\QueuedSynchronizer;

class Upgrader {

	const OPTION_DB_VERSION			 = 'mphb_db_version';
	const OPTION_DB_VERSION_HISTORY	 = 'mphb_db_version_history';

	private $isNeedUpgrade = false;

	/**
	 *
	 * @var Upgrades\BackgroundBookingUpgrader_2_0_0
	 */
	private $bgBookingUpgrader2_0_0;

	/**
	 *
	 * @var Upgrades\BackgroundBookingUpgrader_2_2_0
	 */
	private $bgBookingUpgrader2_2_0;

	/**
	 *
	 * @var Upgrades\BackgroundBookingUpgrader_2_3_0
	 */
	private $bgBookingUpgrader2_3_0;

	/**
	 *
	 * @var Upgrades\BackgroundUpgrader
	 */
	private $bgUpgrader;

	/**
	 *
	 * @var array
	 */
	private $upgrades = array(
		'1.1.0'	 => array(
			'fixForV1_1_0'
		),
		'2.0.0'	 => array(
			'fixForV2_0_0'
		),
		'2.2.0'	 => array(
			'fixForV2_2_0',
			'fixSessionOptions'
		),
		'2.3.0'	 => array(
			'fixForV2_3_0',
			'fixGlobalRule',
			'fixCleanOldRules'
		),
		'2.4.2'	 => array(
			'flushRewriteRules'
		),
		'3.0.0' => array(
			'fixForV3_0_0'
		),
		'3.0.2' => array(
			'fixForV3_0_2'
		),
        '3.2.0' => array(
            'fixForV3_2_0'
        ),
        '3.4.0' => array(
            'markImported',
            'improveStats',
            'moveSyncUrls'
        ),
        '3.5.0' => array(
            'createUploadsDir'
        ),
        '3.6.0' => array(
            'removeOutdatedStripeOptions'
        ),
        '3.6.1' => array(
            'startAutodeleteOfSyncLogs'
        ),
        '3.7.0' => array(
            'hideUpgradeNotice'
        )
	);

	public function __construct(){

		$this->bgBookingUpgrader2_0_0	 = new Upgrades\BackgroundBookingUpgrader_2_0_0();
		$this->bgBookingUpgrader2_2_0	 = new Upgrades\BackgroundBookingUpgrader_2_2_0();
		$this->bgBookingUpgrader2_3_0	 = new Upgrades\BackgroundBookingUpgrader_2_3_0();
		$this->bgUpgrader				 = new Upgrades\BackgroundUpgrader();

		$this->checkVersion();

		add_action( 'init', array( $this, 'upgrade' ) );
		add_action( 'admin_init', array( $this, 'showUpgradeNotice' ) );
		add_action( 'mphb_import_end', array( $this, 'upgradeAfterImport' ) );
	}

	public function checkVersion(){
		$dbVersion = $this->bgUpgrader->isInProgress() ? $this->getScheduledVersion() : $this->getCurrentDBVersion( true );

        if ( is_null( $dbVersion ) ) {
            $this->updateDBVersion();

        } else if ( version_compare( MPHB()->getVersion(), $dbVersion, '>' ) ) {
			$this->isNeedUpgrade = true;

            if ( version_compare( '2.0.0', $this->getCurrentDBVersion(), '>' ) ) {
                $this->blockNewBookings();
            }
		}
	}

	private function blockNewBookings(){
		add_filter( 'mphb_block_booking', '__return_true' );
	}

	public function upgrade(){

		if ( !$this->isNeedUpgrade ) {
			return;
		}

		ignore_user_abort( true );
		mphb_set_time_limit( 0 );

		$newBatchesCount = 0;
		foreach ( $this->upgrades as $upgradeVersion => $callbacks ) {
			if ( version_compare( $this->getScheduledVersion(), $upgradeVersion, '<' ) ) {
				$this->bgUpgrader->data( $callbacks )->save()->data( array() );
				$newBatchesCount += count( $callbacks );
				$this->setScheduledVersion( $upgradeVersion );
			}
		}

		$this->bgUpgrader->push_to_queue( 'complete' )->save();
		$this->setScheduledVersion( MPHB()->getVersion() );
		$newBatchesCount++;

		$this->setTotalQueueSize( $this->getTotalQueueSize() + $newBatchesCount );

		$this->bgUpgrader->dispatch();

		return;
	}

    /**
     * @see \MPHB\ActionsHandler::forceUpgrade()
     *
     * @since 3.7.0
     */
    public function forceUpgrade()
    {
        do_action($this->bgUpgrader->getIdentifier() . '_cron');
        do_action($this->bgBookingUpgrader2_0_0->getIdentifier() . '_cron');
        do_action($this->bgBookingUpgrader2_2_0->getIdentifier() . '_cron');
        do_action($this->bgBookingUpgrader2_3_0->getIdentifier() . '_cron');

		if (!$this->bgUpgrader->isInProgress()) {
            MPHB()->notices()->hideNotice('force_upgrade');
		}

        wp_safe_redirect(admin_url());
        exit;
    }

    /**
     * @return int
     *
     * @since 3.7.0
     */
    public function getProgress()
    {
        if (!$this->bgUpgrader->isInProgress()) {
            return 100;
        }

        $totalItemsCount     = max($this->getTotalQueueSize(), $this->getQueueSize());
        $completedItemsCount = $totalItemsCount - $this->getQueueSize();

        // Fix bug when extra batches lead to negative procent (is there still a such bug?)
        $completedItemsCount = max(0, $completedItemsCount);

        // Calc progress
        if ($totalItemsCount != 0) {
            $progress = round( ($completedItemsCount / $totalItemsCount) * 100 );
            return min($progress, 100);
        } else {
            return 100;
        }
    }

	public function showUpgradeNotice()
    {
		if ($this->bgUpgrader->isInProgress()) {
            MPHB()->notices()->showNotice('force_upgrade');
		}
	}

	/**
	 * fix for 1.1.0
	 * - Change abandon cron name
	 * - Change option name for Cancellation Page
	 */
	public function fixForV1_1_0(){

		$deprecatedAction = 'mphb_abandon_bookings';
		if ( wp_next_scheduled( $deprecatedAction ) ) {
			wp_clear_scheduled_hook( $deprecatedAction );
			MPHB()->cronManager()->getCron( 'abandon_booking_pending_user' )->schedule();
		}

		$this->changeOptionName( 'mphb_user_cancel_redirect', 'mphb_user_cancel_redirect_page' );

		$this->updateDBVersion( '1.1.0' );

		return false;
	}

	/**
	 * fix for 2.0.0
	 * - update bookings structure in db ( create reserved rooms )
	 *
	 * @return string|boolean False when update completed, function name otherwise.
	 */
	public function fixForV2_0_0(){

		if ( $this->bgBookingUpgrader2_0_0->isInProgress() ) {
			$this->bgUpgrader->pause();
			return __FUNCTION__;
		}

		$oldBookingsAtts = array(
			'posts_per_page'		 => -1,
			'post_type'				 => 'mphb_booking',
			'suppress_filters'		 => false,
			'ignore_sticky_posts'	 => true,
			'fields'				 => 'ids',
			'post_status'			 => 'any',
			'meta_query'			 => array(
				array(
					'key'		 => 'mphb_room_id',
					'compare'	 => 'EXISTS',
				)
			),
			'orderby'				 => 'ID',
			'order'					 => 'ASC'
		);

		$oldBookingsIds = get_posts( $oldBookingsAtts );

		if ( empty( $oldBookingsIds ) ) {
			// upgrade 2.0.0 completed
			return false;
		}

		$oldBookingsChunked = array_chunk( $oldBookingsIds, Upgrades\BackgroundBookingUpgrader_2_0_0::BATCH_SIZE );

		foreach ( $oldBookingsChunked as $bookings ) {
			$this->bgBookingUpgrader2_0_0->data( $bookings )->save();
		}

		$this->setTotalQueueSize( $this->getTotalQueueSize() + count( $oldBookingsChunked ) );

		$this->bgUpgrader->waitAction( $this->bgBookingUpgrader2_0_0->getIdentifier() . '_complete' );

		$this->bgBookingUpgrader2_0_0->dispatch();

		$this->bgUpgrader->pause();

		return __FUNCTION__;
	}

	/**
	 * fix for 2.2.0
	 * - generate UID for all  ( create reserved rooms )
	 *
	 * @return string|boolean False when update completed, function name otherwise.
	 */
	public function fixForV2_2_0(){

		if ( $this->bgBookingUpgrader2_2_0->isInProgress() ) {
			$this->bgUpgrader->pause();
			return __FUNCTION__;
		}

		$roomsWithNoUid = get_posts( array(
			'posts_per_page'		 => -1,
			'post_type'				 => 'mphb_reserved_room',
			'suppress_filters'		 => false,
			'ignore_sticky_posts'	 => true,
			'fields'				 => 'ids',
			'post_status'			 => 'any',
			'meta_query'			 => array(
				array(
					'key'		 => '_mphb_uid',
					'compare'	 => 'NOT EXISTS',
				)
			),
			'orderby'				 => 'ID',
			'order'					 => 'ASC'
			) );

		if ( empty( $roomsWithNoUid ) ) {
			// upgrade 2.2.0 completed
			return false;
		}

		$batches = array_chunk( $roomsWithNoUid, Upgrades\BackgroundBookingUpgrader_2_2_0::BATCH_SIZE );

		foreach ( $batches as $batch ) {
			$this->bgBookingUpgrader2_2_0->data( $batch )->save();
		}

		$this->setTotalQueueSize( $this->getTotalQueueSize() + count( $batches ) );

		$this->bgUpgrader->waitAction( $this->bgBookingUpgrader2_2_0->getIdentifier() . '_complete' );

		$this->bgBookingUpgrader2_2_0->dispatch();

		$this->bgUpgrader->pause();

		return __FUNCTION__;
	}

	/**
	 *
	 * @global \WPDB $wpdb
	 */
	public function fixSessionOptions(){
		global $wpdb;

		// Length of prefix _mphb_wp_session_expires_
		$prefixLength = 25;

		$expirationKeys	 = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_mphb_wp_session_expires_%'" );
		$notIn			 = $expirationKeys;
		foreach ( $expirationKeys as $optionName ) {
			$session_id	 = substr( $optionName, $prefixLength );
			$notIn[]	 = "_mphb_wp_session_$session_id";
		}

		$notIn = join( "','", $notIn );

		$wpdb->query( "DELETE FROM $wpdb->options WHERE ( option_name LIKE '_mphb_wp_session_%' ) AND ( option_name NOT IN ('$notIn') )" );

		return false;
	}

	/**
	 * Fix for 2.3.0
	 * - generate reservation rules for global booking rules;
	 * - generate new custom rules (with type ID and room number);
	 * - delete global booking rules and old custom rules.
	 *
	 * @return string|boolean False when update completed, function name otherwise.
	 */
	public function fixForV2_3_0(){
		if ( $this->bgBookingUpgrader2_3_0->isInProgress() ) {
			$this->bgUpgrader->pause();
			return __FUNCTION__;
		}

		$customRules = get_option( 'mphb_custom_booking_rules', array() );

		if ( empty( $customRules ) ) {
			return false;
		}

		$this->bgBookingUpgrader2_3_0->data( $customRules['items'] )->save();

		$this->setTotalQueueSize( $this->getTotalQueueSize() + 1 );

		$this->bgUpgrader->waitAction( $this->bgBookingUpgrader2_3_0->getIdentifier() . '_complete' );

		$this->bgBookingUpgrader2_3_0->dispatch();

		$this->bgUpgrader->pause();

		return false;
	}

	public function fixGlobalRule(){
		$globalRules = array(
			'check_in_days'   => get_option( 'mphb_global_check_in_days', array_keys( \MPHB\Utils\DateUtils::getDaysList() ) ),
			'check_out_days'  => get_option( 'mphb_global_check_out_days', array_keys( \MPHB\Utils\DateUtils::getDaysList() ) ),
			'min_stay_length' => (int)get_option( 'mphb_global_min_days', 1 ),
			'max_stay_length' => (int)get_option( 'mphb_global_max_days', 15 )
		);

		// Save parts of reservation rules
		foreach ( $globalRules as $option => $value ) {
			// No need to save default values for "min_stay_length",
			// "check_in_days" and "check_out_days"
			if ( $option == 'min_stay_length' ) {
				if ( $value == 1 ) {
					continue;
				}
			} else if ( $option == 'check_in_days' || $option == 'check_out_days' ) {
				if ( count( $value ) == 7 ) {
					continue;
				}
			}

			$value = array( array(
				$option			 => $value,
				'room_type_ids'	 => array( 0 ),
				'season_ids'	 => array( 0 )
			) );

			update_option( 'mphb_' . $option, $value, 'no' );

		}

		return false;
	}

	public function fixCleanOldRules(){
		delete_option( 'mphb_global_min_days' );
		delete_option( 'mphb_global_max_days' );
		delete_option( 'mphb_global_check_in_days' );
		delete_option( 'mphb_global_check_out_days' );
		delete_option( 'mphb_custom_booking_rules' );

		return false;
	}

    public function flushRewriteRules(){
        flush_rewrite_rules();

        return false;
    }

	/**
	 * Delete unused rules options
	 */
    public function fixForV3_0_0(){
    	delete_option( 'mphb_booking_rules_reservation' );
	    delete_option( 'mphb_booking_rules_seasons' );
	    delete_option( 'mphb_booking_rules_season_priorities');

		return false;
    }

	/**
     * 1) Create new tables.
	 * 2) Remove outdated sync options.
	 * 3) Move value from "From Email" to "Hotel Administrator Email".
	 */
	public function fixForV3_0_2(){
		global $wpdb;

        // 1) Create new tables
        \HotelBookingPlugin::createTables();

		// 2) Remove outdated sync options (but leave old logs, just in case)
		$wpdb->query("DELETE FROM {$wpdb->options}"
            . " WHERE (`option_name` LIKE '%mphb_ical_upload%' OR `option_name` LIKE '%mphb_ical_sync%')"
            . " AND `option_name` != 'mphb_ical_sync_rooms_queue_processed_data'");

		// 3) Move "From Email" to "Hotel Administrator Email"
		$fromEmail = get_option( 'mphb_email_from_email', '' );

		update_option( 'mphb_email_hotel_admin_email', $fromEmail );
		update_option( 'mphb_email_from_email', '' );

		return false;
	}

    /**
     * Remove outdated sync logs.
     */
    public function fixForV3_2_0()
    {
		global $wpdb;

        // Remove outdated sync logs
		$wpdb->query("DELETE FROM {$wpdb->options} WHERE `option_name` = 'mphb_ical_sync_rooms_queue_processed_data'");

		return false;
    }

    /**
     * Mark imported bookings in version 3.4.0.
     */
    public function markImported()
    {

        return false;
    }

    /**
     * Rename all columns from stat_* to import_* and add clean_* columns.
     */
    public function improveStats()
    {
        global $wpdb;

        $wpdb->query("ALTER TABLE {$wpdb->prefix}mphb_sync_stats"
            . " CHANGE COLUMN stat_total import_total INT NOT NULL DEFAULT 0,"
            . " CHANGE COLUMN stat_succeed import_succeed INT NOT NULL DEFAULT 0,"
            . " CHANGE COLUMN stat_skipped import_skipped INT NOT NULL DEFAULT 0,"
            . " CHANGE COLUMN stat_failed import_failed INT NOT NULL DEFAULT 0,"
            . " ADD clean_total INT NOT NULL DEFAULT 0,"
            . " ADD clean_done INT NOT NULL DEFAULT 0,"
            . " ADD clean_skipped INT NOT NULL DEFAULT 0");

        return false;
    }

    public function moveSyncUrls()
    {
        global $wpdb;

        // Create new table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mphb_sync_urls ("
            . " url_id INT NOT NULL AUTO_INCREMENT,"
            . " room_id INT NOT NULL,"
            . " sync_id VARCHAR(32) NOT NULL,"
            . " calendar_url VARCHAR(250) NOT NULL,"
            . " PRIMARY KEY (url_id)"
            . ") CHARSET=utf8 AUTO_INCREMENT=1");


        return false;
    }

    /**
     * @return mixed
     *
     * @since 3.5.0
     */
    public function createUploadsDir()
    {
        mphb_create_uploads_dir();

        return false;
    }

    /**
     * @return mixed
     *
     * @since 3.6.0
     */
    public function removeOutdatedStripeOptions()
    {
        delete_option('mphb_payment_gateway_stripe_checkout_image_url');
        delete_option('mphb_payment_gateway_stripe_allow_remember_me');
        delete_option('mphb_payment_gateway_stripe_need_billing_address');
        delete_option('mphb_payment_gateway_stripe_use_bitcoin');

        return false;
    }

    /**
     * @return mixed
     *
     * @since 3.6.1
     */
    public function startAutodeleteOfSyncLogs()
    {

        return false;
    }

    /**
     * @since 3.7.0
     */
    public function hideUpgradeNotice()
    {
        // We need this method to create non-empty batch. But there is no need
        // no hide the notice here - Upgrader will hide it on complete()
        return false;
    }

	/**
	 *
	 * @param string $version
	 */
	public function updateDBVersion( $version = null ){

		if ( is_null( $version ) ) {
			$version = MPHB()->getVersion();
		}

		update_option( self::OPTION_DB_VERSION, $version );

		if ( version_compare( $this->getCurrentDBVersion(), $version, '!=' ) ) {
			$this->addDBVersionToHistory( $version );
		}

		if ( version_compare( $this->getScheduledVersion(), $version, '<=' ) ) {
			$this->setScheduledVersion( false );
		}
	}

	/**
	 * @return string
     *
     * @since 3.5.1 added optional parameter $returnNull.
	 */
	private function getCurrentDBVersion( $returnNull = false ){
        $defaultVersion = $returnNull ? null : MPHB()->getVersion();
		return get_option( self::OPTION_DB_VERSION, $defaultVersion );
	}

	public function setScheduledVersion( $version = null ){
		if ( !empty( $version ) ) {
			update_option( 'mphb_scheduled_version', $version );
		} else {
			delete_option( 'mphb_scheduled_version' );
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getScheduledVersion(){
		return get_option( 'mphb_scheduled_version', $this->getCurrentDBVersion() );
	}

	/**
	 *
	 * @param string $version
	 */
	private function addDBVersionToHistory( $version ){
		$dbVersionHistory	 = get_option( self::OPTION_DB_VERSION_HISTORY, array() );
		$dbVersionHistory[]	 = $version;
		update_option( self::OPTION_DB_VERSION_HISTORY, $dbVersionHistory );
	}

	/**
	 * @todo add support to false value of option
	 *
	 * @param string $oldName
	 * @param string $name
	 */
	private function changeOptionName( $oldName, $name ){
		$optionValue = get_option( $oldName );

		if ( false !== $optionValue ) {
			delete_option( $oldName );
			update_option( $name, $optionValue );
		}
	}

	public function resetDBVersion(){
		delete_option( self::OPTION_DB_VERSION );
	}

	public function upgradeAfterImport(){
		$this->resetDBVersion();
	}

	private function getTotalQueueSize(){
		return (int) get_option( 'mphb_upgrade_queue_size', 0 );
	}

	private function setTotalQueueSize( $size ){
		update_option( 'mphb_upgrade_queue_size', $size );
	}

	private function getQueueSize(){
		return $this->bgUpgrader->getQueueSize() + $this->bgBookingUpgrader2_0_0->getQueueSize();
	}

	public function complete(){
		$this->updateDBVersion( MPHB()->getVersion() );

		// clear total size
		delete_option( 'mphb_upgrade_queue_size' );

        MPHB()->notices()->hideNotice('force_upgrade');
	}

}
