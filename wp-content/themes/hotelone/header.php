<?php 
/**
 * The header for the HotelOne theme.
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hotelone
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<meta http-equiv="Content-Security-Policy">
	<?php wp_head(); ?>	
</head>
<body <?php body_class(); ?>>

<?php 
  if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
  }else{
    do_action( 'wp_body_open' );
  }
?>

<?php do_action( 'hotelone_before_site_start' ); ?>
<div id="wrapper">
	
	<?php 
	hotelone_header();
	?>