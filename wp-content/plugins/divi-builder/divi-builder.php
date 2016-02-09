<?php
/*
 * Plugin Name: Divi Builder
 * Plugin URI: http://elegantthemes.com
 * Description: A drag and drop page builder for any WordPress theme.
 * Version: 1.1.1
 * Author: Elegant Themes
 * Author URI: http://elegantthemes.com
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'ET_BUILDER_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'ET_BUILDER_PLUGIN_URI', plugins_url('', __FILE__) );

if ( ! class_exists( 'ET_Dashboard_v2' ) ) {
	require_once( ET_BUILDER_PLUGIN_DIR . 'dashboard/dashboard.php' );
}

class ET_Builder_Plugin extends ET_Dashboard_v2 {
	var $plugin_version = '1.1.1';
	var $_options_pagename = 'et_builder_options';
	var $menu_page;
	private static $_this;

	function __construct() {
		// Don't allow more than one instance of the class
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.', 'et_builder' ),
				get_class( $this ) )
			);
		}

		if ( ( defined( 'ET_BUILDER_THEME' ) && ET_BUILDER_THEME ) || function_exists( 'et_divi_fonts_url' ) ) {
			return; // Disable the plugin, if the theme comes with the Builder
		}

		self::$_this = $this;

		$this->protocol = is_ssl() ? 'https' : 'http';

		$this->et_plugin_setup_builder();

		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

		add_action( 'admin_init', array( $this, 'construct_dashboard' ) );

		add_action( 'wp_ajax_et_builder_save_settings', array( $this, 'builder_save_settings' ) );

		add_action( 'wp_ajax_et_builder_authorize_aweber', array( $this, 'authorize_aweber' ) );

		add_action( 'wp_ajax_et_builder_refresh_lists', array( $this, 'refresh_lists' ) );

		add_filter( 'et_pb_builder_authorization_verdict', array( $this, 'is_aweber_authorized' ) );

		add_filter( 'body_class', array( $this, 'add_body_class' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'et_pb_hide_options_menu' ) );

		add_filter( 'the_content', array( $this, 'add_builder_content_wrapper' ) );

		add_filter( 'et_builder_inner_content_class', array( $this, 'add_builder_inner_content_class' ) );
	}

	function add_builder_content_wrapper( $content ) {
		if ( ! et_pb_is_pagebuilder_used( get_the_ID() ) && ! is_et_pb_preview() ) {
			return $content;
		}

		// Divi builder layout should only be used in singular template
		if ( ! is_singular() ) {
			// get_the_excerpt() for excerpt retrieval causes infinite loop; thus we're using excerpt from global $post variable
			global $post;

			$read_more_title = sprintf(
				__( 'Read more on %1%s', 'et_builder' ),
				get_the_title()
			);

			$read_more = sprintf(
				' <a href="%1$s" title="%2$s" class="more-link">%3$s</a>',
				esc_url( get_permalink() ),
				esc_attr( __( $read_more_title ) ),
				esc_html( __( 'read more', 'et_builder' ) )
			);

			// Use post excerpt if there's any. If there is no excerpt defined,
			// Generate from post content by stripping all shortcode first
			if ( ! empty( $post->post_excerpt ) ) {
				return wpautop( $post->post_excerpt . $read_more );
			} else {
				$shortcodeless_content = preg_replace( '/\[[^\]]+\]/', '', $content );
				return wpautop( et_wp_trim_words( $shortcodeless_content, 270, $read_more ) );
			}
		}

		$outer_class   = apply_filters( 'et_builder_outer_content_class', array( 'et_builder_outer_content' ) );
		$outer_classes = implode( ' ', $outer_class );

		$outer_id      = apply_filters( "et_builder_outer_content_id", "et_builder_outer_content" );

		$inner_class   = apply_filters( 'et_builder_inner_content_class', array( 'et_builder_inner_content' ) );
		$inner_classes = implode( ' ', $inner_class );

		$content = sprintf(
			'<div class="%2$s" id="%4$s">
				<div class="%3$s">
					%1$s
				</div>
			</div>',
			$content,
			esc_attr( $outer_classes ),
			esc_attr( $inner_classes ),
			esc_attr( $outer_id )
		);

		return $content;
	}

	function add_body_class( $classes ) {
		$classes[] = 'et_divi_builder';

		return $classes;
	}

	function add_builder_inner_content_class( $classes ) {
		$classes[] = 'et_pb_gutters3';

		return $classes;
	}

	function construct_dashboard() {
		$dashboard_args = array(
			'et_dashboard_options_pagename'  => $this->_options_pagename,
			'et_dashboard_plugin_name'       => 'pb_builder',
			'et_dashboard_save_button_text'  => __( 'Save', 'et_builder' ),
			'et_dashboard_options_path'      => ET_BUILDER_PLUGIN_DIR . '/dashboard/includes/options.php',
			'et_dashboard_options_page'      => 'toplevel_page',
			'et_dashboard_options_pagename'  => 'et_divi_options',
			'et_dashboard_plugin_class_name' => 'et_builder',
		);

		parent::__construct( $dashboard_args );
	}

	function builder_save_settings() {
		self::dashboard_save_settings();
	}

	/**
	 * Retrieves the Builder options from DB
	 * @return array
	 */
	function get_builder_options() {
		return get_option( 'et_pb_builder_options' ) ? get_option( 'et_pb_builder_options' ) : array();
	}

	function options_page() {
		// display wp error screen if plugin options disabled for current user
		if ( ! et_pb_is_allowed( 'theme_options' ) ) {
			wp_die( __( "You don't have sufficient permissions to access this page", 'et_builder_plugin' ) );
		}

		printf(
			'<div class="et_pb_save_settings_button_wrapper">
				<a href="#" id="et_pb_save_plugin" class="button button-primary button-large">%1$s</a>
				<h3 class="et_pb_settings_title">
					%2$s
				</h3>
			</div>',
			esc_html__( 'Save Settings', 'et_builder_plugin' ),
			esc_html__( 'Divi Builder Options', 'et_builder_plugin' )
		);

		self::generate_options_page();
	}

	function et_plugin_setup_builder() {
		define( 'ET_BUILDER_PLUGIN_ACTIVE', true );

		define( 'ET_BUILDER_VERSION', '1.1.1' );

		define( 'ET_BUILDER_DIR', ET_BUILDER_PLUGIN_DIR . 'framework/' );
		define( 'ET_BUILDER_URI', trailingslashit( plugins_url( '', __FILE__ ) ) . 'framework' );
		define( 'ET_BUILDER_LAYOUT_POST_TYPE', 'et_pb_layout' );

		load_theme_textdomain( 'et_builder', ET_BUILDER_DIR . 'languages' );

		load_plugin_textdomain( 'et_builder_plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		require ET_BUILDER_PLUGIN_DIR . 'functions.php';
		require ET_BUILDER_PLUGIN_DIR . 'theme-compat.php';
		require ET_BUILDER_DIR . 'framework.php';

		et_pb_register_posttypes();

		add_action( 'admin_menu', array( $this, 'add_divi_menu' ));
	}

	function add_divi_menu() {
		add_menu_page( 'Divi', 'Divi', 'switch_themes', 'et_divi_options', array( $this, 'options_page' ) );

		// Add Theme Options menu only if it's enabled for current user
		if ( et_pb_is_allowed( 'theme_options' ) ) {
			add_submenu_page( 'et_divi_options', __( 'Plugin Options', 'et_builder_plugin' ), __( 'Plugin Options', 'et_builder_plugin' ), 'manage_options', 'et_divi_options', array( $this, 'options_page' ) );
		}
		// Add Divi Library menu only if it's enabled for current user
		if ( et_pb_is_allowed( 'divi_library' ) ) {
			add_submenu_page( 'et_divi_options', __( 'Divi Library', 'et_builder' ), __( 'Divi Library', 'et_builder' ), 'manage_options', 'edit.php?post_type=et_pb_layout' );
		}
		add_submenu_page( 'et_divi_options', __( 'Divi Role Editor', 'et_builder' ), __( 'Divi Role Editor', 'et_builder' ), 'manage_options', 'et_divi_role_editor', 'et_pb_display_role_editor' );
	}

	/**
	 *
	 * Adds js script which removes the top menu item from Divi menu if it's disabled
	 *
	 */
	function et_pb_hide_options_menu() {
		// do nothing if plugin options should be displayed in the menu
		if ( et_pb_is_allowed( 'theme_options' ) ) {
			return;
		}

		wp_enqueue_script( 'et-builder-custom-admin-menu', ET_BUILDER_PLUGIN_URI . '/js/menu_fix.js', array( 'jquery' ), $this->plugin_version, true );
	}

	function register_scripts( $hook ) {
		if ( "toplevel_page_et_divi_options" !== $hook ) {
			return;
		}

		wp_enqueue_style( 'et-builder-css', ET_BUILDER_PLUGIN_URI . '/css/admin.css', array(), $this->plugin_version );
		wp_enqueue_script( 'et-builder-js', ET_BUILDER_PLUGIN_URI . '/js/admin.js', array( 'jquery' ), $this->plugin_version, true );
		wp_localize_script( 'et-builder-js', 'builder_settings', array(
			'et_builder_nonce'           => wp_create_nonce( 'et_builder_nonce' ),
			'ajaxurl'                    => admin_url( 'admin-ajax.php', $this->protocol ),
			'authorize_text'             => __( 'Authorize', 'et_builder_plugin' ),
			'reauthorize_text'           => __( 'Re-Authorize', 'et_builder_plugin' ),
			'authorization_successflull' => __( 'AWeber successfully authorized', 'et_builder_plugin' ),
			'save_settings'              => wp_create_nonce( 'save_settings' ),
		) );
	}

	function authorize_aweber() {
		wp_verify_nonce( $_POST['et_builder_nonce'] , 'et_builder_nonce' );

		$api_key = ! empty( $_POST['et_builder_api_key'] ) ? sanitize_text_field( $_POST['et_builder_api_key'] ) : '';

		$error_message = '' !== $api_key ? $this->aweber_authorization( $api_key ) : __( 'please paste valid authorization code', 'et_builder_plugin' );

		$result = 'success' == $error_message ?
			$error_message
			: __( 'Authorization failed: ', 'et_builder_plugin' ) . $error_message;

		die( $result );
	}

	function refresh_lists() {
		wp_verify_nonce( $_POST['et_builder_nonce'] , 'et_builder_nonce' );
		$service = ! empty( $_POST['et_builder_mail_service'] ) ? sanitize_text_field( $_POST['et_builder_mail_service'] ) : '';
		self::process_and_update_options( $_POST['et_builder_form_options'] );

		switch ( $service ) {
			case 'mailchimp':
				$result = et_pb_get_mailchimp_lists( 'on' );
				break;
			case 'aweber':
				$result = et_pb_get_aweber_lists( 'on' );
				break;
		}

		if ( false === $result ) {
			$result = sprintf( __( 'Error: Please make sure %1$s', 'et_builder_plugin' ), 'mailchimp' === $service ? __( 'MailChimp API key is correct', 'et_builder_plugin' ) : __( 'AWeber is authorized', 'et_builder_plugin' ) );
		} else {
			$result = __( 'Lists have been successfully regenerated', 'et_builder_plugin' );
		}

		die( $result );
	}

	/**
	 * Retrieves the tokens from AWeber
	 * @return string
	 */
	function aweber_authorization( $api_key ) {

		if ( ! class_exists( 'AWeberAPI' ) ) {
			require_once( ET_BUILDER_DIR . 'subscription/aweber/aweber_api.php' );
		}

		try {
			$auth = AWeberAPI::getDataFromAweberID( $api_key );

			if ( ! ( is_array( $auth ) && 4 === count( $auth ) ) ) {
				$error_message = __( 'Authorization code is invalid. Try regenerating it and paste in the new code.', 'et_builder_plugin' );
			} else {
				$error_message = 'success';
				list( $consumer_key, $consumer_secret, $access_key, $access_secret ) = $auth;

				self::update_option( array(
					'newsletter_main_aweber_key' => $api_key,
					'aweber_consumer_key'        => $consumer_key,
					'aweber_consumer_secret'     => $consumer_secret,
					'aweber_access_key'          => $access_key,
					'aweber_access_secret'       => $access_secret,
				) );
			}
		} catch ( AWeberAPIException $exc ) {
			$error_message = sprintf(
				'<p>%4$s</p>
				<ul>
					<li>%5$s: %1$s</li>
					<li>%6$s: %2$s</li>
					<li>%7$s: %3$s</li>
				</ul>',
				esc_html( $exc->type ),
				esc_html( $exc->message ),
				esc_html( $exc->documentation_url ),
				esc_html__( 'AWeberAPIException.', 'et_builder_plugin' ),
				esc_html__( 'Type', 'et_builder_plugin' ),
				esc_html__( 'Message', 'et_builder_plugin' ),
				esc_html__( 'Documentation', 'et_builder_plugin' )
			);
		}

		return $error_message;
	}

	/**
	 * Checks whether Aweber is authorized or not.
	 * Used to determine whether to display "Authorize" or "Re-Authorize" text on butoton
	 */
	function is_aweber_authorized( $network ) {
		$builder_settings = $this->get_builder_options();

		// Consider aweber authorized if all 4 fields are not empty
		if ( ! empty( $builder_settings ) && ! empty( $builder_settings['aweber_consumer_key'] ) && ! empty( $builder_settings['aweber_consumer_secret'] ) && ! empty( $builder_settings['aweber_access_key'] ) && ! empty( $builder_settings['aweber_access_secret'] ) ) {
			return true;
		}
	}
}

function et_divi_builder_init_plugin() {
	new ET_Builder_Plugin();
}
add_action( 'init', 'et_divi_builder_init_plugin' );