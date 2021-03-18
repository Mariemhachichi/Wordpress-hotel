<?php
/**
 * selective refresh
 */
function hotelone_customizer_partials( $wp_customize ) {

    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }
	
    $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title',
		'render_callback' => 'hotelone_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'hotelone_customize_partial_blogdescription',
	) );
	
}
add_action( 'customize_register', 'hotelone_customizer_partials', 199 );


function hotelone_selective_refresh_render_section_content( $partial, $container_context = array() ) {
    $tpl = 'section-all/'.$partial->id.'.php';
    $GLOBALS['hotelone_is_selective_refresh'] = true;
    $file = hotelone_load_section( $tpl );
    if ( $file ) {
        include $file;
    }
}

function hotelone_customize_partial_blogname() {
	bloginfo( 'name' );
}

function hotelone_customize_partial_blogdescription() {
	bloginfo( 'description' );
}