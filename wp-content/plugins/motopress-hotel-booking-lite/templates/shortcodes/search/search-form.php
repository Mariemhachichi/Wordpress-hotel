<?php
/**
 * Available variables
 * - string $uniqid
 * - string $action Action for search form
 * - string $checkInDate
 * - string $checkOutDate
 * - int $adults
 * - int $children
 * - array $adultsList
 * - array $childrenList
 * - array $attributes [%Attribute name% => [%Term ID% => %Term title%]]
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="GET" class="mphb_sc_search-form" action="<?php echo esc_attr( $action ); ?>">

	<?php
	/**
	 * @hooked \MPHB\Shortcodes\SearchShortcode::renderHiddenInputs - 10
	 */
	do_action( 'mphb_sc_search_render_form_top' );
	?>

	<p class="mphb_sc_search-check-in-date">
		<label for="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqid ); ?>">
			<?php _e( 'Check-in', 'motopress-hotel-booking' ); ?>
			<abbr title="<?php printf( _x( 'Formatted as %s', 'Date format tip', 'motopress-hotel-booking' ), MPHB()->settings()->dateTime()->getDateFormatJS() ); ?>">*</abbr>
		</label>
		<br />
		<input
			id="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqid ); ?>"
			data-datepick-group="<?php echo esc_attr( $uniqid ); ?>"
			value="<?php echo esc_attr( $checkInDate ); ?>"
			placeholder="<?php _e( 'Check-in Date', 'motopress-hotel-booking' ); ?>"
			required="required"
			type="text"
			name="mphb_check_in_date"
			class="mphb-datepick"
			autocomplete="off"
			/>
	</p>

	<p class="mphb_sc_search-check-out-date">
		<label for="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqid ); ?>">
			<?php _e( 'Check-out', 'motopress-hotel-booking' ); ?>
			<abbr title="<?php printf( _x( 'Formatted as %s', 'Date format tip', 'motopress-hotel-booking' ), MPHB()->settings()->dateTime()->getDateFormatJS() ); ?>">*</abbr>
		</label>
		<br />
		<input
			id="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqid ); ?>"
			data-datepick-group="<?php echo esc_attr( $uniqid ); ?>"
			value="<?php echo esc_attr( $checkOutDate ); ?>"
			placeholder="<?php esc_attr_e( 'Check-out Date', 'motopress-hotel-booking' ); ?>"
			required="required"
			type="text"
			name="mphb_check_out_date"
			class="mphb-datepick"
			autocomplete="off"
			/>
	</p>

	<?php if ( MPHB()->settings()->main()->isAdultsDisabledOrHidden() ) { ?>
		<input type="hidden" id="<?php echo esc_attr( 'mphb_adults-' . $uniqid ); ?>" name="mphb_adults" value="<?php echo esc_attr( MPHB()->settings()->main()->getMinAdults() ); ?>" />
	<?php } else { ?>
		<p class="mphb_sc_search-adults">
			<label for="<?php echo esc_attr( 'mphb_adults-' . $uniqid ); ?>">
				<?php
					if ( MPHB()->settings()->main()->isChildrenAllowed() ) {
						_e( 'Adults', 'motopress-hotel-booking' );
					} else {
						_e( 'Guests', 'motopress-hotel-booking' );
					}
				?>
			</label>
			<br />
			<select id="<?php echo esc_attr( 'mphb_adults-' . $uniqid ); ?>" name="mphb_adults" >
				<?php foreach ( $adultsList as $value ) { ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $adults, $value ); ?>><?php echo esc_html( $value ); ?></option>
				<?php } ?>
			</select>
		</p>
	<?php } ?>

	<?php if ( MPHB()->settings()->main()->isChildrenDisabledOrHidden() ) { ?>
		<input type="hidden" id="<?php echo esc_attr( 'mphb_children-' . $uniqid ); ?>" name="mphb_children" value="<?php echo esc_attr( MPHB()->settings()->main()->getMinChildren() ); ?>" />
	<?php } else { ?>
		<p class="mphb_sc_search-children">
			<label for="<?php echo esc_attr( 'mphb_children-' . $uniqid ); ?>">
				<?php
					$childrenAge = MPHB()->settings()->main()->getChildrenAgeText();
					if ( empty( $childrenAge ) ) {
						_e( 'Children', 'motopress-hotel-booking' );
					} else {
						printf( __( 'Children %s', 'motopress-hotel-booking' ), $childrenAge );
					}
				?>
			</label>
			<br />
			<select id="<?php echo esc_attr( 'mphb_children-' . $uniqid ); ?>" name="mphb_children">
				<?php foreach ( $childrenList as $value ) { ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $children, $value ); ?>><?php echo esc_html( $value ); ?></option>
				<?php } ?>
			</select>
		</p>
	<?php } ?>

	<?php do_action( 'mphb_sc_search_form_before_attributes' ); ?>

	<?php foreach ( $attributes as $attributeName => $terms ) { ?>
		<p class="<?php echo esc_attr( 'mphb_sc_search-' . $attributeName ); ?>">
			<label for="<?php echo esc_attr( 'mphb_' . $attributeName . '-' . $uniqid ); ?>">
				<?php echo esc_html( mphb_attribute_title( $attributeName ) ); ?>
			</label>
			<br />
			<select id="<?php echo esc_attr( 'mphb_' . $attributeName . '-' . $uniqid ); ?>" name="<?php echo esc_attr( 'mphb_attributes[' . $attributeName . ']' ); ?>">
				<option value=""><?php echo mphb_attribute_default_text( $attributeName ); ?></option>
				<?php foreach ( $terms as $termId => $termLabel ) { ?>
					<option value="<?php echo esc_attr( $termId ); ?>"><?php echo esc_html( $termLabel ); ?></option>
				<?php } ?>
			</select>
		</p>
	<?php } ?>

	<?php do_action( 'mphb_sc_search_form_before_submit_btn' ); ?>

	<p class="mphb_sc_search-submit-button-wrapper">
		<input type="submit" class="button" value="<?php _e( 'Search', 'motopress-hotel-booking' ); ?>"/>
	</p>

	<?php do_action( 'mphb_sc_search_form_bottom' ); ?>

</form>