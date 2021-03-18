<?php
/**
 * The Template for Cancelled Booking Email content
 *
 * Email that will be sent to customer when booking is cancelled.
 *
 * @version 2.0.0
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php printf( __( 'Dear %1$s %2$s, your reservation is cancelled!', 'motopress-hotel-booking' ), '%customer_first_name%', '%customer_last_name%' ); ?>

<br/>
<h4><?php _e( 'Details of booking', 'motopress-hotel-booking' ) ?></h4>
<?php printf( __( 'ID: #%s', 'motopress-hotel-booking' ), '%booking_id%' ); ?>
<br/>
<?php printf( __( 'Check-in: %1$s, from %2$s', 'motopress-hotel-booking' ), '%check_in_date%', '%check_in_time%' ); ?>
<br/>
<?php printf( __( 'Check-out: %1$s, until %2$s', 'motopress-hotel-booking' ), '%check_out_date%', '%check_out_time%' ); ?>
<br/>
%reserved_rooms_details%
<h4><?php _e( 'Total Price:', 'motopress-hotel-booking' ) ?></h4>
%booking_total_price%
<br/>
<h4><?php _e( 'Customer Information', 'motopress-hotel-booking' ); ?></h4>
<?php printf( __( 'Name: %1$s %2$s', 'motopress-hotel-booking' ), '%customer_first_name%', '%customer_last_name%' ); ?>
<br/>
<?php printf( __( 'Email: %s', 'motopress-hotel-booking' ), '%customer_email%' ); ?>
<br/>
<?php printf( __( 'Phone: %s', 'motopress-hotel-booking' ), '%customer_phone%' ); ?>
<br/>
<?php printf( __( 'Note: %s', 'motopress-hotel-booking' ), '%customer_note%' ); ?>
<br/><br/>
<?php _e( 'Thank you!', 'motopress-hotel-booking' ); ?>