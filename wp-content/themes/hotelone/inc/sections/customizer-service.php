<?php
function hotelone_customizer_service( $wp_customize ){
	
	$pages  =  get_pages();
	$hotelone_option_pages = array();
	$hotelone_option_pages[0] = esc_html__( 'Select page', 'hotelone' );
	foreach( $pages as $page ){
		$hotelone_option_pages[ $page->ID ] = $page->post_title;
	}
	
	$wp_customize->add_panel( 'hotelone_services_panel' ,
		array(
			'priority'        => 32,
			'title'           => esc_html__( 'Section: Services', 'hotelone' ),
			'description'     => '',
			'active_callback' => 'hotelone_showon_frontpage'
		)
	);
	
		$wp_customize->add_section( 'hotelone_service_section' ,
			array(
				'priority'    => 1,
				'title'       => esc_html__( 'Section Settings', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_services_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_services_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => get_theme_mod('hotelone_services_hide',false),
				)
			);
			$wp_customize->add_control( 'hotelone_services_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_service_section',
					'description' => esc_html__('Check this box to hide this section.', 'hotelone'),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => get_theme_mod( 'hotelone_services_title', sprintf( wp_kses_post('Our <span>Features</span>','hotelone') ) ),
				)
			);
			$wp_customize->add_control( 'hotelone_services_title',
				array(
					'label'     => esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => get_theme_mod('hotelone_services_subtitle',esc_html__('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore', 'hotelone')),
				)
			);
			$wp_customize->add_control( 'hotelone_services_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_service_layout',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => get_theme_mod('hotelone_service_layout','6'),
				)
			);

			$wp_customize->add_control( 'hotelone_service_layout',
				array(
					'label' 		=> esc_html__('Services Layout Settings', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
					'description'   => '',
					'type'          => 'select',
					'choices'       => array(
						'3' => esc_html__( '4 Columns', 'hotelone' ),
						'4' => esc_html__( '3 Columns', 'hotelone' ),
						'6' => esc_html__( '2 Columns', 'hotelone' ),
						'12' => esc_html__( '1 Column', 'hotelone' ),
					),
				)
			);

			// title color
			$wp_customize->add_setting( 'service_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#606060',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'service_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_service_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'service_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#858a99',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'service_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_service_section',
		        )
		    ));
			
		$wp_customize->add_section( 'hotelone_service_content' ,
			array(
				'priority'    => 2,
				'title'       => esc_html__( 'Section Content', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_services_panel',
			)
		);
		
			$wp_customize->add_setting(
				'hotelone_services',
				array(
					'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
				) );


			$wp_customize->add_control(
				new HotelOne_Customize_Repeatable_Control(
					$wp_customize,
					'hotelone_services',
					array(
						'label'     	=> esc_html__('Service content', 'hotelone'),
						'description'   => '',
						'section'       => 'hotelone_service_content',
						'live_title_id' => 'content_page', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'hotelone'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://www.britetechs.com/free-hotelone-wordpress-theme/">Hotelone Pro</a> to be able to add more items and unlock other premium features!', 'hotelone' ) ),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'icon' => esc_html__('Icon', 'hotelone'),
									'image' => esc_html__('image', 'hotelone'),
								),
							),
							'icon'  => array(
								'title' => esc_html__('Icon', 'hotelone'),
								'type'  =>'icon',
								'required' => array( 'icon_type', '=', 'icon' ),
							),
							'image'  => array(
								'title' => esc_html__('Image', 'hotelone'),
								'type'  =>'media',
								'required' => array( 'icon_type', '=', 'image' ),
							),

							'content_page'  => array(
								'title' => esc_html__('Select a page', 'hotelone'),
								'type'  =>'select',
								'options' => $hotelone_option_pages
							),
							'enable_link'  => array(
								'title' => esc_html__('Link to single page', 'hotelone'),
								'type'  =>'checkbox',
							),
						),

					)
				)
			);
			
			$wp_customize->add_setting( 'hotelone_service_icon_size',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => get_theme_mod('hotelone_service_icon_size','5x'),
				)
			);

			$wp_customize->add_control( 'hotelone_service_icon_size',
				array(
					'label' 		=> esc_html__('Icon Size', 'hotelone'),
					'section' 		=> 'hotelone_service_content',
					'description'   => '',
					'type'          => 'select',
					'choices'       => array(
						'5x' => esc_html__( '5x', 'hotelone' ),
						'4x' => esc_html__( '4x', 'hotelone' ),
						'3x' => esc_html__( '3x', 'hotelone' ),
						'2x' => esc_html__( '2x', 'hotelone' ),
						'1x' => esc_html__( '1x', 'hotelone' ),
					),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_mbtn_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => get_theme_mod('hotelone_services_mbtn_text',esc_html__('View More Services <i class="fa fa-angle-double-right"></i>', 'hotelone')),
				)
			);
			$wp_customize->add_control( 'hotelone_services_mbtn_text',
				array(
					'label'     => esc_html__('Services More Button Text', 'hotelone'),
					'section' 		=> 'hotelone_service_content',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_mbtn_url',
				array(
					'sanitize_callback' => 'esc_url_raw',
					'default'           => get_theme_mod('hotelone_services_mbtn_url',''),
				)
			);
			$wp_customize->add_control( 'hotelone_services_mbtn_url',
				array(
					'label'     => esc_html__('Services More Button URL', 'hotelone'),
					'section' 		=> 'hotelone_service_content',
					'description'   => '',
				)
			);
}
add_action('customize_register','hotelone_customizer_service');