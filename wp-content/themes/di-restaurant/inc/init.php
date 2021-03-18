<?php
/**
 * Bootstrap the theme.
 *
 * @package Di Restaurant
 */

// Add class Di_Restaurant_Engine, responsible for setup, styles, scripts, sidebar registration.
require_once get_template_directory() . '/inc/core/class-di-restaurant-engine.php';

// Add class Di_Restaurant_Actions_Filter, responsible for mostly actions and filters.
require_once get_template_directory() . '/inc/core/class-di-restaurant-actions-filters.php';

// Add class Di_Restaurant_Topmain_Nav_Walker, nav walker for main top nav.
require_once get_template_directory() . '/inc/core/class-di-restaurant-topmain-nav-walker.php';

// Add class Di_Restaurant_Methods, for individual method.
require_once get_template_directory() . '/inc/core/class-di-restaurant-methods.php';

// Add class Di_Restaurant_Page_Metabox, for page metabox options.
require_once get_template_directory() . '/inc/core/class-di-restaurant-page-metabox.php';

// Add class Di_Restaurant_Post_Metabox, for page metabox options.
require_once get_template_directory() . '/inc/core/class-di-restaurant-post-metabox.php';

// Add Di Restaurant Theme Page..
require_once get_template_directory() . '/inc/core/class-di-restaurant-theme-page.php';

// Add class Di_Restaurant_Woo.
require_once get_template_directory() . '/inc/core/class-di-restaurant-woo.php';

// Add class TGM_Plugin_Activation.
require_once get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';

// Add TGM_Plugin_Activation config.
require_once get_template_directory() . '/inc/tgm/tgm-options.php';

// Include kirki plugin files if it is not activated.
if ( ! class_exists( 'Kirki' ) ) {
	require get_template_directory() . '/inc/kirki/kirki/kirki.php';
}

// Include the kirki options file.
require get_template_directory() . '/inc/kirki/kirki-options.php';



