<?php
/**
 * Available variables
 * - string $title
 * - string $minPrice
 * - string $description
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php do_action('mphb_sc_room_rates_item_top'); ?>

<?php echo $title; ?>, <?php printf( __( 'from %s', 'motopress-hotel-booking' ), $minPrice ); ?>
<br/>
<?php echo $description; ?>

<?php do_action('mphb_sc_room_rates_item_bottom'); ?>