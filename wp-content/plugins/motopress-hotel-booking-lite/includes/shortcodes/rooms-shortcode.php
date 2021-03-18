<?php

namespace MPHB\Shortcodes;

class RoomsShortcode extends AbstractShortcode {

	protected $name = 'mphb_rooms';
	protected $isShowGallery;
	protected $isShowFeaturedImage;
	protected $isShowTitle;
	protected $isShowExcerpt;
	protected $isShowDetails;
	protected $isShowPrice;
	protected $isShowViewButton;
	protected $isShowBookButton;
	protected $customPostsPerPage;
	protected $category;
	protected $tags;
	protected $relation;
	protected $ids;
	protected $order;

	public function addActions(){
		parent::addActions();

		add_action( 'mphb_sc_rooms_render_gallery', array( '\MPHB\Views\LoopRoomTypeView', 'renderGallery' ) );
		add_action( 'mphb_sc_rooms_render_image', array( '\MPHB\Views\LoopRoomTypeView', 'renderFeaturedImage' ) );
		add_action( 'mphb_sc_rooms_render_title', array( '\MPHB\Views\LoopRoomTypeView', 'renderTitle' ) );
		add_action( 'mphb_sc_rooms_render_excerpt', array( '\MPHB\Views\LoopRoomTypeView', 'renderExcerpt' ) );
		add_action( 'mphb_sc_rooms_render_details', array( '\MPHB\Views\LoopRoomTypeView', 'renderAttributes' ) );
		add_action( 'mphb_sc_rooms_render_price', array( '\MPHB\Views\LoopRoomTypeView', 'renderPrice' ) );
		add_action( 'mphb_sc_rooms_render_view_button', array( '\MPHB\Views\LoopRoomTypeView', 'renderViewDetailsButton' ) );
		add_action( 'mphb_sc_rooms_render_book_button', array( '\MPHB\Views\LoopRoomTypeView', 'renderBookButton' ) );

		add_action( 'mphb_sc_rooms_after_loop', array( '\MPHB\Views\GlobalView', 'renderPagination' ) );
	}

	/**
	 *
	 * @param array $atts
	 * @param string $content
	 * @param string $shortcodeName
	 * @return string
	 */
	public function render( $atts, $content = null, $shortcodeName ){
		$defaultAtts = array(
			'gallery'			 => 'true',
			'featured_image'	 => 'true',
			'title'				 => 'true',
			'excerpt'			 => 'true',
			'details'			 => 'true',
			'price'				 => 'true',
			'view_button'		 => 'true',
			'book_button'		 => 'true',
			'posts_per_page'	 => '',
			'class'				 => '',
			'category'			 => '',
			'tags'				 => '',
			'relation'			 => 'OR',
			'ids'				 => '',
			'orderby'			 => 'date',
			'order'				 => 'DESC',
			'meta_key'			 => '',
			'meta_type'			 => ''
		);

		$atts = shortcode_atts( $defaultAtts, $atts, $shortcodeName );

		$this->isShowGallery		 = \MPHB\Utils\ValidateUtils::validateBool( $atts['gallery'] );
		$this->isShowFeaturedImage	 = \MPHB\Utils\ValidateUtils::validateBool( $atts['featured_image'] );
		$this->isShowTitle			 = \MPHB\Utils\ValidateUtils::validateBool( $atts['title'] );
		$this->isShowExcerpt		 = \MPHB\Utils\ValidateUtils::validateBool( $atts['excerpt'] );
		$this->isShowDetails		 = \MPHB\Utils\ValidateUtils::validateBool( $atts['details'] );
		$this->isShowPrice			 = \MPHB\Utils\ValidateUtils::validateBool( $atts['price'] );
		$this->isShowViewButton		 = \MPHB\Utils\ValidateUtils::validateBool( $atts['view_button'] );
		$this->isShowBookButton		 = \MPHB\Utils\ValidateUtils::validateBool( $atts['book_button'] );
		$this->customPostsPerPage	 = \MPHB\Utils\ValidateUtils::validateInt( $atts['posts_per_page'], -1 );
		$this->category				 = \MPHB\Utils\ValidateUtils::validateCommaSeparatedIds( $atts['category'] );
		$this->tags					 = \MPHB\Utils\ValidateUtils::validateCommaSeparatedIds( $atts['tags'] );
		$this->relation				 = \MPHB\Utils\ValidateUtils::validateRelation( $atts['relation'] );
		$this->ids					 = \MPHB\Utils\ValidateUtils::validateCommaSeparatedIds( $atts['ids'] );
		$this->order				 = $this->buildOrderQuery( $atts, $defaultAtts );

		ob_start();
		$this->mainLoop();
		$content = ob_get_clean();

		$wrapperClass = apply_filters( 'mphb_sc_rooms_wrapper_class', 'mphb_sc_rooms-wrapper mphb-room-types' );
		$wrapperClass .= empty( $wrapperClass ) ? $atts['class'] : ' ' . $atts['class'];
		return '<div class="' . esc_attr( $wrapperClass ) . '">' . $content . '</div>';
	}

	public function mainLoop(){

		$roomTypesQuery = $this->getRoomTypesQuery();

		if ( $roomTypesQuery->have_posts() ) {

			do_action( 'mphb_sc_rooms_before_loop', $roomTypesQuery );

			while ( $roomTypesQuery->have_posts() ) : $roomTypesQuery->the_post();

				do_action( 'mphb_sc_rooms_before_item' );

				$this->renderRoomType();

				do_action( 'mphb_sc_rooms_after_item' );

			endwhile;

			wp_reset_postdata();

			do_action( 'mphb_sc_rooms_after_loop', $roomTypesQuery );
		} else {
			$this->showNotFoundMessage();
		}
	}

	public function getRoomTypesQuery(){
		$queryAtts = array_merge(
			$this->order, // <- "orderby", "order", "meta_key", "meta_type"
			array(
				'post_type'				 => MPHB()->postTypes()->roomType()->getPostType(),
				'post_status'			 => 'publish',
				'paged'					 => mphb_get_paged_query_var(),
				'ignore_sticky_posts'	 => true
			)
		);

		if ( $this->customPostsPerPage !== false ) {
			$queryAtts['posts_per_page'] = $this->customPostsPerPage;
		}

		if ( !empty( $this->ids ) ) {
			$queryAtts['post__in'] = $this->ids;
		} else if ( !empty( $this->category ) || !empty( $this->tags ) ) {
			$queryAtts['tax_query'] = array(
				'relation' => $this->relation
			);
			if ( !empty( $this->category ) ) {
				$queryAtts['tax_query'][] = array(
					'taxonomy'	 => MPHB()->postTypes()->roomType()->getCategoryTaxName(),
					'terms'		 => $this->category
				);
			}
			if ( !empty( $this->tags ) ) {
				$queryAtts['tax_query'][] = array(
					'taxonomy'	 => MPHB()->postTypes()->roomType()->getTagTaxName(),
					'terms'		 => $this->tags
				);
			}
		}

		return new \WP_Query( $queryAtts );
	}

	private function renderRoomType(){
		$templateAtts = array(
			'isShowGallery'		 => $this->isShowGallery,
			'isShowImage'		 => $this->isShowFeaturedImage,
			'isShowTitle'		 => $this->isShowTitle,
			'isShowExcerpt'		 => $this->isShowExcerpt,
			'isShowDetails'		 => $this->isShowDetails,
			'isShowPrice'		 => $this->isShowPrice,
			'isShowViewButton'	 => $this->isShowViewButton,
			'isShowBookButton'	 => $this->isShowBookButton
		);
		mphb_get_template_part( 'shortcodes/rooms/room-content', $templateAtts );
	}

	public function showNotFoundMessage(){
		mphb_get_template_part( 'shortcodes/rooms/not-found' );
	}

}
