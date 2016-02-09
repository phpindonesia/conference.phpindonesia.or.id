<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Array of all sections. All sections will be added into sidebar navigation except for the 'header' section.
$all_sections = array(
	'newsletter'  => array(
		'title'    => __( 'Newsletter Settings', 'et_builder_plugin' ),
		'contents' => array(
			'main'   => __( 'Main', 'bloom' ),
		),
	),
);

/**
 * Array of all options
 * General format for options:
 * '<option_name>' => array(
 *							'type' => ...,
 *							'name' => ...,
 *							'default' => ...,
 *							'validation_type' => ...,
 *							etc
 *						)
 * <option_name> - just an identifier to add the option into $assigned_options array
 * Array of parameters may contain diffrent attributes depending on option type.
 * 'type' is the required attribute for all options. All other attributes depends on the option type.
 * 'validation_type' and 'name' are required attribute for the option which should be saved into DataBase.
 *
 */

$dashboard_options_all = array(

	'mailchimp' => array(
		'section_start' => array(
			'type'     => 'section_start',
			'title'    => __( 'MailChimp', 'et_builder' ),
		),

		'option' => array(
			'type'                 => 'input_field',
			'subtype'              => 'text',
			'placeholder'          => '',
			'title'                => __( 'MailChimp API Key:', 'et_builder_plugin' ),
			'name'                 => 'mailchimp_key',
			'hide_contents'        => true,
			'validation_type'      => 'simple_text',
			'hide_contents'        => true,
			'hint_text'            => sprintf(
				'%1$s<a href="%3$s" target="_blank">%2$s</a>',
				__( 'Enter your MailChimp API key. You can create an api key ', 'et_builder_plugin' ),
				__( 'here', 'et_builder_plugin' ),
				esc_url( 'https://us3.admin.mailchimp.com/account/api/' )
			),
		),

		'regenerate_lists' => array(
			'type'            => 'button',
			'title'           => __( 'Regenerate MailChimp Lists', 'et_builder_plugin' ),
			'link'            => '#',
			'class'           => 'et_dashboard_get_lists et_pb_mailchimp',
			'authorize'       => false,
		),
	),

	'aweber' => array(
		'section_start' => array(
			'type'     => 'section_start',
			'title'    => __( 'Aweber', 'et_builder' ),
		),
		'aweber_key' => array(
			'type'                 => 'input_field',
			'subtype'              => 'text',
			'placeholder'          => '',
			'name'                 => 'aweber_key',
			'title'                => __( 'AWeber code:', 'et_builder_plugin' ),
			'default'              => '',
			'class'                => 'api_option api_option_key',
			'hide_contents'        => true,
			'hint_text'            => sprintf(
				'%3$s <a href="%2$s" target="_blank">%1$s</a> %4$s',
				__( 'here', 'et_builder' ),
				esc_url( 'https://auth.aweber.com/1.0/oauth/authorize_app/b17f3351' ),
				__( 'Generate authorization code', 'et_builder_plugin' ),
				__( ' then paste in the authorization code and click authorize button', 'et_builder_plugin' )
			),
			'validation_type'      => 'simple_text',
		),
		'aweber_button' => array(
			'type'      => 'button',
			'title'     => __( 'Authorize AWeber', 'et_builder_plugin' ),
			'link'      => '#',
			'class'     => 'et_dashboard_authorize',
			'action'    => 'aweber',
			'authorize' => true,
		),
		'regenerate_lists' => array(
			'type'            => 'button',
			'title'           => __( 'Regenerate AWeber Lists', 'et_builder_plugin' ),
			'link'            => '#',
			'class'           => 'et_dashboard_get_lists et_pb_aweber',
			'authorize'       => false,
		),
	),

	'newsletter_title' => array(
		'type'  => 'main_title',
		'title' => __( 'Newsletter Settings', 'et_builder_plugin' ),
	),

	'end_of_section' => array(
		'type' => 'section_end',
	),

	'end_of_sub_section' => array(
		'type'        => 'section_end',
		'sub_section' => 'true',
	),
);

/**
 * Array of options assigned to sections. Format of option key is following:
 * 	<section>_<sub_section>_options
 * where:
 *	<section> = $all_sections -> $key
 *	<sub_section> = $all_sections -> $value['contents'] -> $key
 *
 * Note: name of this array shouldn't be changed. $assigned_options variable is being used in ET_Dashboard class as options container.
 */
$assigned_options = array(
	'newsletter_main_options' => array(
		$dashboard_options_all[ 'newsletter_title' ],
		$dashboard_options_all[ 'mailchimp' ][ 'section_start' ],
			$dashboard_options_all[ 'mailchimp' ][ 'option' ],
			$dashboard_options_all[ 'mailchimp' ][ 'regenerate_lists' ],
			$dashboard_options_all[ 'end_of_section' ],
		$dashboard_options_all[ 'aweber' ][ 'section_start' ],
			$dashboard_options_all[ 'aweber' ][ 'aweber_key' ],
			$dashboard_options_all[ 'aweber' ][ 'aweber_button' ],
			$dashboard_options_all[ 'aweber' ][ 'regenerate_lists' ],
			$dashboard_options_all[ 'end_of_section' ],
	),
);