<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class Hotelone_Contactform_Info extends WP_Customize_Control {
	
	public function enqueue() {
		Hotelone_Plugin_Install_Helper::instance()->enqueue_scripts();
	}

	public function render_content() {
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			printf(
				esc_html__( 'You should be able to see the contact form in contact section on your front-page. You can configure settings from %s, in your WordPress dashboard.', 'hotelone' ),
				sprintf( '<b>%s</b>', esc_html__( 'Contact > Add New', 'hotelone' ) )
			);
		} else {
			printf(
				esc_html__( 'To access contact form in contact section in front page, you need to install the %s plugin.','hotelone' ),
				esc_html( 'contact form 7' )
			);
			echo wp_kses_post( $this->create_plugin_install_button('contact-form-7') );
		}
	}
	public function create_plugin_install_button( $slug ) {
		return Hotelone_Plugin_Install_Helper::instance()->get_button_html( $slug );
	}
}