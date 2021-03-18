<?php
// Disable kirki telemetry
add_filter( 'kirki_telemetry', '__return_false' );

//set Kirki config
Kirki::add_config( 'di_restaurant_config', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );

//the main panel
Kirki::add_panel( 'di_restaurant_options', array(
	'title'       => esc_attr__( 'Di Restaurant Options', 'di-restaurant' ),
	'description' => esc_attr__( 'All options of Di Restaurant theme', 'di-restaurant' ),
) );

//typography
Kirki::add_section( 'typography_options', array(
	'title'          => esc_attr__( 'Typography Options', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'body_typog',
	'label'       => esc_attr__( 'Body Typography', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Lora',
		'variant'        => 'regular',
		'font-size'      => '14px',
	),
	'output'      => array(
		array(
			'element' => 'body',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'stitle_typog',
	'label'       => esc_attr__( 'Site Title Typography', 'di-restaurant' ),
	'description' => esc_attr__( 'If not using logo', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Lobster',
		'variant'        => 'regular',
		'font-size'      => '28px',
		'line-height'    => '1.2',
		'letter-spacing' => '1px',
		'text-transform' => 'inherit',
	),
	'output'      => array(
		array(
			'element' => '.navbar-brand h3.site-name-pr',
		),
	),
	'transport' => 'auto',
	'active_callback'  => 'Di_Restaurant_has_custom_logo',
) );

/*
 * It should return false if has custom logo. used to hide stitle_typog setting.
 */
function Di_Restaurant_has_custom_logo() {
	if( has_custom_logo( ) ) {
		return false;
	}
	return true;
}

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'top_menu_typog',
	'label'       => esc_attr__( 'Main Menu Typography', 'di-restaurant' ),
	'description' => esc_attr__( 'Top main menu typography.', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Open Sans',
		'variant'        => '400',
		'font-size'      => '11px',
		'letter-spacing' => '2px',
		'text-transform' => 'uppercase'
	),
	'output'      => array(
		array(
			'element' => '.navbarprimary ul li a',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'h1_typog',
	'label'       => esc_attr__( 'H1 / Headline 1 Typography', 'di-restaurant' ),
	'description' => esc_attr__( 'Used as Headline of Single Post and page.', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => 'body h1, .h1',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'h2_typog',
	'label'       => esc_attr__( 'H2 / Headline 2 Typography', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => 'body h2, .h2',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'h3_typog',
	'label'       => esc_attr__( 'H3 / Headline 3 Typography', 'di-restaurant' ),
	'description' => esc_attr__( 'Used as Headline of Widgets, Posts on Archive, Comment Box.', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.3',
		'letter-spacing' => '0.5px',
		'text-transform' => 'uppercase'
	),
	'output'      => array(
		array(
			'element' => 'body h3, .h3',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'h4_typog',
	'label'       => esc_attr__( 'H4 / Headline 4 Typography', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '20px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => 'body h4, .h4',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'h5_typog',
	'label'       => esc_attr__( 'H5 / Headline 5 Typography', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '20px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => 'body h5, .h5',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'h6_typog',
	'label'       => esc_attr__( 'H6 / Headline 6 Typography', 'di-restaurant' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '20px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => 'body h6, .h6',
		),
	),
	'transport' => 'auto',
) );


//social profile
Kirki::add_section( 'social_options', array(
	'title'          => esc_attr__( 'Social Profile', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'sprofile_link_endis',
	'label'       => esc_attr__( 'Social Icons', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable Social Icons', 'di-restaurant' ),
	'section'     => 'social_options',
	'default'     => '1',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'sprofile_link_ntabs',
	'label'       => esc_attr__( 'Open Links in New Tab?', 'di-restaurant' ),
	'section'     => 'social_options',
	'default'     => '1',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );


Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_facebook',
	'label'			=> esc_attr__( 'Facebook Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> 'http://facebook.com',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_twitter',
	'label'			=> esc_attr__( 'Twitter Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> 'http://twitter.com',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_youtube',
	'label'			=> esc_attr__( 'YouTube Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> 'http://youtube.com',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_vk',
	'label'			=> esc_attr__( 'VK Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_okru',
	'label'			=> esc_attr__( 'Ok.ru (odnoklassniki) Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_linkedin',
	'label'			=> esc_attr__( 'Linkedin Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_pinterest',
	'label'			=> esc_attr__( 'Pinterest Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_instagram',
	'label'			=> esc_attr__( 'Instagram Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_telegram',
	'label'			=> esc_attr__( 'Telegram Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_snapchat',
	'label'			=> esc_attr__( 'Snapchat Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_flickr',
	'label'			=> esc_attr__( 'Flickr Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_reddit',
	'label'			=> esc_attr__( 'Reddit Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_tumblr',
	'label'			=> esc_attr__( 'Tumblr Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_yelp',
	'label'			=> esc_attr__( 'Yelp Link', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_whatsappno',
	'label'			=> esc_attr__( 'WhatsApp Number', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_skype',
	'label'			=> esc_attr__( 'Skype Id', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'di-restaurant' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'active_callback'  => array(
		array(
			'setting'  => 'sprofile_link_endis',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );
//social profile END


//color options
Kirki::add_section( 'color_options', array(
	'title'          => esc_attr__( 'Color Options', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        	=> 'color',
	'settings'    	=> 'default_a_color',
	'label'       	=> esc_attr__( 'Default Links Color', 'di-restaurant' ),
	'description'	=> esc_attr__( 'This will be color of all default links.', 'di-restaurant' ),
	'section'     	=> 'color_options',
	'default'     	=> apply_filters( 'di_restaurant_default_a_color', '#009999' ),
	'choices'     	=> array(
		'alpha' => true,
	),
	'output' => array(
		array(
			'element'  => 'body a',
			'property' => 'color',
		),
		array(
			'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			'property' => 'border-top-color',
		),
		array(
			'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			'property' => 'border-bottom-color',
		),
		array(
			'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			'property' => 'color',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        	=> 'color',
	'settings'    	=> 'default_a_mover_color',
	'label'       	=> esc_attr__( 'Default Links Color Mouse Over', 'di-restaurant' ),
	'description'	=> esc_attr__( 'This will be color of all default links on mouse over.', 'di-restaurant' ),
	'section'     	=> 'color_options',
	'default'     	=> apply_filters( 'di_restaurant_default_a_mover_color', '#009999' ),
	'choices'     	=> array(
		'alpha' => true,
	),
	'output' => array(
		array(
			'element'  => 'body a:hover, body a:focus',
			'property' => 'color',
		),
		array(
			'element'  => '.right-main-container .widget_categories ul li:hover, .right-main-container .widget_recent_entries ul li:hover, .right-main-container .widget_recent_comments ul li:hover, .right-main-container .widget_archive ul li:hover, .right-main-container .widget_meta ul li:hover, .right-main-container .widget_nav_menu ul li:hover, .right-main-container .widget_pages ul li:hover',
			'property' => 'border-left-color',
		),
		array(
			'element'  => '.right-main-container .widget_categories ul li:hover, .right-main-container .widget_recent_entries ul li:hover, .right-main-container .widget_recent_comments ul li:hover, .right-main-container .widget_archive ul li:hover, .right-main-container .widget_meta ul li:hover, .right-main-container .widget_nav_menu ul li:hover, .right-main-container .widget_pages ul li:hover',
			'property' => 'border-right-color',
		),
		array(
			'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover a',
			'property' => 'color',
		),
		array(
			'element'  => '.widgets_sidebar.widget_product_categories ul li:hover, .widgets_sidebar.woocommerce-widget-layered-nav ul li:hover',
			'property' => 'border-right-color',
		),
		array(
			'element'  => '.widgets_sidebar.widget_product_categories ul li:hover, .widgets_sidebar.woocommerce-widget-layered-nav ul li:hover',
			'property' => 'border-left-color',
		),
	),
	'transport' => 'auto',
) );

do_action( 'di_restaurant_color_options' );
//color options END


// blog options
Kirki::add_section( 'blog_options', array(
	'title'          => esc_attr__( 'Blog Options', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'blog_hdlin_typog',
	'label'       => esc_attr__( 'Post Headline Typography', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.3',
		'letter-spacing' => '0',
		'text-transform' => 'inherit',
		'text-align'	 => 'center',
	),
	'output'      => array(
		array(
			'element' => '.post-headline-typog',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'excerpt_con_typog',
	'label'       => esc_attr__( 'Contents Typography', 'di-restaurant' ),
	'description' => esc_attr__( 'Excerpt or contents typography', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => array(
		'font-family'    => 'Fauna One',
		'variant'        => 'regular',
		'font-size'      => '14px',
		'line-height'    => '1.7',
		'letter-spacing' => '0',
		'text-transform' => 'inherit',
		'text-align'	 => 'justify',
	),
	'output'      => array(
		array(
			'element' => '.excerpt-or-content-typog',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'select',
	'settings'    => 'excerpt_or_content',
	'label'       => esc_attr__( 'Contents on Archive', 'di-restaurant' ),
	'description' => esc_attr__( 'Display excerpt, contents or hide.', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => 'excerpt',
	'choices'     => array(
		'excerpt'	=> esc_attr__( 'Display Excerpt', 'di-restaurant' ),
		'content'	=> esc_attr__( 'Display Content', 'di-restaurant' ),
		'none' 		=> esc_attr__( 'None', 'di-restaurant' ),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'number',
	'settings'    => 'excerpt_length',
	'label'       => esc_attr__( 'Excerpt Length', 'di-restaurant' ),
	'description' => esc_attr__( 'How much words of content, you want to display on Archive page?', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => 40,
	'choices'     => array(
		'min'  => 1,
		'step' => 1,
	),
	'active_callback'  => array(
		array(
			'setting'  => 'excerpt_or_content',
			'operator' => '==',
			'value'    => 'excerpt',
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'select',
	'settings'    => 'dispostdt',
	'label'       => esc_attr__( 'Date to Display?', 'di-restaurant' ),
	'description' => esc_attr__( 'Display post published date, post updated date or hide.', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => 'published',
	'priority'    => 10,
	'choices'     => array(
		'published'		=> esc_attr__( 'Post Publish Date', 'di-restaurant' ),
		'updated'		=> esc_attr__( 'Post Updated Date', 'di-restaurant' ),
		'none'			=> esc_attr__( 'None', 'di-restaurant' ),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'archive_ftr_nav_endis',
	'label'       => esc_attr__( 'Footer pagination on archive', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable pagination on archive', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => '1',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'singl_ftr_nav_endis',
	'label'       => esc_attr__( 'Footer navigation on Single Post', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable navigation on single post', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => '1',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'singl_tgs_endis',
	'label'       => esc_attr__( 'Tags on Single Post', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable tags on single post', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => '1',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'select',
	'settings'    => 'blog_list_grid',
	'label'       => esc_attr__( 'Posts View on Archive', 'di-restaurant' ),
	'description' => esc_attr__( 'Display List or Grid?', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => 'list',
	'choices'     => array(
		'list'		=> esc_attr__( 'List', 'di-restaurant' ),
		'grid2c'	=> esc_attr__( 'Grid 2 Column', 'di-restaurant' ),
		'grid3c'	=> esc_attr__( 'Grid 3 Column', 'di-restaurant' ),
		'msry2c'	=> esc_attr__( 'Masonry 2 Column', 'di-restaurant' ),
		'msry3c'	=> esc_attr__( 'Masonry 3 Column', 'di-restaurant' ),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'radio-image',
	'settings'    => 'blog_archive_layout',
	'label'       => esc_attr__( 'Archive / Loop Layout', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => 'rights',
	'choices'     => array(
		'fullw'	  => get_template_directory_uri() . '/assets/images/fullw.png',
		'rights'  => get_template_directory_uri() . '/assets/images/rights.png',
		'lefts'   => get_template_directory_uri() . '/assets/images/lefts.png',
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'radio-image',
	'settings'    => 'blog_single_layout',
	'label'       => esc_attr__( 'Single Post Layout', 'di-restaurant' ),
	'section'     => 'blog_options',
	'default'     => 'rights',
	'choices'     => array(
		'fullw'	  => get_template_directory_uri() . '/assets/images/fullw.png',
		'rights'  => get_template_directory_uri() . '/assets/images/rights.png',
		'lefts'   => get_template_directory_uri() . '/assets/images/lefts.png',
	),
) );

do_action( 'di_restaurant_blog_options' );
// blog options END


// WooCommerce section.
if( class_exists( 'WooCommerce' ) ) {

	Kirki::add_section( 'woocommerce_options', array(
		'title'          => esc_attr__( 'WooCommerce Options', 'di-restaurant' ),
		'panel'          => 'di_restaurant_options',
		'capability'     => 'edit_theme_options',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'toggle',
		'settings'    => 'shop_icon_endis',
		'label'       => esc_attr__( 'Shop Icon in Menu', 'di-restaurant' ),
		'description' => esc_attr__( 'Enable/Disable shop icon in menu.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'toggle',
		'settings'    => 'cart_icon_endis',
		'label'       => esc_attr__( 'Cart Icon in Menu', 'di-restaurant' ),
		'description' => esc_attr__( 'Enable/Disable cart icon in menu.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'toggle',
		'settings'    => 'myaccount_icon_endis',
		'label'       => esc_attr__( 'My Account Icon in Menu', 'di-restaurant' ),
		'description' => esc_attr__( 'Enable/Disable my account icon in menu.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'toggle',
		'settings'    => 'support_gallery_zoom',
		'label'       => esc_attr__( 'Gallery Zoom', 'di-restaurant' ),
		'description' => esc_attr__( 'Enable/Disable gallery zoom support on single product.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'toggle',
		'settings'    => 'support_gallery_lightbox',
		'label'       => esc_attr__( 'Gallery Light Box', 'di-restaurant' ),
		'description' => esc_attr__( 'Enable/Disable gallery light box support on single product.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'			=> 'toggle',
		'settings'		=> 'support_gallery_slider',
		'label'			=> esc_attr__( 'Gallery Slider', 'di-restaurant' ),
		'description'	=> esc_attr__( 'Enable/Disable gallery slider support on single product.', 'di-restaurant' ),
		'section'		=> 'woocommerce_options',
		'default'		=> '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'			=> 'toggle',
		'settings'		=> 'order_again_btn',
		'label'			=> esc_attr__( 'Display Order Again Button?', 'di-restaurant' ),
		'description'	=> esc_attr__( 'It will show / hide order again button on singe order page.', 'di-restaurant' ),
		'section'		=> 'woocommerce_options',
		'default'		=> '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'toggle',
		'settings'    => 'display_related_prdkt',
		'label'       => esc_attr__( 'Related Products', 'di-restaurant' ),
		'description' => esc_attr__( 'Enable/Disable WooCommerce Related Products,', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'number',
		'settings'    => 'product_per_page',
		'label'       => esc_attr__( 'Number of products display on loop page', 'di-restaurant' ),
		'description' => esc_attr__( 'How much products you want to display on loop page?', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => 12,
		'choices'     => array(
			'min'  => 0,
			'max'  => 100,
			'step' => 1,
		),
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'slider',
		'settings'    => 'product_per_column',
		'label'       => esc_attr__( 'Number of products display per column', 'di-restaurant' ),
		'description' => esc_attr__( 'How much products you want to display in single line?', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => 4,
		'choices'     => array(
			'min'  => '2',
			'max'  => '5',
			'step' => '1',
			),
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'color',
		'settings'    => 'woo_onsale_lbl_clr',
		'label'       => esc_attr__( 'OnSale Sign Color', 'di-restaurant' ),
		'description' => esc_attr__( 'This will be color of OnSale Sign of products.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => apply_filters( 'di_restaurant_woo_onsale_lbl_clr', '#ffffff' ),
		'choices'     => array(
			'alpha' => true,
		),
		'output' => array(
			array(
				'element'	=> '.woocommerce span.onsale',
				'property'	=> 'color',
			),
		),
		'transport' => 'auto'
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'color',
		'settings'    => 'woo_onsale_lbl_bg_clr',
		'label'       => esc_attr__( 'OnSale Sign Background Color', 'di-restaurant' ),
		'description' => esc_attr__( 'This will be background color of OnSale Sign of products.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => apply_filters( 'di_restaurant_woo_onsale_lbl_bg_clr', '#009999' ),
		'choices'     => array(
			'alpha' => true,
		),
		'output' => array(
			array(
				'element'	=> '.woocommerce span.onsale',
				'property'	=> 'background-color',
			),
		),
		'transport' => 'auto'
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'color',
		'settings'    => 'woo_price_clr',
		'label'       => esc_attr__( 'Product Price Color', 'di-restaurant' ),
		'description' => esc_attr__( 'This will be color of product price.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => apply_filters( 'di_restaurant_woo_price_clr', '#009999' ),
		'choices'     => array(
			'alpha' => true,
		),
		'output' => array(
			array(
				'element'	=> '.woocommerce ul.products li.product .price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce .widget_sidebar_main .woocommerce-Price-amount.amount',
				'property'	=> 'color',
			),
		),
		'transport' => 'auto'
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'custom',
		'settings'    => 'info_woo_layout',
		'section'     => 'woocommerce_options',
		'default'     => '<hr /><div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">' . esc_attr__( 'Layouts: For Cart, Checkout and My Account pages layout, use: Template option under Page Attributes on page edit screen.', 'di-restaurant' ) . '</div>',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'custom',
		'settings'    => 'info_woo_layout',
		'section'     => 'woocommerce_options',
		'default'     => '<hr /><div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">' . esc_attr__( 'Layouts: For Cart, Checkout and My Account pages layout, use: Template option under Page Attributes on page edit screen.', 'di-restaurant' ) . '</div>',
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'radio-image',
		'settings'    => 'woo_layout',
		'label'       => esc_attr__( 'Shop / Archive Page Layout', 'di-restaurant' ),
		'description' => esc_attr__( 'This layout will apply on shop, archive, search (products loop) pages.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => 'fullw',
		'choices'     => array(
			'fullw'	  => get_template_directory_uri() . '/assets/images/fullw.png',
			'rights'  => get_template_directory_uri() . '/assets/images/rights.png',
			'lefts'   => get_template_directory_uri() . '/assets/images/lefts.png',
		),
	) );

	Kirki::add_field( 'di_restaurant_config', array(
		'type'        => 'radio-image',
		'settings'    => 'woo_singleprod_layout',
		'label'       => esc_attr__( 'Single Product Page Layout', 'di-restaurant' ),
		'description' => esc_attr__( 'This layout will apply on single product page.', 'di-restaurant' ),
		'section'     => 'woocommerce_options',
		'default'     => 'fullw',
		'choices'     => array(
			'fullw'	  => get_template_directory_uri() . '/assets/images/fullw.png',
			'rights'  => get_template_directory_uri() . '/assets/images/rights.png',
			'lefts'   => get_template_directory_uri() . '/assets/images/lefts.png',
		),
	) );

	do_action( 'di_restaurant_woo_settings' );

}


//footer widgets section
Kirki::add_section( 'footer_options', array(
	'title'          => esc_attr__( 'Footer Widget Options', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );


Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'endis_ftr_wdgt',
	'label'       => esc_attr__( 'Footer Widgets', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable Footer Widgets.', 'di-restaurant' ),
	'section'     => 'footer_options',
	'default'     => '0',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'			=> 'radio-image',
	'settings'		=> 'ftr_wdget_lyot',
	'label'			=> esc_attr__( 'Footer Widget Layout', 'di-restaurant' ),
	'description'	=> esc_attr__( 'Save and go to Widgets page to add.', 'di-restaurant' ),
	'section'		=> 'footer_options',
	'default'		=> '3',
	'choices'		=> array(
		'1'		=> get_template_directory_uri() . '/assets/images/ftrwidlout1.png',
		'2'		=> get_template_directory_uri() . '/assets/images/ftrwidlout2.png',
		'3'		=> get_template_directory_uri() . '/assets/images/ftrwidlout3.png',
		'4'		=> get_template_directory_uri() . '/assets/images/ftrwidlout4.png',
		'48'	=> get_template_directory_uri() . '/assets/images/ftrwidlout48.png',
		'84'	=> get_template_directory_uri() . '/assets/images/ftrwidlout84.png',
	),
	'active_callback'  => array(
		array(
			'setting'  => 'endis_ftr_wdgt',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'mn_footer_typog',
	'label'       => esc_attr__( 'Footer Widgets Typography', 'di-restaurant' ),
	'section'     => 'footer_options',
	'default'     => array(
		'font-family'    => 'Roboto',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '1.7',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => '.footer-widgets',
		),
	),
	'transport' => 'auto',
	'active_callback'  => array(
		array(
			'setting'  => 'endis_ftr_wdgt',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'mn_footer_hdln_typog',
	'label'       => esc_attr__( 'Footer Widgets Headline Typography', 'di-restaurant' ),
	'section'     => 'footer_options',
	'default'     => array(
		'font-family'    	=> 'Roboto',
		'variant'        	=> 'regular',
		'font-size'      	=> '22px',
		'line-height'    	=> '1.1',
		'letter-spacing' 	=> '1px',
		'text-transform' 	=> 'uppercase',
		'text-align' 		=> 'left',
	),
	'output'      => array(
		array(
			'element' => '.footer-widgets .widgets_footer h3.widgets_footer_title',
		),
	),
	'transport' => 'auto',
	'active_callback'  => array(
		array(
			'setting'  => 'endis_ftr_wdgt',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

do_action( 'di_restaurant_footer_widget_options' );

//footer widget section END

//footer copyright section
Kirki::add_section( 'footer_copy_options', array(
	'title'          => esc_attr__( 'Footer Copyright Options', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'typography',
	'settings'    => 'cprt_footer_typog',
	'label'       => esc_attr__( 'Footer Copyright Typography', 'di-restaurant' ),
	'section'     => 'footer_copy_options',
	'default'     => array(
		'font-family'    => 'Roboto',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '25px',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'output'      => array(
		array(
			'element' => 'body .footer-copyright',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'editor',
	'settings'    => 'left_footer_setting',
	'label'       => esc_attr__( 'Footer Left', 'di-restaurant' ),
	'description' => esc_attr__( 'Content of Footer Left Side', 'di-restaurant' ),
	'section'     => 'footer_copy_options',
	'default'     => '<p>' . esc_attr__( 'Site Title, Some rights reserved.', 'di-restaurant' ) . '</p>',
	'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.footer-copyright-left',
			'function' => 'html',
		),
	),
	'partial_refresh' => array(
		'left_footer_setting' => array(
			'selector'        => '.footer-copyright-left',
			'render_callback' => function() {
				return wp_kses_post( get_theme_mod( 'left_footer_setting' ) );
			},
		),
	),
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'editor',
	'settings'    => 'center_footer_setting',
	'label'       => esc_attr__( 'Footer Center', 'di-restaurant' ),
	'description' => esc_attr__( 'Content of Footer Center Side', 'di-restaurant' ),
	'section'     => 'footer_copy_options',
	'default'     => '<p><a href="#">' . esc_attr__( 'Terms of Use - Privacy Policy', 'di-restaurant' ) . '</a></p>',
	'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.footer-copyright-center',
			'function' => 'html',
		),
	),
	'partial_refresh' => array(
		'center_footer_setting' => array(
			'selector'        => '.footer-copyright-center',
			'render_callback' => function() {
				return wp_kses_post( get_theme_mod( 'center_footer_setting' ) );
			},
		),
	),
) );

do_action( 'di_restaurant_footer_copyright_right_setting' );

do_action( 'di_restaurant_footer_copyright' );

//footer copyright section END

// MISC section.
Kirki::add_section( 'misc_options', array(
	'title'          => esc_attr__( 'MISC Options', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'back_to_top',
	'label'       => esc_attr__( 'Back To Top Button', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable Back To Top Button', 'di-restaurant' ),
	'section'     => 'misc_options',
	'default'     => '1',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'loading_icon',
	'label'       => esc_attr__( 'Page Loading Icon', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable Page Loading Icon', 'di-restaurant' ),
	'section'     => 'misc_options',
	'default'     => '0',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'image',
	'settings'    => 'loading_icon_img',
	'label'       => esc_attr__( 'Upload Custom Loading Icon', 'di-restaurant' ),
	'description' => esc_attr__( 'It will replace default loading icon.', 'di-restaurant' ),
	'section'     => 'misc_options',
	'default'     => '',
	'active_callback'  => array(
		array(
			'setting'  => 'loading_icon',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'stickymenu_setting_lrg',
	'label'       => esc_attr__( 'Sticky Menu for Large Devices', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable Sticky Menu (for Large Devices)', 'di-restaurant' ),
	'section'     => 'misc_options',
	'default'     => '1',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'stickymenu_setting_smal',
	'label'       => esc_attr__( 'Sticky Menu for Small Devices', 'di-restaurant' ),
	'description' => esc_attr__( 'Enable/Disable Sticky Menu (for Small Devices)', 'di-restaurant' ),
	'section'     => 'misc_options',
	'default'     => '0',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'toggle',
	'settings'    => 'hdricon_endis_smal',
	'label'       => esc_attr__( 'Hide Header Icons for Small Devices', 'di-restaurant' ),
	'description' => esc_attr__( 'Show/Hide header icons (for Small Devices)', 'di-restaurant' ),
	'section'     => 'misc_options',
	'default'     => '0',
) );

// MISC section END.

//Theme Info section
Kirki::add_section( 'theme_info', array(
	'title'          => esc_attr__( 'Theme Info', 'di-restaurant' ),
	'panel'          => 'di_restaurant_options',
	'capability'     => 'edit_theme_options',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'custom',
	'settings'    => 'custom_dir_demo',
	'label'       => esc_attr__( 'Di Restaurant Demo', 'di-restaurant' ),
	'section'     => 'theme_info',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_attr__( 'You can check demo of ', 'di-restaurant' ) . ' <a target="_blank" href="http://demo.dithemes.com/di-restaurant/">' . esc_attr__( 'Di Restaurant Theme Here', 'di-restaurant' ) . '</a>.</div>',
) );

Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'custom',
	'settings'    => 'custom_dir_docs',
	'label'       => esc_attr__( 'Di Restaurant Docs', 'di-restaurant' ),
	'section'     => 'theme_info',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_attr__( 'You can check documentation of ', 'di-restaurant' ) . ' <a target="_blank" href="https://dithemes.com/di-restaurant-free-wordpress-theme-documentation/">' . esc_attr__( 'Di Restaurant Theme Here', 'di-restaurant' ) . '</a>.</div>',
) );

do_action( 'di_restaurant_cutmzr_theme_info' );

//Theme Info section END


Kirki::add_field( 'di_restaurant_config', array(
	'type'        => 'slider',
	'settings'    => 'custom_logo_width',
	'label'       => esc_attr__( 'Logo Width', 'di-restaurant' ),
	'description' => esc_attr__( 'To resize selected logo image.', 'di-restaurant' ),
	'section'     => 'title_tagline',
	'default'     => '210',
	'priority'    => 9,
	'choices'     => array(
		'min'  => '10',
		'max'  => '600',
		'step' => '1',
	),
	'output' => array(
		array(
			'element'	=> '.custom-logo',
			'property'	=> 'width',
			'suffix'	=> 'px',
		),
	),
	'transport' => 'auto',
	'active_callback'  => 'has_custom_logo',
) );
