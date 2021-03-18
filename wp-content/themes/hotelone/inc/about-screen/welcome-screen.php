<?php
class hotelone_dashboard {
	public function __construct() {
		add_action('admin_menu', array( $this, 'hotelone_about' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'hotelone_admin_scripts' ) );
		
		add_action( 'admin_init', array( $this, 'hotelone_admin_dismiss_actions' ) );
		
		add_action('switch_theme', array( $this, 'hotelone_reset_recommended_actions' ));
		
		add_action( 'load-themes.php',  array( $this, 'hotelone_one_activation_admin_notice' )  );
	}
	function hotelone_one_activation_admin_notice(){
		global $pagenow;
		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'hotelone_admin_notice' ) );
			add_action( 'admin_notices', array( $this, 'hotelone_admin_import_notice' ) );
		}
	}
	function hotelone_admin_notice() {
		if ( ! function_exists( 'hotelone_get_recommended_actions' ) ) {
			return false;
		}
		$actions = hotelone_get_recommended_actions();
		$number_action = $actions['number_notice'];

		if ( $number_action > 0 ) {
			$theme = wp_get_theme();
			?>
			<div class="updated notice notice-success notice-alt is-dismissible">
				<p><?php printf( esc_html__( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our <a href="%2$s">Welcome page</a>', 'hotelone' ),  esc_attr( $theme->Name ), esc_url( admin_url( 'themes.php?page=wd_themepage' ) )  ); ?></p>
			</div>
			<?php
		}
	}

	function hotelone_admin_import_notice(){
		?>
		<div class="updated notice notice-success notice-alt is-dismissible">
			<p><?php printf( esc_html__( 'Save time by import our demo data, your website will be set up and ready to customize in minutes. %s', 'hotelone' ), '<a class="button button-secondary" href="'.esc_url( add_query_arg( array( 'page' => 'wd_themepage&tab=demo-data-importer' ), esc_url( admin_url( 'themes.php' ) ) ) ).'">'.esc_html__( 'Import Demo Data', 'hotelone' ).'</a>'  ); ?></p>
		</div>
		<?php
	}
	public function hotelone_reset_recommended_actions () {
		delete_option('hotelone_actions_dismiss');
	}
	function hotelone_admin_dismiss_actions(){
		// Action for dismiss
		if ( isset( $_GET['hotelone_action_notice'] ) ) {
			$actions_dismiss =  get_option( 'hotelone_actions_dismiss' );
			if ( ! is_array( $actions_dismiss ) ) {
				$actions_dismiss = array();
			}
			$action_key = sanitize_text_field( wp_unslash( $_GET['hotelone_action_notice'] ) );
			if ( isset( $actions_dismiss[ $action_key ] ) &&  $actions_dismiss[ $action_key ] == 'hide' ){
				$actions_dismiss[ $action_key ] = 'show';
			} else {
				$actions_dismiss[ $action_key ] = 'hide';
			}
			update_option( 'hotelone_actions_dismiss', $actions_dismiss );
			$url = esc_url( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			$url = remove_query_arg( 'hotelone_action_notice', $url );
			wp_redirect( $url );
			die();
		}

	}
	public function hotelone_admin_scripts( $hook ){
		
		if ( $hook === 'widgets.php' || $hook === 'appearance_page_wd_themepage'  ) {
			
            wp_enqueue_style( 'hotelone-admin-css', get_template_directory_uri() . '/inc/about-screen/css/dashboard.css' );

            wp_enqueue_style( 'plugin-install' );
            wp_enqueue_script( 'plugin-install' );
            wp_enqueue_script( 'updates' );
            add_thickbox();
			wp_enqueue_script( 'hotelone-plugin-install-helper',  get_template_directory_uri() . '/inc/install/js/install.js' );
				wp_localize_script(
				'hotelone-plugin-install-helper', 'Hotelone_plugin_helper',
				array(
					'activating' => esc_html__( 'Activating ', 'hotelone' ),
				)
			);
			wp_localize_script(
				'hotelone-plugin-install-helper', 'pagenow',
				array( 'import' )
			);
        }
	}
	
	public function hotelone_about(){
		
		$recommended_actions = $this->hotelone_get_recommended_actions();
		
		$number_count = $recommended_actions['number_notice'];

		if ( $number_count > 0 ){
			
			$update_label = sprintf( _n( '%1$s action required', '%1$s actions required', $number_count, 'hotelone' ), $number_count );
			
			$count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $update_label )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
			
			$menu_title = sprintf( esc_html__('Hotelone %s', 'hotelone'), $count );
			
		} else {
			
			$menu_title = esc_html__('Hotelone Theme', 'hotelone');
		}

		add_theme_page( 
			esc_html__( 'Hotelone Dashboard', 'hotelone' ), 
			$menu_title, 
			'edit_theme_options', 
			'wd_themepage', 
			array($this,'hotelone_theme_about_page')
			);
	}
	
	public function hotelone_theme_about_page(){
		$theme = wp_get_theme('hotelone');
		
		if ( isset( $_GET['hotelone_action_dismiss'] ) ) {
			$actions_dismiss =  get_option( 'hotelone_actions_dismiss' );
			if ( ! is_array( $actions_dismiss ) ) {
				$actions_dismiss = array();
			}
			$actions_dismiss[ sanitize_text_field( wp_unslash( $_GET['hotelone_action_dismiss'] ) ) ] = 'dismiss';
			update_option( 'hotelone_actions_dismiss', $actions_dismiss );
		}
		
		$tab = null;
		if ( isset( $_GET['tab'] ) ) {
			$tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		} else {
			$tab = null;
		}
		
		$actions_r = $this->hotelone_get_recommended_actions();
		$number_action = $actions_r['number_notice'];
		$actions = $actions_r['actions'];

		$current_action_link =  admin_url( 'themes.php?page=wd_themepage&tab=recommended_actions' );

		$recommend_plugins = get_theme_support( 'recommend-plugins' );
		if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
			$recommend_plugins = $recommend_plugins[0];
		} else {
			$recommend_plugins[] = array();
		}
		?>
		<div class="wrap about-wrap themepage_wrapper">
		
			<h1>
				<?php printf( esc_html__('Welcome to Hotelone - Version %1s', 'hotelone'), esc_attr( $theme->Version ) ); ?>
			</h1>
			
			<div class="about-text">
				<?php esc_html_e( 'Hotelone is a creative and flexible WordPress theme well suited for hotel owners, resturants and hostels. You can show your rooms features with this cool design WordPress theme.', 'hotelone' ); ?>
			</div>
			
			<a target="_blank" href="<?php echo esc_url('https://www.britetechs.com/'); ?>" class="britetechs-badge wp-badge"><span><?php esc_html_e( 'Britetechs', 'hotelone' ); ?></span></a>
			
			<hr class="wp-header-end">
			
			<h2 class="nav-tab-wrapper">
			
				<a href="?page=wd_themepage" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Hotelone', 'hotelone' ) ?></a>
				
				<a href="?page=wd_themepage&tab=recommended_actions" class="nav-tab<?php echo $tab == 'recommended_actions' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Recommended Actions', 'hotelone' ); echo ( $number_action > 0 ) ? "<span class='theme-action-count'>{$number_action}</span>" : ''; ?></a>
				
				<a href="?page=wd_themepage&tab=free_vs_pro" class="nav-tab<?php echo $tab == 'free_vs_pro' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Free vs PRO', 'hotelone' ); ?></span></a>
				
				<a href="?page=wd_themepage&tab=demo-data-importer" class="nav-tab<?php echo $tab == 'demo-data-importer' ? ' nav-tab-active' : null; ?>">
					<?php esc_html_e( 'One Click Demo Import', 'hotelone' ); ?></span></a>
			</h2>
			
			<?php if ( is_null( $tab ) ) { ?>
				<div class="themepage_info info-tab-content">
					<div class="themepage_info_column clearfix">
						<div class="themepage_info_left">

							<div class="themepage_link">
								<h3><?php esc_html_e( 'Customizer Option Panel Settings', 'hotelone' ); ?></h3>
								<p class="about"><?php printf(esc_html__('%s supports the Theme Customizer option panel settings. Click on below customize button to customize your theme.', 'hotelone'), esc_attr($theme->Name)); ?></p>
								<p>
									<a href="<?php echo esc_url( admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Customize Your Theme', 'hotelone'); ?></a>
								</p>
							</div>
							<div class="themepage_link">
								<h3><?php esc_html_e( 'Documentation', 'hotelone' ); ?></h3>
								<p class="about"><?php printf(esc_html__('Need to setup your %s WordPress theme? Please click on bottom button  to find the theme documentation.', 'hotelone'), esc_attr($theme->Name)); ?></p>
								<p>
									<a href="<?php echo esc_url( 'https://britetechs.com/docs/themes/hotelone-pro/' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Hotelone Theme Documentation', 'hotelone'); ?></a>
								</p>
								<?php do_action( 'hotelone_dashboard_theme_links' ); ?>
							</div>
							<div class="themepage_link">
								<h3><?php esc_html_e( 'Need Support?', 'hotelone' ); ?></h3>
								<p class="about"><?php printf(esc_html__('If you have more queries with %s WordPress theme. Please click below link to create a ticket', 'hotelone'), esc_attr($theme->Name)); ?></p>
								<p>
									<a href="<?php echo esc_url('https://wordpress.org/support/theme/hotelone' ); ?>" target="_blank" class="button button-secondary"><?php echo sprintf( esc_html__('Create a ticket', 'hotelone'), esc_attr($theme->Name)); ?></a>
								</p>
							</div>
						</div>

						<div class="themepage_info_right">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/screenshot.png" alt="<?php esc_attr_e('Hotelone','hotelone'); ?>" />
						</div>
					</div>
				</div><!-- tab 1 -->
			<?php } ?>
			
			<?php if ( $tab == 'recommended_actions' ) { ?>
				<div class="action-required-tab info-tab-content">

					<?php if ( $actions_r['number_active']  > 0 ) { ?>
					
						<?php 
						$actions = wp_parse_args( 
							$actions, 
							array( 
							'page_on_front' => '', 
							'page_template' ) 
							);
						?>

						<?php if ( $actions['recommend_plugins'] == 'active' ) {  ?>
							<div id="plugin-filter" class="recommend-plugins action-required">
								<a  title="" class="dismiss" href="<?php echo esc_url( add_query_arg( array( 'hotelone_action_notice' => 'recommend_plugins' ), $current_action_link ) ); ?>">
									<?php if ( $actions_r['hide_by_click']['recommend_plugins'] == 'hide' ) { ?>
										<span class="dashicons dashicons-hidden"></span>
									<?php } else { ?>
										<span class="dashicons  dashicons-visibility"></span>
									<?php } ?>
								</a>
								<h3><?php esc_html_e( 'Recommend Plugins', 'hotelone' ); ?></h3>
								<?php
								$this->hotelone_render_recommend_plugins( $recommend_plugins );
								?>
							</div>
						<?php } ?>


						<?php if ( $actions['page_on_front'] == 'active' ) {  ?>
							<div class="theme_link  action-required">
								<a title="<?php  esc_attr_e( 'Dismiss', 'hotelone' ); ?>" class="dismiss" href="<?php echo esc_url( add_query_arg( array( 'hotelone_action_notice' => 'page_on_front' ), $current_action_link ) ); ?>">
									<?php if ( $actions_r['hide_by_click']['page_on_front'] == 'hide' ) { ?>
										<span class="dashicons dashicons-hidden"></span>
									<?php } else { ?>
										<span class="dashicons  dashicons-visibility"></span>
									<?php } ?>
								</a>
								<h3><?php esc_html_e( 'Switch "Front page displays" to "A static page"', 'hotelone' ); ?></h3>
								<div class="about">
									<p><?php esc_html_e('In order to have the one page look for your website, please go to Customize -&gt; Static Front Page and switch "Front page displays" to "A static page".', 'hotelone' ); ?></p>
								</div>
								<p>
									<a  href="<?php echo esc_url(admin_url('options-reading.php')); ?>" class="button"><?php esc_html_e('Setup front page displays', 'hotelone'); ?></a>
								</p>
							</div>
						<?php } ?>

						<?php if ( $actions['page_template'] == 'active' ) {  ?>
							<div class="theme_link  action-required">
								<a  title="<?php  esc_attr_e( 'Dismiss', 'hotelone' ); ?>" class="dismiss" href="<?php echo esc_url( add_query_arg( array( 'hotelone_action_notice' => 'page_template' ), $current_action_link ) ); ?>">
									<?php if ( $actions_r['hide_by_click']['page_template'] == 'hide' ) { ?>
										<span class="dashicons dashicons-hidden"></span>
									<?php } else { ?>
										<span class="dashicons  dashicons-visibility"></span>
									<?php } ?>
								</a>
								<h3><?php esc_html_e( 'Set your homepage page template to "Frontpage".', 'hotelone' ); ?></h3>

								<div class="about">
									<p><?php esc_html_e( 'In order to change homepage section contents, you will need to set template "Frontpage" for your homepage.', 'hotelone' ); ?></p>
								</div>
								<p>
									<?php
									$front_page = get_option( 'page_on_front' );
									if ( $front_page <= 0  ) {
										?>
										<a  href="<?php echo esc_url(admin_url('options-reading.php')); ?>" class="button"><?php esc_html_e('Setup front page displays', 'hotelone'); ?></a>
										<?php

									}

									if ( $front_page > 0 && get_post_meta( $front_page, '_wp_page_template', true ) != 'template-frontpage.php' ) {
										?>
										<a href="<?php echo esc_url( get_edit_post_link( $front_page ) ); ?>" class="button"><?php esc_html_e('Change homepage page template', 'hotelone'); ?></a>
										<?php
									}
									?>
								</p>
							</div>
						<?php } ?>
						
					<?php  } else { ?>
					
						<h3><?php  printf( esc_html__( 'Keep %s updated', 'hotelone' ) , esc_attr( $theme->Name )); ?></h3>
						
						<p><?php esc_html_e('Hey! There are no required actions found.', 'hotelone' ); ?></p>
						
					<?php } ?>
					
				</div><!-- tab 2 -->
				
			<?php } ?>
			
			<?php if ( $tab == 'free_vs_pro' ) { ?>
				<div id="free_pro" class="freepro-tab-content info-tab-content">
					<table class="free-pro-table">
						<thead><tr><th></th><th><?php esc_html_e('Hotelone', 'hotelone'); ?></th><th><?php esc_html_e('Hotelone Pro', 'hotelone'); ?></th></tr></thead>
						<tbody>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Unlimited Colors Schemes', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Slider Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						<tr>
							<td>
								<h5><?php esc_html_e('- Number of items', 'hotelone'); ?></h5>
							</td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('2', 'hotelone'); ?></span></td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('Unlimited', 'hotelone'); ?></span></td>
						</tr>
		
						<tr>
							<td>
								<h4><?php esc_html_e('Service Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						<tr>
							<td>
								<h5><?php esc_html_e('- Number of items', 'hotelone'); ?></h5>
							</td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('3', 'hotelone'); ?></span></td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('Unlimited', 'hotelone'); ?></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Room Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						<tr>
							<td>
								<h5><?php esc_html_e('- Number of items', 'hotelone'); ?></h5>
							</td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('3', 'hotelone'); ?></span></td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('Unlimited', 'hotelone'); ?></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Counter Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Team Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						<tr>
							<td>
								<h5><?php esc_html_e('- Number of items', 'hotelone'); ?></h5>
							</td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('3', 'hotelone'); ?></span></td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('Unlimited', 'hotelone'); ?></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Testimonial Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						<tr>
							<td>
								<h5><?php esc_html_e('- Number of items', 'hotelone'); ?></h5>
							</td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('1', 'hotelone'); ?></span></td>
							<td class="only-lite"><span class="dashicons-before "><?php esc_html_e('Unlimited', 'hotelone'); ?></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Video Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>

						<tr>
							<td>
								<h4><?php esc_html_e('Call To Action Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Blog Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-yes"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Client Section', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Drag and drop section manager setting', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Styling for sections ( Text Color, Background Color and Background Image)', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('Google Map in contact template', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>
						
						<tr>
							<td>
								<h4><?php esc_html_e('24/7 Quality Support', 'hotelone'); ?></h4>
							</td>
							<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
							<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
						</tr>						
						

						<tr class="ti-about-page-text-center"><td></td><td colspan="2"><a href="<?php echo esc_url('https://www.britetechs.com/free-hotelone-wordpress-theme/'); ?>" target="_blank" class="button button-primary button-hero"><?php esc_html_e('Get Hotelone Pro Theme!', 'hotelone'); ?></a></td></tr>
						</tbody>
					</table>
				</div>
			<?php } ?>
				
			<?php if ( $tab == 'demo-data-importer' ) { ?>
				<div class="demo-import-tab-content info-tab-content">
				
					<?php if ( class_exists('OCDI_Plugin') ) {?>
					
						<div id="plugin-filter" class="demo-import-boxed">
						<?php printf(sprintf(__( '<p>Congratulations! you installed importer plugin successfully. Click Here to start <a href="%s">Import Data</a></p>', 'hotelone' ),esc_url( admin_url('themes.php?page=pt-one-click-demo-import') ))); ?>
						</div>
						
					<?php } else { ?>
						<div id="plugin-filter" class="demo-import-boxed">
							<?php
							$plugin_name = 'one-click-demo-import';
							$status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_name );
							$button_class = 'install-now button';
							$button_txt = esc_html__( 'Install Now', 'hotelone' );
							if ( ! $status ) {
								$install_url = wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'install-plugin',
											'plugin' => $plugin_name
										),
										network_admin_url( 'update.php' )
									),
									'install-plugin_'.$plugin_name
								);

							} else {
								$install_url = add_query_arg(array(
									'action' => 'activate',
									'plugin' => rawurlencode( $plugin_name . '/' . $plugin_name . '.php' ),
									'plugin_status' => 'all',
									'paged' => '1',
									'_wpnonce' => wp_create_nonce('activate-plugin_' . $plugin_name . '/' . $plugin_name . '.php'),
								), network_admin_url('plugins.php'));
								$button_class = 'activate-now button-primary';
								$button_txt = esc_html__( 'Active Now', 'hotelone' );
							}

							$detail_link = add_query_arg(
								array(
									'tab' => 'plugin-information',
									'plugin' => $plugin_name,
									'TB_iframe' => 'true',
									'width' => '772',
									'height' => '349',

								),
								network_admin_url( 'plugin-install.php' )
							);

							echo '<p>';
							printf( esc_html__(
								'%1$s you will need to install and activate the %2$s plugin first.', 'hotelone' ),
								'<b>'.esc_html__( 'Hey.', 'hotelone' ).'</b>',
								'<a class="thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'Theme Demo Importer', 'hotelone' ).'</a>'
							);
							echo '</p>';

							echo '<p class="plugin-card-'.esc_attr( $plugin_name ).'"><a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_name ).'" class="'.esc_attr( $button_class ).'">'.esc_html($button_txt).'</a></p>';

							?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		
		</div>
		<?php
	}
	
	public function hotelone_get_recommended_actions( ) {

		$actions = array();
		$front_page = get_option( 'page_on_front' );
		$actions['page_on_front'] = 'dismiss';
		$actions['page_template'] = 'dismiss';
		$actions['recommend_plugins'] = 'dismiss';
		if ( 'page' != get_option( 'show_on_front' ) ) {
			$front_page = 0;
		}
		if ( $front_page <= 0  ) {
			$actions['page_on_front'] = 'active';
			$actions['page_template'] = 'active';
		} else {
			if ( get_post_meta( $front_page, '_wp_page_template', true ) == 'template-frontpage.php' ) {
				$actions['page_template'] = 'dismiss';
			} else {
				$actions['page_template'] = 'active';
			}
		}

		$recommend_plugins = get_theme_support( 'recommend-plugins' );
		if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
			$recommend_plugins = $recommend_plugins[0];
		} else {
			$recommend_plugins[] = array();
		}

		if ( ! empty( $recommend_plugins ) ) {

			foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
				$plugin_info = wp_parse_args( $plugin_info, array(
					'name' => '',
					'active_filename' => '',
				) );
				if ( $plugin_info['active_filename'] ) {
					$active_file_name = $plugin_info['active_filename'] ;
				} else {
					$active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
				}
				if ( ! is_plugin_active( $active_file_name ) ) {
					$actions['recommend_plugins'] = 'active';
				}
			}

		}

		$actions = apply_filters( 'hotelone_get_recommended_actions', $actions );
		$hide_by_click = get_option( 'hotelone_actions_dismiss' );
		if ( ! is_array( $hide_by_click ) ) {
			$hide_by_click = array();
		}

		$n_active  = $n_dismiss = 0;
		$number_notice = 0;
		foreach ( $actions as $k => $v ) {
			if ( ! isset( $hide_by_click[ $k ] ) ) {
				$hide_by_click[ $k ] = false;
			}

			if ( $v == 'active' ) {
				$n_active ++ ;
				$number_notice ++ ;
				if ( $hide_by_click[ $k ] ) {
					if ( $hide_by_click[ $k ] == 'hide' ) {
						$number_notice -- ;
					}
				}
			} else if ( $v == 'dismiss' ) {
				$n_dismiss ++ ;
			}

		}

		$return = array(
			'actions' => $actions,
			'number_actions' => count( $actions ),
			'number_active' => $n_active,
			'number_dismiss' => $n_dismiss,
			'hide_by_click'  => $hide_by_click,
			'number_notice'  => $number_notice,
		);
		if ( $return['number_notice'] < 0 ) {
			$return['number_notice'] = 0;
		}

		return $return;
	}
	
	public function hotelone_render_recommend_plugins( $recommend_plugins = array() ){
		foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
			$plugin_info = wp_parse_args( $plugin_info, array(
				'name' => '',
				'active_filename' => '',
			) );
			$plugin_name = $plugin_info['name'];
			$plugin_desc = isset($plugin_info['desc'])?$plugin_info['desc']:'';
			$status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug );
			$button_class = 'install-now button';
			if ( $plugin_info['active_filename'] ) {
				$active_file_name = $plugin_info['active_filename'] ;
			} else {
				$active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
			}

			if ( ! is_plugin_active( $active_file_name ) ) {
				$button_txt = esc_html__( 'Install Now', 'hotelone' );
				if ( ! $status ) {
					$install_url = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $plugin_slug
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_'.$plugin_slug
					);

				} else {
					$install_url = add_query_arg(array(
						'action' => 'activate',
						'plugin' => rawurlencode( $active_file_name ),
						'plugin_status' => 'all',
						'paged' => '1',
						'_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
					), network_admin_url('plugins.php'));
					$button_class = 'activate-now button-primary';
					$button_txt = esc_html__( 'Active Now', 'hotelone' );
				}

				$detail_link = add_query_arg(
					array(
						'tab' => 'plugin-information',
						'plugin' => $plugin_slug,
						'TB_iframe' => 'true',
						'width' => '772',
						'height' => '349',

					),
					network_admin_url( 'plugin-install.php' )
				);

				echo '<div class="rcp">';
				echo '<h4 class="rcp-name">';
				echo esc_html( $plugin_name );
				echo '</h4>';
				echo '<div class="about" style="margin-top:1em;">';
				echo esc_html( $plugin_desc );
				echo '</div>';
				echo '<p class="action-btn plugin-card-'.esc_attr( $plugin_slug ).'"><a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_slug ).'" class="'.esc_attr( $button_class ).'">'.esc_html($button_txt).'</a></p>';
				echo '<a class="plugin-detail thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'Details', 'hotelone' ).'</a>';
				echo '</div>';
			}

		}
	}
}
$GLOBALS['hotelone_dashboard'] = new hotelone_dashboard();