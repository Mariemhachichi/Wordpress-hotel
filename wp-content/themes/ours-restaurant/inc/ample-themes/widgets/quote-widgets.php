<?php
if (!class_exists('Ours_Restaurant_Quote_Widget')) {
    class Ours_Restaurant_Quote_Widget extends WP_Widget
    {
        private function defaults()
        {
            $defaults = array(
                'title' => '',
                'button-text1' => esc_html__('button1', 'ours-restaurant'),
                'button-text-link1' => '#',
                'sub-title' =>'',
                'features_background' => '',
                'section-id' =>'',

            );
            return $defaults;
        }

        public function __construct()
        {
            parent::__construct(
                'ours-restaurant-quote-widget',
                esc_html__('AT : Quote Widget', 'ours-restaurant'),
                array('description' => esc_html__(' Quote Section', 'ours-restaurant'))
            );
        }

        public function form($instance)
        {
            $instance = wp_parse_args((array )$instance, $this->defaults());
            $section_id= esc_attr( $instance['section-id'] );
            $title = esc_attr($instance['title']);
            $subtitle = esc_attr($instance['sub-title']);
            $button_text1 = esc_attr($instance['button-text1']);
            $button_text_link1 = esc_url($instance['button-text-link1']);
            $features_background = esc_url($instance['features_background']);
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
                <label for="<?php echo esc_attr($this->get_field_id('sub-title')); ?>">
                    <?php esc_html_e('Sub Title', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('sub-title')); ?>" class="widefat"
                       id="<?php echo esc_attr($this->get_field_id('sub-title')); ?>" value="<?php echo esc_attr($subtitle); ?>">
            </p>

            <p>
                <label
                        for="<?php echo esc_attr($this->get_field_id('button-text1')); ?>"><?php esc_html_e('Button Text1', 'ours-restaurant'); ?></label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('button-text1')); ?>" class="widefat"
                       id="<?php echo esc_attr($this->get_field_id('button-text1')); ?>"
                       value="<?php echo esc_attr ($button_text1); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('button-text-link1')); ?>">
                    <?php esc_html_e('Button Link1', 'ours-restaurant'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('button-text-link1')); ?>"
                       class="widefat" id="<?php echo esc_attr($this->get_field_id('button-text-link1')); ?>"
                       value="<?php echo esc_attr($button_text_link1); ?>">
            </p>
            
            <p>
                <label for="<?php echo $this->get_field_id('features_background'); ?>">
                    <?php _e('Select Background Image', 'ours-restaurant'); ?>:
                </label>
                <span class="img-preview-wrap" <?php if (empty($features_background)) { ?> style="display:none;" <?php } ?>>
                    <img class="widefat" src="<?php echo esc_url($features_background); ?>"
                         alt="<?php esc_attr_e('Image preview', 'ours-restaurant'); ?>"/>
                </span><!-- .img-preview-wrap -->
                <input type="text" class="widefat" name="<?php echo $this->get_field_name('features_background'); ?>"
                       id="<?php echo $this->get_field_id('features_background'); ?>"
                       value="<?php echo esc_url($features_background); ?>"/>
                <input type="button" id="custom_media_button"
                       value="<?php esc_attr_e('Upload Image', 'ours-restaurant'); ?>" class="button media-image-upload"
                       data-title="<?php esc_attr_e('Select Background Image', 'ours-restaurant'); ?>"
                       data-button="<?php esc_attr_e('Select Background Image', 'ours-restaurant'); ?>"/>
                <input type="button" id="remove_media_button"
                       value="<?php esc_attr_e('Remove Image', 'ours-restaurant'); ?>"
                       class="button media-image-remove"/>
            </p>
            <hr>
            <?php
        }

        public function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $instance['title'] = sanitize_text_field($new_instance['title']);
            $instance['sub-title'] = sanitize_text_field($new_instance['sub-title']);
            $instance['button-text1'] = sanitize_text_field($new_instance['button-text1']);
            $instance['button-text-link1'] = esc_url_raw($new_instance['button-text-link1']);
            $instance['features_background'] = esc_url_raw($new_instance['features_background']);
            $instance['section-id'] = sanitize_text_field($new_instance['section-id']);


            return $instance;
        }

        public function widget($args, $instance)
        {

            if (!empty($instance)) {
                $instance = wp_parse_args((array )$instance, $this->defaults());
                $title = apply_filters('widget_title', !empty($instance['title']) ? esc_html($instance['title']) : '', $instance, $this->id_base);
                $subtitle = esc_html($instance['sub-title']);
                $button_text1 = esc_html($instance['button-text1']);
                $button_text_link1 = esc_url($instance['button-text-link1']);
                $section_id = esc_attr($instance['section-id']);
                $features_background = esc_url($instance['features_background']);
                echo $args['before_widget'];
                ?>

                <section id="<?php  echo esc_attr($section_id); ?>">

                <div id="call-to-action" class="wow fadeIn" style="background-image: url(<?php echo esc_url($features_background); ?>);">
                    <div class="container text-center">
                        <h3><?php echo esc_html($title); ?></h3>
                        <p><?php echo esc_html($subtitle); ?></p>
                <?php if (!empty($button_text1)) { ?>
                        <a class="cta-btn" href="<?php echo esc_url($button_text_link1) ; ?>"><span><?php echo esc_html($button_text1); ?></span></a>
                <?php } ?>
                    </div>
                </div><!-- #call-to-action -->
                </section>

                
                <?php
                echo $args['after_widget'];
            }
        }
    }
}
add_action('widgets_init', 'ours_restaurant_quote_widget');
function ours_restaurant_quote_widget()
{
    register_widget('Ours_Restaurant_Quote_Widget');

}

