<?php
/**
 * Include the TGM_Plugin_Activation class. (included in init.php)
 */

function di_restaurant_register_required_plugins() {
	
	$plugins = array(

		array(
			'name'      => __( 'Di Themes Demo Site Importer', 'di-restaurant'),
			'slug'      => 'di-themes-demo-site-importer',
			'required'  => false,
		),

		array(
			'name'      => __( 'Di Blocks - Awesome blocks for new editor', 'di-restaurant'),
			'slug'      => 'di-blocks',
			'required'  => false,
		),

		array(
			'name'      => __( 'Elementor Page Builder', 'di-restaurant'),
			'slug'      => 'elementor',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'WooCommerce (For E-Commerce)', 'di-restaurant'),
			'slug'      => 'woocommerce',
			'required'  => false,
		),

		array(
			'name'      => __( 'YITH WooCommerce Quick View', 'di-restaurant'),
			'slug'      => 'yith-woocommerce-quick-view',
			'required'  => false,
		),

		array(
			'name'      => __( 'WooCommerce Wishlist Plugin', 'di-restaurant'),
			'slug'      => 'ti-woocommerce-wishlist',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'Contact Form 7 (For Forms)', 'di-restaurant'),
			'slug'      => 'contact-form-7',
			'required'  => false,
		),

		array(
			'name'      => __( 'Regenerate Thumbnails', 'di-restaurant'),
			'slug'      => 'regenerate-thumbnails',
			'required'  => false,
		),
		
	);
	
	
	$config = array(
		'id'           => 'di-restaurant',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'di-restaurant-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'di_restaurant_register_required_plugins' );

