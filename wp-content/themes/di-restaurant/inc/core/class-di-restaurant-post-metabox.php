<?php
/**
 * Di Restaurant Post Metabox options.
 *
 * @package Di Restaurant
 */

/**
 * Class Di_Restaurant_Post_Metabox.
 */
class Di_Restaurant_Post_Metabox {

	/**
	 * Instance object.
	 *
	 * @var instance
	 */
	public static $instance;

	/**
	 * Get_instance method.
	 *
	 * @return instance instance of the class.
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * [__construct description]
	 */
	public function __construct() {
		if( is_admin() ) {
			add_action( 'load-post.php', [ $this, 'meta_box_first_func' ] );
			add_action( 'load-post-new.php', [ $this, 'meta_box_first_func' ] );
		}
	}

	public function meta_box_first_func() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post',      array( $this, 'save' ) );
	}

	public function add_meta_box( $post_type ) {
		if( $post_type == 'post' ) {
			add_meta_box(
				'di_restaurant_post_meta_box_cntnr',
				__( 'Di Restaurant Theme Options for this Post', 'di-restaurant' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'normal',
				'default'
			);
		}
	}

	public function render_meta_box_content( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'di_restaurant_post_meta_bx_key', 'di_restaurant_post_meta_bx_key_nonce' );
 
		// Use get_post_meta to retrieve an existing value from the database.
		$hide_footer_widgets 	= get_post_meta( $post->ID, '_di_restaurant_hide_footer_widgets', true );
		$hide_hdrimg            = get_post_meta( $post->ID, '_di_restaurant_hide_hdrimg', true );
		?>

		<p>
		<label style="padding-right: 16px;" for="hide_hdrimg">
			<?php esc_html_e( 'Want to hide header image? ', 'di-restaurant' ); ?>
		</label>
		<input type="checkbox" id="hide_hdrimg" name="hide_hdrimg_val" <?php checked( $hide_hdrimg, '1' ); ?> /> <?php esc_html_e( 'Info: This will hide header image. if you are using header image.', 'di-restaurant' ); ?>
		</p>

		<p>
		<label style="padding-right: 7px;" for="hide_footer_widgets">
			<?php esc_html_e( 'Want to hide Footer Widgets? ', 'di-restaurant' ); ?>
		</label>
		<input type="checkbox" id="hide_footer_widgets" name="hide_footer_widgets_val" <?php checked( $hide_footer_widgets, '1' ); ?> /> <?php esc_html_e( 'Info: This will hide footer widget section, if you are using footer widget.', 'di-restaurant' ); ?>
		</p>
		
		<?php
		do_action( 'di_restaurant_post_metabox_render', $post );
	}


	public function save( $post_id ) {
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */
 
		// Check if our nonce is set.
		if( ! isset( $_POST['di_restaurant_post_meta_bx_key_nonce'] ) ) {
			return $post_id;
		}
 
		$nonce = wp_unslash( $_POST['di_restaurant_post_meta_bx_key_nonce'] );
 
		// Verify that the nonce is valid.
		if( ! wp_verify_nonce( $nonce, 'di_restaurant_post_meta_bx_key' ) ) {
			return $post_id;
		}
 
		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
 
		// Check the post type.
		if( $_POST['post_type'] != 'post' ) {
			return $post_id;
		}

		// Check current user permission.
		if( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
 
		/* OK, it's safe for us to save the data now. */

		// Store 0 or 1 for hide_hdrimg.
		$saveit = ( isset( $_POST['hide_hdrimg_val'] ) && 'on' === $_POST['hide_hdrimg_val'] ) ? '1' : '0';
		update_post_meta( $post_id, '_di_restaurant_hide_hdrimg', $saveit );

		// Store 0 or 1 for footer_widgets.
		$saveit = ( isset( $_POST['hide_footer_widgets_val'] ) && 'on' === $_POST['hide_footer_widgets_val'] ) ? '1' : '0';
		update_post_meta( $post_id, '_di_restaurant_hide_footer_widgets', $saveit );

		do_action( 'di_restaurant_post_metabox_save', $post_id );
	}

}
Di_Restaurant_Post_Metabox::get_instance();