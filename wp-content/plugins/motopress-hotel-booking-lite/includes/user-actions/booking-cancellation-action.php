<?php

namespace MPHB\UserActions;

class BookingCancellationAction {

	const QUERY_ACTION						 = 'cancel_booking';
	const STATUS_CANCELLED					 = 'cancelled';
	const STATUS_INVALID_REQUEST			 = 'invalid-request';
	const STATUS_ALREADY_CANCELLED			 = 'already-cancelled';
	const STATUS_CANCELLATION_NOT_POSSIBLE	 = 'cancellation-not-possible';

	/**
	 *
	 * @var \MPHB\Entities\Booking
	 */
	private $booking;

	public function __construct(){

		if ( MPHB()->settings()->main()->canUserCancelBooking() && isset( $_GET['mphb_action'] ) && $_GET['mphb_action'] === self::QUERY_ACTION ) {
			add_action( 'init', array( $this, 'checkCancellation' ), 15 );
		}
	}

	public function checkCancellation(){

		if ( !$this->parseRequest() ) {
			$this->redirectWithStatus( self::STATUS_INVALID_REQUEST );
		}

		if ( $this->booking->getStatus() === \MPHB\PostTypes\BookingCPT\Statuses::STATUS_CANCELLED ) {
			$this->redirectWithStatus( self::STATUS_ALREADY_CANCELLED );
		}

		if ( !mphb_is_locking_booking( $this->booking ) ) {
			$this->redirectWithStatus( self::STATUS_CANCELLATION_NOT_POSSIBLE );
		}

		$this->booking->setStatus( \MPHB\PostTypes\BookingCPT\Statuses::STATUS_CANCELLED );
		$isSaved = MPHB()->getBookingRepository()->save( $this->booking );

		if ( !$isSaved ) {
			$this->redirectWithStatus( self::STATUS_CANCELLATION_NOT_POSSIBLE );
		}

		do_action( 'mphb_customer_cancelled_booking', $this->booking );
		$this->redirectWithStatus( self::STATUS_CANCELLED );
	}

	/**
	 *
	 * @return bool
	 */
	private function parseRequest(){

		if ( !$this->issetRequiredParameters() ) {
			return false;
		}

		$allowedArgs = array(
			'booking_id',
			'booking_key',
			'mphb_action',
			'token'
		);

		if ( !MPHB()->userActions()->getActionLinkHelper()->isValidToken( $allowedArgs ) ) {
			return false;
		}

		$bookingId = absint( $_GET['booking_id'] );

		if ( get_post_type( $bookingId ) !== MPHB()->postTypes()->booking()->getPostType() ) {
			return false;
		}

		$booking = MPHB()->getBookingRepository()->findById( $bookingId );

		if ( !$booking ) {
			return false;
		}

		$bookingKey = sanitize_text_field( $_GET['booking_key'] );

		if ( $booking->getKey() !== $bookingKey ) {
			return false;
		}

		$this->booking = $booking;

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	private function issetRequiredParameters(){
		return isset( $_GET['booking_id'] ) &&
			isset( $_GET['booking_key'] ) &&
			isset( $_GET['token'] );
	}

	private function redirectWithStatus( $status ){

		$pageUrl = MPHB()->settings()->pages()->getUserCancelRedirectPageUrl();

		$redirectUrl = add_query_arg( 'mphb_cancellation_status', $status, $pageUrl ? $pageUrl : home_url()  );

		wp_redirect( $redirectUrl );
		exit;
	}

	/**
	 *
	 * @param \MPHB\Entities\Booking $booking
	 * @return string
	 */
	public function generateLink( \MPHB\Entities\Booking $booking ){

		$args = array(
			'booking_id'	 => $booking->getId(),
			'booking_key'	 => $booking->getKey(),
			'mphb_action'	 => self::QUERY_ACTION,
		);

		return MPHB()->userActions()->getActionLinkHelper()->generateLink( $args );
	}

}
