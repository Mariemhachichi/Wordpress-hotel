<?php
/**
 * Functions for get_theme_mod()
 *
 
 */
if (!function_exists('ours_restaurant_get_option')) :

    /**
     * Get theme option.
     *
     * @since 1.0.0
     *
     * @param string $key Option key.
     * @return mixed Option value.
     */
    function ours_restaurant_get_option($key = '')
    {
        if (empty($key)) {
            return;
        }
        $ours_restaurant_default_options = ours_restaurant_get_default_theme_options();

        $default = (isset($ours_restaurant_default_options[$key])) ? $ours_restaurant_default_options[$key] : '';

        $theme_option = get_theme_mod($key, $default);

        return $theme_option;

    }

endif;

