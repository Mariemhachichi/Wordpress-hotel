<?php

namespace Getwid\Blocks;

class PostTags extends \Getwid\Blocks\AbstractBlock {

	protected static $blockName = 'getwid/template-post-tags';

    public function __construct() {

		parent::__construct( self::$blockName );

        register_block_type(
            self::$blockName,
            array(
                'attributes' => array(
                    'blockDivider' => array(
                        'type' => 'string'
                    ),

                    //Colors
                    'textColor' => array(
                        'type' => 'string'
                    ),
                    'customTextColor' => array(
                        'type' => 'string'
                    ),
                    'backgroundColor' => array(
                        'type' => 'string'
                    ),
                    'customBackgroundColor' => array(
                        'type' => 'string'
                    ),

                    //Colors
                    'icon' => array(
                        'type' => 'string',
                        'default' => 'fas fa-tags'
                    ),
                    'iconColor' => array(
                        'type' => 'string'
                    ),
                    'customIconColor' => array(
                        'type' => 'string'
                    ),
                    'fontSize' => array(
                        'type' => 'string'
                    ),
                    'customFontSize' => array(
                        'type' => 'number'
                    ),
                    'divider' => array(
                        'type' => 'string',
                        'default' => ','
                    ),
                    'textAlignment' => array(
                        'type' => 'string'
                    ),
                    'className' => array(
                        'type' => 'string'
                    )
                ),
                'render_callback' => [ $this, 'render_callback' ]
            )
        );
    }

    public function render_callback( $attributes, $content ) {

        //Not BackEnd render if we view from template page
        if ( ( get_post_type() == getwid()->postTemplatePart()->postType ) || ( get_post_type() == 'revision' ) ) {
            return $content;
        }

        $block_name = 'wp-block-getwid-template-post-tags';
        $wrapper_class = $block_name;

        $wrapper_style = '';
        //Classes
        if ( isset( $attributes[ 'className' ] ) ) {
            $wrapper_class .= ' '.esc_attr( $attributes[ 'className' ] );
        }

        if ( isset( $attributes[ 'divider' ] ) && $attributes[ 'divider' ] != '' ) {
            $wrapper_class .= ' has-divider';
        }

        if ( isset( $attributes[ 'textAlignment' ]) ) {
            $wrapper_style .= 'text-align: '.esc_attr( $attributes[ 'textAlignment' ] ) . ';';
        }

        if ( isset( $attributes[ 'customFontSize' ] ) ) {
            $wrapper_style .= 'font-size: ' . esc_attr( $attributes[ 'customFontSize' ] ) . 'px;';
        }

        if ( isset( $attributes[ 'fontSize'] ) ) {
            $wrapper_class .= ' has-' . esc_attr( $attributes[ 'fontSize' ] ) . '-font-size';
        }

        $divider = isset( $attributes['divider'] ) && $attributes[ 'divider' ] != '' ? $attributes[ 'divider' ] : '';

        $is_back_end = \defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST[ 'context' ] ) && 'edit' === $_REQUEST[ 'context' ];

        getwid_custom_color_style_and_class( $wrapper_style, $wrapper_class, $attributes, 'color', $is_back_end );

        $tags_list = get_the_tag_list( '', '');

        $icon_class = '';
        $icon_style = '';
        getwid_custom_color_style_and_class( $icon_style, $icon_class, $attributes, 'color', $is_back_end, [ 'color' => 'iconColor', 'custom' => 'customIconColor' ] );

        $result = '';

        $extra_attr = array(
            'wrapper_class' => $wrapper_class,
            'wrapper_style' => $wrapper_style,

            'divider'    => $divider,
            'icon_class' => $icon_class,
            'icon_style' => $icon_style
        );

        if ($tags_list) {
            ob_start();

            getwid_get_template_part( 'template-parts/post-tags', $attributes, false, $extra_attr );

            $result = ob_get_clean();
        }

        return $result;
    }
}

new \Getwid\Blocks\PostTags();
