<?php

/**
 * Available variables
 * - int $foundRooms Count of found rooms
 * - string $checkInDate Date in human-readable format
 * - string $checkOutDate Date in human-readable format
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

?>

<p class="mphb-search-results-summary">
	<?php
		if ( $foundRooms > 0 ) {
			printf( _n( '%s accommodation found', '%s accommodations found', $foundRooms, 'motopress-hotel-booking' ), $foundRooms );
		} else {
			_e( 'No accommodations found', 'motopress-hotel-booking' );
		}

		printf( __(' from %s - till %s', 'motopress-hotel-booking'), $checkInDate, $checkOutDate );
	?>
</p>
