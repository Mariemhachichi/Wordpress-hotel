<?php

/*
 * This plugin contains hooks that allow you to edit, add and move content without needing to edit template files. This method protects against upgrade issues.
 * Alternatively, you can copy template files from './templates/' folder to '/your-theme/hotel-booking/' to override them.
 */

HotelBookingPlugin::setPluginDirPathAndUrl( MPHB_PLUGIN_FILE, ( isset( $plugin ) ? $plugin : null ), ( isset( $network_plugin ) ? $network_plugin : null ) );

class HotelBookingPlugin {

	/**
	 *
	 * @var \MPHB\HotelBookingPlugin
	 */
	private static $instance = null;
	private static $_pluginFile;
	private static $_pluginDirPath;
	private static $_pluginDirUrl;

	/**
	 * Fix for symlinked plugin
	 *
	 * @global string $wp_version
	 * @param string $file
	 * @param string|null $plugin
	 * @param string|null $network_plugin
	 */
	public static function setPluginDirPathAndUrl( $file, $plugin, $network_plugin ){
		global $wp_version;
		if ( version_compare( $wp_version, '3.9', '<' ) && isset( $network_plugin ) ) {
			self::$_pluginFile = $network_plugin;
		} else {
			self::$_pluginFile = MPHB_PLUGIN_FILE;
		}

		$realDirName	 = basename( dirname( self::$_pluginFile ) );
		$symlinkDirName	 = isset( $plugin ) ? basename( dirname( $plugin ) ) : $realDirName;

		self::$_pluginDirPath = plugin_dir_path( self::$_pluginFile );

		if ( version_compare( $wp_version, '3.9', '<' ) ) {
			self::$_pluginDirUrl = plugin_dir_url( $symlinkDirName . '/' . basename( self::$_pluginFile ) );
		} else {
			self::$_pluginDirUrl = plugin_dir_url( self::$_pluginFile );
		}
	}

	private $name;
    /** @since 3.6.0 */
    private $pluginStoreUri; // Plugin URI from plugin headers: motopress.com/...
	private $author;
	private $version;
	private $pluginSlug; // "motopress-hotel-booking" or "motopress-hotel-booking-lite"
	private $productSlug; // Always "motopress-hotel-booking"
	private $productDir; // "motopress-hotel-booking" or "motopress-hotel-booking-lite"
	private $prefix;
	private $pluginDir;
	private $pluginDirUrl;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\SettingsMenuPage
	 */
	private $settingsMenuPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\ShortcodesMenuPage
	 */
	private $shortcodesMenuPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\LanguageMenuPage
	 */
	private $languageMenuPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\RoomsGeneratorMenuPage
	 */
	private $roomsGeneratorMenuPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\CalendarMenuPage
	 */
	private $calendarMenuPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\BookingRulesMenuPage
	 */
	private $bookingRulesPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\TaxesAndFeesMenuPage
	 */
	private $taxesAndFeesPage;

	/**
	 *
	 * @var \MPHB\Admin\MenuPages\iCalMenuPage
	 */
	private $iCalMenuPage;


    /**
     * @var \MPHB\Admin\MenuPages\EditBookingMenuPage
     * @since 3.8
     */
    private $editBookingMenuPage;

	private $attributesMenuPage;

	private $upgradeToPremiumMenuPage;

    /**
     * @var \MPHB\Admin\MenuPages\ReportsMenuPage
     *
     * @since 3.5.0
     */
    private $reportsPage;

    /** @var MPHB\Admin\MenuPages\ExtensionsMenuPage */
    private $extensionsPage;

	/**
	 *
	 * @var \MPHB\CustomPostTypes
	 */
	private $postTypes;

	/**
	 *
	 * @var \MPHB\Session
	 */
	private $session;

	/**
	 *
	 * @var \MPHB\Ajax
	 */
	private $ajax;

	/**
	 *
	 * @var MPHB\Upgrader
	 */
	private $upgrader;


	/**
	 *
	 * @var \MPHB\Wizard
	 */
	private $wizard;

	/**
	 *
	 * @var \MPHB\Importer
	 */
	private $importer;


    /**
     * @var \MPHB\CSV\Bookings\BookingsExporter
     *
     * @since 3.5.0
     */
    private $bookingsExporter;

    /**
     * @var \MPHB\ActionsHandler
     *
     * @since 3.6.0 (replaced the $downloader)
     */
    private $actionsHandler;

	/**
	 *
	 * @var \MPHB\ScriptManagers\PublicScriptManager
	 */
	private $publicScriptManager;

	/**
	 *
	 * @var \MPHB\ScriptManagers\AdminScriptManager
	 */
	private $adminScriptManager;

	/**
	 *
	 * @var \MPHB\ScriptManagers\BlockScriptManager
	 */
	private $blockScriptManager;

	/**
	 *
	 * @var \MPHB\BlocksRender
	 */
	private $blocksRender;

	/**
	 *
	 * @var \MPHB\Emails\Emails
	 */
	private $emails;

	/**
	 *
	 * @var \MPHB\Shortcodes
	 */
	private $shortcodes;

	/**
	 *
	 * @var \MPHB\UserActions\UserActions
	 */
	private $userActions;

	/**
	 *
	 * @var \MPHB\Entities\RoomType
	 */
	private $currentRoomType;

	/**
	 *
	 * @var \MPHB\BookingRules\RulesChecker
	 */
	private $rulesChecker;

	/**
	 *
	 * @var \MPHB\SearchParametersStorage
	 */
	private $searchParametersStorage;

    /**
     * @var \MPHB\ReservationRequest
     *
     * @since 3.5.0
     */
    private $reservationRequest;

	/**
	 *
	 * @var \MPHB\Settings\SettingsRegistry
	 */
	private $settings;

    /**
     * @var \MPHB\Notices;
     */
    private $notices;

	/**
	 *
	 * @var \MPHB\Admin\Menus
	 */
	private $menus;

	/**
	 *
	 * @var \MPHB\Payments\Gateways\GatewayManager
	 */
	private $gatewayManager;

	/**
	 *
	 * @var \MPHB\Payments\PaymentManager
	 */
	private $paymentManager;
	private $ratePersistence;
	private $roomTypePersistence;
	private $roomPersistence;
	private $attributesPersistence;
	private $bookingPersistence;
	private $servicePersistence;
	private $seasonPersistence;
	private $paymentPersistence;
	private $reservedRoomPersistence;
	private $couponPersistence;
	private $bookingRepository;
	private $rateRepository;
	private $roomRepository;
	private $roomTypeRepository;
	private $seasonRepository;
	private $paymentRepository;
	private $reservedRoomRepository;
	private $couponRepository;
    private $syncUrlsRepository;

	/**
	 *
	 * @var \MPHB\Crons\CronManager
	 */
	private $cronManager;

    private $roomTypeMicrodata = null;

	public static function getInstance(){
		if ( !isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->afterConstruct();
		}
		return self::$instance;
	}

	private function __construct(){
		$this->pluginDir		 = self::$_pluginDirPath;
		$this->classesBasePath	 = trailingslashit( $this->getPluginPath( 'includes' ) );
		$this->pluginDirUrl		 = self::$_pluginDirUrl;
        $this->pluginSlug        = 'motopress-hotel-booking-lite';
		$this->productSlug       = 'motopress-hotel-booking';
		$this->productDir		 = basename( dirname( MPHB_PLUGIN_FILE ) );
		$this->prefix			 = 'mphb';

		$pluginData           = $this->getPluginData();
		$this->author         = isset( $pluginData['Author'] ) ? $pluginData['Author'] : '';
		$this->name           = isset( $pluginData['Name'] ) ? $pluginData['Name'] : '';
        $this->pluginStoreUri = isset( $pluginData['PluginURI'] ) ? $pluginData['PluginURI'] : '';
		$this->version        = isset( $pluginData['Version'] ) ? $pluginData['Version'] : '';
	}

    /**
     * @since 3.7.2 added new action - "mphb_loaded".
     */
	public function afterConstruct(){
		$this->includeFiles();
		$this->addActions();

		$globalNamespace	 = 'MPHB\\';
		$this->autoloader	 = new \MPHB\Autoloader( $globalNamespace, $this->classesBasePath );

        $this->actionsHandler = new \MPHB\ActionsHandler();

		// Settings
		$this->settings = new \MPHB\Settings\SettingsRegistry();

		$this->notices = new \MPHB\Notices();

		// Session
		$this->session = new \MPHB\Session();

		$this->translation = new \MPHB\Translation();

		$this->publicScriptManager	 = new \MPHB\ScriptManagers\PublicScriptManager;
		$this->adminScriptManager	 = new \MPHB\ScriptManagers\AdminScriptManager;

        if (function_exists('register_block_type')) {
            $this->blockScriptManager = new \MPHB\ScriptManagers\BlockScriptManager();
            $this->blocksRender = new \MPHB\BlocksRender();
        }

		$this->paymentManager	 = new \MPHB\Payments\PaymentManager();
		$this->gatewayManager	 = new \MPHB\Payments\Gateways\GatewayManager();

		$this->postTypes = new \MPHB\CustomPostTypes();

		$this->initRepositories();
		$this->menus = new MPHB\Admin\Menus();

		$this->createPages();
		$this->initBookingRules();

		$this->shortcodes = new \MPHB\Shortcodes();

		$this->wizard	 = new \MPHB\Wizard();
		$this->importer	 = new \MPHB\Importer();


        $this->bookingsExporter = new \MPHB\CSV\Bookings\BookingsExporter();

		$this->emails		 = new \MPHB\Emails\Emails();
		$this->userActions	 = new \MPHB\UserActions\UserActions;

		$this->cronManager = new MPHB\Crons\CronManager();

		new \MPHB\Fixes();
		new \MPHB\Views\ViewActions();

		// Widgets
		$this->initWidgets();

		$this->searchParametersStorage = new \MPHB\SearchParametersStorage();
        $this->reservationRequest = new \MPHB\ReservationRequest();

		$this->ajax = new \MPHB\Ajax();

		$this->upgrader = new MPHB\Upgrader();


        do_action('mphb_loaded', $this);
	}

	private function initRepositories(){

		$this->ratePersistence			 = new \MPHB\Persistences\RatePersistence( $this->postTypes->rate()->getPostType() );
		$this->roomTypePersistence		 = new \MPHB\Persistences\RoomTypePersistence( $this->postTypes->roomType()->getPostType() );
		$this->roomPersistence			 = new \MPHB\Persistences\RoomPersistence( $this->postTypes->room()->getPostType() );
		$this->attributesPersistence	 = new \MPHB\Persistences\AttributesPersistence( $this->postTypes->attributes()->getPostType() );
		$this->bookingPersistence		 = new \MPHB\Persistences\BookingPersistence( $this->postTypes->booking()->getPostType() );
		$this->servicePersistence		 = new \MPHB\Persistences\CPTPersistence( $this->postTypes->service()->getPostType() );
		$this->seasonPersistence		 = new \MPHB\Persistences\CPTPersistence( $this->postTypes->season()->getPostType() );
		$this->paymentPersistence		 = new \MPHB\Persistences\PaymentPersistence( $this->postTypes->payment()->getPostType() );
		$this->reservedRoomPersistence	 = new \MPHB\Persistences\ReservedRoomPersistence( $this->postTypes->reservedRoom()->getPostType() );
		$this->couponPersistence		 = new \MPHB\Persistences\CPTPersistence( $this->postTypes->coupon()->getPostType() );

		$this->roomTypeRepository		 = new \MPHB\Repositories\RoomTypeRepository( $this->roomTypePersistence );
		$this->roomRepository			 = new \MPHB\Repositories\RoomRepository( $this->roomPersistence );
		$this->rateRepository			 = new \MPHB\Repositories\RateRepository( $this->ratePersistence );
		$this->bookingRepository		 = new \MPHB\Repositories\BookingRepository( $this->bookingPersistence );
		$this->serviceRepository		 = new \MPHB\Repositories\ServiceRepository( $this->servicePersistence );
		$this->seasonRepository			 = new \MPHB\Repositories\SeasonRepository( $this->seasonPersistence );
		$this->paymentRepository		 = new \MPHB\Repositories\PaymentRepository( $this->paymentPersistence );
		$this->reservedRoomRepository	 = new \MPHB\Repositories\ReservedRoomRepository( $this->reservedRoomPersistence );
		$this->couponRepository			 = new \MPHB\Repositories\CouponRepository( $this->couponPersistence );
        $this->syncUrlsRepository        = new \MPHB\Repositories\SyncUrlsRepository();
	}

	private function initBookingRules(){
		$reservationRules	 = $this->settings->bookingRules()->getReservationRules();
		$reservationRules	 = new MPHB\BookingRules\Reservation\ReservationRules( $reservationRules );

		$customRules = $this->settings->bookingRules()->getCustomRules();
		$customRules = new MPHB\BookingRules\Custom\CustomRules( $customRules );

        $bufferRules = $this->settings->bookingRules()->getBufferRules();
        $bufferRules = MPHB\BookingRules\Buffer\BufferRulesList::create( $bufferRules );

		$this->rulesChecker = new MPHB\BookingRules\RulesChecker( $reservationRules, $customRules, $bufferRules );
	}

	private function initWidgets(){
		\MPHB\Widgets\RoomsWidget::init();
		\MPHB\Widgets\SearchAvailabilityWidget::init();
	}

	/**
	 *
	 * @return \MPHB\BookingRules\RulesChecker
	 */
	public function getRulesChecker(){
		return $this->rulesChecker;
	}

	private function createPages(){

		$roomGeneratorAtts = array(
			'capability'	 => 'edit_posts',
			'parent_menu'	 => MPHB()->postTypes()->roomType()->getMenuSlug(),
			'order'			 => 20
		);

		$this->roomsGeneratorMenuPage = new \MPHB\Admin\MenuPages\RoomsGeneratorMenuPage( 'mphb_rooms_generator', $roomGeneratorAtts );

		$settingsAtts = array(
			'parent_menu'	 => MPHB()->postTypes()->roomType()->getMenuSlug(),
			'order'			 => 30
		);

		$this->settingsMenuPage = new \MPHB\Admin\MenuPages\SettingsMenuPage( 'mphb_settings', $settingsAtts );

		$languageAtts = array(
			'parent_menu'	 => MPHB()->postTypes()->roomType()->getMenuSlug(),
			'order'			 => 35,
		);

		$this->languageMenuPage = new \MPHB\Admin\MenuPages\LanguageMenuPage( 'mphb_language', $languageAtts );

		$shortcodesAtts = array(
			'capability'	 => 'edit_pages',
			'parent_menu'	 => MPHB()->postTypes()->roomType()->getMenuSlug(),
			'order'			 => 40,
		);

		$this->shortcodesMenuPage = new \MPHB\Admin\MenuPages\ShortcodesMenuPage( 'mphb_shortcodes', $shortcodesAtts );

		$calendarAtts = array(
			'order' => 50
		);

		$this->calendarMenuPage = new \MPHB\Admin\MenuPages\CalendarMenuPage( 'mphb_calendar', $calendarAtts );

		$bookingRulesSettings = array(
			'order' => 70
		);

		$this->bookingRulesPage = new \MPHB\Admin\MenuPages\BookingRulesMenuPage( 'mphb_booking_rules', $bookingRulesSettings );

		$taxesAndFeesSettings = array(
			'order' => 90
		);

		$this->taxesAndFeesPage = new \MPHB\Admin\MenuPages\TaxesAndFeesMenuPage( 'mphb_taxes_and_fees', $taxesAndFeesSettings );

		$iCalSettings = array(
			'order' => 110
		);

		$this->iCalMenuPage = new \MPHB\Admin\MenuPages\iCalMenuPage( 'mphb_ical', $iCalSettings );


        $this->editBookingMenuPage = new \MPHB\Admin\MenuPages\EditBookingMenuPage( 'mphb_edit_booking', array( 'order' => 150, 'parent_menu' => 'none' ) );

		$attributesSettings = array('order' => 9, 'parent_menu' => $this->postTypes()->roomType()->getMenuSlug());

		$this->attributesMenuPage = new \MPHB\Admin\MenuPages\AttributesMenuPage('mphb_room_attribute', $attributesSettings);

		$upgradeToPremiumSettings = array( 'order' => 200 );

		$this->upgradeToPremiumMenuPage = new \MPHB\Admin\MenuPages\UpgradeToPremiumMenuPage( 'mphb_premium', $upgradeToPremiumSettings );

        $reportsPageSettings = array(
            'order' => 170
        );

        $this->reportsPage = new \MPHB\Admin\MenuPages\ReportsMenuPage('mphb_reports', $reportsPageSettings);

        $extensionsPageSettings = array(
            'order' => 180
        );

        $this->extensionsPage = new \MPHB\Admin\MenuPages\ExtensionsMenuPage( 'mphb_extensions', $extensionsPageSettings );
	}

	public function requireOnce( $relativePath ){
		require_once $this->getPluginPath( $relativePath );
	}

	public function includeFiles(){

		// Functions
		$this->requireOnce( 'functions.php' );
		$this->requireOnce( 'template-functions.php' );
		$this->requireOnce( 'includes/attribute-functions.php' );
		$this->requireOnce( 'includes/autoloader.php' );
		$this->requireOnce( 'includes/libraries/wp-session-manager/wp-session.php' );

		// WP Backgound Processing
		$this->requireOnce( 'includes/libraries/wp-background-processing/wp-background-processing.php' );
	}

	public function addActions(){
		add_action( 'plugins_loaded', array( $this, 'loadTextdomain' ) );
		add_action( 'admin_init', array( $this, 'initAutoUpdater' ), 9 );

//        add_action( 'wp', array( $this, 'setupRoomTypeMicrodata' ) );
//        add_action( 'wp_head', array( $this, 'pushRoomTypeMicrodata' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueuePublicScripts' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ), 11 );
		add_action( 'the_post', array( $this, 'setCurrentRoomType' ) );

		/**
		 *
		 * @since 3.9.4
		 */
		if ( version_compare( get_bloginfo( 'version' ), '5.1', '>=' ) ) {
			add_action( 'wp_insert_site', array( $this, 'createNewBlog' ) );
		} else {
			add_action( 'wpmu_new_blog', array( $this, 'createNewBlog' ) );
		}

		/**
		 *
		 * @since 3.9.4
		 */
		add_filter( 'wpmu_drop_tables', array( $this, 'deleteBlog' ), 10, 2 );
	}

	public function enqueuePublicScripts(){
		if ( mphb_is_single_room_type_page() ) {
			$this->getPublicScriptManager()->enqueue();
		}

		if ( mphb_is_checkout_page() ) {
			$this->getPublicScriptManager()->enqueue();
		}
	}

	public function enqueueAdminScripts(){
		if ( mphb_is_attribute_taxonomy_edit_page() ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
			$this->getAdminScriptManager()->enqueue();
		}
	}

    /**
     * @return bool
     *
     * @see \MPHB\Translation::updateTextdomains()
     */
    public function loadTextDomain()
    {
        // Get translation file by product slug
        $pluginSlug  = $this->getPluginSlug();  // "motopress-hotel-booking" or "motopress-hotel-booking-lite"
        $productSlug = $this->getProductSlug(); // "motopress-hotel-booking"
        $textDomain  = $this->getTextDomain();  // "motopress-hotel-booking"

        // Do as load_plugin_textdomain() does
        if (function_exists('determine_locale')) {
            $locale = determine_locale(); // Since WP 5.0
        } else if (function_exists('get_user_locale')) {
            $locale = get_user_locale(); // Since WP 4.7
        } else {
            $locale = get_locale();
        }

        $locale = apply_filters('plugin_locale', $locale, $pluginSlug);

        // wp-content/languages/motopress-hotel-booking/motopress-hotel-booking-{$locale}.mo
        $customMoFile = sprintf('%1$s/%2$s/%2$s-%3$s.mo', WP_LANG_DIR, $productSlug, $locale);

        // wp-content/languages/plugins/motopress-hotel-booking-{$locale}.mo
        $defaultMoFile = sprintf('%s/plugins/%s-%s.mo', WP_LANG_DIR, $pluginSlug, $locale);

        // wp-content/plugins/motopress-hotel-booking/languages/motopress-hotel-booking-{$locale}.mo
        $localFile = $this->getPluginDir() . "languages/{$productSlug}-{$locale}.mo";

        return load_textdomain($textDomain, $customMoFile)
            || load_textdomain($textDomain, $defaultMoFile)
            || load_textdomain($textDomain, $localFile);
    }

	public function getPrefix(){
		return $this->prefix;
	}

	public function addPrefix( $str, $separator = '-' ){
		return $this->getPrefix() . $separator . $str;
	}

    /**
     * Retrieve the slug of the plugin (basename of the plugin file).
     *
     * @return string
     *
     * @deprecated 3.8.2
     *
     * @see HotelBookingPlugin::getTextDomain()
     * @see HotelBookingPlugin::getPluginSlug()
     * @see HotelBookingPlugin::getProductSlug()
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     *
     * @since 3.8.2
     */
    public function getTextDomain()
    {
        // Text domain is always the same and equal to EDD product slug
        return $this->productSlug;
    }

    /**
     * Retrieve the slug of the plugin (basename of the plugin file).
     *
     * @return string
     *
     * @since 3.8.2
     */
    public function getPluginSlug()
    {
        return $this->pluginSlug;
    }

    /**
     * Retrieve the EDD product slug.
     *
     * @return string
     *
     * @since 3.8.2
     */
    public function getProductSlug()
    {
        return $this->productSlug;
    }

	/**
	 * Retrieve path to plugin directory
	 *
	 * @return string
	 */
	public function getPluginDir(){
		return $this->pluginDir;
	}

	/**
	 * Retrieve full path for the relative to plugin root path.
	 *
	 * @param string $relativePath
	 * @return string
	 */
	public function getPluginPath( $relativePath = '' ){
		return $this->pluginDir . $relativePath;
	}

	public function getPluginUrl( $relativePath = '' ){
		return $this->pluginDirUrl . $relativePath;
	}

	/**
	 *
	 * @return string
	 */
	public function getAjaxUrl(){
		return admin_url( 'admin-ajax.php' );
	}

	/**
	 * Retreive version of plugin
	 *
	 * @return string
	 */
	public function getVersion(){
		return $this->version;
	}

    /**
     * @return string
     *
     * @since 3.6.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     *
     * @since 3.6.0
     */
    public function getPluginStoreUri()
    {
        return $this->pluginStoreUri;
    }

	/**
	 *
	 * @return \MPHB\Settings\SettingsRegistry
	 */
	public function settings(){
		return $this->settings;
	}

    /**
     * @return \MPHB\Notices
     *
     * @since 3.7.0
     */
    public function notices()
    {
        return $this->notices;
    }

	/**
	 *
	 * @return \MPHB\UserActions\UserActions
	 */
	public function userActions(){
		return $this->userActions;
	}

	/**
	 *
	 * @return \MPHB\Crons\CronManager
	 */
	public function cronManager(){
		return $this->cronManager;
	}

	/**
	 * @return \MPHB\Session
	 */
	public function getSession(){
		return $this->session;
	}

	/**
	 * Retrieve relative to theme root path to templates.
	 *
	 * @return string
	 */
	public function getTemplatePath(){
		return apply_filters( 'mphb_template_path', 'hotel-booking/' );
	}

	/**
	 *
	 * @param \WP_Post|int $post
	 */
	public function setCurrentRoomType( $post ){
		$this->currentRoomType = null;

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}

		if ( !empty( $post->post_type ) && $post->post_type === MPHB()->postTypes()->roomType()->getPostType() ) {
			$this->currentRoomType = MPHB()->getRoomTypeRepository()->findById( $post->ID );
		}
	}

	/**
	 * When a new Blog is created in multisite, see if plugin is network activated, and run the installer
	 *
	 * @param int|WP_Site $blog
	 *
	 * @since 3.9.4
	 */
	public function createNewBlog( $blog ) {

		/*
		 * Additional check in case the plugin is not network active.
		 */
		if ( !is_plugin_active_for_network( plugin_basename( MPHB_PLUGIN_FILE ) ) ) {
			return;
		}

		if ( !is_int( $blog ) ) {
			$blog = $blog->id;
		}

		switch_to_blog( $blog );
		HotelBookingPlugin::install();
		add_action( 'init', array( 'HotelBookingPlugin', 'afterInstall' ) );
		restore_current_blog();
	}

	/**
	 *
	 * @param array $tables
	 * @param int $blog_id
	 *
	 * @since 3.9.4
	 */
	public function deleteBlog( $tables, $blog_id ) {
		global $wpdb;

		switch_to_blog( $blog_id );

		$tables[] = $wpdb->prefix . 'mphb_sync_urls';
		$tables[] = $wpdb->prefix . 'mphb_sync_queue';
		$tables[] = $wpdb->prefix . 'mphb_sync_stats';
		$tables[] = $wpdb->prefix . 'mphb_sync_logs';

		restore_current_blog();

		return $tables;
	}

    public function setupRoomTypeMicrodata()
    {
        if (!mphb_is_single_room_type_page()) {
            return;
        }

        $microdata = array(
            '@context'    => 'http://schema.org',
            '@type'       => 'Hotel',
            'name'        => get_the_title(),
            'description' => get_the_excerpt(),
            'url'         => get_permalink()
        );

        if (has_post_thumbnail()) {
            $microdata['image'] = wp_get_attachment_url(get_post_thumbnail_id());
        }

        // Setup price range
        $roomTypeId = get_the_ID();
        $roomType   = $this->getRoomTypeRepository()->findById($roomTypeId);
        $basePrice  = !is_null($roomType) ? mphb_get_room_type_base_price($roomType) : 0;

        if ($basePrice > 0) {
            // No need to check is_null($roomType) here again
            $nights      = $this->getRulesChecker()->reservationRules()->getMinDaysAllSeason($roomType->getOriginalId());
            $periodPrice = $basePrice * $nights;

            $formattedPrice = mphb_format_price($periodPrice, array(
                'period'        => true,
                'period_nights' => $nights,
                'as_html'       => false
            ));

            $microdata['priceRange'] = sprintf(__('Prices start at: %s', 'motopress-hotel-booking'), $formattedPrice);
        }

        $this->roomTypeMicrodata = apply_filters('mphb_single_room_type_microdata', $microdata, $roomTypeId, $roomType);
    }

    public function pushRoomTypeMicrodata()
    {
        if (!is_null($this->roomTypeMicrodata)) {
            $json = json_encode($this->roomTypeMicrodata);

            if ($json !== false) {
                echo '<script type="application/ld+json">' . $json . '</script>';
            }
        }
    }

	/**
	 *
	 * @return \MPHB\Entities\RoomType
	 */
	public function getCurrentRoomType(){
		return $this->currentRoomType;
	}

	/**
	 *
	 * @return \MPHB\CustomPostTypes
	 */
	public function postTypes(){
		return $this->postTypes;
	}

	/**
	 *
	 * @return \MPHB\Admin\Menus
	 */
	public function menus(){
		return $this->menus;
	}

	/**
	 *
	 * @return \MPHB\Shortcodes
	 */
	public function getShortcodes(){
		return $this->shortcodes;
	}

	/**
	 *
	 * @return \MPHB\Ajax
	 */
	public function getAjax(){
		return $this->ajax;
	}

	/**
	 *
	 * @return MPHB\Upgrader
	 */
	public function upgrader(){
		return $this->upgrader;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\SettingsMenuPage
	 */
	public function getSettingsMenuPage(){
		return $this->settingsMenuPage;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\ShortcodesMenuPage
	 */
	public function getShortcodesMenuPage(){
		return $this->shortcodesMenuPage;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\RoomsGeneratorMenuPage
	 */
	public function getRoomsGeneratorMenuPage(){
		return $this->roomsGeneratorMenuPage;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\CalendarMenuPage
	 */
	public function getCalendarMenuPage(){
		return $this->calendarMenuPage;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\BookingRulesMenuPage
	 */
	public function getBookingRulesPage(){
		return $this->bookingRulesPage;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\TaxesAndFeesMenuPage
	 */
	public function getTaxesAndFeesPage(){
		return $this->taxesAndFeesPage;
	}

	/**
	 *
	 * @return \MPHB\Admin\MenuPages\iCalMenuPage
	 */
	public function getICalMenuPage(){
		return $this->iCalMenuPage;
	}


    /**
     * @return \MPHB\Admin\MenuPages\EditBookingMenuPage
     *
     * @since 3.8
     */
    public function getEditBookingMenuPage(){
        return $this->editBookingMenuPage;
    }

    /**
     * @return MPHB\Admin\MenuPages\ReportsMenuPage
     *
     * @since 3.5.0
     */
    public function getReportsPage()
    {
        return $this->reportsPage;
    }

    /**
     * @return MPHB\Admin\MenuPages\ExtensionsMenuPage
     */
    public function getExtensionsPage()
    {
        return $this->extensionsPage;
    }

	/**
	 *
	 * @return \MPHB\Importer
	 */
	public function getImporter(){
		return $this->importer;
	}


    /**
     * @return \MPHB\CSV\Bookings\BookingsExporter
     *
     * @since 3.5.0
     */
    public function getBookingsExporter()
    {
        return $this->bookingsExporter;
    }

	/**
	 *
	 * @return \MPHB\UserActions
	 */
	public function getUserActions(){
		return $this->userActions;
	}

	/**
	 *
	 * @return \MPHB\ScriptManagers\PublicScriptManager
	 */
	public function getPublicScriptManager(){
		return $this->publicScriptManager;
	}

	/**
	 *
	 * @return \MPHB\ScriptManagers\AdminScriptManager
	 */
	public function getAdminScriptManager(){
		return $this->adminScriptManager;
	}

	/**
	 *
	 * @return \MPHB\BlocksRender
	 */
	public function getBlocksRender(){
		return $this->blocksRender;
	}

	/**
	 *
	 * @return \MPHB\Emails\Emails
	 */
	public function emails(){
		return $this->emails;
	}

	/**
	 *
	 * @return \MPHB\SearchParametersStorage
	 */
	public function searchParametersStorage(){
		return $this->searchParametersStorage;
	}

    /**
     * @return \MPHB\ReservationRequest
     *
     * @since 3.5.0
     */
    public function reservationRequest()
    {
        return $this->reservationRequest;
    }

	/**
	 *
	 * @param string $version version to compare with wp version
	 * @param string $operator Optional. Possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively. Default =.
	  This parameter is case-sensitive, values should be lowercase.
	 * @return bool
	 */
	public function isWPVersion( $version, $operator = '=' ){
		global $wp_version;
		return version_compare( $wp_version, $version, $operator );
	}

	/**
	 *
	 * @since 3.9.4 bool $network_wide
	 */
	static public function activate( $network_wide = false ) {

		if( $network_wide && is_multisite() ) {
			$sites = get_sites();
			foreach( $sites as $site ) {
				$blog_id = $site->blog_id;
				switch_to_blog( $blog_id );
				HotelBookingPlugin::install();
				HotelBookingPlugin::afterInstall();
				restore_current_blog();
			}
		} else {
			HotelBookingPlugin::install();
			HotelBookingPlugin::afterInstall();
		}
	}

	/**
	 *
	 * @since 3.9.4
	 */
	static public function install() {
		HotelBookingPlugin::createTables();
	}

	/**
	 *
	 * @since 3.9.4
	 */
	static public function afterInstall() {
		// This method will be called only once with first activated plugin - Premium or Lite
		MPHB()->postTypes()->flushRewriteRules();
		mphb_create_uploads_dir();


        /** @since 3.9.4 */
        do_action('mphb_activated');
	}

    static public function createTables()
    {
        global $wpdb;

        $syncUrls = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mphb_sync_urls ("
            . " url_id INT NOT NULL AUTO_INCREMENT,"
            . " room_id INT NOT NULL,"
            . " sync_id VARCHAR(32) NOT NULL,"
            . " calendar_url VARCHAR(250) NOT NULL,"
            . " PRIMARY KEY (url_id)"
            . ") CHARSET=utf8 AUTO_INCREMENT=1";

        $syncQueue = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mphb_sync_queue ("
            . " queue_id INT NOT NULL AUTO_INCREMENT,"
            . " queue_name TINYTEXT NOT NULL,"
            . " queue_status VARCHAR(30) NOT NULL,"
            . " PRIMARY KEY (queue_id)"
            . ") CHARSET=utf8 AUTO_INCREMENT=1";

        $syncStats = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mphb_sync_stats ("
            . " stat_id INT NOT NULL AUTO_INCREMENT,"
            . " queue_id INT NOT NULL,"
            . " import_total INT NOT NULL DEFAULT 0,"
            . " import_succeed INT NOT NULL DEFAULT 0,"
            . " import_skipped INT NOT NULL DEFAULT 0,"
            . " import_failed INT NOT NULL DEFAULT 0,"
            . " clean_total INT NOT NULL DEFAULT 0,"
            . " clean_done INT NOT NULL DEFAULT 0,"
            . " clean_skipped INT NOT NULL DEFAULT 0,"
            . " PRIMARY KEY (stat_id)"
            . ") CHARSET=utf8 AUTO_INCREMENT=1";

        $syncLogs = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mphb_sync_logs ("
            . " log_id INT NOT NULL AUTO_INCREMENT,"
            . " queue_id INT NOT NULL,"
            . " log_status VARCHAR(30) NOT NULL,"
            . " log_message TEXT NOT NULL,"
            . " log_context TEXT NOT NULL,"
            . " PRIMARY KEY (log_id)"
            . ") CHARSET=utf8 AUTO_INCREMENT=1";

        $wpdb->query($syncUrls);
        $wpdb->query($syncQueue);
        $wpdb->query($syncStats);
        $wpdb->query($syncLogs);

    }

	static public function deactivate(){
		$mphbActiveCount = (int)\MPHB\Utils\ThirdPartyPluginsUtils::isActiveMphb();
		$mphbActiveCount += (int)\MPHB\Utils\ThirdPartyPluginsUtils::isActiveMphbLite();

		// Check bulk actions
		if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'deactivate-selected' || $_POST['action'] == 'delete-selected' ) ) {
			$removedPlugins = is_array( $_POST['checked'] ) ? mphb_clean( $_POST['checked'] ) : array();

			foreach ( $removedPlugins as $removedPlugin ) {
				if ( strpos( $removedPlugin, 'motopress-hotel-booking.php' ) !== false ) {
					$mphbActiveCount--;
				}
			}
		}

		if ( $mphbActiveCount <= 1 ) {
			flush_rewrite_rules();
		}
	}

	/**
	 *
	 * @return \MPHB\Persistences\RoomTypePersistence
	 */
	public function getRoomTypePersistence(){
		return $this->roomTypePersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\AttributesPersistence
	 */
	public function getAttributesPersistence(){
		return $this->attributesPersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\RoomPersistence
	 */
	public function getRoomPersistence(){
		return $this->roomPersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\RatePersistence
	 */
	public function getRatePersistence(){
		return $this->ratePersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\BookingPersistence
	 */
	public function getBookingPersistence(){
		return $this->bookingPersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\CPTPersistence
	 */
	public function getServicePersistence(){
		return $this->servicePersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\CPTPersistence
	 */
	public function getSeasonPersistence(){
		return $this->seasonPersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\CPTPersistence
	 */
	public function getPaymentPersistence(){
		return $this->paymentPersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\ReservedRoomPersistence
	 */
	public function getReservedRoomPersistence(){
		return $this->reservedRoomPersistence;
	}

	/**
	 *
	 * @return \MPHB\Persistences\CPTPersistence
	 */
	public function getCouponPersistence(){
		return $this->couponPersistence;
	}

	/**
	 *
	 * @return \MPHB\Repositories\RoomTypeRepository
	 */
	public function getRoomTypeRepository(){
		return $this->roomTypeRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\RoomRepository
	 */
	public function getRoomRepository(){
		return $this->roomRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\RateRepository
	 */
	public function getRateRepository(){
		return $this->rateRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\BookingRepository
	 */
	public function getBookingRepository(){
		return $this->bookingRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\ServiceRepository
	 */
	public function getServiceRepository(){
		return $this->serviceRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\SeasonRepository
	 */
	public function getSeasonRepository(){
		return $this->seasonRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\PaymentRepository
	 */
	public function getPaymentRepository(){
		return $this->paymentRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\ReservedRoomRepository
	 */
	public function getReservedRoomRepository(){
		return $this->reservedRoomRepository;
	}

	/**
	 *
	 * @return \MPHB\Repositories\CouponRepository
	 */
	public function getCouponRepository(){
		return $this->couponRepository;
	}

	/**
	 * @return \MPHB\Repositories\SyncUrlsRepository
	 */
	public function getSyncUrlsRepository()
    {
		return $this->syncUrlsRepository;
	}

	/**
	 *
	 * @return \MPHB\Payments\Gateways\GatewayManager
	 */
	public function gatewayManager(){
		return $this->gatewayManager;
	}

	/**
	 *
	 * @return \MPHB\Payments\PaymentManager
	 */
	public function paymentManager(){
		return $this->paymentManager;
	}

	/**
	 *
	 * @return array
	 */
	public function getPluginData(){
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		return get_plugin_data( self::$_pluginFile, false, false );
	}

	public function initAutoUpdater(){

		if ( $this->settings->license()->isEnabled() ) {

			$pluginData = $this->getPluginData();

			$apiData = array(
				'version'	 => $this->getVersion(),
				'license'	 => MPHB()->settings()->license()->getLicenseKey(),
				'item_id'	 => MPHB()->settings()->license()->getProductId(),
				'author'	 => isset( $pluginData['Author'] ) ? $pluginData['Author'] : ''
			);

			new MPHB\Libraries\EDD_Plugin_Updater\EDD_Plugin_Updater( MPHB()->settings()->license()->getStoreUrl(), self::$_pluginFile, $apiData );
			//new MPHB\LicenseNotice();
		}
	}

    /**
     * Determines whether the current request is a WordPress Ajax request.
     *
     * @return bool
     */
    public function isAjax()
    {
        if (function_exists('wp_doing_ajax')) {
            // Since WordPress 4.7.0
            return wp_doing_ajax();
        } else {
            return defined('DOING_AJAX') && DOING_AJAX;
        }
    }

	/**
	 * Check if the home URL is https.
	 *
	 * @return bool
	 */
	public function isSiteSSL(){
		return false !== strstr( get_option( 'home' ), 'https:' );
	}

	/**
	 * Table wp_termmeta required, for instance, to add "custom order" feature
	 * for room attributes.
	 *
	 * @return bool
	 *
	 * @see https://codex.wordpress.org/Current_events
	 */
	public function isWpSupportsTermmeta(){
		return ( get_option( 'db_version' ) >= 35700 ); // Since WordPress 4.4
	}

	/**
	 *
	 * @return \MPHB\Translation
	 */
	public function translation(){
		return $this->translation;
	}

}

register_activation_hook( MPHB_PLUGIN_FILE, array( 'HotelBookingPlugin', 'activate' ) );
register_deactivation_hook( MPHB_PLUGIN_FILE, array( 'HotelBookingPlugin', 'deactivate' ) );
HotelBookingPlugin::getInstance();

/**
 *
 * @return \HotelBookingPlugin
 */
function MPHB(){
	return HotelBookingPlugin::getInstance();
}
