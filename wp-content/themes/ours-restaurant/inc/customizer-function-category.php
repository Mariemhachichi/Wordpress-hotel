<?php
/**
 * Define some custom classes for ours_restaurant
 *
 * https://codex.wordpress.org/Class_Reference/WP_Customize_Control
 * @subpackage Business agency
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

    /**
     * A class to create a dropdown for all categories in your WordPress site
     *
     
     *public
     */
    class Ours_Restaurant_Customize_Category_Control extends WP_Customize_Control {

        /**
         * Render the control's content.
         *
         * @return void
         * @since 1.0.0
         */
        public function render_content() {
            $ours_restaurant_dropdown = wp_dropdown_categories(
                array(
                    'name'              => 'customize-dropdown-categories' . $this->id,
                    'echo'              => 0,
                    'show_option_none'  => esc_html__( '&mdash; Select Category &mdash;','ours-restaurant'),
                    'option_none_value' => '0',
                    'selected'          => $this->value(),
                )
            );

            // Hackily add in the data link parameter.
            $ours_restaurant_dropdown = str_replace( '<select', '<select ' . $this->get_link(), $ours_restaurant_dropdown );

            printf(
                '<label class="customize-control-select"><span class="customize-control-title">%s</span><span class="description customize-control-description">%s</span> %s </label>',
                esc_attr($this->label),
                esc_attr($this->description),
                $ours_restaurant_dropdown
            );
        }
    }


}