<?php

namespace MPHB\BookingRules\Reservation;


use MPHB\BookingRules\RuleVerifiable;

/**
 * Class RulesHolder
 * @package MPHB\BookingRules\Reservation
 */
class RulesHolder implements RuleVerifiable {

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var Rule[]
	 */
	protected $rules;

	/**
	 * RulesChecker constructor.
	 *
	 * @param Rule[] $rules
	 * @param string $type
	 */
	public function __construct( $rules, $type ) {
		$this->rules = $rules;
		$this->type  = $type;
	}

	/**
	 * @param \DateTime $checkInDate
	 * @param int       $roomTypeId
	 *
	 * @return Rule|null
	 */
	public function findActualRule( $checkInDate, $roomTypeId = 0 ) {
		$actualRule = null;
		foreach ( $this->rules as $rule ) {
			if ( $rule->isFor( $checkInDate, $roomTypeId ) ) {
				$actualRule = $rule;
				break;
			}
		}

		return $actualRule;
	}

	/**
	 * @param \DateTime $date
	 *
	 * @return CheckInRule|CheckOutRule|MaxDaysRule|MinDaysRule
	 */
	public function findActualCombinedRule( $date ) {
		$actualRules = array();

		$allRoomTypeIds = MPHB()->getRoomTypePersistence()->getPosts( array(
			'mphb_language' => 'original',
		) );

		$processedRoomTypes = array();
		foreach ( $this->rules as $rule ) {
			$roomTypes = array_diff( $rule->getRoomTypeIds(), $processedRoomTypes );

			if ( empty( $roomTypes ) ) {
				continue;
			}

			if ( !$rule->isForDate( $date ) ) {
				continue;
			}

			$actualRules[]      = $rule;
			$processedRoomTypes = array_merge($processedRoomTypes, $rule->getRoomTypeIds());

			if ( $rule->isForAllRoomTypes() ) {
				break;
			}

			// All Room Processed
			if ( !array_diff( $allRoomTypeIds, $processedRoomTypes ) ) {
				break;
			}

		}

		return $this->combineRules( $actualRules );
	}

	/**
	 * @param Rule[] $rules
	 *
	 * @return CheckInRule|CheckOutRule|MaxDaysRule|MinDaysRule
	 */
	private function combineRules( $rules ) {
		switch ( $this->type ) {
			case ReservationRules::RULE_CHECK_IN:
				$combinedRule = $this->combineCheckInRules( $rules );
				break;
			case ReservationRules::RULE_CHECK_OUT:
				$combinedRule = $this->combineCheckOutRules( $rules );
				break;
			case ReservationRules::RULE_MIN_STAY:
				$combinedRule = $this->combineMinStayRules( $rules );
				break;
			case ReservationRules::RULE_MAX_STAY:
				$combinedRule = $this->combineMaxStayRules( $rules );
				break;
		}

		return $combinedRule;
	}

	/**
	 * @param CheckInRule[] $rules
	 *
	 * @return CheckInRule
	 */
	private function combineCheckInRules( $rules ) {
		$days = array();
		foreach ( $rules as $rule ) {
			$days = array_merge( $days, $rule->getDays() );
		}
		$days = array_unique( $days );

		return new CheckInRule( array(
			'season_ids'    => array( 0 ),
			'room_type_ids' => array( 0 ),
			'check_in_days' => $days,
		) );
	}

	/**
	 * @param CheckOutRule[] $rules
	 *
	 * @return CheckOutRule
	 */
	private function combineCheckOutRules( $rules ) {
		$days = array();
		foreach ( $rules as $rule ) {
			$days = array_merge( $days, $rule->getDays() );
		}
		$days = array_unique( $days );

		return new CheckOutRule( array(
			'season_ids'     => array( 0 ),
			'room_type_ids'  => array( 0 ),
			'check_out_days' => $days,
		) );
	}

	/**
	 * @param MinDaysRule[] $rules
	 *
	 * @return MinDaysRule
	 */
	private function combineMinStayRules( $rules ) {
		return new MinDaysRule( array(
			'season_ids'     => array( 0 ),
			'room_type_ids'  => array( 0 ),
			'min_stay_length' => min( array_map( function ( MinDaysRule $rule ) {
				return $rule->getMinDays();
			}, $rules ) ),
		) );
	}

	/**
	 * @param MaxDaysRule[] $rules
	 *
	 * @return MaxDaysRule
	 */
	private function combineMaxStayRules( $rules ) {
		return new MaxDaysRule( array(
			'season_ids'     => array( 0 ),
			'room_type_ids'  => array( 0 ),
			'max_stay_length' => max( array_map( function ( MaxDaysRule $rule ) {
				return $rule->getMaxDays();
			}, $rules ) ),
		) );
	}

	/**
	 * @param \DateTime $checkInDate
	 * @param \DateTime $checkOutDate
	 * @param int       $roomTypeId
	 *
	 * @return bool
	 */
	public function verify( \DateTime $checkInDate, \DateTime $checkOutDate, $roomTypeId = 0 ) {
		$rule = $this->findActualRule( $checkInDate, $roomTypeId );

		return $rule ? $rule->verify( $checkInDate, $checkOutDate, $roomTypeId ) : true;
	}

	protected function findAllSeasonRules() {
		return array_filter( $this->rules, function ( Rule $rule ) {
			return $rule->isForAllSeason();
		} );
	}

	/**
	 * @param $roomTypeId
	 *
	 * @return Rule
	 */
	public function findAllSeasonRuleForRoomType( $roomTypeId ) {

		$allSeasonRule = null;

		// All Season Rule always exists
		foreach ( $this->rules as $rule ) {
			if ( $rule->isForAllSeason() && $rule->isForRoomType( $roomTypeId ) ) {
				$allSeasonRule = $rule;
				break;
			}
		}

		return $allSeasonRule;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array_map( function ( Rule $rule ) {
			return $rule->toArray();
		}, $this->rules );
	}

}