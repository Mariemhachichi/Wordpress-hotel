<?php

namespace Getwid\Blocks;

class PostButton extends \Getwid\Blocks\AbstractBlock {

	protected static $blockName = 'getwid/template-post-button';

    public function __construct() {

		parent::__construct( self::$blockName );

        register_block_type(
            'getwid/template-post-button',
            array(
                'attributes' => array(
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
                    'buttonText' => array(
                        'type' => 'string',
                        'default' => __( 'Read More', 'getwid' )
                    ),
                    'textAlignment' => array(
                        'type' => 'string'
                    ),

                    'className' => array(
                        'type' => 'string'
                    ),
                ),
                'render_callback' => [ $this, 'render_callback' ]
            )
        );
    }

    public function render_callback( $attributes, $content ) {

		//Not BackEnd render if we view from template page
        if ( ( get_post_type() == getwid()->postTemplatePart()->postType ) || ( get_post_type() == 'revision' ) ){
            return $content;
        }

        $block_name = 'wp-block-getwid-template-post-button';
        $wrapper_class = $block_name;
        $wrapper_class .= ' wp-block-button';

        $wrapper_style = '';
        //Classes
        if ( isset( $attributes[ 'className' ] ) ) {
            $wrapper_class .= ' '.esc_attr( $attributes[ 'className' ] );
        }

        if ( isset( $attributes[ 'textAlignment' ] ) ) {
            $wrapper_style .= 'text-align: '.esc_attr( $attributes[ 'textAlignment' ] ).';';
        }

        $is_back_end = \defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST[ 'context' ] ) && 'edit' === $_REQUEST[ 'context' ];

        //Link style & class
        $link_style = '';
        $link_class = 'wp-block-button__link';

        getwid_custom_color_style_and_class( $link_style, $link_class, $attributes, 'background', $is_back_end );
        getwid_custom_color_style_and_class( $link_style, $link_class, $attributes, 'color'     , $is_back_end );

        $extra_attr = array(
            'wrapper_class' => $wrapper_class,
            'wrapper_style' => $wrapper_style,
            'link_class' => $link_class,
            'link_style' => $link_style
        );

        ob_start();

        getwid_get_template_part( 'template-parts/post-button', $attributes, false, $extra_attr );

        $result = ob_get_clean();
        return $result;
    }
}

new \Getwid\Blocks\PostButton();
