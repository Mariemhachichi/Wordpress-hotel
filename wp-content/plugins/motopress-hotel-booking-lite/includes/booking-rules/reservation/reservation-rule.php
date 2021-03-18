<?php

namespace MPHB\BookingRules\Reservation;

use \MPHB\BookingRules\RuleVerifiable;
use \MPHB\Utils\DateUtils;

class ReservationRule implements RuleVerifiable {

	/**
	 *
	 * @var array
	 */
	protected $checkInDays;

	/**
	 *
	 * @var array
	 */
	protected $checkOutDays;

	/**
	 *
	 * @var int
	 */
	protected $minStayLength = 1;

	/**
	 *
	 * @var int 0 - no limit.
	 */
	protected $maxStayLength = 0;

	public function __construct( $atts ){
		$this->checkInDays   = $atts['check_in_days'];
		$this->checkOutDays  = $atts['check_out_days'];
		$this->minStayLength = $atts['min_stay_length'];
		$this->maxStayLength = $atts['max_stay_length'];
	}

	/**
	 *
	 * @param \DateTime $checkInDate
	 * @param \DateTime $checkOutDate
	 *
	 * @return bool
	 */
	public function verify( \DateTime $checkInDate, \DateTime $checkOutDate, $roomTypeId = 0 ){
		$nightCount  = DateUtils::calcNights( $checkInDate, $checkOutDate );
		$checkInDay  = (int)$checkInDate->format( 'w' ); // 0 (for Sunday) through 6 (for Saturday)
		$checkOutDay = (int)$checkOutDate->format( 'w' );

		return $nightCount >= $this->minStayLength
			&& ( $this->maxStayLength == 0 || $nightCount <= $this->maxStayLength )
			&& in_array( $checkInDay, $this->checkInDays )
			&& in_array( $checkOutDay, $this->checkOutDays );
	}

	/**
	 *
	 * @return array Type/X data.
	 */
	public function getData(){
		return array(
			'check_in_days'   => $this->checkInDays,
			'check_out_days'  => $this->checkOutDays,
			'min_stay_length' => $this->minStayLength,
			'max_stay_length' => $this->maxStayLength
		);
	}

}
