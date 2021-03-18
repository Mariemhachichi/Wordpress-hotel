<?php
if (!class_exists('Ours_Restaurant_hotel_room_Widget')) {
    class Ours_Restaurant_hotel_room_Widget extends WP_Widget
    {

        private function defaults()
        {

            $defaults = array(
                'cat_id' => 0,
                'title' => esc_html__('Menu List', 'ours-restaurant'),
                'section-id' =>'',


            );
            return $defaults;
        }

        public function __construct()
        {
            parent::__construct(
                'ours-restaurant-hotel-room-post-widget',
                esc_html__(' AT : Menu List Widget', 'ours-restaurant'),
                array('description' => esc_html__('Menu List to select woocommerce category', 'ours-restaurant'))
            );
        }

        public function form($instance)
        {
            $instance = wp_parse_args( (array ) $instance, $this->defaults() );
            $section_id= esc_attr( $instance['section-id'] );
            $instance = wp_parse_args((array )$instance, $this->defaults());
            $catid = absint($instance['cat_id']);
            $title = esc_attr($instance['title']);



            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('section-id') ); ?>">
                    <?php esc_html_e( 'Section Id', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('section-id')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('section-id')); ?>" value="<?php echo esc_attr($section_id); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                    <?php esc_html_e('Title', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>" class="widefat"
                       id="<?php echo esc_attr($this->get_field_id('title')); ?>" value="<?php echo esc_attr($title); ?>">
            </p>



            <p>
                <label for="<?php echo esc_attr($this->get_field_id('cat_id')); ?>">
                    <?php esc_html_e('Select Category', 'ours-restaurant'); ?>
                </label><br/>
                <?php
                $restaurant_con_dropown_cat = array(
                    'show_option_none' => esc_html__('Choose Menu Categories', 'ours-restaurant'),
                    'orderby' => 'name',
                    'order' => 'asc',
                    'show_count' => 1,
                    'hide_empty' => 1,
                    'echo' => 1,
                    'selected' => $catid,
                    'hierarchical' => 1,
                    'name' => esc_attr($this->get_field_name('cat_id')),
                    'id' => esc_attr($this->get_field_name('cat_id')),
                    'class' => 'widefat',
                    'taxonomy' => 'product_cat',
                    'hide_if_empty' => false,
                );
                wp_dropdown_categories($restaurant_con_dropown_cat);
                ?>
            </p>
            <hr>
            <?php
        }

        public function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $instance['cat_id'] = (isset($new_instance['cat_id'])) ? absint($new_instance['cat_id']) : '';
            $instance['title'] = sanitize_text_field($new_instance['title']);
            $instance['section-id'] = sanitize_text_field($new_instance['section-id']);



            return $instance;

        }

        public function widget($args, $instance)
        {
            echo $args['before_widget'];
            if (!empty($instance)) {
                $instance = wp_parse_args((array )$instance, $this->defaults());
                $catid = absint($instance['cat_id']);
                $title = apply_filters('widget_title', !empty($instance['title']) ? esc_html($instance['title']) : '', $instance, $this->id_base);
                $section_id = esc_attr($instance['section-id']);



                ?>




                <section id="<?php echo esc_attr( $section_id); ?>">
                    <div id="about">
                        <div class="container" >

                            <header class="section-header">
                                <h3><?php echo esc_html($title); ?></h3>
                            </header>

                            <div class="row about-cols">
                                <?php
                                $i = 0;
                                $sticky = get_option('sticky_posts');
                                if ($catid != -1) {
                                    $home_product_section = array(
                                        'post_type' => 'product',
                                        'post_status' => 'publish',
                                        'posts_per_page' => 8,
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'terms' => $catid,
                                            )
                                        )
                                    );
                                } else {
                                    $home_product_section = array(
                                        'post_type' => 'product',
                                        'post_status' => 'publish',
                                        'terms' => $catid,
                                        'posts_per_page' => 8,
                                        'orderby' => 'post_date',
                                        'order' => 'DESC',
                                    );
                                }

                                $home_product_section_query = new WP_Query($home_product_section);

                                if ($home_product_section_query->have_posts()) {
                                    while ($home_product_section_query->have_posts()) {
                                        $home_product_section_query->the_post();
                                        $product = wc_get_product( $home_product_section_query->post->ID );
                                        $image_id = get_post_thumbnail_id();
                                        $image_url = wp_get_attachment_image_src($image_id,'large', false);?>

                                        <div class="col-sm-3 img-product" >
                                            <figure class="featured-img">
                                                <?php if($image_url[0]) { ?>
                                                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"><img src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php the_title_attribute(); ?>"></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"> <img src="<?php echo esc_url(ours_restaurant_woocommerce_placeholder_img_src()); ?>" alt="<?php the_title_attribute(); ?>"></a>
                                                <?php } ?>


                                            </figure>
                                        </div>
                                        <div class="col-sm-3" >
                                            <div class="list-info hotel-info">
                                                <header class="entry-header">

                                                    <a href="<?php the_permalink();?>">
                                                        <h3 class="entry-title"><?php the_title();?></h3>
                                                    </a>

                                                </header>
                                                <p class="description"><?php echo esc_html(wp_trim_words(get_the_content(), 15)); ?></p>

                                                <?php if ( $price_html = $product->get_price_html() ) : ?>
                                                    <span class="price"><?php esc_html_e('Price : ','ours-restaurant'); ?><?php echo wp_kses_post($price_html); ?></span>
                                                <?php endif; ?>

                                                <?php
                                                if( function_exists( 'YITH_WCWL' ) ){
                                                    $url = add_query_arg( 'add_to_wishlist', $product->get_id() );
                                                    ?>
                                                    <a href="<?php echo esc_url($url); ?>" class="single_add_to_wishlist" >
                                                        <?php esc_html_e('Add to Wishlist','ours-restaurant'); ?><i class="fa fa-heart"></i>
                                                    </a>
                                                <?php } else{

                                                    woocommerce_template_loop_add_to_cart( $product );

                                                } ?>

                                            </div>
                                        </div>

                                        <?php
                                        $i++;
                                    }
                                    wp_reset_postdata();
                                } ?>


                            </div>
                        </div>

                    </div>
                </section><!-- #about -->

                <?php
                echo $args['after_widget'];
            }
        }

    }
}
add_action('widgets_init', 'ours_restaurant_hotel_room_widget');
function ours_restaurant_hotel_room_widget()
{
    register_widget('Ours_Restaurant_hotel_room_Widget');

}
