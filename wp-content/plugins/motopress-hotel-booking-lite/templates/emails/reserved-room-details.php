<?php
/**
 * The Template for reserved room details content
 *
 * Content that will be replace %reserved_rooms_details% tag in emails.
 *
 * @version 2.0.0
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h4><?php printf( __( 'Accommodation #%s', 'motopress-hotel-booking' ), '%room_key%' ); ?></h4>
<?php printf( __( 'Adults: %s', 'motopress-hotel-booking' ), '%adults%' ); ?>
<br/>
<?php printf( __( 'Children: %s', 'motopress-hotel-booking' ), '%children%' ); ?>
<br/>
<?php printf( __( 'Accommodation: <a href="%1$s">%2$s</a>', 'motopress-hotel-booking' ), '%room_type_link%"', '%room_type_title%' ); ?>
<br/>
<?php printf( __( 'Accommodation Rate: %s', 'motopress-hotel-booking' ), '%room_rate_title%' ); ?>
<br/>
%room_rate_description%
<br/>
<?php printf( __( 'Bed Type: %s', 'motopress-hotel-booking' ), '%room_type_bed_type%' ); ?>
<br/>
<h4><?php _e( 'Additional Services', 'motopress-hotel-booking' ); ?></h4>
%services%
<br/>