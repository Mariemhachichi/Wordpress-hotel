<?php
/**
 * Main menu walker.
 */
class Di_Restaurant_Topmain_Nav_Walker extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$li_attributes = '';
			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'nav-item';
			$classes[] = ( $args->has_children ) ? 'dropdown' : '';
			$classes[] = ( $item->current || $item->current_item_ancestor ) ? 'active' : '';
			$classes[] = 'menu-item-' . $item->ID;


			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter($classes), $item, $args ) );

			if( $depth == 1 && $args->has_children ) {
				$class_names .= ' dropdown-submenu';
			}
			$class_names = ' class="' . esc_attr( $class_names ) . '"';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
			$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

			$attributes = !empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= !empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
			$attributes .= !empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
			$attributes .= !empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
			//$attributes .= ( $args->has_children ) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
			$attributes .= ( $args->has_children ) ? ' class="dropdown-toggle nav-link"' : ' class="nav-link"';

			$item_output = $args->before;
			$item_output .= '<a ' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

			$item_output .= ( $depth == 0 && $args->has_children || $depth == 1 && $args->has_children ) ? '</a><p class="dropdowntoggle"><a href="#"><span class="fa fa-chevron-circle-down caretmobile"></span></a></p>' : '</a>';
			
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

			if( !$element ) {
				return;
			}

			$id_field = $this->db_fields['id'];

			// Display this element.
			if( is_array( $args[0] ) ) {
				$args[0]['has_children'] = !empty( $children_elements[$element->$id_field] );
			} elseif( is_object( $args[0] ) ) {
				$args[0]->has_children = !empty( $children_elements[$element->$id_field] );
			}

			$cb_args = array_merge( array( &$output, $element, $depth ), $args );
			call_user_func_array( array( &$this, 'start_el' ), $cb_args );

			$id = $element->$id_field;

			// Descend only when the depth is right and there are childrens for this element.
			if( ( $max_depth == 0 || $max_depth > $depth + 1 ) && isset( $children_elements[$id] ) ) {
				foreach( $children_elements[$id] as $child ) {
					if( !isset( $newlevel ) ) {
						$newlevel = true;
						// Start the child delimiter.
						$cb_args = array_merge( array( &$output, $depth ), $args );
						call_user_func_array( array( &$this, 'start_lvl' ), $cb_args );
					}
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}
				unset( $children_elements[$id] );
			}

			if( isset( $newlevel ) && $newlevel ) {
				// End the child delimiter.
				$cb_args = array_merge( array( &$output, $depth ), $args );
				call_user_func_array( array( &$this, 'end_lvl' ), $cb_args );
			}

			// End this element.
			$cb_args = array_merge( array( &$output, $element, $depth ), $args );
			call_user_func_array( array( &$this, 'end_el' ), $cb_args );
		}

	/*
	 * Main Menu Fallback.
	 */
	public static function nav_fallback( $args ) {
		extract( $args );
		$output = null;
		if( $container ) {
			$output = '<' . $container;
			if ( $container_id ) {
				$output .= ' id="' . $container_id . '"';
			}
			if ( $container_class ) {
				$output .= ' class="' . $container_class . '"';
			}
			$output .= '>';
		}
		
		$output .= '<ul';
		if( $menu_id ) {
			$output .= ' id="' . $menu_id . '"';
		}
		if( $menu_class ) {
			$output .= ' class="' . $menu_class . '"';
		}
		$output .= '>';
		
		$output .= '<li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item current_page_item active"><a class="nav-link" href="' . esc_url( home_url( '/' ) ) . '">'. __( 'Home', 'di-restaurant' ) .'</a></li>';

		$output .= '<li class="menu-item menu-item-type-custom"><a class="nav-link" href="#">'. __( 'Blog', 'di-restaurant' ) .'</a></li>';

		$output .= '<li class="menu-item menu-item-type-custom"><a class="nav-link" href="#">'. __( 'Responsive', 'di-restaurant' ) .'</a></li>';

		$output .= '<li class="menu-item menu-item-type-custom"><a class="nav-link" href="#">'. __( 'SEO Friendly', 'di-restaurant' ) .'</a></li>';
		
		if( current_user_can( 'manage_options' ) ) {
			$output .= '<li class="menu-item menu-item-type-custom"><a class="nav-link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">'. __( 'Add Menu', 'di-restaurant' ) .'</a></li>';
		}
		
		$output .= '</ul>';
		if( $container ) {
			$output .= '</' . $container . '>';
		}
		echo $output;
	}

}
