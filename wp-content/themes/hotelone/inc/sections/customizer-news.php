<?php
function hotelone_customizer_news( $wp_customize ){
	$wp_customize->add_panel( 'hotelone_news_panel' ,
		array(
			'priority'        => 36,
			'title'           => esc_html__( 'Section: News', 'hotelone' ),
			'description'     => '',
			'active_callback' => 'hotelone_showon_frontpage'
		)
	);
	
		$wp_customize->add_section( 'hotelone_news_section' ,
			array(
				'priority'    => 1,
				'title'       => esc_html__( 'Section Settings', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_news_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_news_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'hotelone_news_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_news_section',
					'description' => esc_html__('Check this box to hide this section.', 'hotelone'),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_news_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => wp_kses_post('Our Latest <span>News</span>', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_news_title',
				array(
					'label'     => esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_news_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_news_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => wp_kses_post('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_news_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_news_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_news_layout',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => '6',
				)
			);

			$wp_customize->add_control( 'hotelone_news_layout',
				array(
					'label' 		=> esc_html__('News Layout Settings', 'hotelone'),
					'section' 		=> 'hotelone_news_section',
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
			$wp_customize->add_setting( 'news_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#606060',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'news_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_news_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'news_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => '#858a99',
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'news_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_news_section',
		        )
		    ));
			
		$wp_customize->add_section( 'hotelone_news_content' ,
			array(
				'priority'    => 2,
				'title'       => esc_html__( 'Section Content', 'hotelone' ),
				'description' => '',
				'panel'       => 'hotelone_news_panel',
			)
		);
			
			$wp_customize->add_setting( 'hotelone_news_no',
				array(
					'sanitize_callback' => 'hotelone_sanitize_number',
					'default'           => '3',
				)
			);
			$wp_customize->add_control( 'hotelone_news_no',
				array(
					'label'     	=> esc_html__('Number of post to show', 'hotelone'),
					'section' 		=> 'hotelone_news_content',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_news_cat',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => 0,
				)
			);

			$wp_customize->add_control( new HotelOne_Category_Control(
				$wp_customize,
				'hotelone_news_cat',
				array(
					'label' 		=> esc_html__('Category to show', 'hotelone'),
					'section' 		=> 'hotelone_news_content',
					'description'   => '',
				)
			));
			
			$wp_customize->add_setting( 'hotelone_news_orderby',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => 0,
				)
			);

			$wp_customize->add_control(
				'hotelone_news_orderby',
				array(
					'label' 		=> esc_html__('Order By', 'hotelone'),
					'section' 		=> 'hotelone_news_content',
					'type'   => 'select',
					'choices' => array(
						'default' => esc_html__('Default', 'hotelone'),
						'id' => esc_html__('ID', 'hotelone'),
						'author' => esc_html__('Author', 'hotelone'),
						'title' => esc_html__('Title', 'hotelone'),
						'date' => esc_html__('Date', 'hotelone'),
						'comment_count' => esc_html__('Comment Count', 'hotelone'),
						'menu_order' => esc_html__('Order by Page Order', 'hotelone'),
						'rand' => esc_html__('Random order', 'hotelone'),
					)
				)
			);
			
			$wp_customize->add_setting( 'hotelone_news_order',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => 'desc',
				)
			);

			$wp_customize->add_control(
				'hotelone_news_order',
				array(
					'label' 		=> esc_html__('Order', 'hotelone'),
					'section' 		=> 'hotelone_news_content',
					'type'   => 'select',
					'choices' => array(
						'desc' => esc_html__('Descending', 'hotelone'),
						'asc' => esc_html__('Ascending', 'hotelone'),
					)
				)
			);
			
			$wp_customize->add_setting( 'hotelone_news_more_link',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'hotelone_news_more_link',
				array(
					'label'       => esc_html__('More News button link', 'hotelone'),
					'section'     => 'hotelone_news_content',
					'description' => esc_html__(  'It should be your blog page link.', 'hotelone' )
				)
			);
			$wp_customize->add_setting( 'hotelone_news_more_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__('Read More', 'hotelone'),
				)
			);
			$wp_customize->add_control( 'hotelone_news_more_text',
				array(
					'label'     	=> esc_html__('More News Button Text', 'hotelone'),
					'section' 		=> 'hotelone_news_content',
					'description'   => '',
				)
			);
}
add_action('customize_register','hotelone_customizer_news');