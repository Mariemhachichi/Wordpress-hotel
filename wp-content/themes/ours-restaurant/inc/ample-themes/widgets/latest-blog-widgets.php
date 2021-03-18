<?php
if (!class_exists('Ours_Restaurant_Recent_Post_Widget')) {
    class Ours_Restaurant_Recent_Post_Widget extends WP_Widget
    {

        private function defaults()
        {

            $defaults = array(
                'cat_id' => 0,
                'title' => esc_html__('Latest Blogs', 'ours-restaurant'),
                'section-id' =>'',


            );
            return $defaults;
        }

        public function __construct()
        {
            parent::__construct(
                'ours-restaurant-recent-post-widget',
                esc_html__(' AT : Latest Blog Widget', 'ours-restaurant'),
                array('description' => esc_html__('Business Latest Blog Section', 'ours-restaurant'))
            );
        }

        public function form($instance)
        {
           
            $instance = wp_parse_args((array )$instance, $this->defaults());
            $section_id= esc_attr( $instance['section-id'] );
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
                $business_con_dropown_cat = array(
                    'show_option_none' => esc_html__('From Recent Posts', 'ours-restaurant'),
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
                    'taxonomy' => 'category',
                    'hide_if_empty' => false,
                );
                wp_dropdown_categories($business_con_dropown_cat);
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
                    $home_recent_post_section = array(
                        'ignore_sticky_posts' => true,
                        'post__not_in' => $sticky,
                        'cat' => $catid,
                        'posts_per_page' => 3,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                    );
                } else {
                    $home_recent_post_section = array(
                        'ignore_sticky_posts' => true,
                        'post__not_in' => $sticky,
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                    );
                }

                $home_recent_post_section_query = new WP_Query($home_recent_post_section);

                if ($home_recent_post_section_query->have_posts()) {
                    while ($home_recent_post_section_query->have_posts()) {
                        $home_recent_post_section_query->the_post();
                        ?>
                        <!-- Single blog item -->

                                                <div class="col-md-4 wow fadeInUp">
                                                    <div class="about-col blog">
                                                        <div class="img">
                                                            <?php
                                                            if (has_post_thumbnail()) {
                                                                $image_id = get_post_thumbnail_id();
                                                                $image_url = wp_get_attachment_image_src($image_id, 'large', true);
                                                                ?>
                                                                <img src="<?php echo esc_url($image_url[0]); ?>"
                                                                     class="img-fluid">
                                                            <?php } ?>


                                                        </div>

                                                        <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
                                                        
                                                        <p>
                                                            <?php echo esc_html(wp_trim_words(get_the_content(), 20)); ?>
                                                     <div class="read">  <a class="example_f" href="<?php the_permalink();?>" rel="nofollow"><span><?php esc_html_e('Read More', 'ours-restaurant'); ?></span></a></div>

                                                        </p>
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
add_action('widgets_init', 'ours_restaurant_recent_post_widget');
function ours_restaurant_recent_post_widget()
{
    register_widget('Ours_Restaurant_Recent_Post_Widget');

}
