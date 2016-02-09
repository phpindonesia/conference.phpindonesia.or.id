<?php
if ( ! function_exists( 'et_divi_font_style_choices' ) ) :
/**
 * Returns font style options
 * @return array
 */
function et_divi_font_style_choices() {
	return apply_filters( 'et_divi_font_style_choices', array(
		'bold'       => __( 'Bold', 'Divi' ),
		'italic'     => __( 'Italic', 'Divi' ),
		'uppercase'  => __( 'Uppercase', 'Divi' ),
		'underline'  => __( 'Underline', 'Divi' ),
	) );
}
endif;

if ( ! function_exists( 'et_divi_color_scheme_choices' ) ) :
/**
 * Returns list of color scheme used by Divi
 * @return array
 */
function et_divi_color_scheme_choices() {
	return apply_filters( 'et_divi_color_scheme_choices', array(
		'none'   => __( 'Default', 'Divi' ),
		'green'  => __( 'Green', 'Divi' ),
		'orange' => __( 'Orange', 'Divi' ),
		'pink'   => __( 'Pink', 'Divi' ),
		'red'    => __( 'Red', 'Divi' ),
	) );
}
endif;

if ( ! function_exists( 'et_divi_header_style_choices' ) ) :
/**
 * Returns list of header styles used by Divi
 * @return array
 */
function et_divi_header_style_choices() {
	return apply_filters( 'et_divi_header_style_choices', array(
		'left'     => __( 'Default', 'Divi' ),
		'centered' => __( 'Centered', 'Divi' ),
		'split'	   => __( 'Centered Inline Logo', 'Divi' )
	) );
}
endif;

if ( ! function_exists( 'et_divi_dropdown_animation_choices' ) ) :
/**
 * Returns list of dropdown animation
 * @return array
 */
function et_divi_dropdown_animation_choices() {
	return apply_filters( 'et_divi_dropdown_animation_choices', array(
		'fade'     => __( 'Fade', 'Divi' ),
		'expand'   => __( 'Expand', 'Divi' ),
		'slide'	   => __( 'Slide', 'Divi' ),
		'flip'	   => __( 'Flip', 'Divi' )
	) );
}
endif;

if ( ! function_exists( 'et_divi_footer_column_choices' ) ) :
/**
 * Returns list of footer column choices
 * @return array
 */
function et_divi_footer_column_choices() {
	return apply_filters( 'et_divi_footer_column_choices', array(
		'4'			=> sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '4' ) ),
		'3' 		=> sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '3' ) ),
		'2' 		=> sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '2' ) ),
		'1'  		=> __( '1 Column', 'Divi' ),
		'_1_4__3_4' => sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '1/4 + 3/4' ) ),
		'_3_4__1_4' => sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '3/4 + 1/4' ) ),
		'_1_3__2_3' => sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '1/3 + 2/3' ) ),
		'_2_3__1_3' => sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '2/3 + 1/3' ) ),
		'_1_4__1_2' => sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '1/4 + 1/4 + 1/2' ) ),
		'_1_2__1_4' => sprintf( __( '%1$s Columns', 'Divi' ), esc_html( '1/2 + 1/4 + 1/4' ) ),
	) );
}
endif;

if ( ! function_exists( 'et_divi_yes_no_choices' ) ) :
/**
 * Returns yes no choices
 * @return array
 */
function et_divi_yes_no_choices() {
	return apply_filters( 'et_divi_yes_no_choices', array(
		'yes'  => __( 'Yes', 'Divi' ),
		'no'   => __( 'No', 'Divi' )
	) );
}
endif;

if ( ! function_exists( 'et_divi_left_right_choices' ) ) :
/**
 * Returns left or right choices
 * @return array
 */
function et_divi_left_right_choices() {
	return apply_filters( 'et_divi_left_right_choices', array(
		'right'  => __( 'Right', 'Divi' ),
		'left'   => __( 'Left', 'Divi' )
	) );
}
endif;

if ( ! function_exists( 'et_divi_image_animation_choices' ) ) :
/**
 * Returns image animation choices
 * @return array
 */
function et_divi_image_animation_choices() {
	return apply_filters( 'et_divi_image_animation_choices', array(
		'left' 		=> __( 'Left to Right', 'Divi' ),
		'right' 	=> __( 'Right to Left', 'Divi' ),
		'top' 		=> __( 'Top to Bottom', 'Divi' ),
		'bottom' 	=> __( 'Bottom to Top', 'Divi' ),
		'fade_in'	=> __( 'Fade In', 'Divi' ),
		'off' 		=> __( 'No Animation', 'Divi' ),
	) );
}
endif;

if ( ! function_exists( 'et_divi_divider_style_choices' ) ) :
/**
 * Returns divider style choices
 * @return array
 */
function et_divi_divider_style_choices() {
	return apply_filters( 'et_divi_divider_style_choices', array(
		'solid'		=> __( 'Solid', 'Divi' ),
		'dotted'	=> __( 'Dotted', 'Divi' ),
		'dashed'	=> __( 'Dashed', 'Divi' ),
		'double'	=> __( 'Double', 'Divi' ),
		'groove'	=> __( 'Groove', 'Divi' ),
		'ridge'		=> __( 'Ridge', 'Divi' ),
		'inset'		=> __( 'Inset', 'Divi' ),
		'outset'	=> __( 'Outset', 'Divi' ),
	) );
}
endif;

if ( ! function_exists( 'et_divi_divider_position_choices' ) ) :
/**
 * Returns divider position choices
 * @return array
 */
function et_divi_divider_position_choices() {
	return apply_filters( 'et_divi_divider_position_choices', array(
		'top'		=> __( 'Top', 'Divi' ),
		'center'	=> __( 'Vertically Centered', 'Divi' ),
		'bottom'	=> __( 'Bottom', 'Divi' ),
	) );
}
endif;