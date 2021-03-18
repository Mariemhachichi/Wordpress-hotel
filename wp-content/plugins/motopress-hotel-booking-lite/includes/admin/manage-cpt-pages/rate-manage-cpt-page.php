<?php

namespace MPHB\Admin\ManageCPTPages;

use \MPHB\Entities;

class RateManageCPTPage extends ManageCPTPage {

	public function __construct( $postType, $atts = array() ){
		parent::__construct( $postType, $atts );
		$this->description = __( 'Rates are used to offer different prices of the same accommodation type depending on extra conditions. E.g. Double room with breakfast included; double room with no breakfast. Guests will choose the preferable rate when submitting a booking request.', 'motopress-hotel-booking' );
		add_filter( 'post_row_actions', array( $this, 'filterRowActions' ) );
		add_action( 'load-edit.php', array( $this, 'doActions' ) );
		add_action( 'admin_notices', array( $this, 'showNotices' ) );
		add_action( 'parse_query', array( $this, 'parseQuery' ) );
		add_filter( 'request', array( $this, 'filterCustomOrderBy' ) );
	}

	public function filterColumns( $columns ){
		$customColumns	 = array(
			'room_type'	 => __( 'Accommodation Type', 'motopress-hotel-booking' ),
			'prices'	 => __( 'Season &#8212; Price', 'motopress-hotel-booking' ),
		);
		$offset			 = array_search( 'date', array_keys( $columns ) ); // Set custom columns position before "DATE" column
		$columns		 = array_slice( $columns, 0, $offset, true ) + $customColumns + array_slice( $columns, $offset, count( $columns ) - 1, true );

		unset( $columns['date'] );

		return $columns;
	}

	public function filterSortableColumns( $columns ){
		$columns['room_type'] = 'mphb_room_type_id';

		return $columns;
	}

	public function renderColumns( $column, $postId ){
		$rate = MPHB()->getRateRepository()->findById( $postId );
		switch ( $column ) {
			case 'room_type' :
				$roomType = MPHB()->getRoomTypeRepository()->findById( $rate->getRoomTypeId() );
				if ( !empty( $roomType ) ) {
					printf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'mphb_room_type_id', $roomType->getId() ) ), $roomType->getTitle() );
				} else {
					echo '<span aria-hidden="true">' . static::EMPTY_VALUE_PLACEHOLDER . '</span>';
				}
				break;
			case 'prices' :
				$seasonPrices = $rate->getSeasonPrices();
				if ( !$seasonPrices ) {
					echo '<span aria-hidden="true">' . static::EMPTY_VALUE_PLACEHOLDER . '</span>';
				} else {
					$seasonPriceItems = array_map( function( Entities\SeasonPrice $seasonPrice ) {

						$season = $seasonPrice->getSeason();

						$seasonLabel = $season ? esc_html( $season->getTitle() ) : '';

						$price = mphb_format_price( $seasonPrice->getPrice() );

						return sprintf( '%s &#8212; %s', $seasonLabel, $price );
					}, $seasonPrices );

					$seasonPriceItems	 = array_reverse( $seasonPriceItems );
					$seasonPriceItems	 = join( '</li><li>', $seasonPriceItems );
					echo '<ul style="margin:0;"><li>' . $seasonPriceItems . '</li></ul>';
				}
				break;
		}
	}

	/**
	 *
	 * @param \WP_Query $query
	 */
	public function parseQuery( $query ){
		if ( $this->isCurrentPage() && $query->is_main_query() ) {
			if ( isset( $_GET['mphb_room_type_id'] ) && $_GET['mphb_room_type_id'] != '' ) {
				$query->set( 'meta_key', 'mphb_room_type_id' );
				$query->set( 'meta_value', sanitize_text_field( $_GET['mphb_room_type_id'] ) );
				$query->set( 'meta_compare', '=' );
			}
		}
	}

	public function filterCustomOrderBy( $vars ){
		if ( $this->isCurrentPage() ) {
			if ( isset( $vars['orderby'] ) ) {
				switch ( $vars['orderby'] ) {
					case 'mphb_room_type_id':
						$vars = array_merge( $vars, array(
							'meta_key'	 => 'mphb_room_type_id',
							'orderby'	 => 'meta_value_num'
							) );
						break;
				}
			}
		}
		return $vars;
	}

	public function filterRowActions( $actions ){

		if ( !$this->isCurrentPage() ) {
			return $actions;
		}

		// Prevent Quick Edit
		if ( isset( $actions['inline hide-if-no-js'] ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}

		// No need to add custom actions to Trash
		if ( $this->isCurrentTrashPage() ) {
			return $actions;
		}

		$customActions = array();

		if ( !MPHB()->translation()->isTranslationPage() ) {

			$duplicateQueryArgs = array(
				'post_type'		 => $this->postType,
				'id'			 => get_the_ID(),
				'mphb_action'	 => 'duplicate'
			);

			$duplicateUrl	 = wp_nonce_url( admin_url( 'edit.php' ), 'duplicate', 'mphb_nonce' );
			$duplicateUrl	 = add_query_arg( $duplicateQueryArgs, $duplicateUrl );

			$customActions['duplicate'] = sprintf( '<a href="%s">%s</a>', esc_url( $duplicateUrl ), __( 'Duplicate', 'motopress-hotel-booking' ) );
		}

		// Set custom actions position before "trash" action
		$offset = array_search( 'trash', array_keys( $actions ) );
		if ( $offset !== false ) {
			$actions = array_slice( $actions, 0, $offset, true ) + $customActions + array_slice( $actions, $offset, count( $actions ) - 1, true );
		} else {
			$actions = array_merge( $actions, $customActions );
		}

		return $actions;
	}

	public function doActions(){

		if ( !$this->isCurrentPage() ) {
			return;
		}

		$input = $_GET;

		if ( !isset( $input['mphb_action'] ) ) {
			return;
		}

		if ( $input['mphb_action'] !== 'duplicate' ) {
			return;
		}

		if ( !isset( $input['id'] ) ) {
			return;
		}

		$id = intval( $input['id'] );
		$rate = MPHB()->getRateRepository()->findById( $id );

		if ( !$rate ) {
			return;
		}

		check_admin_referer( 'duplicate', 'mphb_nonce' );

		$duplicatedRateId = MPHB()->getRateRepository()->duplicate( $rate );
		if ( !$duplicatedRateId ) {
			return;
		}

		$queryArgs = array(
			'post_type'		 => $this->postType,
			'report_action'	 => 'duplicated',
			'id'			 => $duplicatedRateId
		);

		$sendback = add_query_arg( $queryArgs, admin_url( 'edit.php' ) );

		if ( isset( $_GET['post_status'] ) ) {
			$sendback = add_query_arg( 'post_status', sanitize_text_field( $_GET['post_status'] ), $sendback );
		}

		wp_redirect( esc_url_raw( $sendback ) );
		exit;
	}

	public function showNotices(){
		if ( !$this->isCurrentPage() ) {
			return;
		}

		if ( !isset( $_GET['report_action'] ) ) {
			return;
		}

		if ( $_GET['report_action'] !== 'duplicated' ) {
			return;
		}

		$editLink = get_edit_post_link( intval( $_GET['id'] ) );

		echo '<div class="updated"><p><a href="' . esc_attr( $editLink ) . '">';
		_e( 'Rate was duplicated.', 'motopress-hotel-booking' );
		echo '</a></p></div>';
	}

}
