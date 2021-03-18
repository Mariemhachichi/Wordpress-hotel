<?php

namespace MPHB;

use \MPHB\Entities;
use \MPHB\Views;
use \MPHB\Utils\DateUtils;
use \MPHB\Utils\ThirdPartyPluginsUtils;
use \MPHB\Utils\ValidateUtils;

/**
 * @todo move each ajax controller to separate class
 *
 * @since 3.5.0 added new event - "export_bookings_csv".
 * @since 3.5.0 added new event - "check_bookings_csv".
 * @since 3.5.0 added new event - "cancel_bookings_csv".
 */
class Ajax {

	protected $nonceName		 = 'mphb_nonce';
	protected $actionPrefix	 = 'mphb_';
	protected $ajaxActions	 = array(
	// Admin
	'install_plugin' => array(
	    'method' => 'POST',
	    'nopriv' => false
	),
	'display_imported_bookings'  => array(
	    'method' => 'POST',
	    'nopriv' => false
	),
	'recalculate_total'			 => array(
		'method' => 'POST',
		'nopriv' => false
	),
	'get_rates_for_room'		 => array(
		'method' => 'GET',
		'nopriv' => false
	),
	'dismiss_license_notice'	 => array(
		'method' => 'POST',
		'nopriv' => false
	),
	'attributes_custom_ordering' => array(
		'method' => 'POST',
		'nopriv' => false
	),
	// Frontend
	'update_checkout_info'		 => array(
		'method' => 'GET',
		'nopriv' => true
	),
	'update_rate_prices'		 => array(
		'method' => 'GET',
		'nopriv' => true
	),
	'get_billing_fields'		 => array(
		'method' => 'GET',
		'nopriv' => true
	),
	'apply_coupon'				 => array(
		'method' => 'POST',
		'nopriv' => true
	),
	'get_booking_info'           => array(
		'method' => 'GET'
	),
	'get_accommodations_list'	 => array(
		'method' => 'GET'
	),
	'get_free_accommodations_amount'	 => array(
		'method' => 'GET',
		'nopriv' => true
	)
	);

	public function __construct(){
		foreach ( $this->ajaxActions as $action => $details ) {
			$noPriv = isset( $details['nopriv'] ) ? $details['nopriv'] : false;
			$this->addAjaxAction( $action, $noPriv );
		}
	}

	/**
	 *
	 * @param string $action
	 * @param bool $noPriv
	 */
	public function addAjaxAction( $action, $noPriv ){

		add_action( 'wp_ajax_' . $this->actionPrefix . $action, array( $this, $action ) );

		if ( $noPriv ) {
			add_action( 'wp_ajax_nopriv_' . $this->actionPrefix . $action, array( $this, $action ) );
		}
	}

	/**
	 *
	 * @param string $action
	 * @return bool
	 */
	protected function checkNonce( $action ){

		if ( !isset( $this->ajaxActions[$action] ) ) {
			return false;
		}

		$input = $this->retrieveInput( $action );

		$nonce = isset( $input[$this->nonceName] ) ? $input[$this->nonceName] : '';

		return wp_verify_nonce( $nonce, $this->actionPrefix . $action );
	}

	/**
	 *
	 * @param string $action Name of AJAX action without wp prefix.
	 * @return array
	 */
	protected function retrieveInput( $action ){

		$method = isset( $this->ajaxActions[$action]['method'] ) ? $this->ajaxActions[$action]['method'] : '';

		switch ( $method ) {
			case 'GET':
				$input	 = $_GET;
				break;
			case 'POST':
				$input	 = $_POST;
				break;
			default:
				$input	 = $_REQUEST;
		}

		if ( isset( $input['lang'] ) ) {
			MPHB()->translation()->switchLanguage( sanitize_text_field( $input['lang'] ) );
		}

		return $input;
	}

	/**
	 *
	 * @param string $action
	 */
	protected function verifyNonce( $action ){
		if ( !$this->checkNonce( $action ) ) {
			wp_send_json_error( array(
				'message' => __( 'Request does not pass security verification. Please refresh the page and try one more time.', 'motopress-hotel-booking' )
			) );
		}
	}

	/**
	 *
	 * @return array
	 */
	public function getAdminNonces(){
		$nonces = array();
		foreach ( $this->ajaxActions as $actionName => $actionDetails ) {
			$nonces[$this->actionPrefix . $actionName] = wp_create_nonce( $this->actionPrefix . $actionName );
		}
		return $nonces;
	}

	/**
	 *
	 * @return array
	 */
	public function getFrontNonces(){
		$nonces = array();
		foreach ( $this->ajaxActions as $actionName => $actionDetails ) {
			if ( isset( $actionDetails['nopriv'] ) && $actionDetails['nopriv'] ) {
				$nonces['mphb_' . $actionName] = wp_create_nonce( 'mphb_' . $actionName );
			}
		}
		return $nonces;
	}


	/**
	 * @since 3.8.1
	 */
	public function install_plugin()
	{
		$this->verifyNonce(__FUNCTION__);

		$input = $this->retrieveInput(__FUNCTION__);

		if (!isset($input['plugin_slug'], $input['plugin_zip'])) {
			wp_send_json_error(array('message' => __('No enough data', 'motopress-hotel-booking')));
		}

		$pluginSlug = sanitize_text_field($input['plugin_slug']);
		$pluginZip = sanitize_text_field($input['plugin_zip']);

		$installed = ThirdPartyPluginsUtils::isPluginInstalled($pluginSlug);

		if (!$installed) {
			$installed = ThirdPartyPluginsUtils::installPlugin($pluginZip);
		}

		$activated = ThirdPartyPluginsUtils::isPluginActive($pluginSlug)
			|| ($installed && ThirdPartyPluginsUtils::activatePlugin($pluginSlug));

		if ($installed && $activated) {
			wp_send_json_success();
		} else {
			wp_send_json_error(array('message' => __('An error has occurred', 'motopress-hotel-booking'))); // Very informative
		}
	}

	public function display_imported_bookings()
	{
		$this->verifyNonce(__FUNCTION__);

		$input = $this->retrieveInput(__FUNCTION__);

		if (!isset($input['new_value']) || !isset($input['user_id'])) {
			wp_send_json_error(array(
				'message' => __('Please complete all required fields and try again.', 'motopress-hotel-booking')
			));
		}

		$newValue = Utils\ValidateUtils::validateBool($input['new_value']);
		$userId = Utils\ValidateUtils::parseInt($input['user_id']);

		if ($userId > 0) {
			MPHB()->settings()->main()->displayImportedBookings($userId, $newValue);
		}

		wp_send_json_success();
	}

	public function recalculate_total(){

		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		if (
			!isset( $input['formValues'] ) ||
			!is_array( $input['formValues'] ) ||
			!isset( $input['formValues']['post_ID'] )
		) {
			wp_send_json_error( array(
				'message' => __( 'An error has occurred, please try again later.', 'motopress-hotel-booking' ),
			) );
		}

		$bookingId = intval( $input['formValues']['post_ID'] );

		$atts = MPHB()->postTypes()->booking()->getEditPage()->getAttsFromRequest( $input['formValues'] );

		// Check Required Fields
		if (
			empty( $atts['mphb_check_in_date'] ) ||
			empty( $atts['mphb_check_out_date'] )
		) {
			wp_send_json_error( array(
				'message' => __( 'Please complete all required fields and try again.', 'motopress-hotel-booking' )
			) );
		}

		$checkInDate	 = \DateTime::createFromFormat( 'Y-m-d', $atts['mphb_check_in_date'] );
		$checkOutDate	 = \DateTime::createFromFormat( 'Y-m-d', $atts['mphb_check_out_date'] );

		$reservedRooms = MPHB()->getReservedRoomRepository()->findAllByBooking( $bookingId );

		$bookingAtts = array(
			'check_in_date'	 => $checkInDate,
			'check_out_date' => $checkOutDate,
			'reserved_rooms' => $reservedRooms
		);

		$booking = Entities\Booking::create( $bookingAtts );

		if ( MPHB()->settings()->main()->isCouponsEnabled() && !empty( $input['formValues']['mphb_coupon_id'] ) ) {
			$coupon = MPHB()->getCouponRepository()->findById( intval( $input['formValues']['mphb_coupon_id'] ) );
			if ( $coupon ) {
				$booking->applyCoupon( $coupon );
			}
		}

		// array_walk_recursive() not required. wp_send_json_success() adds all
		// required slashes
		$priceBreakdown = $booking->getPriceBreakdown();

		wp_send_json_success( array(
			// [MB-684] Prevent excess number of digits
			'total'					 => round( $booking->calcPrice(), MPHB()->settings()->currency()->getPriceDecimalsCount() ),
			'price_breakdown'		 => json_encode( $priceBreakdown ),
			'price_breakdown_html'	 => \MPHB\Views\BookingView::generatePriceBreakdownArray( $priceBreakdown )
		) );
	}

	/**
	 * @param string $input Date string.
	 *
	 * @return \DateTime
	 */
	protected function parseCheckInDate( $input ){
		$checkInDate = \DateTime::createFromFormat( 'Y-m-d', $input );

		if ( !$checkInDate ) {
			wp_send_json_error( array(
				'message' => __( 'Check-in date is not valid.', 'motopress-hotel-booking' )
			) );
		}

		return $checkInDate;
	}

	/**
	 * @param string $input Date string.
	 *
	 * @return \DateTime
	 */
	protected function parseCheckOutDate( $input ){
		$checkOutDate = \DateTime::createFromFormat( 'Y-m-d', $input );

		if ( !$checkOutDate ) {
			wp_send_json_error( array(
				'message' => __( 'Check-out date is not valid.', 'motopress-hotel-booking' )
			) );
		}

		return $checkOutDate;
	}

	protected function parseAdults( $input, $allowEmptyString = false ){
		$adults = Utils\ValidateUtils::validateInt( $input, 1 );

		if ( $adults === false ) {
			if ( $allowEmptyString ) {
				return '';
			}

			wp_send_json_error( array(
				'message' => __( 'Adults number is not valid.', 'motopress-hotel-booking' )
			) );
		}

		return $adults;
	}

	protected function parseChildren( $input, $allowEmptyString = false ){
		$children = Utils\ValidateUtils::validateInt( $input, 0 );

		if ( $children === false ) {
			if ( $allowEmptyString ) {
				return '';
			}

			wp_send_json_error( array(
				'message' => __( 'Children number is not valid.', 'motopress-hotel-booking' )
			) );
		}

		return $children;
	}

	public function update_rate_prices(){

		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		if ( !isset( $input['rates'], $input['adults'], $input['children'], $input['check_in_date'], $input['check_out_date'] ) ||
			!is_array( $input['rates'] )
		) {
			wp_send_json_error( array(
				'message' => __( 'An error has occurred. Please try again later.', 'motopress-hotel-booking' ),
			) );
		}

		$rates = \MPHB\Utils\ValidateUtils::validateIds( $input['rates'] );
		$adults = $this->parseAdults( $input['adults'], true );
		$children = $this->parseChildren( $input['children'], true );
		$checkInDate = $this->parseCheckInDate( $input['check_in_date'] );
		$checkOutDate = $this->parseCheckInDate( $input['check_out_date'] );

		MPHB()->reservationRequest()->setupParameters( array(
			'adults' => $adults,
			'children' => $children,
			'check_in_date' => $checkInDate,
			'check_out_date' => $checkOutDate
		) );

		$prices = array();

		foreach ( $rates as $rateId ) {
			$rate = MPHB()->getRateRepository()->findById( $rateId );

			if ( !$rate ) {
				continue;
			}

			$price = $rate->calcPrice( $checkInDate, $checkOutDate );
			$prices[$rateId] = mphb_format_price( $price );
		}

		wp_send_json_success( $prices );
	}

	/**
	 * Parse booking from checkout form values.
	 *
	 * @param array $input
	 * @return Entities\Booking
	 */
	protected function parseCheckoutFormBooking( $input ){

		$isSetRequiredFields = isset( $input['formValues'] ) &&
			is_array( $input['formValues'] ) &&
			!empty( $input['formValues']['mphb_room_details'] ) &&
			is_array( $input['formValues']['mphb_room_details'] ) &&
			!empty( $input['formValues']['mphb_check_in_date'] ) &&
			!empty( $input['formValues']['mphb_check_out_date'] );

		if ( $isSetRequiredFields ) {
			foreach ( $input['formValues']['mphb_room_details'] as &$roomDetails ) {
				if (
					!is_array( $roomDetails ) ||
					empty( $roomDetails['room_type_id'] ) ||
					!isset( $roomDetails['adults'] ) ||
					empty( $roomDetails['rate_id'] )
				) {
					$isSetRequiredFields = false;
					break;
				}

				if ( !isset( $roomDetails['children'] ) ) {
					$roomDetails['children'] = 0;
				}
			}
			unset( $roomDetails );
		}

		if ( !$isSetRequiredFields ) {
			wp_send_json_error( array(
				'message' => __( 'An error has occurred. Please try again later.', 'motopress-hotel-booking' ),
			) );
		}

		$atts = $input['formValues'];

		$checkInDate = $this->parseCheckInDate( $atts['mphb_check_in_date'] );
		$checkOutDate = $this->parseCheckOutDate( $atts['mphb_check_out_date'] );

		$reservedRooms = array();

		foreach ( $atts['mphb_room_details'] as $roomDetails ) {

			$roomTypeId	 = Utils\ValidateUtils::validateInt( $roomDetails['room_type_id'], 0 );
			$roomType	 = $roomTypeId ? MPHB()->getRoomTypeRepository()->findById( $roomTypeId ) : null;
			if ( !$roomType ) {
				wp_send_json_error( array(
					'message' => __( 'Accommodation Type is not valid.', 'motopress-hotel-booking' )
				) );
			}

			$roomRateId	 = Utils\ValidateUtils::validateInt( $roomDetails['rate_id'], 0 );
			$roomRate	 = $roomRateId ? MPHB()->getRateRepository()->findById( $roomRateId ) : null;
			if ( !$roomRate ) {
				wp_send_json_error( array(
					'message' => __( 'Rate is not valid.', 'motopress-hotel-booking' )
				) );
			}

			$adults = $this->parseAdults( $roomDetails['adults'] );
			$children = $this->parseChildren( $roomDetails['children'] );

			if ($roomType->hasLimitedTotalCapacity() && $adults + $children > $roomType->getTotalCapacity()) {
				wp_send_json_error(array(
					'message' => __('The total number of guests is not valid.', 'motopress-hotel-booking')
				));
			}

			$allowedServices = $roomType->getServices();

			$services = array();

			if ( !empty( $roomDetails['services'] ) && is_array( $roomDetails['services'] ) ) {
				foreach ( $roomDetails['services'] as $serviceDetails ) {

					if ( empty( $serviceDetails['id'] ) || !in_array( $serviceDetails['id'], $allowedServices ) ) {
						continue;
					}

					$serviceAdults = Utils\ValidateUtils::validateInt( $serviceDetails['adults'] );
					if ( $serviceAdults === false || $serviceAdults < 1 ) {
						continue;
					}

					$quantity = isset($serviceDetails['quantity']) ? Utils\ValidateUtils::validateInt($serviceDetails['quantity']) : 1;
					if (isset($serviceDetails['quantity']) && $quantity < 1) {
						continue;
					}

					$services[] = Entities\ReservedService::create( array(
						'id'        => (int) $serviceDetails['id'],
						'adults'    => $serviceAdults,
						'quantity'  => $quantity
					) );
				}
			}
			$services = array_filter( $services );

			$reservedRoomAtts = array(
				'room_type_id'		 => $roomTypeId,
				'rate_id'			 => $roomRateId,
				'adults'			 => $adults,
				'children'			 => $children,
				'reserved_services'	 => $services
			);

			$reservedRooms[] = Entities\ReservedRoom::create( $reservedRoomAtts );
		}

		$bookingAtts = array(
			'check_in_date'	 => $checkInDate,
			'check_out_date' => $checkOutDate,
			'reserved_rooms' => $reservedRooms,
		);

		$booking = Entities\Booking::create( $bookingAtts );

		if (
			MPHB()->settings()->main()->isCouponsEnabled() &&
			!empty( $input['formValues']['mphb_applied_coupon_code'] )
		) {
			$coupon = MPHB()->getCouponRepository()->findByCode( $input['formValues']['mphb_applied_coupon_code'] );
			if ( $coupon ) {
				$booking->applyCoupon( $coupon );
			}
		}

		return $booking;
	}


	public function update_checkout_info(){

		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		$booking = $this->parseCheckoutFormBooking( $input );

		$total = $booking->calcPrice();

		$responseData = array(
			'newAmount'      => $total,
			'priceHtml'      => mphb_format_price($total),
			'priceBreakdown' => Views\BookingView::generatePriceBreakdown($booking)
		);

		if ( MPHB()->settings()->main()->getConfirmationMode() === 'payment' ) {
			$responseData['depositAmount'] = $booking->calcDepositAmount();
			$responseData['depositPrice'] = mphb_format_price($responseData['depositAmount']);

			$responseData['gateways'] = array_map( function($gateway) use ($booking) {
				return $gateway->getCheckoutData( $booking );
			}, MPHB()->gatewayManager()->getListActive() );

			$responseData['isFree'] = $total == 0;
		}

		wp_send_json_success( $responseData );
	}

	public function get_billing_fields(){

		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		$gatewayId = !empty( $input['mphb_gateway_id'] ) ? mphb_clean( $input['mphb_gateway_id'] ) : '';

		if ( !array_key_exists( $gatewayId, MPHB()->gatewayManager()->getListActive() ) ) {
			wp_send_json_error( array(
				'message' => __( 'Chosen payment method is not available. Please refresh the page and try one more time.', 'motopress-hotel-booking' ),
			) );
		}

		$booking = $this->parseCheckoutFormBooking( $input );

		ob_start();
		MPHB()->gatewayManager()->getGateway( $gatewayId )->renderPaymentFields( $booking );
		$fields = ob_get_clean();

		wp_send_json_success( array(
			'fields'			 => $fields,
			'hasVisibleFields'	 => MPHB()->gatewayManager()->getGateway( $gatewayId )->hasVisiblePaymentFields()
		) );
	}

	public function get_rates_for_room(){

		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		$titlesList = array();

		if (
			isset( $input['formValues'] ) &&
			is_array( $input['formValues'] ) &&
			!empty( $input['formValues']['mphb_room_id'] )
		) {
			$roomId	 = absint( $input['formValues']['mphb_room_id'] );
			$room	 = MPHB()->getRoomRepository()->findById( $roomId );

			if ( !$room ) {
				wp_send_json_success( array(
					'options' => array()
				) );
			}

			foreach ( MPHB()->getRateRepository()->findAllActiveByRoomType( $room->getRoomTypeId() ) as $rate ) {
				$titlesList[$rate->getId()] = $rate->getTitle();
			}
		}

		wp_send_json_success( array(
			'options' => $titlesList
		) );
	}

	public function apply_coupon(){

		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		$booking = $this->parseCheckoutFormBooking( $input );

		$responseData = array();

		if ( MPHB()->settings()->main()->isCouponsEnabled() && isset( $input['mphb_coupon_code'] ) ) {

			$coupon = MPHB()->getCouponRepository()->findByCode( $input['mphb_coupon_code'] );

			if ( $coupon ) {
				$couponApplied = $booking->applyCoupon( $coupon );

				if ( is_wp_error( $couponApplied ) ) {
					$responseData['coupon'] = array(
						'applied_code'	 => '',
						'message'		 => $couponApplied->get_error_message()
					);
				} else {
					$responseData['coupon'] = array(
						'applied_code'	 => $booking->getCouponCode(),
						'message'		 => __( 'Coupon applied successfully.', 'motopress-hotel-booking' )
					);
				}
			} else {
				$responseData['coupon'] = array(
					'applied_code'	 => '',
					'message'		 => __( 'Coupon is not valid.', 'motopress-hotel-booking' )
				);
			}
		}

		$total = $booking->calcPrice();

		$responseData['newAmount']       = $total;
		$responseData['priceHtml']       = mphb_format_price( $total );
		$responseData['priceBreakdown']	 = Views\BookingView::generatePriceBreakdown( $booking );

		if ( MPHB()->settings()->main()->getConfirmationMode() === 'payment' ) {
			$responseData['depositAmount'] = $booking->calcDepositAmount();
			$responseData['depositPrice'] = mphb_format_price($responseData['depositAmount']);

			$responseData['gateways'] = array_map( function($gateway) use ($booking) {
				return $gateway->getCheckoutData( $booking );
			}, MPHB()->gatewayManager()->getListActive() );

			$responseData['isFree'] = $total == 0;
		}

		wp_send_json_success( $responseData );
	}

	public function dismiss_license_notice(){

		$this->verifyNonce( __FUNCTION__ );

		MPHB()->settings()->license()->setNeedHideNotice( true );

		wp_send_json_success();
	}

	public function attributes_custom_ordering(){

		$this->verifyNonce( __FUNCTION__ );

		$termId = absint( $_POST['term_id'] );
		$nextTermId = ( isset( $_POST['next_term_id'] ) && absint( $_POST['next_term_id'] ) ) ? absint( $_POST['next_term_id'] ) : null;
		$taxonomyName = isset( $_POST['taxonomy_name'] ) ? esc_attr( $_POST['taxonomy_name'] ) : null;

		if ( !$termId || !$taxonomyName) {
			wp_send_json_error();
		}

		if ( !term_exists( $termId, $taxonomyName ) ) {
			wp_send_json_error();
		}

		mphb_reorder_attributes( $termId, $nextTermId, $taxonomyName );

		wp_send_json_success();
	}


	public function get_booking_info()
	{
		$this->verifyNonce(__FUNCTION__);

		$input     = $this->retrieveInput(__FUNCTION__);
		$bookingId = isset($input['bookingId']) ? absint($input['bookingId']) : 0;
		$booking   = $bookingId > 0 ? MPHB()->getBookingRepository()->findById($bookingId) : null;

		if (is_null($booking)) {
			wp_send_json_error( array('message' => __('The booking not found.', 'motopress-hotel-booking')) );
		}

		$customer   = $booking->getCustomer();
		$couponCode = $booking->getCouponCode();
		$dateFormat = MPHB()->settings()->dateTime()->getDateFormat();

		ob_start();
		?>
		<h2><?php _e('Booking Information', 'motopress-hotel-booking'); ?></h2>
		<table class="widefat striped">
			<tbody>
				<tr>
					<th><?php _e('ID', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($booking->getId()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Check-in Date', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($booking->getCheckInDate()->format($dateFormat)); ?></td>
				</tr>
				<tr>
					<th><?php _e('Check-out Date', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($booking->getCheckOutDate()->format($dateFormat)); ?></td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e('Reserved Accommodations', 'motopress-hotel-booking'); ?></h2>
		<?php mphb_tmpl_the_reserved_rooms_details($booking->getReservedRooms()); ?>

		<h2><?php _e('Customer Information', 'motopress-hotel-booking'); ?></h2>
		<table class="widefat striped">
			<tbody>
				<tr>
					<th><?php _e('First Name', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getFirstName()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Last Name', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getLastName()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Email', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getEmail()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Phone', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getPhone()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Country', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getCountry()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Address', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getAddress1()); ?></td>
				</tr>
				<tr>
					<th><?php _e('City', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getCity()); ?></td>
				</tr>
				<tr>
					<th><?php _e('State / County', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getState()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Postcode', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($customer->getZip()); ?></td>
				</tr>
				<tr>
					<th><?php _e('Customer Note', 'motopress-hotel-booking'); ?></th>
					<td><?php echo esc_html($booking->getNote()); ?></td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e('Additional Information', 'motopress-hotel-booking'); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th><?php _e('Coupon', 'motopress-hotel-booking'); ?></th>
					<td><?php echo !empty($couponCode) ? esc_html($couponCode) : '&#8212;' ?></td>
				</tr>
				<tr>
					<th><?php _e('Total Booking Price', 'motopress-hotel-booking'); ?></th>
					<td><?php mphb_tmpl_the_payments_table($booking); ?></td>
				</tr>
			</tbody>
		</table>

		<?php
		if( !empty( $booking->getInternalNotes() ) ) {
			?>
			<h2><?php echo __( 'Notes', 'motopress-hotel-booking' ); ?></h2>
			<table class="widefat striped">
				<tbody>
					<?php
					$dateFormat = get_option( 'date_format' );
					foreach( $booking->getInternalNotes() as $note ) {
						$user = get_user_by( 'id', $note['user'] );
						$displayName = $user ? esc_attr( $user->display_name ) : '';
						$noteDate = wp_date( $dateFormat, $note['date'] );
						?><tr> 
							<td>
								<?php echo wpautop( sprintf( '%s', $note['note'] ) ); ?>
								<p class="description"><?php
								if ( $displayName ) {
									/* translators: %1$s: note author, %1$s: note date */
									printf( esc_html__( '%1$s on %2$s', 'motopress-hotel-booking' ),
										$displayName,
										$noteDate
									);
								} else {
									echo $noteDate;
								}?></p>
							</td>
						</tr><?php
					}
					?>
				</tbody>
			</table>
			<p></p>
			<?php
		}

		$bookingInfo = ob_get_clean();

		wp_send_json_success(array('bookingInfo' => $bookingInfo));
	}

	public function get_accommodations_list(){
		$this->verifyNonce( __FUNCTION__ );

		$input = $this->retrieveInput( __FUNCTION__ );

		$formValues	 = $input['formValues'];
		$typeId		 = ( isset( $formValues['room_type_id'] ) ) ? (int)$formValues['room_type_id'] : 0;
		$roomsList	 = mphb_get_rooms_select_list( $typeId );

		wp_send_json_success( array( 'options' => $roomsList ) );
	}

	/**
	 * @since 3.7.0 added new filter - "mphb_search_available_rooms".
	 * @since 3.8.3 added "price" and "priceHtml" to response.
	 */
	public function get_free_accommodations_amount(){
		$this->verifyNonce( __FUNCTION__ );

		$input		 = $this->retrieveInput( __FUNCTION__ );
		$dateFormat	 = MPHB()->settings()->dateTime()->getDateTransferFormat();
		$checkIn	 = \DateTime::createFromFormat( $dateFormat, $input['checkInDate'] );
		$checkOut	 = \DateTime::createFromFormat( $dateFormat, $input['checkOutDate'] );
		$adults      = isset( $input['adults'] ) ? ValidateUtils::validateAdults( $input['adults'] ) : false;
		$children    = isset( $input['children'] ) ? ValidateUtils::validateChildren( $input['children'] ) : false;

		if ( $checkIn === false || $checkOut === false || $checkIn > $checkOut ) {
			wp_send_json_error( array( 'message' => __( 'Nothing found. Please try again with different search parameters.', 'motopress-hotel-booking' ) ) );
		}

		$typeId		 = $input['typeId'];
		$roomType	 = MPHB()->getRoomTypeRepository()->findById( $typeId );

		if ( !MPHB()->getRulesChecker()->reservationRules()->verify( $checkIn, $checkOut, $typeId ) ) {
			wp_send_json_error( array( 'message' => __( 'Nothing found. Please try again with different search parameters.', 'motopress-hotel-booking' ) ) );
		}

		$searchArgs = apply_filters('mphb_search_available_rooms', array(
			'availability'	 => 'free',
			'from_date'		 => $checkIn,
			'to_date'		 => $checkOut,
			'room_type_id'	 => $typeId,
			'skip_buffer_rules'=> false
		));
		$availableRooms = MPHB()->getRoomPersistence()->searchRooms($searchArgs);
		$unavailableRooms = MPHB()->getRulesChecker()->customRules()->getUnavailableRooms( $checkIn, $checkOut, $roomType->getOriginalId() );
		$unavailableRooms = array_intersect( $availableRooms, $unavailableRooms ); // Filter not available rooms
		$freeCount = count( $availableRooms ) - count( $unavailableRooms );

		// Calculate the price for the period
		$price = 0;
		$priceHtml = '';

		if ( MPHB()->settings()->main()->getDirectBookingPricing() != 'disabled' ) {
			$args = array();

			if ( $adults !== false && $children !== false ) {
				$args['adults'] = $adults;
				$args['children'] = $children;
			}

			$price = mphb_get_room_type_period_price( $checkIn, $checkOut, $roomType, $args );

			$priceHtml = mphb_format_price( $price, array(
				'period'        => true,
				'period_nights' => DateUtils::calcNights( $checkIn, $checkOut ),
				'period_title'  => __( 'Based on your search parameters', 'motopress-hotel-booking' )
			) );
		}

		if ( $freeCount > 0 ) {
			wp_send_json_success( array(
				'freeCount' => $freeCount,
				'price'     => $price,
				'priceHtml' => $priceHtml
			) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Nothing found. Please try again with different search parameters.', 'motopress-hotel-booking' ) ) );
		}
	}

}
