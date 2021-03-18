<?php
/**
 * All individual methods / functions.
 */
class Di_Restaurant_Methods {

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

	public static function the_thumbnail() {
		?>
		<div class="post-thumbnail-otr">
		<?php
			if( has_post_thumbnail() ) {
			?>
				<div class="post-thumbnail">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'aligncenter img-fluid' ) ); ?>
				</div>
			<?php
			}
			?>
		</div>
		<?php
	}

	public static function the_thumbnail_loop() {
		?>
		<div class="post-thumbnail-otr">
		<?php
			if( has_post_thumbnail() ) {
			?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'aligncenter img-fluid' ) ); ?>
					</a>
				</div>
			<?php
			}
			?>
		</div>
		<?php
	}

}
Di_Restaurant_Methods::get_instance();
