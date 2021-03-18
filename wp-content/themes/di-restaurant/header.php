<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php do_action( 'di_restaurant_the_head' ); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

<?php
if( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'di-restaurant' ); ?></a>

<?php get_template_part( 'template-parts/header', 'loading-icon' ); ?>

<?php get_template_part( 'template-parts/header', 'logo-nav' ); ?>

<?php get_template_part( 'template-parts/header', 'image' ); ?>

<?php get_template_part( 'template-parts/header', 'slider' ); ?>

<!-----##### main container post and widget section start ######------>
<div id="content" class="container">
	<div class="row container-set">
