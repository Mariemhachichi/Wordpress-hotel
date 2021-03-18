<?php

namespace MPHB\BookingRules\Reservation;

use \MPHB\BookingRules\RuleVerifiable;

abstract class Rule implements RuleVerifiable {

	/**
	 * @var int[]
	 */
	protected $seasonIds;
	/**
	 * @var int[]
	 */
	protected $roomTypeIds;

	/**
	 * Rule constructor.
	 *
	 * @param array $atts
	 */
	public function __construct( $atts ) {
		$this->seasonIds   = array_map( 'intval', $atts['season_ids'] );
		$this->roomTypeIds = array_map( 'intval', $atts['room_type_ids'] );
	}

    // Don't redeclare functions of the interface. Fixes "Fatal error: Can't inherit abstract
    // function RuleVerifiable::verify() (previously declared abstract in Rule)" on PHP 5.3
    //
    // abstract public function verify( \DateTime $checkInDate, \DateTime $checkOutDate, $roomTypeId = 0 );

	/**
	 * @param \DateTime $date
	 * @param int       $roomTypeId
	 *
	 * @return bool
	 */
	public function isFor( $date, $roomTypeId ) {
		return $this->isForRoomType( $roomTypeId ) && $this->isForDate( $date );
	}

	/**
	 * @param \DateTime $date
	 *
	 * @return bool
	 */
	public function isForDate( $date ) {

		if ( $this->isForAllSeason() ) {
			return true;
		}

		$isForDate = false;
		foreach ( $this->seasonIds as $seasonId ) {
			$season = MPHB()->getSeasonRepository()->findById( $seasonId );
			if ( !is_null( $season ) && $season->isDateInSeason( $date ) ) {
				$isForDate = true;
				break;
			}
		}

		return $isForDate;
	}

	/**
	 * @param int $roomTypeId
	 *
	 * @return bool
	 */
	public function isForRoomType( $roomTypeId ) {
		return in_array( $roomTypeId, $this->roomTypeIds ) || $this->isForAllRoomTypes();
	}

	/**
	 * @return bool
	 */
	public function isForAllRoomTypes() {
		return in_array( 0, $this->roomTypeIds );
	}

	/**
	 * @return bool
	 */
	public function isForAllSeason() {
		return in_array( 0, $this->seasonIds );
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array(
			'season_ids'    => $this->seasonIds,
			'room_type_ids' => $this->roomTypeIds,
		);
	}

	/**
	 * @return array
	 */
	public function getRoomTypeIds() {
		return $this->roomTypeIds;
	}

	/**
	 * @return array
	 */
	public function getSeasonIds() {
		return $this->seasonIds;
	}

}
