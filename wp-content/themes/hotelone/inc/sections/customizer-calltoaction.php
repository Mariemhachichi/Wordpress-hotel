<?php
function hotelone_customizer_calltoaction( $wp_customize ){
	$wp_customize->add_panel( 'hotelone_calltoaction_panel' ,
		array(
			'priority'        => 39,
			'title'           => esc_html__( 'Section: Call To Action', 'hotelone' ),
			'description'     => '',
			'active_callback' => 'hotelone_showon_frontpage'
		)
	);
	
		$wp_customize->add_section( 'hotelone_calltoaction_section' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Section Settings', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_calltoaction_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_calltoaction_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => get_theme_mod('hotelone_calltoaction_hide',true),
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_calltoaction_section',
					'description' => esc_html__('Check this box to hide this section.', 'hotelone'),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => get_theme_mod('hotelone_calltoaction_title',wp_kses_post('WordPress Theme For Hotels', 'hotelone')),
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_title',
				array(
					'label'    		=> esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => get_theme_mod('hotelone_calltoaction_subtitle',wp_kses_post('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore', 'hotelone')),
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_btn_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => get_theme_mod('hotelone_calltoaction_btn_text',esc_html__('Download Now', 'hotelone')),
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_btn_text',
				array(
					'label'     => esc_html__('Call To Action Button Text', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_btn_URL',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => get_theme_mod('hotelone_calltoaction_btn_URL','#'),
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_btn_URL',
				array(
					'label'     => esc_html__('Call To Action Button URL', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
					'description'   => '',
				)
			);

			// title color
			$wp_customize->add_setting( 'calltoaction_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#ffffff',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'calltoaction_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_calltoaction_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'calltoaction_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#ffffff',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'calltoaction_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_calltoaction_section',
		        )
		    ));
			
		$wp_customize->add_section( 'hotelone_calltoactionbg_section' , array(
			'title'      => __('Section Background', 'hotelone'),
			'panel'  => 'hotelone_calltoaction_panel',
		) );
			$wp_customize->add_setting( 'hotelone_calltoaction_bgcolor', array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => '#333',
                'transport' => 'postMessage',
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'hotelone_calltoaction_bgcolor',
                array(
                    'label'       => esc_html__( 'Background Color', 'hotelone' ),
                    'section'     => 'hotelone_calltoactionbg_section',
                    'description' => esc_html__( 'Change the background color of this section.', 'hotelone' ),
                )
            ));
			$wp_customize->add_setting( 'hotelone_calltoaction_bgimage',
				array(
					'sanitize_callback' => 'esc_url_raw',
					'default'           => get_theme_mod('hotelone_calltoaction_bgimage',''),
				)
			);
			$wp_customize->add_control( new WP_Customize_Image_Control(
				$wp_customize,
				'hotelone_calltoaction_bgimage',
				array(
					'label' 		=> esc_html__('Background image', 'hotelone'),
					'section' 		=> 'hotelone_calltoactionbg_section',
					'description' => esc_html__('Upload the background image for this section.', 'hotelone' ),
				)
			));
}
add_action('customize_register','hotelone_customizer_calltoaction');