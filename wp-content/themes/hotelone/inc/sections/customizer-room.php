<?php
function hotelone_customizer_room( $wp_customize ){
	
	$pages  =  get_pages();
	$hotelone_option_pages = array();
	$hotelone_option_pages[0] = esc_html__( 'Select page', 'hotelone' );
	foreach( $pages as $page ){
		$hotelone_option_pages[ $page->ID ] = $page->post_title;
	}
	
	$wp_customize->add_panel( 'hotelone_room_panel' ,
		array(
			'priority'        => 34,
			'title'           => esc_html__( 'Section: Room', 'hotelone' ),
			'description'     => '',
			'active_callback' => 'hotelone_showon_frontpage'
		)
	);
	
		$wp_customize->add_section( 'hotelone_room_section' ,
			array(
				'priority'    => 1,
				'title'       => esc_html__( 'Section Settings', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_room_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_room_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'hotelone_room_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_room_section',
					'description' => esc_html__('Check this box to hide this section.', 'hotelone'),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_room_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => wp_kses_post('Our <span>Rooms</span>', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_room_title',
				array(
					'label'     => esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_room_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_room_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => wp_kses_post('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_room_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_room_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_room_layout',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => '6',
				)
			);

			$wp_customize->add_control( 'hotelone_room_layout',
				array(
					'label' 		=> esc_html__('Room Layout Settings', 'hotelone'),
					'section' 		=> 'hotelone_room_section',
					'description'   => '',
					'type'          => 'select',
					'choices'       => array(
						'4' => esc_html__( '3 Columns', 'hotelone' ),
						'6' => esc_html__( '2 Columns', 'hotelone' ),
						'12' => esc_html__( '1 Column', 'hotelone' ),
					),
				)
			);

			// title color
			$wp_customize->add_setting( 'room_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#606060',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'room_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_room_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'room_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#858a99',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'room_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_room_section',
		        )
		    ));
			
		$wp_customize->add_section( 'hotelone_room_content' ,
			array(
				'priority'    => 2,
				'title'       => esc_html__( 'Section Content', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_room_panel',
			)
		);
		
			$wp_customize->add_setting(
				'hotelone_room',
				array(
					'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
				) );


			$wp_customize->add_control(
				new HotelOne_Customize_Repeatable_Control(
					$wp_customize,
					'hotelone_room',
					array(
						'label'     	=> esc_html__('Hotel Rooms', 'hotelone'),
						'description'   => '',
						'section'       => 'hotelone_room_content',
						'live_title_id' => 'content_page', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'hotelone'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://www.britetechs.com/free-hotelone-wordpress-theme/">Hotelone Pro</a> to be able to add more items and unlock other premium features!', 'hotelone' ) ),
						'fields'    => array(
							'content_page'  => array(
								'title' => esc_html__('Select a page', 'hotelone'),
								'type'  =>'select',
								'options' => $hotelone_option_pages
							),
							'rating'  => array(
								'title' => esc_html__('Rating', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'' => __('Rating','hotelone'),
									1 => 1,
									2 => 2,
									3 => 3,
									4 => 4,
									5 => 5,
								)
							),
							'person'  => array(
								'title' => esc_html__('Persons', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'' => __('Person','hotelone'),
									1 => 1,
									2 => 2,
									3 => 3,
									4 => 4,
								)
							),
							'price'  => array(
								'title' => esc_html__('Price', 'hotelone'),
								'type'  =>'text',
							),
							'enable_link'  => array(
								'title' => esc_html__('Link to single page', 'hotelone'),
								'type'  =>'checkbox',
							),
						),

					)
				)
			);
		
}
add_action('customize_register','hotelone_customizer_room');