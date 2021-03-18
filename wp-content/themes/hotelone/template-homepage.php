<?php 
/*
 * Template Name: Front Page
 */
if ( is_page_template() ) {
	
	get_header();
		
		if(function_exists('bc_init')){
			$option = wp_parse_args(  
			get_option( 'hotelone_option', array() ), 
			array(
			'layout_manager_data' => 'service,room,testimonial,gallery,blog,callout' 
			) );
		}else{
			$option = wp_parse_args(  
			get_option( 'hotelone_option', array() ), 
			array(
			'layout_manager_data' => 'service,room,gallery,blog,callout' 
			) );
		}
		
		$data = is_array($option['layout_manager_data']) ? $option['layout_manager_data'] : explode(",",$option['layout_manager_data']);
		if($data){
			foreach($data as $key=>$value){
				if($value=='testimonial'){
					do_action('hotelone_sections', false);
				}else{
					hotelone_load_section( $value );
				}
			}
		}
		
	get_footer();
	
} else {
	
	include get_page_template();
	
}