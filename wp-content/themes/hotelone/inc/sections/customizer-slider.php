<?php
function hotelone_customizer_slider( $wp_customize ){
	
	$wp_customize->add_panel( 'hotelone_slider_panel' ,
		array(
			'priority'        => 31,
			'title'           => esc_html__( 'Section: Slider', 'hotelone' ),
			'description'     => '',
			'active_callback' => 'hotelone_showon_frontpage'
		)
	);		
		$wp_customize->add_section( 'hotelone_slider_section' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Slider Settings', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_slider_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_slider_disable',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => get_theme_mod('hotelone_slider_disable',false),
				)
			);
			$wp_customize->add_control( 'hotelone_slider_disable',
					array(
						'type'        => 'checkbox',
						'label'       => esc_html__('Hide this section?', 'hotelone'),
						'section'     => 'hotelone_slider_section',
						'description' => esc_html__('Check this box to hide this section.', 'hotelone'),
					)
				);
				
			$wp_customize->add_section( 'hotelone_slider_images' ,
				array(
					'priority'    => 6,
					'title'       => esc_html__( 'Slider Background Images', 'hotelone' ),
					'description' => '',
					'panel'       => 'hotelone_slider_panel',
				)
			);
			
			$wp_customize->add_setting(
				'hotelone_slider_images',
				array(
					'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'default' => json_encode( array(
						array(
							'image'=> array(
								'url' => get_template_directory_uri().'/images/slider/slide1.jpg',
								'id' => ''
							)
						)
					) )
				) );

			$wp_customize->add_control(
				new HotelOne_Customize_Repeatable_Control(
					$wp_customize,
					'hotelone_slider_images',
					array(
						'label'     => esc_html__('Background Images', 'hotelone'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'hotelone_slider_images',
						'title_format'  => esc_html__( 'Background', 'hotelone'), // [live_title]
						'max_item'      => 2,
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Background Image', 'hotelone'),
								'type'  =>'media',
								'default' => array(
									'url' => get_template_directory_uri().'/images/slider/slide1.jpg',
									'id' => ''
								)
							),

						),

					)
				)
			);
			
		$wp_customize->add_section( 'hotelone_slider_content' ,
			array(
				'priority'    => 9,
				'title'       => esc_html__( 'Slider Content', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_slider_panel',

			)
		);
		
			$wp_customize->add_setting( 'hotelone_slider_rating__hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'hotelone_slider_rating__hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Disable slider rating?', 'hotelone'),
					'section'     => 'hotelone_slider_content',
					'description' => esc_html__('Check this settings to disable slider ratings.', 'hotelone')
				)
			);
			
			$wp_customize->add_setting( 'hotelone_slider_rating',
					array(
						'sanitize_callback' => 'hotelone_sanitize_select',
						'default'           => 5,
						'transport'			=> 'postMessage'
					)
				);
			$wp_customize->add_control( 'hotelone_slider_rating',
				array(
					'type'        => 'select',
					'label'       => esc_html__('Rating', 'hotelone'),
					'description'       => esc_html__('Slider Rating, Select this setting to show ratings on this slide.', 'hotelone'),
					'section'     => 'hotelone_slider_content',
					'choices' => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
						5 => 5,
					)
				)
			);
			
		
			$wp_customize->add_setting( 'hotelone_slider_bigtitle',
				array(
					'sanitize_callback' => 'hotelone_sanitize_text',
					'mod' 				=> 'html',
					'default'           => wp_kses_post( __( 'Welcome to Hotelone Theme', 'hotelone') ),
				)
			);
			$wp_customize->add_control( new hotelone_Editor_Custom_Control(
				$wp_customize,
				'hotelone_slider_bigtitle',
				array(
					'label' 		=> esc_html__('Large Text', 'hotelone'),
					'section' 		=> 'hotelone_slider_content',
					'description'   => esc_html__('Add your big section title in this setting.', 'hotelone'),
				)
			));
			
			$wp_customize->add_setting( 'hotelone_slider_subtitle',
				array(
					'sanitize_callback' => 'hotelone_sanitize_text',
					'default'			=> wp_kses_post('Lorem ipsum dolor sit amet, consectetur adipiscing elit labore et dolore magna aliqua.', 'hotelone'),
				)
			);
			$wp_customize->add_control( new hotelone_Editor_Custom_Control(
				$wp_customize,
				'hotelone_slider_subtitle',
				array(
					'label' 		=> esc_html__('Small Text', 'hotelone'),
					'section' 		=> 'hotelone_slider_content',
					'mod' 				=> 'html',
					'description'   => esc_html__('You can use text rotate slider in this textarea too.', 'hotelone'),
				)
			));
			
			$wp_customize->add_setting( 'hotelone_pbtn_text',
				array(
					'sanitize_callback' => 'hotelone_sanitize_text',
					'default'           => esc_html__('Download Now', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_pbtn_text',
				array(
					'label' 		=> esc_html__('Primary Button Text', 'hotelone'),
					'section' 		=> 'hotelone_slider_content'
				)
			);
			
			$wp_customize->add_setting( 'hotelone_pbtn_link',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'hotelone_pbtn_link',
				array(
					'label' 		=> esc_html__('Primary Button Link', 'hotelone'),
					'section' 		=> 'hotelone_slider_content'
				)
			);
			
			$wp_customize->add_setting( 'hotelone_sbtn_text',
				array(
					'sanitize_callback' => 'hotelone_sanitize_text',
					'default'           => esc_html__('View Demo', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_sbtn_text',
				array(
					'label' 		=> esc_html__('Secondary Button Text', 'hotelone'),
					'section' 		=> 'hotelone_slider_content'
				)
			);
			
			$wp_customize->add_setting( 'hotelone_sbtn_link',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'hotelone_sbtn_link',
				array(
					'label' 		=> esc_html__('Secondary Button Link', 'hotelone'),
					'section' 		=> 'hotelone_slider_content'
				)
			);

			// bit title color
			$wp_customize->add_setting( 'big_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#ffffff',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'big_title_color',
		        array(
		            'label'       => esc_html__( 'Large Text Color', 'hotelone' ),
		            'section'     => 'hotelone_slider_content',
		        )
		    ));

		    // bit title color
			$wp_customize->add_setting( 'slider_content_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#ffffff',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'slider_content_color',
		        array(
		            'label'       => esc_html__( 'Small Text Color', 'hotelone' ),
		            'section'     => 'hotelone_slider_content',
		        )
		    ));
}
add_action('customize_register','hotelone_customizer_slider');