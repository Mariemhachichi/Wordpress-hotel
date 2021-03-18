<?php
/**
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Hotelone_bootstrap_navwalker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\"dropdown-menu\">\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth >= 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} elseif ( strcasecmp( $item->title, 'divider') == 0 && $depth >= 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if($args->has_children && $depth === 0) {
				$class_names .= ' dropdown';
			} elseif($args->has_children && $depth > 0) {
				$class_names .= ' dropdown dropdown-submenu';
			}

			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$atts = array();
			$atts['title']  = ! empty( $item->title )	? sanitize_text_field( $item->title )	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			if ( in_array( 'social-item', $classes ) )
				$item->title  = '';

			if ( $args->has_children ) {
				$atts['href']           = ! empty( $item->url ) ? $item->url : '';
				$atts['data-toggle']    = 'dropdown';
				$atts['class']          = 'dropdown-toggle';
				$atts['aria-haspopup']  = 'true';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

            $is_wpml_item = ! empty( $item->type ) && $item->type === 'wpml_ls_menu_item';

            if ( ! empty( $item->attr_title ) && ! $is_wpml_item ) {
	            $item_output .= '<a' . $attributes . '><i class="fa ' . esc_attr( $item->attr_title ) . ' "></i>&nbsp;';
            } elseif( in_array( 'hotelone-mm-heading', $item->classes ) && ( $item->url === '#' ) ) {
            	$item_output .= '<span class="mm-heading-wrapper">';
            } elseif( in_array( 'hotelone-mm-heading', $item->classes ) ) {
            	$item_output .= '<span class="mm-heading-wrapper"><a' . $attributes . '>';
            } else {
	            $item_output .= '<a' . $attributes . '>';
            }
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= ( $args->has_children ) ? ' <span class="caret"></span></a>' : '</a></li>';

			if( ! empty( $item->description ) && ( $item->description !== ' ' ) && $depth >= 1 ) {
				$item_output .= '<li class="hotelone-mm-description">' . $item->description;
			}

			$item_output .= $args->after;


			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		}
	}
	
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">'.__('Add a menu','hotelone').'</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			$allowed_html = array(
				'a' => array( 'href' => array(), ),
				'div' => array( 'id' => array(), 'class' => array(), ),
				'ul' => array( 'class' => array() ),
				'li' => array(),
			);

			echo wp_kses( $fb_output, $allowed_html );
		}
	}
}
