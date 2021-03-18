<?php

namespace MPHB\Views;

use \MPHB\Entities;

class ReservedRoomView {

	/**
	 *
	 * @param \MPHB\Entities\ReservedRoom $reservedRoom
	 */
	public static function renderServicesList( Entities\ReservedRoom $reservedRoom ){
		$reservedServices = $reservedRoom->getReservedServices();
		if ( !empty( $reservedServices ) ) {
			?>
			<p>
				<?php
				foreach ( $reservedServices as $reservedService ) {
					$reservedService = apply_filters( '_mphb_translate_reserved_service', $reservedService );
					echo $reservedService->getTitle();
					if ( $reservedService->isPayPerAdult() ) {
						echo ' <em>';
						printf( _n( 'x %d adult', 'x %d adults', $reservedService->getAdults(), 'motopress-hotel-booking' ), $reservedService->getAdults() );
						echo '</em>';
					}
                    if ($reservedService->isFlexiblePay()) {
                        echo ' <em>';
                        printf(_n('x %d time', 'x %d times', $reservedService->getQuantity(), 'motopress-hotel-booking'), $reservedService->getQuantity());
                        echo '</em>';
                    }
					echo '<br />';
				}
				?>
			</p>
			<?php
		} else {
			echo "&#8212;";
		}
	}

}
