<?php

//extract styles & classes
extract($extra_attr);
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>" <?php echo (!empty($wrapper_style) ? 'style="'.esc_attr($wrapper_style).'"' : '');?>>
    <?php echo (!empty($attributes['icon']) ? '<i '.(!empty($attributes['customIconColor']) ? 'style="'.esc_attr($icon_style).'" ' : '' ).'class="'.esc_attr($attributes['icon']).(!empty($icon_class) ? ' '.esc_attr($icon_class) : '').'"></i>' : ''); ?>
	<a href="<?php echo get_comments_link(); ?>"><?php
        if ( get_comments_number() ) {
            //translators: %d is a comments count
			echo sprintf( _n( '%d Comment', '%d Comments', get_comments_number(), 'getwid' ), get_comments_number() );
        } else {
            echo __( 'No comments', 'getwid' );
        }
    ?></a>
</div><?php if (isset($attributes['blockDivider']) && $attributes['blockDivider'] != ''){ ?><span class='getwid-post-meta-divider'><?php echo esc_attr($attributes['blockDivider']); ?></span><?php } ?>
