<?php

use MPHB\PostTypes\PaymentCPT\Statuses as PaymentStatuses;

/**
 * Display the room type default (average minimal) price for min days stay
 *
 * @param int $id Optional. Current room type by default.
 */
function mphb_tmpl_the_room_type_default_price( $id = null ){

	$roomType = $id ? MPHB()->getRoomTypeRepository()->findById( $id ) : MPHB()->getCurrentRoomType();

	$nights = MPHB()->getRulesChecker()->reservationRules()->getMinDaysAllSeason( $roomType->getOriginalId() );
	$price  = mphb_get_room_type_base_price( $roomType );

	$defaultPriceForNights = $price * $nights;

	$title = __( 'Choose dates to see relevant prices', 'motopress-hotel-booking' );

	$formattedPrice = mphb_format_price( $defaultPriceForNights, array(
		'period'		 => true,
		'period_nights'	 => $nights,
		'period_title'	 => $title
		)
	);

	echo $formattedPrice;
}

/**
 *
 * @param int $id Optional. Current room type by default.
 * @return bool
 */
function mphb_tmpl_has_room_type_default_price( $id = null ){
	$roomType = $id ? MPHB()->getRoomTypeRepository()->findById( $id ) : MPHB()->getCurrentRoomType();
	return mphb_get_room_type_base_price( $roomType ) > 0;
}

/**
 * Display the room type minimal price for dates
 *
 * @param \DateTime $checkInDate
 * @param \DateTime $checkOutDate
 * @return string
 */
function mphb_tmpl_the_room_type_price_for_dates( \DateTime $checkInDate, \DateTime $checkOutDate ){
	$price = mphb_get_room_type_period_price( $checkInDate, $checkOutDate );

	$nights = \MPHB\Utils\DateUtils::calcNights( $checkInDate, $checkOutDate );

	$title = __( 'Based on your search parameters', 'motopress-hotel-booking' );

	$formattedPrice = mphb_format_price( $price, array(
		'period'		 => true,
		'period_nights'	 => $nights,
		'period_title'	 => $title
		)
	);

	echo $formattedPrice;
}

/**
 * Retrieve dayname for key
 *
 * @param string|int $key number from 0 to 6
 * @return string
 */
function mphb_tmpl_get_day_by_key( $key ){
	return \MPHB\Utils\DateUtils::getDayByKey( $key );
}

/**
 * @return int
 *
 * @since 3.7.2
 */
function mphb_tmpl_get_room_type_total_capacity()
{
    return MPHB()->getCurrentRoomType()->getTotalCapacity();
}

/**
 * Retrieve the room type adults capacity
 *
 * @return int
 */
function mphb_tmpl_get_room_type_adults_capacity(){
	return MPHB()->getCurrentRoomType()->getAdultsCapacity();
}

/**
 * Retrieve the room type children capacity
 *
 * @return int
 */
function mphb_tmpl_get_room_type_children_capacity(){
	return MPHB()->getCurrentRoomType()->getChildrenCapacity();
}

/**
 * Retrieve the room type bed type
 *
 * @return string
 */
function mphb_tmpl_get_room_type_bed_type(){
	return MPHB()->getCurrentRoomType()->getBedType();
}

/**
 * Retrieve the room type facilities
 *
 * @return array
 */
function mphb_tmpl_get_room_type_facilities(){
	return MPHB()->getCurrentRoomType()->getFacilities();
}

/**
 * Retrieve the room type attributes
 *
 * @return array [%Attribute name% => [%ID% => %Term title%]]
 */
function mphb_tmpl_get_room_type_attributes(){
	return MPHB()->getCurrentRoomType()->getAttributes();
}

/**
 * Retrieve the room type size
 *
 * @param $withUnits Optional. Add units to size. TRUE by default.
 * @return string
 *
 * @since 3.6.1 added optional parameter $withUnits.
 */
function mphb_tmpl_get_room_type_size( $withUnits = true ){
	return MPHB()->getCurrentRoomType()->getSize( $withUnits );
}

/**
 * Retrieve the room type categories
 *
 * @return array
 */
function mphb_tmpl_get_room_type_categories(){
	return MPHB()->getCurrentRoomType()->getCategories();
}

/**
 * Retrieve the room type view
 *
 * @return string
 */
function mphb_tmpl_get_room_type_view(){
	return MPHB()->getCurrentRoomType()->getView();
}

/**
 * Check is current room type has gallery.
 *
 * @return bool
 */
function mphb_tmpl_has_room_type_gallery(){
	return MPHB()->getCurrentRoomType()->hasGallery();
}

/**
 *
 * @param bool $withFeaturedImage
 * @return array
 */
function mphb_tmpl_get_room_type_gallery_ids( $withFeaturedImage = false ){
	$roomType	 = MPHB()->getCurrentRoomType();
	$galleryIds	 = $roomType->getGalleryIds();

	if ( $withFeaturedImage && $roomType->hasFeaturedImage() ) {
		array_unshift( $galleryIds, $roomType->getFeaturedImageId() );
	}

	return $galleryIds;
}

/**
 *
 * @param array $atts @see gallery_shortcode
 */
function mphb_tmpl_the_room_type_galery( $atts = array() ){

	$defaultAtts = array(
		'ids' => join( ',', mphb_tmpl_get_room_type_gallery_ids() )
	);

	$atts = array_merge( $defaultAtts, $atts );

	if ( isset( $atts['link'] ) && $atts['link'] === 'post' ) {
		$forceLinkToPost = true;
		$atts['link']	 = '';
	} else {
		$forceLinkToPost = false;
	}

	$wrapperClass = 'mphb-room-type-gallery-wrapper';
	if ( isset( $atts['mphb_wrapper_class'] ) ) {
		$wrapperClass .= ' ' . $atts['mphb_wrapper_class'];
		unset( $atts['mphb_wrapper_class'] );
	}

	$galleryContent = gallery_shortcode( $atts );

	if ( $forceLinkToPost ) {
		$linkToAttachmentRegExp = join( '|', array_map( function($id) {
				return preg_quote( get_the_permalink( $id ), '/' );
			}, explode( ',', $atts['ids'] ) ) );
		$linkToPost = get_the_permalink();

		if ( !empty( $linkToAttachmentRegExp ) ) {
			$galleryContent = preg_replace( '/href=["|\'](' . $linkToAttachmentRegExp . ')["|\']/', 'href="' . $linkToPost . '"', $galleryContent );
		}
	}

	$result = sprintf( '<div class="%s">', esc_attr( $wrapperClass ) );
	$result .= $galleryContent;
	$result .= '</div>';

	echo $result;
}

function mphb_tmpl_the_single_room_type_gallery(){

	$galleryAtts = array(
		'link'				 => apply_filters( 'mphb_single_room_type_gallery_image_link', 'file' ),
		'columns'			 => apply_filters( 'mphb_single_room_type_gallery_columns', '4' ),
		'size'				 => apply_filters( 'mphb_single_room_type_gallery_image_size', 'thumbnail' ),
		'mphb_wrapper_class' => apply_filters( 'mphb_single_room_type_gallery_wrapper_class', 'mphb-single-room-type-gallery-wrapper' )
	);

	mphb_tmpl_the_room_type_galery( $galleryAtts );
}

function mphb_tmpl_the_room_type_flexslider_gallery(){

	$uniqid = uniqid();

	$galleryIds = mphb_tmpl_get_room_type_gallery_ids();

	$sliderUniqueClass		 = 'mphb-gallery-main-slider-' . $uniqid;
	$navSliderUniqueClass	 = 'mphb-gallery-thumbnail-slider-' . $uniqid;

	$mainGalleryAtts = array(
		'link'				 => apply_filters( 'mphb_loop_room_type_gallery_main_slider_image_link', 'post' ),
		'columns'			 => apply_filters( 'mphb_loop_room_type_gallery_main_slider_columns', '1' ),
		'size'				 => apply_filters( 'mphb_loop_room_type_gallery_main_slider_image_size', 'large' ),
		'mphb_wrapper_class' => apply_filters( 'mphb_loop_room_type_gallery_main_slider_wrapper_class', 'mphb-gallery-main-slider mphb-flexslider-gallery-wrapper mphb-room-type-gallery-wrapper ' . $sliderUniqueClass ),
		'group_id'			 => $uniqid
	);

	$mainGalleryFlexsliderOptions = array(
		'animation'		 => 'slide',
		'controlNav'	 => false,
		'animationLoop'	 => true,
		'smoothHeight'	 => true,
		'slideshow'		 => false
	);

	$mainGalleryFlexsliderOptions = apply_filters( 'mphb_loop_room_type_gallery_main_slider_flexslider_options', $mainGalleryFlexsliderOptions );

	do_action( 'mphb_loop_room_type_gallery_main_slider_flexslider_before' );

	mphb_the_flexslider_gallery( $galleryIds, $mainGalleryAtts, $mainGalleryFlexsliderOptions );

	do_action( 'mphb_loop_room_type_gallery_main_slider_flexslider_after' );

	if ( apply_filters( 'mphb_loop_room_type_gallery_use_nav_slider', true ) ) {

		$navGalleryAtts = array(
			'link'				 => apply_filters( 'mphb_loop_room_type_gallery_nav_slider_image_size', 'large' ),
			'columns'			 => apply_filters( 'mphb_loop_room_type_gallery_nav_slider_columns', '4' ),
			'size'				 => apply_filters( 'mphb_loop_room_type_gallery_nav_slider_image_size', 'thumbnail' ),
			'mphb_wrapper_class' => apply_filters( 'mphb_loop_room_type_gallery_main_slider_wrapper_class', 'mphb-gallery-thumbnail-slider mphb-flexslider-gallery-wrapper mphb-room-type-gallery-wrapper ' . $navSliderUniqueClass ),
			'group_id'			 => $uniqid
		);

		$navGalleryFlexsliderOptions = array(
			'animation'		 => 'slide',
			'controlNav'	 => false,
			'animationLoop'	 => true,
			'slideshow'		 => false,
			'itemMargin'	 => 5,
		);

		$navGalleryFlexsliderOptions = apply_filters( 'mphb_loop_room_type_gallery_nav_slider_flexslider_options', $navGalleryFlexsliderOptions );

		do_action( 'mphb_loop_room_type_gallery_nav_slider_flexslider_before' );

		mphb_the_flexslider_gallery( $galleryIds, $navGalleryAtts, $navGalleryFlexsliderOptions );

		do_action( 'mphb_loop_room_type_gallery_nav_slider_flexslider_after' );
	}

	MPHB()->getPublicScriptManager()->enqueue();
}

/**
 *
 * @param int[] $ids Id of attachments
 * @param array $atts
 * @param string $atts['order'] Default 'ASC';
 * @param string $atts['orderby'] Default 'post__in';
 * @param string $atts['columns'] Default 3;
 * @param string $atts['include'] Default 'thumbnail';
 * @param string $atts['exclude']
 * @param string $atts['link'] Optional. Possible values 'post', 'file', 'none', ''. Default '';
 * @param array $flexsliderAtts
 */
function mphb_the_flexslider_gallery( $ids, $atts, $flexsliderAtts = array() ){

	static $instance = 0;
	$instance++;

	$defaultAtts = array(
		'order'				 => 'ASC',
		'orderby'			 => 'post__in',
		'columns'			 => 3,
		'size'				 => 'thumbnail',
		'exclude'			 => '',
		'link'				 => '',
		'mphb_wrapper_class' => '',
		'group_id'			 => ''
	);

	$atts = array_merge( $defaultAtts, $atts );

	$atts['include'] = $ids;

	if ( empty( $ids ) ) {
		return '';
	}

	$flexsliderDefaultAtts	 = array();
	$flexsliderAtts			 = array_merge( $flexsliderDefaultAtts, $flexsliderAtts );

	$attachmentsArgs = array(
		'include'		 => $ids,
		'post_status'	 => 'inherit',
		'post_type'		 => 'attachment',
		'post_mime_type' => 'image',
		'order'			 => 'ASC',
		'orderby'		 => 'post__in',
	);

	$attachments = array();
	foreach ( get_posts( $attachmentsArgs ) as $key => $val ) {
		$attachments[$val->ID] = $val;
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	$columns	 = intval( $atts['columns'] );
	$itemwidth	 = $columns > 0 ? floor( 100 / $columns ) : 100;
	$float		 = is_rtl() ? 'right' : 'left';

	$selector = "mphb-flexslider-gallery-{$instance}";

	$sizeClass = sanitize_html_class( $atts['size'] );

	$flexsliderAttsData = json_encode( $flexsliderAtts );

	$dataAtts = "data-flexslider-atts='{$flexsliderAttsData}'";
	if ( !empty( $atts['group_id'] ) ) {
		$dataAtts .= " data-group='{$atts['group_id']}'";
	}

	$output = "<div id='$selector' class='gallery-columns-{$columns} gallery-size-{$sizeClass} {$atts['mphb_wrapper_class']}' {$dataAtts}>";

	$i = 0;
	$output .= '<ul class="slides">';
	foreach ( $attachments as $id => $attachment ) {

		$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : array();
        $attr['loading'] = 'eager';

		/**
		 * Disable lazy loading for gallery images
		 *
		 * @see https://developer.jetpack.com/hooks/lazyload_is_enabled/
		 */
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'lazy-images' ) ) {
			$attr['class'] = "attachment-{$sizeClass} size-{$sizeClass} skip-lazy";
		}

		if ( !empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$imageOutput = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
		} elseif ( !empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$imageOutput = wp_get_attachment_image( $id, $atts['size'], false, $attr );
		} elseif ( !empty( $atts['link'] ) && 'post' === $atts['link'] ) {
			$imageOutput = '<a href="' . esc_url( get_the_permalink() ) . '">';
			$imageOutput .= wp_get_attachment_image( $id, $atts['size'], false, $attr );
			$imageOutput .= '</a>';
		} else {
			$imageOutput = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
		}
		$imageMeta = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $imageMeta['height'], $imageMeta['width'] ) ) {
			$orientation = ( $imageMeta['height'] > $imageMeta['width'] ) ? 'portrait' : 'landscape';
		}
		$output .= "<li class='gallery-item'>";
		$output .= "<span class='gallery-icon {$orientation}'>
				$imageOutput
			</span>";
		$output .= "</li>";
	}
	$output .='</ul>';

	$output .= "
		</div>\n";

	echo $output;
}

function mphb_tmpl_the_room_type_featured_image(){
	$imageExcerpt	 = get_post_field( 'post_excerpt', get_post_thumbnail_id() );
	$imageLink		 = wp_get_attachment_url( get_post_thumbnail_id() );
	$image			 = mphb_tmpl_get_room_type_image();

	printf( '<a href="%s" class="mphb-lightbox" title="%s" data-rel="magnific-popup[mphb-room-type-gallery]">%s</a>', esc_url( $imageLink ), esc_attr( $imageExcerpt ), $image );
}

/**
 * Retrieve single room type featured image
 *
 * @param int $id Optional. ID of post.
 * @param string $size Optional. Size of image.
 * @return string HTML img element or empty string on failure.
 */
function mphb_tmpl_get_room_type_image( $postID = null, $size = null ){
	if ( is_null( $postID ) ) {
		$postID = get_the_ID();
	}
	if ( is_null( $size ) ) {
		$size = apply_filters( 'mphb_single_room_type_image_size', 'large' );
	}
	$imageTitle = get_the_title( get_post_thumbnail_id( $postID ) );
	return get_the_post_thumbnail( $postID, $size, array(
		'title' => $imageTitle,
		) );
}

/**
 * Retrieve in-loop room type thumbnail
 *
 * @param string $size
 */
function mphb_tmpl_the_loop_room_type_thumbnail( $size = null ){
	if ( is_null( $size ) ) {
		$size = apply_filters( 'mphb_loop_room_type_thumbnail_size', 'post-thumbnail' );
	}
	the_post_thumbnail( $size );
}

/**
 *
 * @param string $buttonText
 */
function mphb_tmpl_the_loop_room_type_book_button( $buttonText = null ){
	if ( is_null( $buttonText ) ) {
		$buttonText = __( 'Book', 'motopress-hotel-booking' );
	}
	echo '<a class="button mphb-book-button" href="' . esc_url( get_the_permalink() ) . '#booking-form-' . get_the_ID() . '">' . $buttonText . '</a>';
}

/**
 *
 * @param string $buttonText
 */
function mphb_tmpl_the_loop_room_type_book_button_form( $buttonText = null ){
	if ( is_null( $buttonText ) ) {
		$buttonText = __( 'Book', 'motopress-hotel-booking' );
	}
	$actionUrl	 = get_the_permalink();
	$queryArgs	 = mphb_get_query_args( $actionUrl );
	echo '<form action="' . $actionUrl . '#booking-form-' . get_the_ID() . '" method="get" >';
	foreach ( $queryArgs as $name => $value ) {
		echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';
	}
	echo '<button type="submit" class="button mphb-book-button" >' . $buttonText . '</button>';
	echo '</form>';
}

/**
 *
 * @param string $buttonText
 */
function mphb_tmpl_the_loop_room_type_view_details_button( $buttonText = null ){
	if ( is_null( $buttonText ) ) {
		$buttonText = __( 'View Details', 'motopress-hotel-booking' );
	}
	// a.button causes promlems on some themes, when text color = background color
	echo '<a class="button mphb-view-details-button" href="' . esc_url( get_the_permalink() ) . '" >' . $buttonText . '</a>';
}

/**
 * Display room type calendar
 *
 * @param \MPHB\Entities\RoomType $roomType Optional. Use current room type by default.
 * @param string $atts Optional. Additional attributes.
 */
function mphb_tmpl_the_room_type_calendar( $roomType = null, $atts = '' ){
	if ( is_null( $roomType ) ) {
		$roomType = MPHB()->getCurrentRoomType();
	}
	?>
	<div class="mphb-calendar mphb-datepick inlinePicker" id="mphb-calendar-<?php echo $roomType->getId(); ?>"<?php echo $atts; ?>></div>
	<?php
}

/**
 * Display room type reservation form
 *
 * @param \MPHB\Entities\RoomType $roomType Optional. Use current room type by default.
 */
function mphb_tmpl_the_room_reservation_form( $roomType = null ){
	if ( is_null( $roomType ) ) {
		$roomType = MPHB()->getCurrentRoomType();
	}

	$typeId               = $roomType->getId();
	$uniqueSuffix         = uniqid();
	$isDirectBooking      = MPHB()->settings()->main()->isDirectRoomBooking();
    $directBookingPricing = MPHB()->settings()->main()->getDirectBookingPricing();

	if ($isDirectBooking) {
		$searchParameters = MPHB()->searchParametersStorage()->getForRoomType( $roomType );
	} else {
		$searchParameters = MPHB()->searchParametersStorage()->get();
	}

	$checkInDate			 = $searchParameters['mphb_check_in_date'];
	$checkInDateFormatted	 = \MPHB\Utils\DateUtils::convertDateFormat( $checkInDate, MPHB()->settings()->dateTime()->getDateTransferFormat(), MPHB()->settings()->dateTime()->getDateFormat() );
	$checkOutDate			 = $searchParameters['mphb_check_out_date'];
	$checkOutDateFormatted	 = \MPHB\Utils\DateUtils::convertDateFormat( $checkOutDate, MPHB()->settings()->dateTime()->getDateTransferFormat(), MPHB()->settings()->dateTime()->getDateFormat() );

    $selectedAdults   = $searchParameters['mphb_adults'] !== '' ? (int)$searchParameters['mphb_adults'] : -1;
    $selectedChildren = $searchParameters['mphb_children'] !== '' ? (int)$searchParameters['mphb_children'] : -1;

	$actionUrl		 = MPHB()->settings()->pages()->getSearchResultsPageUrl();
	$formMethod		 = 'GET';
	if ( $isDirectBooking ) {
		$actionUrl	 = MPHB()->settings()->pages()->getCheckoutPageUrl();
		$formMethod	 = 'POST';
	}
	?>
	<form method="<?php echo esc_attr( $formMethod ); ?>" action="<?php echo esc_url( $actionUrl ); ?>" class="mphb-booking-form" id="<?php echo esc_attr( 'booking-form-' . $typeId ); ?>">
		<p class="mphb-required-fields-tip"><small><?php printf( __( 'Required fields are followed by %s', 'motopress-hotel-booking' ), '<abbr title="required">*</abbr>' ); ?></small></p>
		<?php wp_nonce_field( \MPHB\Shortcodes\CheckoutShortcode::NONCE_ACTION_CHECKOUT, \MPHB\Shortcodes\CheckoutShortcode::NONCE_NAME ); ?>
		<?php
		foreach ( mphb_get_query_args( $actionUrl ) as $paramName => $paramValue ) {
			printf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $paramName ), esc_attr( $paramValue ) );
		}
		?>
		<input type="hidden" name="mphb_room_type_id" value="<?php echo esc_attr( $typeId ); ?>" />
		<p class="mphb-check-in-date-wrapper">
			<label for="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqueSuffix ); ?>">
				<?php _e( 'Check-in Date', 'motopress-hotel-booking' ); ?>
				<abbr title="<?php printf( _x( 'Formatted as %s', 'Date format tip', 'motopress-hotel-booking' ), MPHB()->settings()->dateTime()->getDateFormatJS() ); ?>">*</abbr>
			</label>
			<br />
			<input id="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqueSuffix ); ?>" type="text" class="mphb-datepick" value="<?php echo esc_attr( $checkInDateFormatted ); ?>" required="required" autocomplete="off" placeholder="<?php _e( 'Check-in Date', 'motopress-hotel-booking' ); ?>" />
			<input id="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqueSuffix . '-hidden' ); ?>" type="hidden" name="mphb_check_in_date" value="<?php echo esc_attr( $checkInDate ); ?>" />
		</p>
		<p class="mphb-check-out-date-wrapper">
			<label for="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqueSuffix ); ?>">
				<?php _e( 'Check-out Date', 'motopress-hotel-booking' ); ?>
				<abbr title="<?php printf( _x( 'Formatted as %s', 'Date format tip', 'motopress-hotel-booking' ), MPHB()->settings()->dateTime()->getDateFormatJS() ); ?>">*</abbr>
			</label>
			<br />
			<input id="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqueSuffix ); ?>" type="text" class="mphb-datepick" value="<?php echo esc_attr( $checkOutDateFormatted ); ?>" required="required" autocomplete="off" placeholder="<?php esc_attr_e( 'Check-out Date', 'motopress-hotel-booking' ); ?>" />
			<input id="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqueSuffix . '-hidden' ); ?>" type="hidden" name="mphb_check_out_date" value="<?php echo esc_attr( $checkOutDate ); ?>" />
		</p>
		<?php if ( !$isDirectBooking || $directBookingPricing == 'capacity' ) { ?>
			<?php if ( MPHB()->settings()->main()->isAdultsDisabledOrHidden() ) { ?>
				<input type="hidden" id="<?php echo esc_attr( 'mphb_adults-' . $uniqueSuffix ); ?>" name="mphb_adults" value="<?php echo esc_attr( MPHB()->settings()->main()->getMinAdults() ); ?>" />
			<?php } else { ?>
				<p class="mphb-adults-wrapper mphb-capacity-wrapper">
					<label for="<?php echo esc_attr( 'mphb_adults-' . $uniqueSuffix ); ?>">
						<?php
							if ( MPHB()->settings()->main()->isChildrenAllowed() ) {
								_e( 'Adults', 'motopress-hotel-booking' );
							} else {
								_e( 'Guests', 'motopress-hotel-booking' );
							}
						?>
					</label>
					<br />
					<select id="<?php echo esc_attr( 'mphb_adults-' . $uniqueSuffix ); ?>" name="mphb_adults">
						<?php foreach ( range( MPHB()->settings()->main()->getMinAdults(), MPHB()->settings()->main()->getSearchMaxAdults() ) as $value ) { ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( (string) esc_attr( $searchParameters['mphb_adults'] ), (string) $value ); ?>><?php echo esc_html( $value ); ?></option>
						<?php } ?>
					</select>
				</p>
			<?php } ?>
			<?php if ( MPHB()->settings()->main()->isChildrenDisabledOrHidden() ) { ?>
				<input type="hidden" id="<?php echo esc_attr( 'mphb_children-' . $uniqueSuffix ); ?>" name="mphb_children" value="<?php echo esc_attr( MPHB()->settings()->main()->getMinChildren() ); ?>" />
			<?php } else { ?>
				<p class="mphb-children-wrapper mphb-capacity-wrapper">
					<label for="<?php echo esc_attr( 'mphb_children-' . $uniqueSuffix ); ?>">
						<?php printf( __( 'Children %s', 'motopress-hotel-booking' ), MPHB()->settings()->main()->getChildrenAgeText() ); ?>
					</label>
					<br />
					<select id="<?php echo esc_attr( 'mphb_children-' . $uniqueSuffix ); ?>" name="mphb_children">
						<?php foreach ( range( 0, MPHB()->settings()->main()->getSearchMaxChildren() ) as $value ) { ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( esc_attr( (string) $searchParameters['mphb_children'] ), (string) $value ); ?>><?php echo esc_html( $value ); ?></option>
						<?php } ?>
					</select>

				</p>
			<?php } ?>
		<?php } ?>
		<p class="mphb-reserve-btn-wrapper">
			<input class="mphb-reserve-btn button" disabled="disabled" type="submit" value="<?php _e( 'Check Availability', 'motopress-hotel-booking' ); ?>" />
			<span class="mphb-preloader mphb-hide"></span>
		</p>
		<div class="mphb-errors-wrapper mphb-hide"></div>
		<?php if ( $isDirectBooking ) { ?>
			<div class="mphb-reserve-room-section mphb-hide">
				<p class="mphb-rooms-quantity-wrapper mphb-rooms-quantity-multiple mphb-hide"><?php
					$select = '<select class="mphb-rooms-quantity" id="' . esc_attr( 'mphb-rooms-quantity-' . $typeId ) . '" name="' . esc_attr( 'mphb_rooms_details[' . $typeId . ']' ) . '">';
					$select .= '<option value="1" selected="selected">1</option>';
					$select .= '</select>';

					printf( __( 'Reserve %1$s of %2$s available accommodations.', 'motopress-hotel-booking' ), $select, '<span class="mphb-available-rooms-count">1</span>' );
				?></p>
				<p class="mphb-rooms-quantity-wrapper mphb-rooms-quantity-single mphb-hide">
					<?php printf( __( '%s is available for selected dates.', 'motopress-hotel-booking' ), esc_html( $roomType->getTitle() ) ); ?>
				</p>
                <?php if ( $directBookingPricing != 'disabled' ) { ?>
                    <p class="mphb-period-price mphb-regular-price mphb-hide">
                        <strong><?php _e( 'Prices start at:', 'motopress-hotel-booking' ); ?></strong>
                    </p>
                <?php } ?>
				<input type="hidden" name="mphb_is_direct_booking" value="1" />
				<input class="button mphb-button mphb-confirm-reservation" type="submit" value="<?php _e( 'Confirm Reservation', 'motopress-hotel-booking' ); ?>" />
			</div>
		<?php } ?>
	</form>
	<?php
}

/**
 * Retrieve in-loop service thumbnail
 *
 * @param string $size
 */
function mphb_tmpl_the_loop_service_thumbnail( $size = null ){
	if ( is_null( $size ) ) {
		$size = apply_filters( 'mphb_loop_service_thumbnail_size', 'post-thumbnail' );
	}
	the_post_thumbnail( $size );
}

function mphb_tmpl_the_service_price(){
	$service = MPHB()->getServiceRepository()->findById( get_the_ID() );
	echo $service ? $service->getPriceWithConditions() : '';
}

/**
 * Retrieve the classes for the post div as an array.
 *
 * @param string|array $class   One or more classes to add to the class list.
 * @param int|WP_Post  $post_id Optional. Post ID or post object.
 * @return array Array of classes.
 */
function mphb_tmpl_get_filtered_post_class( $class = '', $postId = null ){
	$classes = get_post_class( $class, $postId );
	if ( false !== ( $key = array_search( 'hentry', $classes ) ) ) {
		unset( $classes[$key] );
	}
	return $classes;
}

/**
 * @param \MPHB\Entities\ReservedRoom[] $reservedRooms
 */
function mphb_tmpl_the_reserved_rooms_details($reservedRooms)
{
    foreach ($reservedRooms as $reservedRoom) {
        $room             = MPHB()->getRoomRepository()->findById($reservedRoom->getRoomId());
        $rate             = MPHB()->getRateRepository()->findById($reservedRoom->getRateId());
        $reservedServices = $reservedRoom->getReservedServices();
        $guestName        = $reservedRoom->getGuestName();
        $placeholder      = ' &#8212;';

        _e('Accommodation:', 'motopress-hotel-booking');
        if ($room) {
            echo ' <a href="' . esc_url(get_edit_post_link($room->getId())) . '">' . esc_html($room->getTitle()) . '</a>';
        } else {
            echo $placeholder;
        }

        echo '<br />';

        _e('Rate:', 'motopress-hotel-booking');
        if ($rate) {
            echo ' <a href="' . esc_url(get_edit_post_link($rate->getOriginalId())) . '">' . esc_html($rate->getTitle()) . '</a>';
        } else {
            echo $placeholder;
        }

        echo '<br />';

        _e('Adults:', 'motopress-hotel-booking');
        echo ' ' . esc_html($reservedRoom->getAdults());

        echo '<br />';

        _e('Children:', 'motopress-hotel-booking');
        echo ' ' . esc_html($reservedRoom->getChildren());

        echo '<br />';

        _e('Services:', 'motopress-hotel-booking');
        if (!empty($reservedServices)) {
            echo '<ol>';
            foreach ($reservedServices as $reservedService) {
                echo '<li>';
                echo '<a href="' . esc_url(get_edit_post_link($reservedService->getOriginalId())) . '">' . esc_html($reservedService->getTitle()) . '</a>';
                if ($reservedService->isPayPerAdult()) {
                    echo ' <em>' . sprintf(_n('x %d guest', 'x %d guests', $reservedService->getAdults(), 'motopress-hotel-booking'), $reservedService->getAdults()) . '</em>';
                }
                if ($reservedService->isFlexiblePay()) {
                    echo ' <em>' . sprintf(_n('x %d time', 'x %d times', $reservedService->getQuantity(), 'motopress-hotel-booking'), $reservedService->getQuantity()) . '</em>';
                }
                echo '</li>';
            }
            echo '</ol>';
        } else {
            echo $placeholder;
        }

        if (!empty($guestName)) {
            echo '<br />';

            _e('Guest:', 'motopress-hotel-booking');
            echo ' ' . esc_html($guestName);
        }

        echo '<hr />';
    }
}

/**
 * @param \MPHB\Entities\Booking $booking
 */
function mphb_tmpl_the_payments_table($booking)
{
    /** @var \MPHB\Entities\Payment[] */
    $payments = MPHB()->getPaymentRepository()->findAll(array('booking_id' => $booking->getId()));

    $totalPaid = 0.0;

    ?>
    <table class="mphb-payments-table">
        <thead>
            <tr>
                <th><?php _e('Payment ID', 'motopress-hotel-booking'); ?></th>
                <th><?php _e('Status', 'motopress-hotel-booking'); ?></th>
                <th><?php _e('Amount', 'motopress-hotel-booking'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($payments)) { ?>
                <tr>
                    <td>&#8212;</td>
                    <td>&#8212;</td>
                    <td>&#8212;</td>
                </tr>
            <?php } else { ?>
                <?php
                foreach ($payments as $payment) {
                    if ($payment->getStatus() == PaymentStatuses::STATUS_COMPLETED) {
                        $totalPaid += $payment->getAmount();
                    }

                    printf('<tr class="%s">', esc_attr('mphb-payment mphb-payment-status-' . $payment->getStatus()));
                    echo '<td>', sprintf( '<a href="%1$s">#%2$s</a>', esc_url(get_edit_post_link($payment->getId())), $payment->getId() ), '</td>';
                    echo '<td>', mphb_get_status_label($payment->getStatus()), '</td>';
                    echo '<td>', mphb_format_price($payment->getAmount()), '</td>';
                    echo '</tr>';
                }
                ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="mphb-total-label" colspan="2"><?php _e('Total Paid', 'motopress-hotel-booking'); ?></th>
                <th><?php echo mphb_format_price($totalPaid); ?></th>
            </tr>
            <tr>
                <th class="mphb-to-pay-label" colspan="2"><?php _e('To Pay', 'motopress-hotel-booking'); ?></th>
                <th>
                    <?php
                    $needToPay = $booking->getTotalPrice() - $totalPaid;
                    echo mphb_format_price($needToPay);
                    ?>
                </th>
            </tr>
        </tfoot>
    </table>
    <?php

    $createManualPaymentUrl = MPHB()->postTypes()->payment()->getEditPage()->getUrl(
        array(
            'mphb_defaults' => array(
                '_mphb_booking_id'      => $booking->getId(),
                '_mphb_gateway'         => 'manual',
                '_mphb_gateway_mode'    => 'live',
                '_mphb_amount'          => $needToPay
            )
        ),
        true
    );

    printf('<a href="%1$s" class="button button-primary">%2$s</a>', esc_url($createManualPaymentUrl), __('Add Payment Manually', 'motopress-hotel-booking'));
}

/**
 * @since 3.5.0
 */
function mphb_tmpl_select_html($args, $options, $selected)
{
    $args = array_map(function ($attribute, $value) {
        return $attribute . '="' . esc_attr($value) . '"';
    }, array_keys($args), $args);

    echo '<select ' . implode(' ', $args) . '>';

    foreach ($options as $value => $label) {
        echo '<option value="' . esc_attr($value) . '"' . selected($selected, $value, false) . '>';
        echo esc_html($label);
        echo '</option>';
    }

    echo '</select>';
}

/**
 * @since 3.5.0
 */
function mphb_tmpl_multicheck_html($name, $options, $selected)
{
    if (substr($name, -2) != '[]') {
        $name .= '[]';
    }

    foreach ($options as $value => $label) {
        $isChecked = in_array($value, $selected);

        echo '<label>';
        echo '<input name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" type="checkbox"' . checked(true, $isChecked, false) . ' />';
        echo ' ' . esc_html($label);
        echo '</label>';

        echo '<br />';
    }

    echo '<button class="button-link mphb-checkbox-select-all">' . __('Select all', 'motopress-hotel-booking') . '</button>';
    echo ' - ';
    echo '<button class="button-link mphb-checkbox-unselect-all">' . __('Unselect all', 'motopress-hotel-booking') . '</button>';
}

/**
 * @param array $options
 * @param mixed $selected
 * @param array $atts Optional.
 * @return string
 *
 * @since 3.7.2
 */
function mphb_tmpl_render_select($options, $selected, $atts = array())
{
    $output = '<select' . mphb_tmpl_render_atts($atts) . '>';
        $output .= mphb_tmpl_render_select_options($options, $selected);
    $output .= '</select>';

    return $output;
}

/**
 * @param array $options
 * @param mixed $selected
 * @return string
 *
 * @since 3.7.2
 */
function mphb_tmpl_render_select_options($options, $selected)
{
    $output = '';

    foreach ($options as $value => $label) {
        $output .= '<option value="' . esc_attr($value) . '"' . selected($selected, $value, false) . '>';
            $output .= esc_html($label);
        $output .= '</option>';
    }

    return $output;
}

/**
 * @param array $atts
 * @return string
 *
 * @since 3.7.2
 */
function mphb_tmpl_render_atts($atts)
{
    $output = '';

    foreach ($atts as $name => $value) {
        $output .= ' ' . $name . '="' . esc_attr($value) . '"';
    }

    return $output;
}
