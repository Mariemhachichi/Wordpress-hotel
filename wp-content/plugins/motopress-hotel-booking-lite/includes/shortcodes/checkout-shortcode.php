<?php

namespace MPHB\Shortcodes;

use \MPHB\Entities;

class CheckoutShortcode extends AbstractShortcode {

	protected $name = 'mphb_checkout';

	const STEP_CHECKOUT				 = 'checkout';
	const STEP_BOOKING				 = 'booking';
	const STEP_COMPLETE				 = 'complete';
	const NONCE_ACTION_CHECKOUT		 = 'mphb-checkout';
	const NONCE_ACTION_BOOKING		 = 'mphb-booking';
	const NONCE_NAME					 = 'mphb-checkout-nonce';
	const RECOMMENDATION_NONCE_NAME	 = 'mphb-checkout-recommendation-nonce';
	const BOOKING_CID_NAME			 = 'mphb-booking-checkout-id';

	/**
	 *
	 * @var string
	 */
	protected $currentStep;

	/*
	 *  Booking info
	 */
	protected $isCorrectPage	 = false;
	protected $isCorrectNonce	 = false;

	/**
	 *
	 * @var CheckoutShortcode\Step[]
	 */
	protected $steps = array();

	public function __construct(){
		parent::__construct();

		$this->steps[self::STEP_CHECKOUT]	 = new CheckoutShortcode\StepCheckout();
		$this->steps[self::STEP_BOOKING]	 = new CheckoutShortcode\StepBooking();
		$this->steps[self::STEP_COMPLETE]	 = new CheckoutShortcode\StepComplete();

		add_action( 'mphb_sc_checkout_errors_content', array( $this, 'showErrorsContent' ) );
		add_filter( 'mphb_sc_checkout_error', array( $this, 'filterErrorOutput' ) );

		add_action( 'wp', array( $this, 'setup' ) );

		add_action( 'template_redirect', array( $this, 'enforceSSLRedirect' ) );
	}

	public function setup(){

		$this->isCorrectPage = mphb_is_checkout_page();

		if ( !$this->isCorrectPage ) {
			return;
		}

		$this->currentStep = $this->detectStep();

		if ( !$this->checkNonce() ) {
			return;
		}

		$this->steps[$this->currentStep]->setup();
	}

	/**
	 *
	 * @return string
	 */
	protected function detectStep(){
		$step = ( isset( $_REQUEST['mphb_checkout_step'] ) ? mphb_clean( $_REQUEST['mphb_checkout_step'] ) : self::STEP_CHECKOUT );

		if ( $step === self::STEP_BOOKING && MPHB()->getSession()->get( 'mphb_checkout_step' ) === self::STEP_COMPLETE ) {

			// Is it a rebooking?
			$checkoutId = isset( $_REQUEST[self::BOOKING_CID_NAME] ) ? mphb_clean( $_REQUEST[self::BOOKING_CID_NAME] ) : '';
			$unfinishedBooking = ( $checkoutId != '' ) ? MPHB()->getBookingRepository()->findByCheckoutId( $checkoutId ) : null;

			if ( empty( $unfinishedBooking ) ) {
				// Nope, just go to the next step, as it was in previous versions
				$step = self::STEP_COMPLETE;
			}

		}

		return $step;
	}

	/**
	 *
	 * @return boolean
	 */
	protected function checkNonce(){

		$nonce = '';

		if ( $this->currentStep === self::STEP_CHECKOUT ) {

			$nonceAction = self::NONCE_ACTION_CHECKOUT;

			if ( isset( $_POST[self::NONCE_NAME] ) ) {
				$nonce = $_POST[self::NONCE_NAME];
			} else if ( isset( $_POST[self::RECOMMENDATION_NONCE_NAME] ) ) {
				$nonce = $_POST[self::RECOMMENDATION_NONCE_NAME];
			}
		} else {

			$nonceAction = self::NONCE_ACTION_BOOKING;

			if ( isset( $_POST[self::BOOKING_CID_NAME] ) ) {
				$nonceAction .= '-' . $_POST[self::BOOKING_CID_NAME];
			}

			if ( isset( $_POST[self::NONCE_NAME] ) ) {
				$nonce = $_POST[self::NONCE_NAME];
			}
		}

		$this->isCorrectNonce = wp_verify_nonce( $nonce, $nonceAction );

		return $this->isCorrectNonce;
	}

	/**
	 *
	 * @param array $atts
	 * @param string $content
	 * @param string $shortcodeName
	 * @return string
	 */
	public function render( $atts, $content = '', $shortcodeName ){

		$defaultAtts = array(
			'class' => ''
		);

		$atts = shortcode_atts( $defaultAtts, $atts, $shortcodeName );

		ob_start();

		if ( $this->isCorrectPage && $this->isCorrectNonce && !MPHB()->settings()->main()->isBookingDisabled() ) {
			$this->steps[$this->currentStep]->render();
		}

		$content = ob_get_clean();

		$wrapperClass = apply_filters( 'mphb_sc_checkout_wrapper_classes', 'mphb_sc_checkout-wrapper' );
		$wrapperClass .= empty( $wrapperClass ) ? $atts['class'] : ' ' . $atts['class'];
		return '<div class="' . esc_attr( $wrapperClass ) . '">' . $content . '</div>';
	}

	/**
	 *
	 * @param array $errors
	 */
	public function showErrorsContent( $errors ){
		foreach ( $errors as $error ) {
			echo apply_filters( 'mphb_sc_checkout_error', $error );
		}
	}

	public function filterErrorOutput( $error ){
		return '<br/>' . $error;
	}

	/**
	 * Handle redirections for SSL enforced checkouts
	 */
	public function enforceSSLRedirect(){

		if ( is_ssl() ) {
			return;
		}

		if ( !mphb_is_checkout_page() || !MPHB()->settings()->payment()->isForceCheckoutSSL() ) {
			return;
		}

		if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
			$url = preg_replace( '|^http://|', 'https://', $_SERVER['REQUEST_URI'] );
		} else {
			$url = 'https://' . (!empty( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST'] ) . $_SERVER['REQUEST_URI'];
		}

		wp_safe_redirect( $url );
		exit;
	}

}
