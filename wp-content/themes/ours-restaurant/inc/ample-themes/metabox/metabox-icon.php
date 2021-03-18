<?php
if( !class_exists( 'Ours_Restaurant_Font_Awesome_Class_Metabox') ){
    class Ours_Restaurant_Font_Awesome_Class_Metabox {

        public function __construct()
        {

            add_action( 'add_meta_boxes', array( $this, 'ours_restaurant_icon_metabox') );

            add_action( 'save_post', array( $this, 'ours_restaurant_save_icon_value') );
        }


        public function ours_restaurant_icon_metabox()
        {

            add_meta_box(
                'ours_restaurant_icon',
                esc_html__('Font Awesome class', 'ours-restaurant'),
                array(
                    $this, 'ours_restaurant_generate_icon'),
                'post',
                'side',
                'high'
            );


            add_meta_box(
                'ours_restaurant_icon',
                esc_html__('Font Awesome class', 'ours-restaurant'),
                array(
                    $this, 'ours_restaurant_generate_icon'),
                'page',
                'side',
                'high'
            );
        }

        public function ours_restaurant_generate_icon($post)
        {
            $values = get_post_meta( $post->ID, 'ours_restaurant_icon', true );
            wp_nonce_field( basename(__FILE__), 'ours_restaurant_fontawesome_fields_nonce');
            ?>
            <input type="text" name="icon" value="<?php echo esc_html($values) ?>" />
            <small>
                <?php

                printf( __( '%1$sRefer here%2$s for icon class. For example: %3$sfas fa-shipping-fast%4$s', 'ours-restaurant' ),
              '<br /><a href="'.esc_url( 'https://fontawesome.com/icons' ).'" target="_blank">','</a>',"<code>","
              </code>");
                ?>
            </small>
            <?php
        }

        public function ours_restaurant_save_icon_value($post_id)
        {

            /*
                * A Guide to Writing Secure Themes – Part 4: Securing Post Meta
                *https://make.wordpress.org/themes/2015/06/09/a-guide-to-writing-secure-themes-part-4-securing-post-meta/
                * */
            if (
                !isset($_POST['ours_restaurant_fontawesome_fields_nonce']) ||
                !wp_verify_nonce($_POST['ours_restaurant_fontawesome_fields_nonce'], basename(__FILE__)) || /*Protecting against unwanted requests*/
                (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || /*Dealing with autosaves*/
                !current_user_can('edit_post', $post_id)/*Verifying access rights*/
            ) {
                return;
            }

            //Execute this saving function
            if (isset($_POST['icon']) && !empty($_POST['icon'])) {
                $fontawesomeclass = sanitize_text_field( $_POST['icon'] );
                update_post_meta($post_id, 'ours_restaurant_icon', $fontawesomeclass);
            }
        }
    }
}
$productsMetabox = new ours_restaurant_Font_awesome_Class_Metabox;




