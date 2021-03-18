<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class Hotelone_Woocommerce_Info extends WP_Customize_Control {
	
	public function enqueue() {
		Hotelone_Plugin_Install_Helper::instance()->enqueue_scripts();
	}

	public function render_content() {
		
		echo '<h2>Britetechs Companion</h2>';		
		if ( function_exists('bc_init') ) {
			printf(
				esc_html__( 'Now you should be able to see the team and testimonial sections on your front-page. You can configure settings from %s, in your customize option panel.', 'hotelone' ),
				sprintf( '<b>%s</b>', esc_html__( 'Dashboard > Apprearance >> Customize', 'hotelone' ) )
			);
		} else {
			printf(
				esc_html__( 'To access team and testimonial sections in front page, you need to install and activate the %s plugin.','hotelone' ),
				esc_html( 'britetechs companion' )
			);
			echo $this->create_plugin_install_button('britetechs-companion');			
		}
	}
	public function create_plugin_install_button( $slug ) {
		return Hotelone_Plugin_Install_Helper::instance()->get_button_html( $slug );
	}
}