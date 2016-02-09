<?php

add_action( 'et_pb_before_page_builder', array( 'ET_Builder_Element', 'output_templates' ) );
add_action( 'init', array( 'ET_Builder_Element', 'set_media_queries' ), 11 );

class ET_Builder_Element {
	public $name;
	public $slug;
	public $type;
	public $child_slug;
	public $decode_entities;
	public $fields = array();
	public $fields_unprocessed = array();
	public $main_css_element;
	public $custom_css_options = array();
	public $child_title_var;
	public $child_title_fallback_var;
	public $shortcode_atts = array();
	public $shortcode_content;
	public $post_types = array();
	public $main_tabs = array();
	public $used_tabs = array();
	public $custom_css_tab;

	// number of times shortcode_callback function has been executed
	private $_shortcode_callback_num;

	// priority number, applied to some CSS rules
	private $_style_priority;

	private static $styles = array();
	private static $media_queries = array();
	private static $modules_order;
	private static $parent_modules = array();
	private static $child_modules = array();

	const DEFAULT_PRIORITY = 10;
	const HIDE_ON_MOBILE   = 'et-hide-mobile';

	function __construct() {
		$this->init();

		$this->process_whitelisted_fields();
		$this->set_fields();

		$this->_additional_fields_options = array();
		$this->_add_additional_fields();
		$this->_add_custom_css_fields();

		$this->_maybe_add_defaults();

		if ( ! isset( $this->main_css_element ) ) {
			$this->main_css_element = '%%order_class%%';
		}

		$this->_shortcode_callback_num = 0;

		$this->type = isset( $this->type ) ? $this->type : '';

		$this->decode_entities = isset( $this->decode_entities ) ? (bool) $this->decode_entities : false;

		$this->_style_priority = (int) self::DEFAULT_PRIORITY;
		if ( isset( $this->type ) && 'child' === $this->type ) {
			$this->_style_priority = $this->_style_priority + 1;
		}

		$this->main_tabs = $this->get_main_tabs();

		$this->custom_css_tab = isset( $this->custom_css_tab ) ? $this->custom_css_tab : true;

		$this->post_types = et_builder_get_builder_post_types();

		foreach ( $this->post_types as $post_type ) {
			if ( ! in_array( $post_type, $this->post_types ) ) {
				$this->register_post_type( $post_type );
			}

			if ( 'child' == $this->type ) {
				self::$child_modules[ $post_type ][ $this->slug ] = $this;
			} else {
				self::$parent_modules[ $post_type ][ $this->slug ] = $this;
			}
		}

		if ( ! isset( $this->no_shortcode_callback ) ) {
			$shortcode_slugs = array( $this->slug );

			if ( ! empty( $this->additional_shortcode_slugs ) ) {
				$shortcode_slugs = array_merge( $shortcode_slugs, $this->additional_shortcode_slugs );
			}

			foreach ( $shortcode_slugs as $shortcode_slug ) {
				add_shortcode( $shortcode_slug, array( $this, '_shortcode_callback' ) );
			}

			if ( isset( $this->additional_shortcode ) ) {
				add_shortcode( $this->additional_shortcode, array( $this, 'additional_shortcode_callback' ) );
			}
		}
	}

	function process_whitelisted_fields() {
		$fields = array();

		foreach ( $this->whitelisted_fields as $key ) {
			$fields[ $key ] = array();
		}

		$this->whitelisted_fields = $fields;
	}

	/**
	 * Set $this->fields_unprocessed property to all field settings on backend.
	 * Store only default settings for use in shortcode_callback() on frontend.
	 */
	function set_fields() {
		$fields_defaults = array();

		$module_defaults = isset( $this->fields_defaults ) && is_array( $this->fields_defaults )
			? $this->fields_defaults
			: array();

		if ( ! empty( $module_defaults ) ) {
			foreach ( $module_defaults as $key => $default_setting ) {
				$setting_fields = array();

				$default_value = $module_defaults[ $key ][0];

				$use_default_value = isset( $module_defaults[ $key ][1] ) && 'add_default_setting' === $module_defaults[ $key ][1];
				$use_only_default_value = isset( $module_defaults[ $key ][1] ) && 'only_default_setting' === $module_defaults[ $key ][1];

				/**
				 * If default value is set, it should be used for "shortcode_default",
				 * unless 'only_default_setting' is set
				 */
				if ( ! $use_only_default_value ) {
					$setting_fields['shortcode_default'] = $default_value;
				}

				/**
				 * Add "default" setting and set it to the default value,
				 * if 'add_default_setting' or 'only_default_setting' is provided
				 */
				if ( $use_default_value || $use_only_default_value ) {
					$setting_fields['default'] = $default_value;
				}

				$fields_defaults[ $key ] = $setting_fields;
			}
		}

		/**
		 * Only use whitelisted fields names on frontend.
		 * All fields settings are only needed in Page Builder.
		 */
		$fields = ! is_admin() ? $this->whitelisted_fields : $this->get_fields();

		# update settings with defaults
		foreach ( $fields as $key => $settings ) {
			if ( ! isset( $fields_defaults[ $key ] ) ) {
				continue;
			}

			$settings = array_merge( $settings, $fields_defaults[ $key ] );

			$fields[ $key ] = $settings;
		}

		$this->fields_unprocessed = $fields;
	}

	private function register_post_type( $post_type ) {
		$this->post_types[] = $post_type;
		self::$parent_modules[ $post_type ] = array();
		self::$child_modules[ $post_type ] = array();
	}

	/**
	 * Double quote are saved as "%22" in shortcode attributes.
	 * Decode them back into "
	 *
	 * @return void
	 */
	private function _decode_double_quotes() {
		if ( ! isset( $this->shortcode_atts ) ) {
			return;
		}

		$shortcode_attributes = array();
		$font_icon_options = array( 'font_icon', 'button_icon', 'button_one_icon', 'button_two_icon' );

		foreach ( $this->shortcode_atts as $attribute_key => $attribute_value ) {
			$shortcode_attributes[ $attribute_key ] = in_array( $attribute_key, $font_icon_options ) ? $attribute_value : str_replace( '%22', '"', $attribute_value );
		}

		$this->shortcode_atts = $shortcode_attributes;
	}

	/**
	 * Provide a way for sub-class to access $this->_shortcode_callback_num without a chance to alter its value
	 *
	 * @return int
	 */
	protected function shortcode_callback_num() {
		return $this->_shortcode_callback_num;
	}

	function _shortcode_callback( $atts, $content = null, $function_name ) {
		$this->shortcode_atts = shortcode_atts( $this->get_shortcode_fields(), $atts );

		$this->_decode_double_quotes();

		$this->_maybe_remove_default_atts_values();

		$global_shortcode_content = false;

		// If the section/row/module is disabled, hide it
		if ( isset( $this->shortcode_atts['disabled'] ) && 'on' === $this->shortcode_atts['disabled'] ) {
			return;
		}

		//override module attributes for global module
		if ( ! empty( $this->shortcode_atts['global_module'] ) ) {
			$global_content = et_pb_load_global_module( $this->shortcode_atts['global_module'] );

			if ( '' !== $global_content ) {
				$global_atts = shortcode_parse_atts( $global_content );

				foreach( $this->shortcode_atts as $single_attr => $value ) {
					if ( isset( $global_atts[$single_attr] ) ) {
						$this->shortcode_atts[$single_attr] = $global_atts[$single_attr];
					}
				}

				if ( false !== strpos( $this->shortcode_atts['saved_tabs'], 'general' ) || 'all' === $this->shortcode_atts['saved_tabs'] ) {
					$global_shortcode_content = et_pb_extract_shortcode_content( $global_content, $function_name );
				}
			}
		}

		self::set_order_class( $function_name );

		$this->pre_shortcode_content();

		$content = false !== $global_shortcode_content ? $global_shortcode_content : $content;

		$this->shortcode_content = ! ( isset( $this->is_structure_element ) && $this->is_structure_element ) ? do_shortcode( et_pb_fix_shortcodes( $content, $this->decode_entities ) ) : '';

		$this->shortcode_atts();

		$output = $this->shortcode_callback( $atts, $content, $function_name );

		$this->_shortcode_callback_num++;

		$this->process_additional_options( $function_name );
		$this->process_custom_css_options( $function_name );

		if ( empty( $this->template_name ) ) {
			return $output;
		}

		return $this->shortcode_output();
	}

	/**
	 * Delete default shortcode attribute values, defined in ET_Global_Settings class
	 * @return void
	 */
	private function _maybe_remove_default_atts_values() {
		$fields = $this->fields_unprocessed;

		foreach ( $fields as $field_key => $field_settings ) {
			$global_setting_name  = $this->get_global_setting_name( $field_key );
			$global_setting_value = ET_Global_Settings::get_value( $global_setting_name );
			$shortcode_attr_value = ! empty( $this->shortcode_atts[ $field_key ] ) ? $this->shortcode_atts[ $field_key ] : '';

			// Don't do anything if there is no default or actual value for a setting
			// or shortcode attribute is no set
			if ( ! $global_setting_value || '' === $shortcode_attr_value ) {
				continue;
			}

			// Delete shortcode attribute value if it equals to the default global value
			if ( $global_setting_value === $shortcode_attr_value ) {
				$this->shortcode_atts[ $field_key ] = '';
			}
		}
	}

	function shortcode_output() {
		$this->shortcode_atts['content'] = $this->shortcode_content;
		extract( $this->shortcode_atts );
		ob_start();
		require( locate_template( $this->template_name . '.php' ) );
		return ob_get_clean();
	}

	function shortcode_atts_to_data_atts( $atts = array() ) {
		if ( empty( $atts ) ) {
			return;
		}

		$output = array();
		foreach ( $atts as $attr ) {
			$output[] = 'data-' . esc_attr( $attr ) . '="' . esc_attr( $this->shortcode_atts[ $attr ] ) . '"';
		}

		return implode( ' ', $output );
	}

	// intended to be overridden as needed
	function shortcode_atts(){}

	// intended to be overridden as needed
	function pre_shortcode_content(){}

	// intended to be overridden as needed
	function shortcode_callback( $atts, $content = null, $function_name ){}

	// intended to be overridden as needed
	function additional_shortcode_callback( $atts, $content = null, $function_name ){}

	/**
	 * Generate global setting name
	 * @param  string $option_slug  Option slug
	 * @return string               Global setting name in the following format: "module_slug-option_slug"
	 */
	public function get_global_setting_name( $option_slug ) {
		$global_setting_name = sprintf(
			'%1$s-%2$s',
			$this->slug,
			$option_slug
		);

		return $global_setting_name;
	}

	/**
	 * Add global default values to all fields, if they don't have defaults set
	 *
	 * @return void
	 */
	private function _maybe_add_defaults() {
		// Don't add default settings to "child" modules
		if ( 'child' === $this->type ) {
			return;
		}

		$fields       = $this->fields_unprocessed;
		$ignored_keys = array(
			'custom_margin',
			'custom_padding',
		);

		// Font color settings have custom_color set to true, so add them to ingored keys array
		if ( ! empty( $this->advanced_options['fonts'] ) && is_array( $this->advanced_options['fonts'] ) ) {
			foreach ( $this->advanced_options['fonts'] as $font_key => $font_settings ) {
				$ignored_keys[] = sprintf( '%1$s_text_color', $font_key );
			}
		}

		$ignored_keys = apply_filters( 'et_builder_add_defaults_ignored_keys', $ignored_keys );

		foreach ( $fields as $field_key => $field_settings ) {
			if ( in_array( $field_key, $ignored_keys ) ) {
				continue;
			}

			$global_setting_name  = $this->get_global_setting_name( $field_key );
			$global_setting_value = ET_Global_Settings::get_value( $global_setting_name );

			if ( ! isset( $field_settings['default'] ) && $global_setting_value ) {
				$fields[ $field_key ]['default'] = $fields[ $field_key ]['shortcode_default'] = $global_setting_value;
			}
		}

		$this->fields_unprocessed = $fields;
	}

	private function _add_additional_fields() {
		if ( ! isset( $this->advanced_options ) ) {
			return false;
		}

		$this->_add_additional_font_fields();

		$this->_add_additional_background_fields();

		$this->_add_additional_border_fields();

		$this->_add_additional_custom_margin_padding_fields();

		$this->_add_additional_button_fields();

		if ( ! isset( $this->_additional_fields_options ) ) {
			return false;
		}

		$additional_options = $this->_additional_fields_options;

		if ( ! empty( $additional_options ) ) {
			// delete second level advanced options default values
			if ( isset( $this->type ) && 'child' === $this->type && apply_filters( 'et_pb_remove_child_module_defaults', true ) ) {
				$default_keys = array( 'default', 'shortcode_default' );

				foreach ( $additional_options as $name => $settings ) {
					foreach ( $default_keys as $default_key ) {
						if ( isset( $additional_options[ $name ][ $default_key ] ) ) {
							$additional_options[ $name ][ $default_key ] = '';
						}
					}
				}
			}

			$this->fields_unprocessed = array_merge( $this->fields_unprocessed, $additional_options );
		}
	}

	private function _add_additional_font_fields() {
		if ( ! isset( $this->advanced_options['fonts'] ) ) {
			return;
		}

		$advanced_font_options = $this->advanced_options['fonts'];

		$additional_options = array();
		$defaults = array(
			'all_caps' => 'off',
		);

		foreach ( $advanced_font_options as $option_name => $option_settings ) {
			$advanced_font_options[ $option_name ]['defaults'] = $defaults;
		}

		$this->advanced_options['fonts'] = $advanced_font_options;

		foreach ( $advanced_font_options as $option_name => $option_settings ) {
			$option_settings = wp_parse_args( $option_settings, array(
				'label'          => '',
				'font_size'      => array(),
				'letter_spacing' => array(),
			) );

			$additional_options["{$option_name}_font"] = array(
				'label'           => sprintf( __( '%1$s Font', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'font',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
			);

			$additional_options["{$option_name}_font_size"] = wp_parse_args( $option_settings['font_size'], array(
				'label'           => sprintf( __( '%1$s Font Size', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			) );

			$additional_options["{$option_name}_text_color"] = array(
				'label'           => sprintf( __( '%1$s Text Color', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'color',
				'option_category' => 'font_option',
				'custom_color'    => true,
				'tab_slug'        => 'advanced',
			);

			$additional_options["{$option_name}_letter_spacing"] = wp_parse_args( $option_settings['letter_spacing'], array(
				'label'           => sprintf( __( '%1$s Letter Spacing', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
			) );

			if ( ! isset( $option_settings['hide_line_height'] ) || ! $option_settings['hide_line_height'] ) {
				$default_option_line_height = array(
					'label'           => sprintf( __( '%1$s Line Height', 'et_builder' ), $option_settings['label'] ),
					'type'            => 'range',
					'option_category' => 'font_option',
					'tab_slug'        => 'advanced',
					'range_settings'  => array(
						'min'  => '1',
						'max'  => '3',
						'step' => '0.1',
					),
				);

				if ( isset( $option_settings['line_height'] ) ) {
					$additional_options["{$option_name}_line_height"] = wp_parse_args(
					 	$option_settings['line_height'],
					 	$default_option_line_height
					);
				} else {
					$additional_options["{$option_name}_line_height"] = $default_option_line_height;
				}
			}

			if ( isset( $option_settings['use_all_caps'] ) && $option_settings['use_all_caps'] ) {
				$additional_options["{$option_name}_all_caps"] = array(
					'label'           => sprintf( __( '%1$s All Caps', 'et_builder' ), $option_settings['label'] ),
					'type'            => 'yes_no_button',
					'option_category' => 'font_option',
					'options'         => array(
						'off' => __( 'Off', 'et_builder' ),
						'on'  => __( 'On', 'et_builder' ),
					),
					'shortcode_default' => $option_settings['defaults']['all_caps'],
					'tab_slug' => 'advanced',
				);
			}
		}

		$this->_additional_fields_options = array_merge( $this->_additional_fields_options, $additional_options );
	}

	private function _add_additional_background_fields() {
		if ( ! isset( $this->advanced_options['background'] ) ) {
			return;
		}

		$additional_options = array();

		$color_type = isset( $this->advanced_options['background']['settings']['color'] ) && 'alpha' === $this->advanced_options['background']['settings']['color'] ? 'color-alpha' : 'color';
		$defaults = array(
			'use_background_color'  => true,
			'use_background_image' => true,
		);
		$this->advanced_options['background'] = wp_parse_args( $this->advanced_options['background'], $defaults );

		if ( $this->advanced_options['background']['use_background_color'] ) {
			$additional_options['background_color'] = array(
				'label'           => __( 'Background Color', 'et_builder' ),
				'type'            => $color_type,
				'option_category' => 'configuration',
				'custom_color'    => true,
				'tab_slug'        => 'advanced',
			);
		}

		if ( $this->advanced_options['background']['use_background_image'] ) {
			$additional_options['background_image'] = array(
				'label'              => __( 'Background Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'configuration',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Image', 'et_builder' ),
				'update_text'        => __( 'Set As Background', 'et_builder' ),
				'tab_slug'           => 'advanced',
			);
		}

		$this->_additional_fields_options = array_merge( $this->_additional_fields_options, $additional_options );
	}

	private function _add_additional_border_fields () {
		if ( ! isset( $this->advanced_options['border'] ) ) {
			return;
		}

		$additional_options = array();

		$color_type = isset( $this->advanced_options['border']['settings']['color'] ) && 'alpha' === $this->advanced_options['border']['settings']['color'] ? 'color-alpha' : 'color';

		$additional_options['use_border_color'] = array(
			'label'           => __( 'Use Border', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => array(
				'off' => __( 'No', 'et_builder' ),
				'on'  => __( 'Yes', 'et_builder' ),
			),
			'affects' => array(
				'#et_pb_border_color',
				'#et_pb_border_width',
				'#et_pb_border_style',
			),
			'shortcode_default' => 'off',
			'tab_slug'	       	=> 'advanced',
		);

		$additional_options['border_color'] = array(
			'label'             => __( 'Border Color', 'et_builder' ),
			'type'              => $color_type,
			'option_category'   => 'layout',
			'default'           => '#ffffff',
			'shortcode_default' => '#ffffff',
			'tab_slug'	       	=> 'advanced',
			'depends_default'   => true,
		);

		$additional_options['border_width'] = array(
			'label'             => __( 'Border Width', 'et_builder' ),
			'type'              => 'range',
			'option_category'   => 'layout',
			'default'           => '1px',
			'shortcode_default' => '1px',
			'tab_slug'          => 'advanced',
			'depends_default'   => true,
		);

		$additional_options['border_style'] = array(
			'label'             => __( 'Border Style', 'et_builder' ),
			'type'              => 'select',
			'option_category'   => 'layout',
			'options'           => et_builder_get_border_styles(),
			'shortcode_default' => 'solid',
			'tab_slug'          => 'advanced',
			'depends_default'   => true,
		);

		$this->_additional_fields_options = array_merge( $this->_additional_fields_options, $additional_options );
	}

	private function _add_additional_custom_margin_padding_fields() {
		if ( ! isset( $this->advanced_options['custom_margin_padding'] ) ) {
			return;
		}

		$additional_options = array();

		$defaults = array(
			'use_margin'  => true,
			'use_padding' => true,
		);
		$this->advanced_options['custom_margin_padding'] = wp_parse_args( $this->advanced_options['custom_margin_padding'], $defaults );

		if ( $this->advanced_options['custom_margin_padding']['use_margin'] ) {
			$additional_options['custom_margin'] = array(
				'label'           => __( 'Custom Margin', 'et_builder' ),
				'type'            => 'custom_margin',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
			);
		}

		if ( $this->advanced_options['custom_margin_padding']['use_padding'] ) {
			$additional_options['custom_padding'] = array(
				'label'           => __( 'Custom Padding', 'et_builder' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
			);
		}

		$this->_additional_fields_options = array_merge( $this->_additional_fields_options, $additional_options );
	}

	private function _add_additional_button_fields() {
		if ( ! isset( $this->advanced_options['button'] ) ) {
			return;
		}

		$additional_options = array();

		foreach ( $this->advanced_options['button'] as $option_name => $option_settings ) {
			$additional_options["custom_{$option_name}"] = array(
				'label'           => sprintf( __( 'Use Custom Styles for %1$s ', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'yes_no_button',
				'option_category' => 'button',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects' => array(
					"#et_pb_{$option_name}_text_color",
					"#et_pb_{$option_name}_text_size",
					"#et_pb_{$option_name}_border_width",
					"#et_pb_{$option_name}_border_radius",
					"#et_pb_{$option_name}_letter_spacing",
					"#et_pb_{$option_name}_spacing",
					"#et_pb_{$option_name}_bg_color",
					"#et_pb_{$option_name}_border_color",
					"#et_pb_{$option_name}_use_icon",
					"#et_pb_{$option_name}_font",
					"#et_pb_{$option_name}_text_color_hover",
					"#et_pb_{$option_name}_bg_color_hover",
					"#et_pb_{$option_name}_border_color_hover",
					"#et_pb_{$option_name}_border_radius_hover",
					"#et_pb_{$option_name}_letter_spacing_hover",
				),
				'shortcode_default' => 'off',
				'tab_slug'	       	=> 'advanced',
			);

			$additional_options["{$option_name}_text_size"] = array(
				'label'           => sprintf( __( '%1$s Text Size', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'range',
				'option_category' => 'button',
				'default'         => ET_Global_Settings::get_value( 'all_buttons_font_size' ),
				'tab_slug'        => 'advanced',
				'depends_default' => true,
			);

			$additional_options["{$option_name}_text_color"] = array(
				'label'             => sprintf( __( '%1$s Text Color', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'color-alpha',
				'option_category'   => 'button',
				'custom_color'      => true,
				'default'           => '',
				'shortcode_default' => '',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_bg_color"] = array(
				'label'             => sprintf( __( '%1$s Background Color', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'color-alpha',
				'option_category'   => 'button',
				'custom_color'      => true,
				'default'           => ET_Global_Settings::get_value( 'all_buttons_bg_color' ),
				'shortcode_default' => '',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_border_width"] = array(
				'label'             => sprintf( __( '%1$s Border Width', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'range',
				'option_category'   => 'button',
				'default'           => ET_Global_Settings::get_value( 'all_buttons_border_width' ),
				'shortcode_default' => '',
				'tab_slug'          => 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_border_color"] = array(
				'label'             => sprintf( __( '%1$s Border Color', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'color-alpha',
				'option_category'   => 'button',
				'custom_color'      => true,
				'default'           => '',
				'shortcode_default' => '',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_border_radius"] = array(
				'label'             => sprintf( __( '%1$s Border Radius', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'range',
				'option_category'   => 'button',
				'default'           => ET_Global_Settings::get_value( 'all_buttons_border_radius' ),
				'shortcode_default' => '',
				'tab_slug'          => 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_letter_spacing"] = array(
				'label'             => sprintf( __( '%1$s Letter Spacing', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'range',
				'option_category'   => 'button',
				'default'           => ET_Global_Settings::get_value( 'all_buttons_spacing' ),
				'shortcode_default' => '',
				'tab_slug'          => 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_font"] = array(
				'label'           => sprintf( __( '%1$s Font', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'font',
				'option_category' => 'button',
				'tab_slug'        => 'advanced',
				'depends_default' => true,
			);

			$additional_options["{$option_name}_use_icon"] = array(
				'label'           => sprintf( __( 'Add %1$s Icon', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'select',
				'option_category' => 'button',
				'options'         => array(
					'default' => __( 'Default', 'et_builder' ),
					'on'      => __( 'Yes', 'et_builder' ),
					'off'     => __( 'No', 'et_builder' ),
				),
				'affects' => array(
					"#et_pb_{$option_name}_icon_color",
					"#et_pb_{$option_name}_icon_placement",
					"#et_pb_{$option_name}_on_hover",
					"#et_pb_{$option_name}_icon",
				),
				'shortcode_default' => 'on',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_icon"] = array(
				'label'               => sprintf( __( '%1$s Icon', 'et_builder' ), $option_settings['label'] ),
				'type'                => 'text',
				'option_category'     => 'button',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'default'             => '',
				'tab_slug'            => 'advanced',
				'depends_show_if_not' => 'off',
			);

			$additional_options["{$option_name}_icon_color"] = array(
				'label'               => sprintf( __( '%1$s Icon Color', 'et_builder' ), $option_settings['label'] ),
				'type'                => 'color-alpha',
				'option_category'     => 'button',
				'custom_color'        => true,
				'default'             => '',
				'shortcode_default'   => '',
				'tab_slug'	       	  => 'advanced',
				'depends_show_if_not' => 'off',
			);

			$additional_options["{$option_name}_icon_placement"] = array(
				'label'           => sprintf( __( '%1$s Icon Placement', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'select',
				'option_category' => 'button',
				'options'         => array(
					'right'   => __( 'Right', 'et_builder' ),
					'left'    => __( 'Left', 'et_builder' ),
				),
				'shortcode_default'   => 'right',
				'tab_slug'	       	  => 'advanced',
				'depends_show_if_not' => 'off',
			);

			$additional_options["{$option_name}_on_hover"] = array(
				'label'           => sprintf( __( 'Only Show Icon On Hover for %1$s', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'yes_no_button',
				'option_category' => 'button',
				'options'         => array(
					'on'      => __( 'Yes', 'et_builder' ),
					'off'     => __( 'No', 'et_builder' ),
				),
				'shortcode_default'   => 'on',
				'tab_slug'	       	  => 'advanced',
				'depends_show_if_not' => 'off',
			);

			$additional_options["{$option_name}_text_color_hover"] = array(
				'label'             => sprintf( __( '%1$s Hover Text Color', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'color-alpha',
				'option_category'   => 'button',
				'custom_color'      => true,
				'default'           => '',
				'shortcode_default' => '',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_bg_color_hover"] = array(
				'label'             => sprintf( __( '%1$s Hover Background Color', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'color-alpha',
				'option_category'   => 'button',
				'custom_color'      => true,
				'default'           => '',
				'shortcode_default' => '',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_border_color_hover"] = array(
				'label'             => sprintf( __( '%1$s Hover Border Color', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'color-alpha',
				'option_category'   => 'button',
				'custom_color'      => true,
				'default'           => '',
				'shortcode_default' => '',
				'tab_slug'	       	=> 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_border_radius_hover"] = array(
				'label'             => sprintf( __( '%1$s Hover Border Radius', 'et_builder' ), $option_settings['label'] ),
				'type'              => 'range',
				'option_category'   => 'button',
				'default'           => ET_Global_Settings::get_value( 'all_buttons_border_radius_hover' ),
				'shortcode_default' => '',
				'tab_slug'          => 'advanced',
				'depends_default'   => true,
			);

			$additional_options["{$option_name}_letter_spacing_hover"] = array(
				'label'           => sprintf( __( '%1$s Hover Letter Spacing', 'et_builder' ), $option_settings['label'] ),
				'type'            => 'range',
				'option_category' => 'button',
				'default'         => ET_Global_Settings::get_value( 'all_buttons_spacing_hover' ),
				'tab_slug'        => 'advanced',
				'depends_default' => true,
			);
		}

		$this->_additional_fields_options = array_merge( $this->_additional_fields_options, $additional_options );
	}

	private function _add_custom_css_fields() {
		if ( isset( $this->custom_css_tab ) && ! $this->custom_css_tab ) {
			return;
		}

		$custom_css_fields = array();
		$custom_css_options = array();

		$custom_css_default_options = array(
			'before' => array(
				'label'    => __( 'Before', 'et_builder' ),
				'selector' => ':before',
				'no_space_before_selector' => true,
			),
			'main_element' => array(
				'label'    => __( 'Main Element', 'et_builder' ),
			),
			'after' => array(
				'label'    => __( 'After', 'et_builder' ),
				'selector' => ':after',
				'no_space_before_selector' => true,
			),
		);
		$custom_css_options = apply_filters( 'et_default_custom_css_options', $custom_css_default_options );

		if ( ! empty( $this->custom_css_options ) ) {
			$custom_css_options = array_merge( $custom_css_options, $this->custom_css_options );
		}

		$this->custom_css_options = apply_filters( 'et_custom_css_options_' . $this->slug, $custom_css_options );

		// optional settings names in custom css options
		$additional_option_slugs = array( 'description', 'priority' );

		foreach ( $custom_css_options as $slug => $option ) {
			$custom_css_fields[ "custom_css_{$slug}" ] = array(
				'label'    => $option['label'],
				'type'     => 'custom_css',
				'tab_slug' => 'custom_css',
			);

			// add optional settings if needed
			foreach ( $additional_option_slugs as $option_slug ) {
				if ( isset( $option[ $option_slug ] ) ) {
					$custom_css_fields[ "custom_css_{$slug}" ][ $option_slug ] = $option[ $option_slug ];
				}
			}
		}

		if ( ! empty( $custom_css_fields ) ) {
			$this->fields_unprocessed = array_merge( $this->fields_unprocessed, $custom_css_fields );
		}
	}

	private function _get_fields() {
		$this->fields = array();

		$this->fields = $this->fields_unprocessed;

		$this->fields = $this->process_fields( $this->fields );

		$this->fields = apply_filters( 'et_builder_module_fields_' . $this->slug, $this->fields );

		foreach ( $this->fields as $field_name => $field ) {
			$this->fields[ $field_name ] = apply_filters('et_builder_module_fields_' . $this->slug . '_field_' . $field_name, $field );
			$this->fields[ $field_name ]['name'] = $field_name;
		}

		return $this->fields;
	}

	// intended to be overridden as needed
	function process_fields( $fields ) { return $fields; }

	// intended to be overridden as needed
	function get_fields() { return array(); }

	function hex2rgb( $color ) {
		if ( substr( $color, 0, 1 ) == '#' ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 6 ) {
			list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return false;
		}

		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		return implode( ', ', array( $r, $g, $b ) );
	}

	function rgba_string_from_field_color_set( $color_set ) {
		if ( empty( $color_set ) || false === strpos($color_set, '|') ) {
			return false;
		}

		$color_set = explode('|', $color_set );

		$color_set_hex = $color_set[0];
		$color_set_rgb = $color_set[1];
		$color_set_alpha = $color_set[2];

		$color_set_rgba = 'rgba(' . $color_set_rgb . ', ' . $color_set_alpha . ')';
		return $color_set_rgba;
	}

	function get_post_type() {
		global $post, $et_builder_post_type;

		if ( is_admin() ) {
			return $post->post_type;
		} else {
			return $et_builder_post_type;
		}
	}

	function module_classes( $classes = array() ) {
		if ( ! empty( $classes ) ) {
			if ( ! is_array( $classes ) ) {
				if ( strpos( $classes, ' ' ) !== false ) {
					$classes = explode( ' ', $classes );
				} else {
					$classes = array( $classes );
				}
			}
		}

		$classes = apply_filters( 'et_builder_module_classes', $classes, $this->slug );
		$classes = apply_filters( 'et_builder_module_classes_' . $this->slug, $classes );

		$classes = array_map( 'trim', $classes );

		$_classes = array();
		foreach( $classes as $class ) {
			if ( ! empty( $class ) ) {
				$_classes[] = $class;
			}
		}

		return $_classes;
	}

	function wrap_settings_option( $option_output, $field ) {
		$depends = false;
		if ( isset( $field['depends_show_if'] ) || isset( $field['depends_show_if_not'] ) ) {
			$depends = true;
			if ( isset( $field['depends_show_if_not'] ) ) {
				$depends_attr = sprintf( ' data-depends_show_if_not="%s"', esc_attr( $field['depends_show_if_not'] ) );
			} else {
				$depends_attr = sprintf( ' data-depends_show_if="%s"', esc_attr( $field['depends_show_if'] ) );
			}
		}

		$output = sprintf(
			'%6$s<div class="et-pb-option%1$s%2$s%3$s%8$s"%4$s>%5$s</div> <!-- .et-pb-option -->%7$s',
			( ! empty( $field['type'] ) && 'tiny_mce' == $field['type'] ? ' et-pb-option-main-content' : '' ),
			( ( $depends || isset( $field['depends_default'] ) ) ? ' et-pb-depends' : '' ),
			( ! empty( $field['type'] ) && 'hidden' == $field['type'] ? ' et_pb_hidden' : '' ),
			( $depends ? $depends_attr : '' ),
			"\n\t\t\t\t" . $option_output . "\n\t\t\t",
			"\t",
			"\n\n\t\t",
			( ! empty( $field['type'] ) && 'hidden' == $field['type'] ? esc_attr( sprintf( ' et-pb-option-%1$s', $field['name'] ) ) : '' )
		);

		return $output;
	}

	function wrap_settings_option_field( $field ) {
		$use_container_wrapper = isset( $field['use_container_wrapper'] ) && ! $field['use_container_wrapper'] ? false : true;

		if ( ! empty( $field['renderer'] ) ) {
			$renderer_options = isset( $field['renderer_options'] ) ? $field['renderer_options'] : $field;

			$field_el = is_callable( $field['renderer'] ) ? call_user_func( $field['renderer'], $renderer_options ) : $field['renderer'];

			if ( ! empty( $field['renderer_with_field'] ) && $field['renderer_with_field'] ) {
				$field_el .= $this->render_field( $field );
			}
		} else {
			$field_el = $this->render_field( $field );
		}

		$description = ! empty( $field['description'] ) ? sprintf( '%2$s<p class="description">%1$s</p>', $field['description'], "\n\t\t\t\t\t" ) : '';

		if ( '' === $description && ! $use_container_wrapper ) {
			$output = $field_el;
		} else {
			$output = sprintf(
				'%3$s<div class="et-pb-option-container%5$s">
					%1$s
					%2$s
				%4$s</div> <!-- .et-pb-option-container -->',
				$field_el,
				$description,
				"\n\n\t\t\t\t",
				"\t",
				( isset( $field['type'] ) && 'custom_css' === $field['type'] ? ' et-pb-custom-css-option' : '' )
			);
		}

		return $output;
	}

	function wrap_settings_option_label( $field ) {
		if ( ! empty( $field['label'] ) ) {
			$label = $field['label'];
		} else {
			return '';
		}

		$field_name = $this->get_field_name( $field );
		if ( isset( $field['type'] ) && 'font' === $field['type'] ) {
			$field_name .= '_select';
		}

		$required = ! empty( $field['required'] ) ? '<span class="required">*</span>' : '';
		$attributes = ! ( isset( $field['type'] ) && in_array( $field['type'], array( 'custom_margin', 'custom_padding' )  ) )
			? sprintf( ' for="%1$s"', esc_attr( $field_name ) )
			: ' class="et_custom_margin_label"';

		$label = sprintf(
			'<label%1$s>%2$s: %3$s</label>',
			$attributes,
			$label,
			$required
		);

		return $label;
	}

	function get_field_name( $field ) {
		// Don't add 'et_pb_' prefix to the "Admin Label" field
		if ( 'admin_label' === $field['name'] ) {
			return $field['name'];
		}

		return sprintf( 'et_pb_%s', $field['name'] );
	}

	function render_field( $field ) {
		$classes = array();
		$hidden_field = '';
		$is_custom_color = isset( $field['custom_color'] ) && $field['custom_color'];
		$reset_button_html = '<span class="et-pb-reset-setting"></span>';

		if ( 'select' !== $field['type'] ) {
			$classes = array( 'regular-text' );
		}

		foreach( $this->get_validation_class_rules() as $rule ) {
			if ( ! empty( $field[ $rule ] ) ) {
				$this->validation_in_use = true;
				$classes[] = $rule;
			}
		}

		if ( isset( $field['validate_unit'] ) && $field['validate_unit'] ) {
			$classes[] = 'et-pb-validate-unit';
		}

		if ( ! empty( $field['class'] ) ) {
			if ( is_string( $field['class'] ) ) {
				$field['class'] = array( $field['class'] );
			}

			$classes = array_merge( $classes, $field['class'] );
		}
		$field['class'] = implode(' ', $classes );

		$field_name = $this->get_field_name( $field );

		$field['id'] = ! empty( $field['id'] ) ? $field['id'] : $field_name;

		$field['name'] = $field_name;

		if ( isset( $this->type ) && 'child' === $this->type ) {
			$field_name = "data.{$field_name}";
		}

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value_html = ' value="<%%- typeof( %1$s ) !== \'undefined\' ?  %2$s : \'%3$s\' %%>" ';
		$value = sprintf(
			$value_html,
			esc_attr( $field_name ),
			esc_attr( $field_name ),
			$default
		);

		$attributes = '';
		if ( ! empty( $field['attributes'] ) ) {
			if ( is_array( $field['attributes'] )  ) {
				foreach( $field['attributes'] as $attribute_key => $attribute_value ) {
					$attributes .= ' ' . esc_attr( $attribute_key ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			} else {
				$attributes = ' '.$field['attributes'];
			}
		}

		if ( ! empty( $field['affects'] ) ) {
			$field['class'] .= ' et-pb-affects';
			$attributes .= sprintf( ' data-affects="%s"', esc_attr( implode( ', ', $field['affects'] ) ) );
		}

		if ( 'font' === $field['type'] ) {
			$field['class'] .= ' et-pb-font-select';
		}

		if ( in_array( $field['type'], array( 'font', 'hidden' ) ) ) {
			$hidden_field = sprintf(
				'<input type="hidden" name="%1$s" id="%2$s" class="et-pb-main-setting %3$s" %4$s %5$s/>',
				esc_attr( $field['name'] ),
				esc_attr( $field['id'] ),
				esc_attr( $field['class'] ),
				$value,
				$attributes
			);
		}

		foreach ( $this->get_validation_attr_rules() as $rule ) {
			if ( ! empty( $field[ $rule ] ) ) {
				$this->validation_in_use = true;
				$attributes .= ' data-rule-' . esc_attr( $rule ). '="' . esc_attr( $field[ $rule ] ) . '"';
			}
		}

		switch( $field['type'] ) {
			case 'tiny_mce':
				if ( ! empty( $field['tiny_mce_html_mode'] ) ) {
					$field['class'] .= ' html_mode';
				}

				$main_content_property_name = $main_content_field_name = 'et_pb_content_new';

				if ( isset( $this->type ) && 'child' === $this->type ) {
					$main_content_property_name = "data.{$main_content_property_name}";
				}

				$field_el = sprintf(
					'<div id="%1$s"><%%= typeof( %2$s ) !== \'undefined\' ? %2$s : \'\' %%></div>',
					esc_attr( $main_content_field_name ),
					esc_html( $main_content_property_name )
				);

				break;
			case 'textarea':
			case 'custom_css':
				$field_custom_value = esc_html( $field_name );
				if ( 'custom_css' === $field['type'] ) {
					$field_custom_value .= '.replace( /\|\|/g, "\n" )';
				}

				if ( 'et_pb_raw_content' === $field_name ) {
					$field_custom_value = sprintf( '_.unescape( %1$s )', $field_custom_value );
				}

				$field_el = sprintf(
					'<textarea class="et-pb-main-setting large-text code%1$s" rows="4" cols="50" id="%2$s"><%%= typeof( %3$s ) !== \'undefined\' ? %4$s : \'\' %%></textarea>',
					esc_attr( $field['class'] ),
					esc_attr( $field['id'] ),
					esc_html( $field_name ),
					$field_custom_value
				);
				break;
			case 'select':
			case 'yes_no_button':
			case 'font':
				if ( 'font' === $field['type'] ) {
					$field['id']    .= '_select';
					$field_name     .= '_select';
					$field['class'] .= ' et-pb-helper-field';
					$field['options'] = array();
				}

				$button_options = array();

				if ( 'yes_no_button' === $field['type'] ) {
					$button_options = isset( $field['button_options'] ) ? $field['button_options'] : array();
				}

				$field_el = $this->render_select( $field_name, $field['options'], $field['id'], $field['class'], $attributes, $field['type'], $button_options );

				if ( 'font' === $field['type'] ) {
					$font_style_button_html =
						'<div class="et_builder_%1$s_font et_builder_font_style mce-widget mce-btn">
							<button type="button">
								<i class="mce-ico mce-i-%1$s"></i>
							</button>
						</div>';

					$field_el .= sprintf(
						'<div class="et_builder_font_styles mce-toolbar">
							%1$s
							%2$s
							%3$s
							%4$s
						</div> <!-- .et_builder_font_styles -->',
						sprintf( $font_style_button_html, 'bold' ),
						sprintf( $font_style_button_html, 'italic' ),
						sprintf( $font_style_button_html, 'uppercase' ),
						sprintf( $font_style_button_html, 'underline' )
					);

					$field_el .= $hidden_field;
				}
				break;
			case 'color':
			case 'color-alpha':
				$field['default'] = ! empty( $field['default'] ) ? $field['default'] : '';

				if ( $is_custom_color && ( ! isset( $field['default'] ) || '' === $field['default'] ) ) {
					$field['default'] = '';
				}

				$default = ! empty( $field['default'] ) && ! $is_custom_color ? sprintf( ' data-default-color="%s"', $field['default'] ) : '';

				$color_id = sprintf( ' id="%1$s"', esc_attr( $field['id'] ) );
				$color_value_html = '<%%- typeof( %1$s ) !== \'undefined\' && %1$s !== \'\' ? %1$s : \'%2$s\' %%>';
				$main_color_value = sprintf( $color_value_html, esc_attr( $field_name ), $field['default'] );
				$hidden_color_value = sprintf( $color_value_html, esc_attr( $field_name ), '' );

				$field_el = sprintf(
					'<input%1$s class="et-pb-color-picker-hex%5$s%8$s" type="text"%6$s%7$s placeholder="%9$s" data-selected-value="%2$s" value="%2$s"%3$s />
					%4$s',
					( ! $is_custom_color ? $color_id : '' ),
					$main_color_value,
					$default,
					( ! empty( $field['additional_code'] ) ? $field['additional_code'] : '' ),
					( 'color-alpha' === $field['type'] ? ' et-pb-color-picker-hex-alpha' : '' ),
					( 'color-alpha' === $field['type'] ? ' data-alpha="true"' : '' ),
					( 'color' === $field['type'] ? ' maxlength="7"' : '' ),
					( ! $is_custom_color ? ' et-pb-main-setting' : '' ),
					esc_attr__( 'Hex Value', 'et_builder' )
				);

				if ( $is_custom_color ) {
					$field_el = sprintf(
						'<span class="et-pb-custom-color-button et-pb-choose-custom-color-button"><span>%1$s</span></span>
						<div class="et-pb-custom-color-container et_pb_hidden">
							%2$s
							<input%3$s class="et-pb-main-setting et-pb-custom-color-picker" type="hidden" value="%4$s" />
							%5$s
						</div> <!-- .et-pb-custom-color-container -->',
						esc_html__( 'Choose Custom Color', 'et_builder' ),
						$field_el,
						$color_id,
						$hidden_color_value,
						$reset_button_html
					);
				}
				break;
			case 'upload':
				$field_data_type = ! empty( $field['data_type'] ) ? $field['data_type'] : 'image';
				$field['upload_button_text'] = ! empty( $field['upload_button_text'] ) ? $field['upload_button_text'] : __( 'Upload', 'et_builder' );
				$field['choose_text'] = ! empty( $field['choose_text'] ) ? $field['choose_text'] : __( 'Choose image', 'et_builder' );
				$field['update_text'] = ! empty( $field['update_text'] ) ? $field['update_text'] : __( 'Set image', 'et_builder' );
				$field['classes'] = ! empty( $field['classes'] ) ? ' ' . $field['classes'] : '';
				$field_additional_button = ! empty( $field['additional_button'] ) ? "\n\t\t\t\t\t" . $field['additional_button'] : '';

				$field_el = sprintf(
					'<input id="%1$s" type="text" class="et-pb-main-setting regular-text et-pb-upload-field%8$s" value="<%%- typeof( %2$s ) !== \'undefined\' ? %2$s : \'\' %%>" />
					<input type="button" class="button button-upload et-pb-upload-button" value="%3$s" data-choose="%4$s" data-update="%5$s" data-type="%6$s" />%7$s',
					esc_attr( $field['id'] ),
					esc_attr( $field_name ),
					esc_attr( $field['upload_button_text'] ),
					esc_attr( $field['choose_text'] ),
					esc_attr( $field['update_text'] ),
					esc_attr( $field_data_type ),
					$field_additional_button,
					esc_attr( $field['classes'] )
				);
				break;
			case 'checkbox':
				$field_el = sprintf(
					'<input type="checkbox" name="%1$s" id="%2$s" class="et-pb-main-setting" value="on" <%%- typeof( %1$s ) !==  \'undefined\' && %1$s == \'on\' ? checked="checked" : "" %%>>',
					esc_attr( $field['name'] ),
					esc_attr( $field['id'] )
				);
				break;
			case 'hidden':
				$field_el = $hidden_field;
				break;
			case 'custom_margin':
			case 'custom_padding':

				$custom_margin_class = "";

				// Add auto_important class to field which automatically append !important tag
				if ( isset( $this->advanced_options['custom_margin_padding']['css']['important'] ) ) {
					$custom_margin_class .= " auto_important";
				}

				$field_el = sprintf(
					'<div class="et_custom_margin_padding">
						%6$s
						%7$s
						%8$s
						%9$s
						<input type="hidden" name="%1$s" data-default="%5$s" id="%2$s" class="et-pb-main-setting et_custom_margin_main" %3$s %4$s/>
					</div> <!-- .et_custom_margin_padding -->',
					esc_attr( $field['name'] ),
					esc_attr( $field['id'] ),
					$value,
					$attributes,
					esc_attr( $default ),
					! isset( $field['sides'] ) || ( ! empty( $field['sides'] ) && in_array( 'top', $field['sides'] ) ) ?
						sprintf(
							'<label>%1$s <input type="text" class="et_custom_margin et_custom_margin_top%2$s" /></label>',
							esc_html__( 'Top', 'et_builder' ),
							esc_attr( $custom_margin_class )
						)
						: '',
					! isset( $field['sides'] ) || ( ! empty( $field['sides'] ) && in_array( 'right', $field['sides'] ) ) ?
						sprintf(
							'<label>%1$s <input type="text" class="et_custom_margin et_custom_margin_right%2$s" /></label>',
							esc_html__( 'Right', 'et_builder' ),
							esc_attr( $custom_margin_class )
						)
						: '',
					! isset( $field['sides'] ) || ( ! empty( $field['sides'] ) && in_array( 'bottom', $field['sides'] ) ) ?
						sprintf(
							'<label>%1$s <input type="text" class="et_custom_margin et_custom_margin_bottom%2$s" /></label>',
							esc_html__( 'Bottom', 'et_builder' ),
							esc_attr( $custom_margin_class )
						)
						: '',
					! isset( $field['sides'] ) || ( ! empty( $field['sides'] ) && in_array( 'left', $field['sides'] ) ) ?
						sprintf(
							'<label>%1$s <input type="text" class="et_custom_margin et_custom_margin_left%2$s" /></label>',
							esc_html__( 'Left', 'et_builder' ),
							esc_attr( $custom_margin_class )
						)
						: ''
				);
				break;
			case 'text':
			case 'date_picker':
			case 'range':
			default:
				$validate_number = isset( $field['number_validation'] ) && $field['number_validation'] ? true : false;

				if ( 'date_picker' === $field['type'] ) {
					$field['class'] .= ' et-pb-date-time-picker';
				}

				$field['class'] .= 'range' === $field['type'] ? ' et-pb-range-input' : ' et-pb-main-setting';

				$field_el = sprintf(
					'<input id="%1$s" type="text" class="%2$s%5$s"%6$s%3$s%8$s %4$s/>%7$s',
					esc_attr( $field['id'] ),
					esc_attr( $field['class'] ),
					$value,
					$attributes,
					( $validate_number ? ' et-validate-number' : '' ),
					( $validate_number ? ' maxlength="3"' : '' ),
					( ! empty( $field['additional_button'] ) ? $field['additional_button'] : '' ),
					( '' !== $default
						? sprintf( ' data-default="%1$s"', esc_attr( $default ) )
						: ''
					)
				);

				if ( 'range' === $field['type'] ) {
					$value = sprintf(
						$value_html,
						esc_attr( $field_name ),
						esc_attr( sprintf( 'parseFloat( %1$s )', $field_name ) ),
						( '' !== $default ? floatval( $default ) : '' )
					);

					$range_settings_html = '';
					$range_properties = apply_filters( 'et_builder_range_properties', array( 'min', 'max', 'step' ) );
					foreach ( $range_properties as $property ) {
						if ( isset( $field['range_settings'][ $property ] ) ) {
							$range_settings_html .= sprintf( ' %2$s="%1$s"',
								esc_attr( $field['range_settings'][ $property ] ),
								esc_html( $property )
							);
						}
					}

					$range_el = sprintf(
						'<input type="range" class="et-pb-main-setting et-pb-range" data-default="%2$s"%1$s%3$s />',
						$value,
						esc_attr( $default ),
						$range_settings_html
					);

					$field_el = $range_el . "\n" . $field_el;
				}

				break;
		}

		if ( isset( $field['type'] ) && isset( $field['tab_slug'] ) && 'advanced' === $field['tab_slug'] && ! $is_custom_color ) {
			$field_el .= $reset_button_html;
		}

		return "\t" . $field_el;
	}

	function render_select( $name, $options, $id = '', $class = '', $attributes = '', $field_type = '', $button_options = array() ) {
		$options_output = '';

		if ( 'font' === $field_type ) {
			$options_output = '<%= window.et_builder.fonts_template() %>';
		} else {
			foreach ( $options as $option_value => $option_label ) {
				$data = '';

				if ( is_array( $option_label ) ) {
					if ( isset( $option_label['data'] ) ) {
						$data_key_name = key( $option_label['data'] );

						$data = sprintf(
							' data-%1$s="%2$s"',
							esc_html( $data_key_name ),
							esc_attr( $option_label['data'][ $data_key_name ] )
						);
					}

					$option_label = $option_label['value'];
				}

				$selected_attr = '<%- typeof( ' . esc_attr( $name ) . ' ) !== \'undefined\' && \'' . esc_attr( $option_value ) . '\' === ' . esc_attr( $name ) . ' ?  \' selected="selected"\' : \'\' %>';
				$options_output .= sprintf(
					'%4$s<option%5$s value="%1$s"%2$s>%3$s</option>',
					esc_attr( $option_value ),
					$selected_attr,
					esc_html( $option_label ),
					"\n\t\t\t\t\t\t",
					( '' !== $data ? $data : '' )
				);
			}

			$class = rtrim( 'et-pb-main-setting ' . $class );
		}

		$output = sprintf(
			'%6$s
				<select name="%1$s"%2$s%3$s%4$s%8$s>%5$s</select>
			%7$s',
			esc_attr( $name ),
			( ! empty( $id ) ? sprintf(' id="%s"', esc_attr( $id ) ) : '' ),
			( ! empty( $class ) ? sprintf(' class="%s"', esc_attr( $class ) ) : '' ),
			( ! empty( $attributes ) ? $attributes : '' ),
			$options_output . "\n\t\t\t\t\t",
			'yes_no_button' === $field_type ?
				sprintf( '<div class="et_pb_yes_no_button_wrapper %3$s">
							<div class="et_pb_yes_no_button et_pb_off_state">
								<span class="et_pb_value_text et_pb_on_value">%1$s</span>
								<span class="et_pb_button_slider"></span>
								<span class="et_pb_value_text et_pb_off_value">%2$s</span>
							</div>',
					esc_html( $options['on'] ),
					esc_html( $options['off'] ),
					( ! empty( $button_options['button_type'] ) && 'equal' === $button_options['button_type'] ? ' et_pb_button_equal_sides' : '' )
				) : '',
			'yes_no_button' === $field_type ? '</div>' : '',
			( 'et_pb_transparent_background' === $name ? '<%- typeof( ' . esc_html( $name ) . ' ) === \'undefined\' ?  \' data-default=default\' : \'\' %>' : '' )
		);
		return $output;
	}

	function get_main_tabs() {
		$tabs = array(
			'general'    => __( 'General Settings', 'et_builder' ),
			'advanced'   => __( 'Advanced Design Settings', 'et_builder' ),
			'custom_css' => __( 'Custom CSS', 'et_builder' ),
		);

		return apply_filters( 'et_builder_main_tabs', $tabs );
	}

	function get_validation_attr_rules() {
		return array(
			'minlength',
			'maxlength',
			'min',
			'max'
		);
	}

	function get_validation_class_rules() {
		return array(
			'required',
			'email',
			'url',
			'date',
			'dateISO',
			'number',
			'digits',
			'creditcard'
		);
	}

	function sort_fields( $fields ) {
		$tabs_fields   = array();
		$sorted_fields = array();
		$i = 0;

		// Sort fields array by tab name
		foreach ( $fields as $field_slug => $field_options ) {
			$field_options['_order_number'] = $i;

			$tab_slug = ! empty( $field_options['tab_slug'] ) ? $field_options['tab_slug'] : 'general';
			$tabs_fields[ $tab_slug ][ $field_slug ] = $field_options;

			$i++;
		}

		// Sort fields within tabs by priority
		foreach ( $tabs_fields as $tab_fields ) {
			uasort( $tab_fields, array( 'self', 'compare_by_priority' ) );
			$sorted_fields = array_merge( $sorted_fields, $tab_fields );
		}

		return $sorted_fields;
	}

	function get_options() {
		$output = '';
		$tab_output = '';
		$tab_slug = '';
		$toggle_slug = '';
		$toggle_all_options_slug = 'all_options';
		$toggles_used = isset( $this->options_toggles );
		$tabs_output = array();
		$all_fields = $this->sort_fields( $this->_get_fields() );

		foreach( $all_fields as $field_name => $field ) {
			if ( ! empty( $field['type'] ) && 'skip' == $field['type'] ) {
				continue;
			}

			// add only options allowed for current user
			if (
				( ! et_pb_is_allowed( 'edit_colors' ) && ( ! empty( $field['type'] ) && in_array( $field['type'], array( 'color', 'color-alpha' ) ) || ( ! empty( $field['option_category'] ) && 'color_option' === $field['option_category'] ) ) )
				||
				( ! et_pb_is_allowed( 'edit_content' ) && ! empty( $field['option_category'] ) && 'basic_option' === $field['option_category'] )
				||
				( ! et_pb_is_allowed( 'edit_layout' ) && ! empty( $field['option_category'] ) && 'layout' === $field['option_category'] )
				||
				( ! et_pb_is_allowed( 'edit_configuration' ) && ! empty( $field['option_category'] ) && 'configuration' === $field['option_category'] )
				||
				( ! et_pb_is_allowed( 'edit_fonts' ) && ! empty( $field['option_category'] ) && 'font_option' === $field['option_category'] )
				||
				( ! et_pb_is_allowed( 'edit_buttons' ) && ! empty( $field['option_category'] ) && 'button' === $field['option_category'] )
			) {
				continue;
			}

			$option_output = '';
			$option_output .= $this->wrap_settings_option_label( $field );
			$option_output .= $this->wrap_settings_option_field( $field );

			$tab_slug = ! empty( $field['tab_slug'] ) ? $field['tab_slug'] : 'general';
			$is_toggle_option = isset( $field['toggle_slug'] ) && $toggles_used && isset( $this->options_toggles[ $tab_slug ] );
			$toggle_slug = $is_toggle_option ? $field['toggle_slug'] : $toggle_all_options_slug;
			$tabs_output[ $tab_slug ][ $toggle_slug ][] = $this->wrap_settings_option( $option_output, $field );

		}

		foreach ( $tabs_output as $tab_slug => $tab_settings ) {
			// Generate only options allowed for current user
			if ( ! et_pb_is_allowed( $tab_slug . '_settings' ) ) {
				continue;
			}

			$tab_output        = '';
			$this->used_tabs[] = $tab_slug;
			$i = 0;

			if ( isset( $tabs_output[ $tab_slug ] ) ) {
				if ( isset( $this->options_toggles[ $tab_slug ] ) ) {
					foreach ( $this->options_toggles[ $tab_slug ]['toggles'] as $toggle_slug => $toggle_heading ) {
						$i++;
						$toggle_output = '';
						$is_accordion_disabled = isset( $this->options_toggles[ $tab_slug ]['settings']['toggles_disabled'] ) && $this->options_toggles[ $tab_slug ]['settings']['toggles_disabled'] ? true : false;

						foreach ( $tabs_output[ $tab_slug ][ $toggle_slug ] as $toggle_option_output ) {
							$toggle_output .= $toggle_option_output;
						}

						$toggle_output = sprintf(
							'<div class="et-pb-options-toggle-container%3$s%4$s">
								<h3 class="et-pb-option-toggle-title">%1$s</h3>
								<div class="et-pb-option-toggle-content">
									%2$s
								</div> <!-- .et-pb-option-toggle-content -->
							</div> <!-- .et-pb-options-toggle-container -->',
							esc_html( $toggle_heading ),
							$toggle_output,
							( $is_accordion_disabled ? ' et-pb-options-toggle-disabled' : ' et-pb-options-toggle-enabled' ),
							( 1 === $i && ! $is_accordion_disabled ? ' et-pb-option-toggle-content-open' : '' )
						);

						$tab_output .= $toggle_output;
					}
				}

				if ( isset( $tabs_output[ $tab_slug ][ $toggle_all_options_slug ] ) ) {
					foreach ( $tabs_output[ $tab_slug ][ $toggle_all_options_slug ] as $no_toggle_option_output ) {
						$tab_output .= $no_toggle_option_output;
					}
				}
			}

			$output .= sprintf(
				'<div class="et-pb-options-tab et-pb-options-tab-%1$s">
					%3$s
					%2$s
				</div> <!-- .et-pb-options-tab_%1$s -->',
				esc_attr( $tab_slug ),
				$tab_output,
				( 'general' === $tab_slug ? $this->children_settings() : '' )
			);
		}

		// return error message if no tabs allowed for current user
		if ( '' === $output ) {
			$output = __( "You don't have sufficient permissions to access the settings", 'et_builder' );
		}

		return $output;
	}

	function children_settings() {
		$output = '';
		if ( ! empty( $this->child_slug ) ) {
			$output = sprintf(
			'%6$s<div class="et-pb-option-advanced-module-settings" data-module_type="%1$s">
				<ul class="et-pb-sortable-options">
				</ul>
				<a href="#" class="et-pb-add-sortable-option"><span>%2$s</span></a>
			</div> <!-- .et-pb-option -->

			<div class="et-pb-option et-pb-option-main-content et-pb-option-advanced-module">
				<label for="et_pb_content_new">%3$s</label>
				<div class="et-pb-option-container">
					<div id="et_pb_content_new"><%%= typeof( et_pb_content_new )!== \'undefined\' ? et_pb_content_new.replace( /%%22/g, \'||\' ) : \'\' %%></div>
					<p class="description">%4$s</p>
				</div> <!-- .et-pb-option-container -->
			</div> <!-- .et-pb-option -->%5$s',
			esc_attr( $this->child_slug ),
			esc_html( $this->add_new_child_text() ),
			esc_html__( 'Content', 'et_builder' ),
			esc_html__( 'Here you can define the content that will be placed within the current tab.', 'et_builder' ),
			"\n\n",
			"\t"
			);
		}

		return $output;
	}

	function add_new_child_text() {
		$child_slug = ! empty( $this->child_item_text ) ? $this->child_item_text : '';

		$child_slug = '' === $child_slug ? __( 'Add New Item', 'et_builder' ) : sprintf( __( 'Add New %s', 'et_builder' ), $child_slug );

		return $child_slug;
	}

	function wrap_settings( $output ) {
		$tabs_output = '';
		$i = 0;
		$tabs = array();

		// General Settings Tab should be added to all modules if allowed
		if ( et_pb_is_allowed( 'general_settings' ) ) {
			$tabs['general'] = isset( $this->main_tabs['general'] ) ? $this->main_tabs['general'] : __( 'General Settings', 'et_builder' );
		}

		foreach ( $this->used_tabs as $tab_slug ) {
			if ( 'general' === $tab_slug ) {
				continue;
			}

			// Add only tabs allowed for current user
			if ( et_pb_is_allowed( $tab_slug . '_settings' ) ) {
				$tabs[ $tab_slug ] = $this->main_tabs[ $tab_slug ];
			}
		}

		foreach ( $tabs as $tab_slug => $tab_name ) {
			$i++;

			$tabs_output .= sprintf(
				'<li class="%2$s%3$s">
					<a href="#">%1$s</a>
				</li>',
				esc_html( $tab_name ),
				esc_attr( "et_pb_options_tab_{$tab_slug}" ),
				( 1 === $i ? ' et-pb-options-tabs-links-active' : '' )
			);
		}

		$tabs_output = sprintf( '<ul class="et-pb-options-tabs-links">%1$s</ul>', $tabs_output );

		$preview_tabs_output = sprintf(
			'<ul class="et-pb-preview-screensize-switcher">
				<li><a href="#" class="et-pb-preview-mobile" data-width="375"><span class="label">%1$s</span></a></li>
				<li><a href="#" class="et-pb-preview-tablet" data-width="768"><span class="label">%2$s</span></a></li>
				<li><a href="#" class="et-pb-preview-desktop active"><span class="label">%3$s</span></a></li>
			</ul>',
			esc_html__( 'Mobile', 'et_builder' ),
			esc_html__( 'Tablet', 'et_builder' ),
			esc_html__( 'Desktop', 'et_builder' )
		);

		$output = sprintf(
			'%2$s
			%3$s
			<div class="et-pb-options-tabs">
				%1$s
			</div> <!-- .et-pb-options-tabs -->
			<div class="et-pb-preview-tab"></div> <!-- .et-pb-preview-tab -->
			',
			$output,
			$tabs_output,
			$preview_tabs_output
		);

		return sprintf(
			'%2$s<div class="et-pb-main-settings">%1$s</div> <!-- .et-pb-main-settings -->%3$s',
			"\n\t\t" . $output,
			"\n\t\t",
			"\n"
		);
	}

	function wrap_validation_form( $output ) {
		return '<form class="et-builder-main-settings-form validate">' . $output . '</form>';
	}

	function get_shortcode_fields() {
		$fields = array();

		foreach( $this->process_fields( $this->fields_unprocessed ) as $field_name => $field ) {
			$value = '';
			if ( isset( $field['shortcode_default'] ) ) {
				$value = $field['shortcode_default'];
			} else if( isset( $field['default'] ) ) {
				$value = $field['default'];
			}

			$fields[ $field_name ] = $value;
		}

		$fields['disabled'] = 'off';
		$fields['global_module'] = '';
		$fields['saved_tabs'] = '';

		return $fields;
	}

	function build_microtemplate() {
		$this->validation_in_use = false;

		if ( 'child' === $this->type ) {
			$id_attr = sprintf( 'et-builder-advanced-setting-%s', $this->slug );
		} else {
			$id_attr = sprintf( 'et-builder-%s-module-template', $this->slug );
		}

		if ( ! isset( $this->settings_text ) ) {
			$settings_text = sprintf(
				__( '%1$s %2$s Settings', 'et_builder' ),
				esc_html( $this->name ),
				'child' === $this->type ? esc_html__( 'Item', 'et_builder' ) : esc_html__( 'Module', 'et_builder' )
			);
		} else {
			$settings_text = $this->settings_text;
		}

		if ( file_exists( ET_BUILDER_DIR . 'microtemplates/' . $this->slug . '.php' ) ) {
			ob_start();
			include ET_BUILDER_DIR . 'microtemplates/' . $this->slug . '.php';
			$output = ob_get_clean();
		} else {
			$output = $this->get_options();
		}

		$output = $this->wrap_settings( $output );
		if ( $this->validation_in_use ) {
			$output = $this->wrap_validation_form( $output );
		}

		printf(
			'<script type="text/template" id="%1$s">
				<h3 class="et-pb-settings-heading">%2$s</h3>
				%3$s
			</script> <!-- #%4$s -->%5$s',
			esc_attr( $id_attr ),
			$settings_text,
			$output,
			esc_html( $id_attr ),
			"\n"
		);

		if ( $this->type == 'child' ) {
			$title_var = esc_js( $this->child_title_var );
			$title_var = false === strpos( $title_var, 'et_pb_' ) ? 'et_pb_'. $title_var : $title_var;
			$title_fallback_var = esc_js( $this->child_title_fallback_var );
			$title_fallback_var = false === strpos( $title_fallback_var, 'et_pb_' ) ? 'et_pb_'. $title_fallback_var : $title_fallback_var;
			$add_new_text = isset( $this->advanced_setting_title_text ) ? $this->advanced_setting_title_text : $this->add_new_child_text();

			printf(
				'%6$s<script type="text/template" id="et-builder-advanced-setting-%1$s-title">
					<%% if ( typeof( %2$s ) !== \'undefined\' && typeof( %2$s ) === \'string\' && %2$s !== \'\' ) { %%>
						<%%- %2$s %%>
					<%% } else if ( typeof( %3$s ) !== \'undefined\' && typeof( %3$s ) === \'string\' && %3$s !== \'\' ) { %%>
						<%%- %3$s %%>
					<%% } else { %%>
						<%%- \'%4$s\' %%>
					<%% } %%>
				</script>%5$s',
				esc_attr( $this->slug ),
				esc_html( $title_var ),
				esc_html( $title_fallback_var ),
				esc_html( $add_new_text ),
				"\n\n",
				"\t"
			);
		}
	}

	function process_additional_options( $function_name ) {
		if ( ! isset( $this->advanced_options ) ) {
			return false;
		}

		$this->process_advanced_fonts_options( $function_name );

		$this->process_advanced_background_options( $function_name );

		$this->process_advanced_border_options( $function_name );

		$this->process_advanced_custom_margin_options( $function_name );

		$this->process_advanced_button_options( $function_name );
	}

	function process_advanced_fonts_options( $function_name ) {
		if ( ! isset( $this->advanced_options['fonts'] ) ) {
			return;
		}

		$font_options = array();
		$slugs = array(
			'font',
			'font_size',
			'text_color',
			'letter_spacing',
			'line_height',
		);

		foreach ( $this->advanced_options['fonts'] as $option_name => $option_settings ) {
			$style = '';
			$important_options = array();
			$is_important_set = isset( $option_settings['css']['important'] );
			$use_global_important = $is_important_set && 'all' === $option_settings['css']['important'];
			if ( $is_important_set && is_array( $option_settings['css']['important'] ) ) {
				$important_options = $option_settings['css']['important'];
			}

			foreach ( $slugs as $font_option_slug ) {
				if ( isset( $this->shortcode_atts["{$option_name}_{$font_option_slug}"] ) ) {
					$font_options["{$option_name}_{$font_option_slug}"] = $this->shortcode_atts["{$option_name}_{$font_option_slug}"];
				}
			}

			if ( '' !== $font_options["{$option_name}_{$slugs[0]}"] ) {
				$important = in_array( 'font', $important_options ) || $use_global_important ? ' !important' : '';

				$style .= et_builder_set_element_font( $font_options["{$option_name}_{$slugs[0]}"], ( '' !== $important ) );
			}

			$size_option_name = "{$option_name}_{$slugs[1]}";
			$default_size     = isset( $this->fields_unprocessed[ $size_option_name ]['default'] ) ? $this->fields_unprocessed[ $size_option_name ]['default'] : '';

			if ( ! in_array( trim( $font_options[ $size_option_name ] ), array( '', 'px', $default_size ) ) ) {
				$important = in_array( 'size', $important_options ) || $use_global_important ? ' !important' : '';

				$style .= sprintf(
					'font-size: %1$s%2$s; ',
					esc_html( et_builder_process_range_value( $font_options[ $size_option_name ] ) ),
					esc_html( $important )
				);
			}

			$text_color_option_name = "{$option_name}_{$slugs[2]}";

			if ( '' !== $font_options[ $text_color_option_name ] ) {
				$important = ' !important';

				if ( isset( $option_settings['css']['color'] ) ) {
					self::set_style( $function_name, array(
						'selector'    => $option_settings['css']['color'],
						'declaration' => sprintf(
							'color: %1$s%2$s;',
							esc_html( $font_options[ $text_color_option_name ] ),
							esc_html( $important )
						),
						'priority'    => $this->_style_priority,
					) );
				} else {
					$style .= sprintf(
						'color: %1$s%2$s; ',
						esc_html( $font_options[ $text_color_option_name ] ),
						esc_html( $important )
					);
				}
			}

			$letter_spacing_option_name = "{$option_name}_{$slugs[3]}";
			$default_letter_spacing     = isset( $this->fields_unprocessed[ $letter_spacing_option_name ]['default'] ) ? $this->fields_unprocessed[ $letter_spacing_option_name ]['default'] : '';

			if ( ! in_array( trim( $font_options[ $letter_spacing_option_name ] ), array( '', 'px', $default_letter_spacing ) ) ) {
				$important = in_array( 'letter-spacing', $important_options ) || $use_global_important ? ' !important' : '';

				$style .= sprintf(
					'letter-spacing: %1$s%2$s; ',
					esc_html( et_builder_process_range_value( $font_options[ $letter_spacing_option_name ] ) ),
					esc_html( $important )
				);
			}

			$line_height_option_name = "{$option_name}_{$slugs[4]}";

			if ( isset( $font_options[ $line_height_option_name ] ) ) {
				$default_line_height     = isset( $this->fields_unprocessed[ $line_height_option_name ]['default'] ) ? $this->fields_unprocessed[ $line_height_option_name ]['default'] : '';

				if ( ! in_array( trim( $font_options[ $line_height_option_name ] ), array( '', 'px', $default_line_height ) ) ) {
					$important = in_array( 'line-height', $important_options ) || $use_global_important ? ' !important' : '';

					$style .= sprintf(
						'line-height: %1$s%2$s; ',
						esc_html( et_builder_process_range_value( $font_options[ $line_height_option_name ], 'line_height' ) ),
						esc_html( $important )
					);

					if ( isset( $option_settings['css']['line_height'] ) ) {
						self::set_style( $function_name, array(
							'selector'    => $option_settings['css']['line_height'],
							'declaration' => sprintf(
								'line-height: %1$s%2$s;',
								esc_html( et_builder_process_range_value( $font_options[ $line_height_option_name ], 'line_height' ) ),
								esc_html( $important )
							),
							'priority'    => $this->_style_priority,
						) );
					}
				}
			}

			if ( isset( $option_settings['use_all_caps'] ) && $option_settings['use_all_caps'] && 'on' === $this->shortcode_atts["{$option_name}_all_caps"] ) {
				$important = in_array( 'all_caps', $important_options ) || $use_global_important ? ' !important' : '';

				$style .= sprintf( 'text-transform: uppercase%1$s; ', esc_html( $important ) );
			}

			if ( '' !== $style ) {
				$css_element = ! empty( $option_settings['css']['main'] ) ? $option_settings['css']['main'] : $this->main_css_element;

				self::set_style( $function_name, array(
					'selector'    => $css_element,
					'declaration' => rtrim( $style ),
					'priority'    => $this->_style_priority,
				) );
			}
		}
	}

	function process_advanced_background_options( $function_name ) {
		if ( ! isset( $this->advanced_options['background'] ) ) {
			return;
		}

		$style = '';
		$settings = $this->advanced_options['background'];
		$important = isset( $settings['css']['use_important'] ) && $settings['css']['use_important'] ? ' !important' : '';

		if ( $this->advanced_options['background']['use_background_color'] ) {
			$background_color = $this->shortcode_atts['background_color'];

			if ( '' !== $background_color ) {
				$style .= sprintf(
					'background-color: %1$s%2$s; ',
					esc_html( $background_color ),
					esc_html( $important )
				);
			}
		}

		if ( $this->advanced_options['background']['use_background_image'] ) {
			$background_image = $this->shortcode_atts['background_image'];

			if ( '' !== $background_image ) {
				$style .= sprintf(
					'background-image: url(%1$s)%2$s; ',
					esc_html( $background_image ),
					esc_html( $important )
				);
			}
		}

		if ( '' !== $style ) {
			$css_element = ! empty( $settings['css']['main'] ) ? $settings['css']['main'] : $this->main_css_element;

			self::set_style( $function_name, array(
				'selector'    => $css_element,
				'declaration' => rtrim( $style ),
				'priority'    => $this->_style_priority,
			) );
		}
	}

	function process_advanced_border_options( $function_name ) {
		if ( ! isset( $this->advanced_options['border'] ) ) {
			return;
		}

		$style = '';
		$settings = $this->advanced_options['border'];

		$use_border_color = $this->shortcode_atts['use_border_color'];
		$border_style     = $this->shortcode_atts['border_style'];
		$border_color     =	'' !== $this->shortcode_atts['border_color'] ? $this->shortcode_atts['border_color'] : $this->fields_unprocessed['border_color']['default'];
		$border_width     = '' !== $this->shortcode_atts['border_width'] ? $this->shortcode_atts['border_width'] : $this->fields_unprocessed['border_width']['default'];

		if ( 'on' === $use_border_color ) {
			$border_declaration_html = sprintf(
				'%1$s %3$s %2$s',
				esc_attr( et_builder_process_range_value( $border_width ) ),
				esc_attr( $border_color ),
				esc_attr( $border_style )
			);

			$style .= "border: {$border_declaration_html}; ";
		}

		if ( '' !== $style ) {
			$css_element = ! empty( $settings['css']['main'] ) ? $settings['css']['main'] : $this->main_css_element;

			self::set_style( $function_name, array(
				'selector'    => $css_element,
				'declaration' => rtrim( $style ),
				'priority'    => $this->_style_priority,
			) );

			if ( ! empty( $border_declaration_html ) && isset( $settings['additional_elements'] ) && is_array( $settings['additional_elements'] ) ) {
				foreach ( $settings['additional_elements'] as $selector => $border_type ) {
					$style = '';

					if ( ! is_array( $border_type ) ) {
						continue;
					}

					foreach ( $border_type as $direction ) {
						$style .= sprintf(
							'border-%1$s: %2$s; ',
							( 'all' !== $border_type ? esc_html( $direction ) : '' ),
							$border_declaration_html
						);
					}

					self::set_style( $function_name, array(
						'selector'    => $selector,
						'declaration' => rtrim( $style ),
						'priority'    => $this->_style_priority,
					) );
				}
			}
		}
	}

	function process_advanced_custom_margin_options( $function_name ) {
		if ( ! isset( $this->advanced_options['custom_margin_padding'] ) ) {
			return;
		}

		$style = '';
		$important_options = array();
		$is_important_set = isset( $this->advanced_options['custom_margin_padding']['css']['important'] );
		$use_global_important = $is_important_set && 'all' === $this->advanced_options['custom_margin_padding']['css']['important'];
		if ( $is_important_set && is_array( $this->advanced_options['custom_margin_padding']['css']['important'] ) ) {
			$important_options = $this->advanced_options['custom_margin_padding']['css']['important'];
		}

		$custom_margin  = $this->advanced_options['custom_margin_padding']['use_margin'] ? $this->shortcode_atts['custom_margin'] : '';
		$custom_padding = $this->advanced_options['custom_margin_padding']['use_padding'] ? $this->shortcode_atts['custom_padding'] : '';

		if ( '' !== $custom_padding ) {
			$important = in_array( 'custom_padding', $important_options ) || $use_global_important ? true : false;
			$style .= et_builder_get_element_style_css( $custom_padding, 'padding', $important );
		}

		if ( '' !== $custom_margin ) {
			$important = in_array( 'custom_margin', $important_options ) || $use_global_important ? true : false;
			$style .= et_builder_get_element_style_css( $custom_margin, 'margin', $important );
		}

		if ( '' !== $style ) {
			$css_element = ! empty( $this->advanced_options['custom_margin_padding']['css']['main'] ) ? $this->advanced_options['custom_margin_padding']['css']['main'] : $this->main_css_element;

			self::set_style( $function_name, array(
				'selector'    => $css_element,
				'declaration' => rtrim( $style ),
				'priority'    => $this->_style_priority,
			) );
		}
	}

	function process_advanced_button_options( $function_name ) {
		if ( ! isset( $this->advanced_options['button'] ) ) {
			return;
		}

		foreach ( $this->advanced_options['button'] as $option_name => $option_settings ) {
			$button_custom               = $this->shortcode_atts["custom_{$option_name}"];
			$button_text_size            = $this->shortcode_atts["{$option_name}_text_size"];
			$button_text_color           = $this->shortcode_atts["{$option_name}_text_color"];
			$button_bg_color             = $this->shortcode_atts["{$option_name}_bg_color"];
			$button_border_width         = $this->shortcode_atts["{$option_name}_border_width"];
			$button_border_color         = $this->shortcode_atts["{$option_name}_border_color"];
			$button_border_radius        = $this->shortcode_atts["{$option_name}_border_radius"];
			$button_font                 = $this->shortcode_atts["{$option_name}_font"];
			$button_letter_spacing       = $this->shortcode_atts["{$option_name}_letter_spacing"];
			$button_use_icon             = $this->shortcode_atts["{$option_name}_use_icon"];
			$button_icon                 = $this->shortcode_atts["{$option_name}_icon"];
			$button_icon_color           = $this->shortcode_atts["{$option_name}_icon_color"];
			$button_icon_placement       = $this->shortcode_atts["{$option_name}_icon_placement"];
			$button_on_hover             = $this->shortcode_atts["{$option_name}_on_hover"];
			$button_text_color_hover     = $this->shortcode_atts["{$option_name}_text_color_hover"];
			$button_bg_color_hover       = $this->shortcode_atts["{$option_name}_bg_color_hover"];
			$button_border_color_hover   = $this->shortcode_atts["{$option_name}_border_color_hover"];
			$button_border_radius_hover  = $this->shortcode_atts["{$option_name}_border_radius_hover"];
			$button_letter_spacing_hover = $this->shortcode_atts["{$option_name}_letter_spacing_hover"];

			if ( 'on' === $button_custom ) {

				$button_text_size = '' === $button_text_size || 'px' === $button_text_size ? '20px' : $button_text_size;
				$button_text_size = '' !== $button_text_size && false === strpos( $button_text_size, 'px' ) ? $button_text_size . 'px' : $button_text_size;

				$css_element = ! empty( $option_settings['css']['main'] ) ? $option_settings['css']['main'] : $this->main_css_element . ' .et_pb_button';
				$css_element_processed = et_is_builder_plugin_active() ? $css_element : 'body #page-container ' . $css_element;

				if ( '' !== $button_bg_color && et_is_builder_plugin_active() ) {
					$button_bg_color .= ' !important';
				}

				$main_element_styles = sprintf(
					'%1$s
					%2$s
					%3$s
					%4$s
					%5$s
					%6$s
					%7$s
					%8$s
					%9$s',
					'' !== $button_text_color ? sprintf( 'color:%1$s !important;', $button_text_color ) : '',
					'' !== $button_bg_color ? sprintf( 'background:%1$s;', $button_bg_color ) : '',
					'' !== $button_border_width && 'px' !== $button_border_width ? sprintf( 'border-width:%1$s !important;', et_builder_process_range_value( $button_border_width ) ) : '',
					'' !== $button_border_color ? sprintf( 'border-color:%1$s;', $button_border_color ) : '',
					'' !== $button_border_radius && 'px' !== $button_border_radius ? sprintf( 'border-radius:%1$s;', et_builder_process_range_value( $button_border_radius ) ) : '',
					'' !== $button_letter_spacing && 'px' !== $button_letter_spacing ? sprintf( 'letter-spacing:%1$s;', et_builder_process_range_value( $button_letter_spacing ) ) : '',
					'' !== $button_text_size && 'px' !== $button_text_size ? sprintf( 'font-size:%1$s;', et_builder_process_range_value( $button_text_size ) ) : '',
					'' !== $button_font ? et_builder_set_element_font( $button_font, true ) : '',
					'off' === $button_on_hover ?
						sprintf( 'padding-left:%1$s; padding-right: %2$s;',
							'left' === $button_icon_placement ? '2em' : '0.7em',
							'left' === $button_icon_placement ? '0.7em' : '2em'
						)
						: ''
				);

				self::set_style( $function_name, array(
					'selector'    => $css_element_processed,
					'declaration' => rtrim( $main_element_styles ),
				) );

				$main_element_styles_hover = sprintf(
					'%1$s
					%2$s
					%3$s
					%4$s
					%5$s
					%6$s',
					'' !== $button_text_color_hover ? sprintf( 'color:%1$s !important;', $button_text_color_hover ) : '',
					'' !== $button_bg_color_hover ? sprintf( 'background:%1$s !important;', $button_bg_color_hover ) : '',
					'' !== $button_border_color_hover ? sprintf( 'border-color:%1$s !important;', $button_border_color_hover ) : '',
					'' !== $button_border_radius_hover ? sprintf( 'border-radius:%1$s;', et_builder_process_range_value( $button_border_radius_hover ) ) : '',
					'' !== $button_letter_spacing_hover ? sprintf( 'letter-spacing:%1$spx;', $button_letter_spacing_hover ) : '',
					'off' === $button_on_hover ?
						''
						:
						sprintf( 'padding-left:%1$s; padding-right: %2$s;',
							'left' === $button_icon_placement ? '2em' : '0.7em',
							'left' === $button_icon_placement ? '0.7em' : '2em'
						)
				);

				self::set_style( $function_name, array(
					'selector'    => $css_element_processed . ':hover',
					'declaration' => rtrim( $main_element_styles_hover ),
				) );

				if ( 'off' === $button_use_icon ) {
					$main_element_styles_after = 'display:none !important;';
					$no_icon_styles = 'padding: 0.3em 1em !important;';

					self::set_style( $function_name, array(
						'selector'    => $css_element_processed . ',' . $css_element_processed . ':hover',
						'declaration' => rtrim( $no_icon_styles ),
					) );
				} else {
					$button_icon_code = '' !== $button_icon ? str_replace( ';', '', str_replace( '&#x', '', html_entity_decode( et_pb_process_font_icon( $button_icon ) ) ) ) : '';
					$int_font_size = intval( str_replace( 'px', '', $button_text_size ) );

					if ( '' !== $button_text_size ) {
						$button_icon_size = '35' !== $button_icon_code ? $button_text_size : ( $int_font_size * 1.6 ) . 'px';
					}

					$main_element_styles_after = sprintf(
						'%1$s
						%2$s
						%3$s
						%4$s
						%5$s
						%6$s
						%7$s',
						'' !== $button_icon_color ? sprintf( 'color:%1$s;', $button_icon_color ) : '',
						'' !== $button_icon_code ?
							sprintf( 'line-height:%1$s;', '35' !== $button_icon_code ? '1.7em' : '1em' )
							: '',
						'' !== $button_icon_code ? sprintf( 'font-size:%1$s !important;', $button_icon_size ) : '',
						sprintf( 'opacity:%1$s;', 'on' === $button_on_hover ? '0' : '1' ),
						'off' !== $button_on_hover && '' !== $button_icon_code ?
							sprintf( 'margin-left:%1$s;left:%2$s;',
								'left' === $button_icon_placement ? '0' : '-1em',
								'left' === $button_icon_placement ? '1em' : 'auto'
							)
							: '',
						'off' === $button_on_hover ?
							sprintf( 'margin-left:%1$s;left:%2$s;',
								'left' === $button_icon_placement ? '0' : '.3em',
								'left' === $button_icon_placement ? '0.15em' : 'auto'
							)
							: '',
						'on' === $button_use_icon ? 'display: inline-block;' : ''

					);

					$hover_after_styles = sprintf(
						'%1$s
						%2$s
						%3$s',
						'' !== $button_icon_code ?
							sprintf( 'margin-left:%1$s;', '35' !== $button_icon_code ? '.3em' : '0' )
							: '',
							'' !== $button_icon_code ?
								sprintf( 'left:%1$s;margin-left:%2$s;',
									'left' === $button_icon_placement ? '0.15em' : 'auto',
									'35' !== $button_icon_code ? '.3em' : '0'
								)
							: '',
						'on' === $button_on_hover ? 'opacity: 1;' : ''
					);

					self::set_style( $function_name, array(
						'selector'    => $css_element_processed . ':hover:after',
						'declaration' => rtrim( $hover_after_styles ),
					) );

					if ( '' === $button_icon ) {
						$default_icons_size = $int_font_size * 1.6 . 'px';
						$custom_icon_size = $button_text_size;

						self::set_style( $function_name, array(
							'selector'    => $css_element_processed . ':after',
							'declaration' => sprintf( 'font-size:%1$s;', $default_icons_size ),
						) );

						self::set_style( $function_name, array(
							'selector'    => 'body.et_button_custom_icon #page-container ' . $css_element . ':after',
							'declaration' => sprintf( 'font-size:%1$s;', $custom_icon_size ),
						) );
					}
				}

				self::set_style( $function_name, array(
					'selector'    => $css_element_processed . ':after',
					'declaration' => rtrim( $main_element_styles_after ),
				) );
			}
		}
	}

	function process_custom_css_options( $function_name ) {
		if ( empty( $this->custom_css_options ) ) {
			return false;
		}

		foreach ( $this->custom_css_options as $slug => $option ) {
			$css      = $this->shortcode_atts["custom_css_{$slug}"];
			$selector = ! empty( $option['selector'] ) ? $option['selector'] : '';

			if ( false === strpos( $selector, '%%order_class%%' ) ) {
				if ( ! ( isset( $option['no_space_before_selector'] ) && $option['no_space_before_selector'] ) && '' !== $selector ) {
					$selector = " {$selector}";
				}

				$selector = "%%order_class%%{$selector}";
			}

			if ( '' !== $css ) {
				self::set_style( $function_name, array(
					'selector'    => $selector,
					'declaration' => trim( $css ),
				) );
			}
		}
	}

	static function compare_by_priority( $a, $b ) {
		$a_priority = ! empty( $a['priority'] ) ? (int) $a['priority'] : self::DEFAULT_PRIORITY;
		$b_priority = ! empty( $b['priority'] ) ? (int) $b['priority'] : self::DEFAULT_PRIORITY;

		if ( isset( $a['_order_number'], $b['_order_number'] ) && ( $a_priority === $b_priority ) ) {
			return $a['_order_number'] - $b['_order_number'];
		}

		return $a_priority - $b_priority;
	}

	static function compare_by_name( $a, $b ) {
		return strcasecmp( $a->name, $b->name );
	}

	static function get_modules_js_array( $post_type ) {
		$modules = array();
		if ( ! empty( self::$parent_modules[ $post_type ] ) ) {
			/**
			 * Sort modules alphabetically by name.
			 */
			$sorted_modules = self::$parent_modules[ $post_type ];

			uasort( $sorted_modules, array( 'self', 'compare_by_name' ) );

			foreach( $sorted_modules as $module ) {
				/**
				 * Replace single and double quotes with %% and || respectively
				 * to avoid js conflicts
				 */
				$module_name = str_replace( '"', '%%', $module->name );
				$module_name = str_replace( "'", '||', $module_name );

				$modules[] = sprintf(
					'{ "title" : "%1$s", "label" : "%2$s"%3$s}',
					esc_js( $module_name ),
					esc_js( $module->slug ),
					( isset( $module->fullwidth ) && $module->fullwidth ? ', "fullwidth_only" : "on"' : '' )
				);
			}
		}

		return '[' . implode( ',', $modules ) . ']';
	}

	static function get_parent_shortcodes( $post_type ) {
		$shortcodes = array();
		if ( ! empty( self::$parent_modules[ $post_type ] ) ) {
			foreach( self::$parent_modules[ $post_type ] as $module ) {
				$shortcodes[] = $module->slug;
			}
		}

		return implode( '|', $shortcodes );
	}

	static function get_child_shortcodes( $post_type ) {
		$shortcodes = array();
		if ( ! empty( self::$child_modules[ $post_type ] ) ) {
			foreach( self::$child_modules[ $post_type ] as $module ) {
				if ( ! empty( $module->slug ) ) {
					$shortcodes[] = $module->slug;
				}
			}
		}

		return implode( '|', $shortcodes );
	}

	static function get_raw_content_shortcodes( $post_type ) {
		$shortcodes = array();

		if ( ! empty( self::$parent_modules[ $post_type ] ) ) {
			foreach( self::$parent_modules[ $post_type ] as $module ) {
				if ( isset( $module->use_row_content ) && $module->use_row_content ) {
					$shortcodes[] = $module->slug;
				}
			}
		}

		if ( ! empty( self::$child_modules[ $post_type ] ) ) {
			foreach( self::$child_modules[ $post_type ] as $module ) {
				if ( isset( $module->use_row_content ) && $module->use_row_content ) {
					$shortcodes[] = $module->slug;
				}
			}
		}

		return implode( '|', $shortcodes );
	}

	static function output_templates() {
		global $typenow;
		$post_type = $typenow;

		if ( ! empty( self::$parent_modules[ $post_type ] ) ) {
			foreach( self::$parent_modules[ $post_type ] as $module ) {
				$module->build_microtemplate();
			}
		}

		if ( ! empty( self::$child_modules[ $post_type ] ) ) {
			foreach( self::$child_modules[ $post_type ] as $module ) {
				$module->build_microtemplate();
			}
		}
	}

	static function set_media_queries() {
		$media_queries = array(
			'min_width_1405' => '@media only screen and ( min-width: 1405px )',
			'1100_1405'      => '@media only screen and ( min-width: 1100px ) and ( max-width: 1405px)',
			'981_1405'       => '@media only screen and ( min-width: 981px ) and ( max-width: 1405px)',
			'981_1100'       => '@media only screen and ( min-width: 981px ) and ( max-width: 1100px )',
			'min_width_981'  => '@media only screen and ( min-width: 981px )',
			'max_width_980'  => '@media only screen and ( max-width: 980px )',
			'768_980'        => '@media only screen and ( min-width: 768px ) and ( max-width: 980px )',
			'max_width_767'  => '@media only screen and ( max-width: 767px )',
			'max_width_479'  => '@media only screen and ( max-width: 479px )',
		);

		$media_queries['mobile'] = $media_queries['max_width_767'];

		self::$media_queries = apply_filters( 'et_builder_media_queries', $media_queries );
	}

	static function get_media_query( $name ) {
		if ( ! isset( self::$media_queries[ $name ] ) ) {
			return false;
		}

		return self::$media_queries[ $name ];
	}

	static function get_style() {
		if ( empty( self::$styles ) ) {
			return false;
		}

		$output = '';

		$styles_by_media_queries = self::$styles;
		$styles_count            = (int) count( $styles_by_media_queries );

		foreach ( $styles_by_media_queries as $media_query => $styles ) {
			$media_query_output    = '';
			$wrap_into_media_query = 'general' !== $media_query;

			// sort styles by priority
			uasort( $styles, array( 'self', 'compare_by_priority' ) );

			// get each rule in a media query
			foreach ( $styles as $selector => $settings ) {
				$media_query_output .= sprintf(
					'%3$s%4$s%1$s { %2$s }',
					$selector,
					$settings['declaration'],
					"\n",
					( $wrap_into_media_query ? "\t" : '' )
				);
			}

			// All css rules that don't use media queries are assigned to the "general" key.
			// Wrap all non-general settings into media query.
			if ( $wrap_into_media_query ) {
				$media_query_output = sprintf(
					'%3$s%3$s%1$s {%2$s%3$s}',
					$media_query,
					$media_query_output,
					"\n"
				);
			}

			$output .= $media_query_output;
		}

		return $output;
	}

	static function set_style( $function_name, $style ) {
		$order_class_name = self::get_module_order_class( $function_name );

		$selector    = str_replace( '%%order_class%%', ".{$order_class_name}", $style['selector'] );
		$selector    = str_replace( '%order_class%', ".{$order_class_name}", $selector );

		// Prepend .et_divi_builder class before all CSS rules in the Divi Builder plugin
		if ( et_is_builder_plugin_active() ) {
			$selector = ".et_divi_builder #et_builder_outer_content $selector";
		}

		$declaration = $style['declaration'];
		// New lines are saved as || in CSS Custom settings, remove them
		$declaration = preg_replace( '/(\|\|)/i', '', $declaration );

		$media_query = isset( $style[ 'media_query' ] ) ? $style[ 'media_query' ] : 'general';

		if ( isset( self::$styles[ $media_query ][ $selector ]['declaration'] ) ) {
			self::$styles[ $media_query ][ $selector ]['declaration'] = sprintf(
				'%1$s %2$s',
				self::$styles[ $media_query ][ $selector ]['declaration'],
				$declaration
			);
		} else {
			self::$styles[ $media_query ][ $selector ]['declaration'] = $declaration;
		}

		if ( isset( $style['priority'] ) ) {
			self::$styles[ $media_query ][ $selector ]['priority'] = (int) $style['priority'];
		}
	}

	static function get_module_order_class( $function_name ) {
		if ( ! isset( self::$modules_order[ $function_name ] ) ) {
			return false;
		}

		$shortcode_order_num = self::$modules_order[ $function_name ];

		$order_class_name = sprintf( '%1$s_%2$s', $function_name, $shortcode_order_num );

		return $order_class_name;
	}

	static function set_order_class( $function_name ) {
		if ( ! isset( self::$modules_order ) ) {
			self::$modules_order = array();
		}

		self::$modules_order[ $function_name ] = isset( self::$modules_order[ $function_name ] ) ? (int) self::$modules_order[ $function_name ] + 1 : 0;
	}

	static function add_module_order_class( $module_class, $function_name ) {
		$order_class_name = self::get_module_order_class( $function_name );

		return "{$module_class} {$order_class_name}";
	}


	/**
	 * Convert smart quotes and &amp; entity to their applicable characters
	 * @param  string $text Input text
	 * @return string
	 */
	static function convert_smart_quotes_and_amp( $text ) {
		$smart_quotes = array(
			'&#8220;',
			'&#8221;',
			'&#8243;',
			'&#8216;',
			'&#8217;',
			'&#x27;',
			'&amp;',
		);

		$replacements = array(
			'&quot;',
			'&quot;',
			'&quot;',
			'&#39;',
			'&#39;',
			'&#39;',
			'&',
		);

		if ( 'fr_FR' === get_locale() ) {
			$french_smart_quotes = array(
				'&nbsp;&raquo;',
				'&Prime;&gt;',
			);

			$french_replacements = array(
				'&quot;',
				'&quot;&gt;',
			);

			$smart_quotes = array_merge( $smart_quotes, $french_smart_quotes );
			$replacements = array_merge( $replacements, $french_replacements );
		}

		$text = str_replace( $smart_quotes, $replacements, $text );

		return $text;
	}
}

do_action( 'et_pagebuilder_module_init' );

class ET_Builder_Module extends ET_Builder_Element {}

class ET_Builder_Structure_Element extends ET_Builder_Element {
	public $is_structure_element = true;

	function wrap_settings_option( $option_output, $field ) {
		if ( ! empty( $field['type'] ) && 'column_settings' == $field['type'] ) {
			$output = $this->generate_columns_settings();
		} else {
			$depends = false;
			if ( isset( $field['depends_show_if'] ) || isset( $field['depends_show_if_not'] ) ) {
				$depends = true;
				if ( isset( $field['depends_show_if_not'] ) ) {
					$depends_attr = sprintf( ' data-depends_show_if_not="%s"', esc_attr( $field['depends_show_if_not'] ) );
				} else {
					$depends_attr = sprintf( ' data-depends_show_if="%s"', esc_attr( $field['depends_show_if'] ) );
				}
			}

			$output = sprintf(
				'%6$s<div class="et-pb-option%1$s%2$s%3$s%8$s"%4$s>%5$s</div> <!-- .et-pb-option -->%7$s',
				( ! empty( $field['type'] ) && 'tiny_mce' == $field['type'] ? ' et-pb-option-main-content' : '' ),
				( ( $depends || isset( $field['depends_default'] ) ) ? ' et-pb-depends' : '' ),
				( ! empty( $field['type'] ) && 'hidden' == $field['type'] ? ' et_pb_hidden' : '' ),
				( $depends ? $depends_attr : '' ),
				"\n\t\t\t\t" . $option_output . "\n\t\t\t",
				"\t",
				"\n\n\t\t",
				( ! empty( $field['type'] ) && 'hidden' == $field['type'] ? esc_attr( sprintf( ' et-pb-option-%1$s', $field['name'] ) ) : '' )
			);
		}

		return $output;
	}

	function generate_columns_settings() {
		$output =
			"<% var columns = typeof columns_layout !== 'undefined' ? columns_layout.split(',') : [],
				counter = 1;
				_.each( columns, function ( column_type ) {
					var current_value_bg = typeof( et_pb_background_color_1 ) !== 'undefined' ? et_pb_background_color_1 : '',
						current_value_pt = typeof( et_pb_padding_top_1 ) !== 'undefined' ? et_pb_padding_top_1 : '',
						current_value_pr = typeof( et_pb_padding_right_1 ) !== 'undefined' ? et_pb_padding_right_1 : '',
						current_value_pb = typeof( et_pb_padding_bottom_1 ) !== 'undefined' ? et_pb_padding_bottom_1 : '',
						current_value_pl = typeof( et_pb_padding_left_1 ) !== 'undefined' ? et_pb_padding_left_1 : '',
						current_value_bg_img = typeof( et_pb_bg_img_1 ) !== 'undefined' ? et_pb_bg_img_1 : '';
						current_value_parallax = typeof( et_pb_parallax_1 ) !== 'undefined' && 'on' === et_pb_parallax_1 ? ' selected=\"selected\"' : '';
						current_value_parallax_method = typeof( et_pb_parallax_method_1 ) !== 'undefined' && 'on' === et_pb_parallax_method_1 ? ' selected=\"selected\"' : '';
					switch ( counter ) {
						case 2 :
							current_value_bg = typeof( et_pb_background_color_2 ) !== 'undefined' ? et_pb_background_color_2 : '';
							current_value_pt = typeof( et_pb_padding_top_2 ) !== 'undefined' ? et_pb_padding_top_2 : '';
							current_value_pr = typeof( et_pb_padding_right_2 ) !== 'undefined' ? et_pb_padding_right_2 : '';
							current_value_pb = typeof( et_pb_padding_bottom_2 ) !== 'undefined' ? et_pb_padding_bottom_2 : '';
							current_value_pl = typeof( et_pb_padding_left_2 ) !== 'undefined' ? et_pb_padding_left_2 : '';
							current_value_bg_img = typeof( et_pb_bg_img_2 ) !== 'undefined' ? et_pb_bg_img_2 : '';
							current_value_parallax = typeof( et_pb_parallax_2 ) !== 'undefined' && 'on' === et_pb_parallax_2 ? ' selected=\"selected\"' : '';
							current_value_parallax_method = typeof( et_pb_parallax_method_2 ) !== 'undefined' && 'on' === et_pb_parallax_method_2 ? ' selected=\"selected\"' : '';
							break;
						case 3 :
							current_value_bg = typeof( et_pb_background_color_3 ) !== 'undefined' ? et_pb_background_color_3 : '';
							current_value_pt = typeof( et_pb_padding_top_3 ) !== 'undefined' ? et_pb_padding_top_3 : '';
							current_value_pr = typeof( et_pb_padding_right_3 ) !== 'undefined' ? et_pb_padding_right_3 : '';
							current_value_pb = typeof( et_pb_padding_bottom_3 ) !== 'undefined' ? et_pb_padding_bottom_3 : '';
							current_value_pl = typeof( et_pb_padding_left_3 ) !== 'undefined' ? et_pb_padding_left_3 : '';
							current_value_bg_img = typeof( et_pb_bg_img_3 ) !== 'undefined' ? et_pb_bg_img_3 : '';
							current_value_parallax = typeof( et_pb_parallax_3 ) !== 'undefined' && 'on' === et_pb_parallax_3 ? ' selected=\"selected\"' : '';
							current_value_parallax_method = typeof( et_pb_parallax_method_3 ) !== 'undefined' && 'on' === et_pb_parallax_method_3 ? ' selected=\"selected\"' : '';
							break;
						case 4 :
							current_value_bg = typeof( et_pb_background_color_4 ) !== 'undefined' ? et_pb_background_color_4 : '';
							current_value_pt = typeof( et_pb_padding_top_4 ) !== 'undefined' ? et_pb_padding_top_4 : '';
							current_value_pr = typeof( et_pb_padding_right_4 ) !== 'undefined' ? et_pb_padding_right_4 : '';
							current_value_pb = typeof( et_pb_padding_bottom_4 ) !== 'undefined' ? et_pb_padding_bottom_4 : '';
							current_value_pl = typeof( et_pb_padding_left_4 ) !== 'undefined' ? et_pb_padding_left_4 : '';
							current_value_bg_img = typeof( et_pb_bg_img_4 ) !== 'undefined' ? et_pb_bg_img_4 : '';
							current_value_parallax = typeof( et_pb_parallax_4 ) !== 'undefined' && 'on' === et_pb_parallax_4 ? ' selected=\"selected\"' : '';
							current_value_parallax_method = typeof( et_pb_parallax_method_4 ) !== 'undefined' && 'on' === et_pb_parallax_method_4 ? ' selected=\"selected\"' : '';
							break;
					}
			%>";

		$output .= sprintf(
			'<div class="et-pb-option">
				<label for="et_pb_bg_img_<%%= counter %%>">
					%1$s
					<%% if ( "4_4" !== column_type ) { %%>
						<%%= counter + " " %%>
					<%% } %%>
					%2$s:
				</label>

				<div class=et-pb-option-container>
					<input id="et_pb_bg_img_<%%= counter %%>" type="text" class="regular-text et-pb-upload-field" value="<%%= current_value_bg_img  %%>" />
					<input type="button" class="button button-upload et-pb-upload-button" value="%3$s" data-choose="%4$s" data-update="%5$s" data-type="image" />
				</div>
			</div> <!-- .et-pb-option -->

			<div class="et-pb-option">
				<label for="et_pb_parallax_<%%= counter %%>">
					%1$s
					<%% if ( "4_4" !== column_type ) { %%>
						<%%= counter + " " %%>
					<%% } %%>
					%13$s:
				</label>

				<div class="et-pb-option-container">
					<div class="et_pb_yes_no_button_wrapper ">
						<div class="et_pb_yes_no_button et_pb_off_state">
							<span class="et_pb_value_text et_pb_on_value">%14$s</span>
							<span class="et_pb_button_slider"></span>
							<span class="et_pb_value_text et_pb_off_value">%15$s</span>
						</div>
						<select name="et_pb_parallax_<%%= counter %%>" id="et_pb_parallax_<%%= counter %%>" class="et-pb-main-setting regular-text et-pb-affects" data-affects="#et_pb_parallax_method_<%%= counter %%>">
							<option value="off">%15$s</option>
							<option value="on" <%%= current_value_parallax %%>>%14$s</option>
						</select>
					</div>
				</div> <!-- .et-pb-option-container -->
			</div>

			<div class="et-pb-option et-pb-depends" data-depends_show_if="on">
				<label for="et_pb_parallax_method_<%%= counter %%>">
					%1$s
					<%% if ( "4_4" !== column_type ) { %%>
						<%%= counter + " " %%>
					<%% } %%>
					%16$s:
				</label>

				<div class="et-pb-option-container">
					<select name="et_pb_parallax_method_<%%= counter %%>" id="et_pb_parallax_method_<%%= counter %%>" class="et-pb-main-setting">
						<option value="off">%17$s</option>
						<option value="on" <%%= current_value_parallax_method %%>>%18$s</option>
					</select>
				</div> <!-- .et-pb-option-container -->
			</div>

			<div class="et-pb-option">
				<label for="et_pb_background_color_<%%= counter %%>">
					%1$s
					<%% if ( "4_4" !== column_type ) { %%>
						<%%= counter + " " %%>
					<%% } %%>
					%6$s:
				</label>
				<div class="et-pb-option-container">
					<input id="et_pb_background_color_<%%= counter %%>" class="et-pb-color-picker-hex et-pb-color-picker-hex-alpha wp-color-picker" type="text" data-alpha="true" placeholder="%7$s" value="<%%= current_value_bg %%>" />
							<span class="et-pb-reset-setting" style="display: none;"></span>
				</div> <!-- .et-pb-option-container -->
			</div> <!-- .et-pb-option -->

			<div class="et-pb-option">
				<label for="et_pb_padding_<%%= counter %%>">
					%1$s
					<%% if ( "4_4" !== column_type ) { %%>
						<%%= counter + " " %%>
					<%% } %%>
					%8$s:
				</label>
				<div class="et-pb-option-container">
					<div class="et_custom_margin_padding">
						<label>
							%9$s
							<input type="text" class="medium-text et_custom_margin_top et-pb-validate-unit" id="et_pb_padding_top_<%%= counter %%>" name="et_pb_padding_top_<%%= counter %%>" value="<%%= current_value_pt %%>"></label>
						<label>
						<label>
							%10$s
							<input type="text" class="medium-text et_custom_margin_right et-pb-validate-unit" id="et_pb_padding_right_<%%= counter %%>" name="et_pb_padding_right_<%%= counter %%>" value="<%%= current_value_pr %%>"></label>
						<label>
						<label>
							%11$s
							<input type="text" class="medium-text et_custom_margin_bottom et-pb-validate-unit" id="et_pb_padding_bottom_<%%= counter %%>" name="et_pb_padding_bottom_<%%= counter %%>" value="<%%= current_value_pb %%>"></label>
						<label>
						<label>
							%12$s
							<input type="text" class="medium-text et_custom_margin_left et-pb-validate-unit" id="et_pb_padding_left_<%%= counter %%>" name="et_pb_padding_left_<%%= counter %%>" value="<%%= current_value_pl %%>"></label>
						<label>
					</div> <!-- .et_custom_margin_padding -->
					<span class="et-pb-reset-setting" style="display: none;"></span>
				</div><!-- .et-pb-option-container -->
			</div><!-- .et-pb-option -->

			<%% counter++;
			}); %%>',
			esc_html__( 'Column', 'et_builder' ),
			esc_html__( 'Background Image', 'et_builder' ),
			esc_html__( 'Upload an image', 'et_builder' ),
			esc_html__( 'Choose a Background Image', 'et_builder' ),
			esc_html__( 'Set As Background', 'et_builder' ), // #5
			esc_html__( 'Background Color', 'et_builder' ),
			esc_html__( 'Hex Value', 'et_builder' ),
			esc_html__( 'Padding', 'et_builder' ),
			esc_html__( 'Top', 'et_builder' ),
			esc_html__( 'Right', 'et_builder' ), // #10
			esc_html__( 'Bottom', 'et_builder' ),
			esc_html__( 'Left', 'et_builder' ),
			esc_html__( 'Parallax Effect', 'et_builder' ),
			esc_html__( 'Yes', 'et_builder' ),
			esc_html__( 'No', 'et_builder' ), // #15
			esc_html__( 'Parallax Method', 'et_builder' ),
			esc_html__( 'CSS', 'et_builder' ),
			esc_html__( 'True Parallax', 'et_builder' )
		);

		return $output;
	}
}