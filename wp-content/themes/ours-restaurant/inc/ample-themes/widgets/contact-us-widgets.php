<?php
/**
 * Class for adding Contact Section Widget
 *
 * @package Acme Themes
 * @subpackage ours_restaurant
 * @since 1.0.0
 */
if ( ! class_exists( 'Ours_Restaurant_Contact' ) ) {

    class Ours_Restaurant_Contact extends WP_Widget {
        /*defaults values for fields*/
        private $defaults = array(
            'title'         => '',
            'shortcode'     => '',
            'page_id'       => '',
            'section-id' =>'',
            

        );

        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'ours_restaurant_contact',
                /*Widget name will appear in UI*/
                __('AT : Contact Widgets', 'ours-restaurant'),
                /*Widget description*/
                array( 'description' => __( 'Show Contact Section.', 'ours-restaurant' ), )
            );
        }

        /*Widget Backend*/
        public function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, $this->defaults );
            /*default values*/
            $title = esc_attr( $instance[ 'title' ] );
            $shortcode = esc_attr( $instance[ 'shortcode' ] );
            $page_id = absint( $instance[ 'page_id' ] );
            $section_id= esc_attr( $instance['section-id'] );


            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('section-id') ); ?>">
                    <?php esc_html_e( 'Section Id', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('section-id')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('section-id')); ?>" value="<?php echo $section_id; ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'ours-restaurant' ); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            
            
            <p>
                <label for="<?php echo $this->get_field_id( 'shortcode' ); ?>"><?php _e( 'Enter Shortcode', 'ours-restaurant' ); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'shortcode' ); ?>" name="<?php echo $this->get_field_name( 'shortcode' ); ?>" type="text" value="<?php echo $shortcode; ?>" />
                <small>
                    <?php
                    printf( __( 'Download contact form 7 from %1$shere%2$s', 'ours-restaurant' ), "<a target='_blank' href='".esc_url( 'https://wordpress.org/plugins/contact-form-7/' )."''>","</a>" );
                    ?>
                </small>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'page_id' ); ?>"><?php _e( 'Select Page For Contact', 'ours-restaurant' ); ?>:</label>
                <br />
                <small><?php _e( 'Select page and its title and excerpt will display in the frontend. No need of subpages.', 'ours-restaurant' ); ?></small>
                <?php
                /* see more here https://codex.wordpress.org/Function_Reference/wp_dropdown_pages*/
                $args = array(
                    'selected'              => $page_id,
                    'name'                  => $this->get_field_name( 'page_id' ),
                    'id'                    => $this->get_field_id( 'page_id' ),
                    'class'                 => 'widefat',
                    'show_option_none'      => __('Select Page','ours-restaurant'),
                );
                wp_dropdown_pages( $args );
                ?>
           


            <?php
        }
        /**
         * Function to Updating widget replacing old instances with new
         *
         * @access public
         * @since 1.0.0
         *
         * @param array $new_instance new arrays value
         * @param array $old_instance old arrays value
         * @return array
         *
         */
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
            $instance[ 'shortcode' ] = wp_kses_post( $new_instance[ 'shortcode' ] );
            $instance[ 'page_id' ] = absint( $new_instance[ 'page_id' ] );
            $instance['section-id'] = sanitize_text_field($new_instance['section-id']);



            return $instance;
        }
        public function widget($args, $instance)
        {
            $instance = wp_parse_args((array)$instance, $this->defaults);
            $title = apply_filters('widget_title', !empty($instance['title']) ? $instance['title'] : '', $instance, $this->id_base);
            $shortcode = wp_kses_post($instance['shortcode']);
            $page_id = absint($instance['page_id']);
            $section_id = esc_attr($instance['section-id']);



            echo $args['before_widget'];
            ?>





            <section id="<?php echo esc_attr($section_id); ?>">
                <div id="about title-faq">
                    <div class="container" >

                        <header class="section-header">
                            <h3><?php echo esc_html($title); ?></h3>

                        </header>
                    </div>
                </div>

                <div id="faq" class="section-bg">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="faq-box">


                                    <div id="accordion" class=" wow fadeInUp">

                                        <?php echo do_shortcode( $shortcode); ?>

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="contact-details">
                                    <?php
                                    if( !empty ( $page_id ) ) :
                                    $ours_restaurant_child_page_args = array(
                                        'page_id'             => $page_id,
                                        'posts_per_page'      => 1,
                                        'post_type'           => 'page',
                                        'no_found_rows'       => true,
                                        'post_status'         => 'publish'
                                    );
                                    $service_query = new WP_Query( $ours_restaurant_child_page_args );
                                    /*The Loop*/
                                    if ( $service_query->have_posts() ):
                                    while( $service_query->have_posts() ):$service_query->the_post();
                                    ?>
                                    <?php

                                    if (has_post_thumbnail() ) {
                                        $image_id = get_post_thumbnail_id();
                                        $image_url = wp_get_attachment_image_src($image_id, 'large', true);
                                        ?>

                                        <img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php the_title_attribute();?>"/>


                                    <?php }
                                    else {
                                       ?>
                                    <div class="contact-page-content">
                                        <?php
                                        the_content();
                                        wp_link_pages( array(
                                            'before' => '<div class="page-links">' . esc_html__( 'Pages:','ours-restaurant' ),
                                            'after'  => '</div>',
                                        ) );
                                        ?>
                                    </div>
                                </div>
                                <?php
                                }

                                endwhile;
                                endif;
                                endif;
                                ?>
                            </div>


                            </div>
                        </div>
                    </div>
            
            </section>
            <?php echo $args['after_widget']; ?>
            
            <!-- End innerpage content site -->
            <?php
        }
    } // Class ours_restaurant_contact ends here
}
/**
 * Function to Register and load the widget
 *
 * @since 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'ours_restaurant_contact' ) ) :

    function ours_restaurant_contact() {
        register_widget( 'Ours_Restaurant_Contact' );
    }
endif;
add_action( 'widgets_init', 'ours_restaurant_contact' );