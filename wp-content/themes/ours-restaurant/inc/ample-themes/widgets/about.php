<?php
/**
 * Class for adding About Section Widget
 *
 * @package Acme Themes
 * @subpackage ours_restaurant
 * @since 1.0.0
 */
if ( ! class_exists( 'Ours_Restaurant_About' ) ) {

    class Ours_Restaurant_About extends WP_Widget {
        /*defaults values for fields*/
        private $defaults = array(
            'title'         => '',
            'page_id'       => '',
            'section-id' =>'',
           
            'button-text-link1' => '#',




        );

        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'ours_restaurant_About',
                /*Widget name will appear in UI*/
                __('AT : About Us Widgets', 'ours-restaurant'),
                /*Widget description*/
                array( 'description' => __( 'Show About us Section.', 'ours-restaurant' ), )
            );
        }

        /*Widget Backend*/
        public function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, $this->defaults );
            /*default values*/
            $title = esc_attr( $instance[ 'title' ] );
            $page_id = absint( $instance[ 'page_id' ] );
            $section_id= esc_attr( $instance['section-id'] );
            $button_text_link1 = esc_url($instance['button-text-link1']);



            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('section-id') ); ?>">
                    <?php esc_html_e( 'Section Id', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('section-id')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('section-id')); ?>" value="<?php echo esc_attr($section_id); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'ours-restaurant' ); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
           



            <p>
                <label for="<?php echo $this->get_field_id( 'page_id' ); ?>"><?php _e( 'Select Page For About', 'ours-restaurant' ); ?>:</label>
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
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('button-text-link1')); ?>">
                    <?php esc_html_e('Make Your Order Link', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('button-text-link1')); ?>"
                       class="widefat" id="<?php echo esc_attr($this->get_field_id('button-text-link1')); ?>"
                       value="<?php echo esc_attr($button_text_link1); ?>">
            </p>


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
            $instance[ 'page_id' ] = absint( $new_instance[ 'page_id' ] );
            $instance['post_image'] = ($new_instance['post_image']);
            $instance['section-id'] = sanitize_text_field($new_instance['section-id']);
            $instance['button-text-link1'] = esc_url_raw($new_instance['button-text-link1']);





            return $instance;
        }
        public function widget($args, $instance)
        {
            $instance = wp_parse_args((array)$instance, $this->defaults);
            $title = apply_filters('widget_title', !empty($instance['title']) ? $instance['title'] : '', $instance, $this->id_base);
            $page_id = absint($instance['page_id']);
            $section_id = esc_attr($instance['section-id']);
            $button_text_link1 = esc_url($instance['button-text-link1']);



            echo $args['before_widget'];
            ?>


            <section id="<?php echo esc_attr($section_id); ?>">

                <div id="faq" class="section-bg">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <div class="About-details">

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

                                    if (has_post_thumbnail()) {
                                        $image_id = get_post_thumbnail_id();
                                        $image_url = wp_get_attachment_image_src($image_id, 'large', true);
                                        ?>

                                        <img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php the_title_attribute();?>"/>


                                    <?php }

                                   ?>
                                        </div>
                            </div>
                                        <div class="col-md-6">
                                            <div class="About-page-content">
                                                <header class="section-header about">
                                                    <h3><?php echo esc_html($title); ?></h3>

                                                  </header>

                                                <?php
                                                 the_content();
                                                wp_link_pages(array(
                                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'ours-restaurant'),
                                                    'after' => '</div>',
                                                ));

                                if(!empty($button_text_link1)){
                                        ?>

                                        <div class="button_cont" ><a class="example_f" href="<?php echo esc_url($button_text_link1) ; ?>" rel="nofollow"><span><?php esc_html_e('Make Your Order','ours-restaurant'); ?> </a></span></div>
                                 <?php } ?>
                                    </div>
                                </div>
                                <?php

                                endwhile;
                                endif;
                                endif;
                                ?>
                            </div>


                        </div>
                    </div>
                
            </section>
            <?php echo $args['after_widget']; ?>

            <!-- End innerpage content site -->
            <?php
        }
    } // Class ours_restaurant_About ends here
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
if ( ! function_exists( 'ours_restaurant_About' ) ) :

    function ours_restaurant_About() {
        register_widget( 'Ours_Restaurant_About' );
    }
endif;
add_action( 'widgets_init', 'ours_restaurant_About' );