<?php

class ET_Builder_Section extends ET_Builder_Structure_Element {
	function init() {
		$this->name = __( 'Section', 'et_builder' );
		$this->slug = 'et_pb_section';

		$this->whitelisted_fields = array(
			'background_image',
			'transparent_background',
			'background_color',
			'background_video_mp4',
			'background_video_webm',
			'background_video_width',
			'background_video_height',
			'allow_player_pause',
			'inner_shadow',
			'parallax',
			'parallax_method',
			'custom_padding',
			'padding_mobile',
			'module_id',
			'module_class',
			'make_fullwidth',
			'use_custom_width',
			'width_unit',
			'custom_width_px',
			'custom_width_percent',
			'make_equal',
			'use_custom_gutter',
			'gutter_width',
			'columns',
			'fullwidth',
			'specialty',
			'background_color_1',
			'background_color_2',
			'background_color_3',
			'bg_img_1',
			'bg_img_2',
			'bg_img_3',
			'padding_top_1',
			'padding_right_1',
			'padding_bottom_1',
			'padding_left_1',
			'padding_top_2',
			'padding_right_2',
			'padding_bottom_2',
			'padding_left_2',
			'padding_top_3',
			'padding_right_3',
			'padding_bottom_3',
			'padding_left_3',
			'admin_label',
		);

		$this->fields_defaults = array(
			'transparent_background' => array( 'default' ),
			'background_color'       => array( '', 'only_default_setting' ),
			'allow_player_pause'     => array( 'off' ),
			'inner_shadow'           => array( 'off' ),
			'parallax'               => array( 'off' ),
			'parallax_method'        => array( 'on' ),
			'padding_mobile'         => array( 'off' ),
			'make_fullwidth'         => array( 'off' ),
			'use_custom_width'       => array( 'off' ),
			'width_unit'             => array( 'off' ),
			'custom_width_px'        => array( '1080px', 'only_default_setting' ),
			'custom_width_percent'   => array( '80%', 'only_default_setting' ),
			'make_equal'             => array( 'off' ),
			'use_custom_gutter'      => array( 'off' ),
			'gutter_width'           => array( '3', 'only_default_setting' ),
			'fullwidth'              => array( 'off' ),
			'specialty'              => array( 'off' ),
			'background_color_1'     => array( '' ),
			'background_color_2'     => array( '' ),
			'background_color_3'     => array( '' ),
			'bg_img_1'               => array( '' ),
			'bg_img_2'               => array( '' ),
			'bg_img_3'               => array( '' ),
			'padding_top_1'          => array( '' ),
			'padding_right_1'        => array( '' ),
			'padding_bottom_1'       => array( '' ),
			'padding_left_1'         => array( '' ),
			'padding_top_2'          => array( '' ),
			'padding_right_2'        => array( '' ),
			'padding_bottom_2'       => array( '' ),
			'padding_left_2'         => array( '' ),
			'padding_top_3'          => array( '' ),
			'padding_right_3'        => array( '' ),
			'padding_bottom_3'       => array( '' ),
			'padding_left_3'         => array( '' ),
		);
	}

	function get_fields() {
		$fields = array(
			'background_image' => array(
				'label'              => __( 'Background Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Image', 'et_builder' ),
				'update_text'        => __( 'Set As Background', 'et_builder' ),
				'description'        => __( 'If defined, this image will be used as the background for this module. To remove a background image, simply delete the URL from the settings field.', 'et_builder' ),
			),
			'transparent_background' => array(
				'label'             => __( 'Transparent Background Color', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'color_option',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_background_color',
				),
				'description'       => __( 'Enabling this option will remove the background color of this section, allowing the website background color or background image to show through.', 'et_builder' ),
			),
			'background_color' => array(
				'label'           => __( 'Background Color', 'et_builder' ),
				'type'            => 'color',
				'depends_show_if' => 'off',
				'description'     => __( 'Define a custom background color for your module, or leave blank to use the default color.', 'et_builder' ),
				'additional_code' => '<span class="et-pb-reset-setting reset-default-color" style="display: none;"></span>',
			),
			'background_video_mp4' => array(
				'label'              => __( 'Background Video MP4', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Video MP4 File', 'et_builder' ),
				'update_text'        => __( 'Set As Background Video', 'et_builder' ),
				'description'        => __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .MP4 version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ),
			),
			'background_video_webm' => array(
				'label'              => __( 'Background Video Webm', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Video WEBM File', 'et_builder' ),
				'update_text'        => __( 'Set As Background Video', 'et_builder' ),
				'description'        => __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .WEBM version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ),
			),
			'background_video_width' => array(
				'label'           => __( 'Background Video Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'In order for videos to be sized correctly, you must input the exact width (in pixels) of your video here.', 'et_builder' ),
				'validate_unit'   => true,
			),
			'background_video_height' => array(
				'label'           => __( 'Background Video Height', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'In order for videos to be sized correctly, you must input the exact height (in pixels) of your video here.', 'et_builder' ),
				'validate_unit'   => true,
			),
			'allow_player_pause' => array(
				'label'           => __( 'Pause Video', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description'       => __( 'Allow video to be paused by other players when they begin playing', 'et_builder' ),
			),
			'inner_shadow' => array(
				'label'           => __( 'Show Inner Shadow', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description'       => __( 'Here you can select whether or not your section has an inner shadow. This can look great when you have colored backgrounds or background images.', 'et_builder' ),
			),
			'parallax' => array(
				'label'             => __( 'Use Parallax Effect', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_parallax_method',
				),
				'description'       => __( 'If enabled, your background image will stay fixed as your scroll, creating a fun parallax-like effect.', 'et_builder' ),
			),
			'parallax_method' => array(
				'label'             => __( 'Parallax Method', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'off'  => __( 'CSS', 'et_builder' ),
					'on'   => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Define the method, used for the parallax effect.', 'et_builder' ),
			),
			'custom_padding' => array(
				'label'           => __( 'Custom Padding', 'et_builder' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'description'     => __( 'Adjust padding to specific values, or leave blank to use the default padding.', 'et_builder' ),
			),
			'padding_mobile' => array(
				'label'             => __( 'Keep Custom Padding on Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description'       => __( 'Allow custom padding to be retained on mobile screens', 'et_builder' ),
			),
			'module_id' => array(
				'label'             => __( 'CSS ID', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'             => __( 'CSS Class', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the section in the builder for easy identification when collapsed.', 'et_builder' ),
			),
			'make_fullwidth' => array(
				'label'             => __( 'Make This Section Fullwidth', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
				'tab_slug' => 'advanced',
			),
			'use_custom_width' => array(
				'label'             => __( 'Use Custom Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_make_fullwidth',
					'#et_pb_custom_width',
					'#et_pb_width_unit',
				),
				'tab_slug' => 'advanced',
			),
			'width_unit' => array(
				'label'             => __( 'Unit', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'px', 'et_builder' ),
					'off' => '%',
				),
				'button_options' => array(
						'button_type'       => 'equal',
				),
				'depends_show_if' => 'on',
				'affects'           => array(
					'#et_pb_custom_width_px',
					'#et_pb_custom_width_percent',
				),
				'tab_slug' => 'advanced',
			),
			'custom_width_px' => array(
				'label'           => __( 'Custom Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'depends_show_if' => 'on',
				'range_settings'  => array(
					'min'  => 500,
					'max'  => 2600,
					'step' => 1,
				),
				'tab_slug' => 'advanced',
			),
			'custom_width_percent' => array(
				'label'           => __( 'Custom Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'depends_show_if' => 'off',
				'range_settings'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'tab_slug' => 'advanced',
			),
			'make_equal' => array(
				'label'             => __( 'Equalize Column Heights', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'use_custom_gutter' => array(
				'label'             => __( 'Use Custom Gutter Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_gutter_width',
				),
				'tab_slug' => 'advanced',
			),
			'gutter_width' => array(
				'label'           => __( 'Gutter Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => 1,
					'max'  => 4,
					'step' => 1,
				),
				'depends_show_if' => 'on',
				'tab_slug'        => 'advanced',
			),
			'columns' => array(
				'type'            => 'column_settings',
				'option_category' => 'configuration',
				'tab_slug'        => 'advanced',
			),
			'fullwidth' => array(
				'type' => 'skip',
			),
			'specialty' => array(
				'type' => 'skip',
			),
			'background_color_1' => array(
				'type' => 'skip',
			),
			'background_color_2' => array(
				'type' => 'skip',
			),
			'background_color_3' => array(
				'type' => 'skip',
			),
			'bg_img_1' => array(
				'type' => 'skip',
			),
			'bg_img_2' => array(
				'type' => 'skip',
			),
			'bg_img_3' => array(
				'type' => 'skip',
			),
			'padding_top_1' => array(
				'type' => 'skip',
			),
			'padding_right_1' => array(
				'type' => 'skip',
			),
			'padding_bottom_1' => array(
				'type' => 'skip',
			),
			'padding_left_1' => array(
				'type' => 'skip',
			),
			'padding_top_2' => array(
				'type' => 'skip',
			),
			'padding_right_2' => array(
				'type' => 'skip',
			),
			'padding_bottom_2' => array(
				'type' => 'skip',
			),
			'padding_left_2' => array(
				'type' => 'skip',
			),
			'padding_top_3' => array(
				'type' => 'skip',
			),
			'padding_right_3' => array(
				'type' => 'skip',
			),
			'padding_bottom_3' => array(
				'type' => 'skip',
			),
			'padding_left_3' => array(
				'type' => 'skip',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$background_image        = $this->shortcode_atts['background_image'];
		$background_color        = $this->shortcode_atts['background_color'];
		$background_video_mp4    = $this->shortcode_atts['background_video_mp4'];
		$background_video_webm   = $this->shortcode_atts['background_video_webm'];
		$background_video_width  = $this->shortcode_atts['background_video_width'];
		$background_video_height = $this->shortcode_atts['background_video_height'];
		$allow_player_pause      = $this->shortcode_atts['allow_player_pause'];
		$inner_shadow            = $this->shortcode_atts['inner_shadow'];
		$parallax                = $this->shortcode_atts['parallax'];
		$parallax_method         = $this->shortcode_atts['parallax_method'];
		$fullwidth               = $this->shortcode_atts['fullwidth'];
		$specialty               = $this->shortcode_atts['specialty'];
		$transparent_background  = $this->shortcode_atts['transparent_background'];
		$custom_padding          = $this->shortcode_atts['custom_padding'];
		$padding_mobile          = $this->shortcode_atts['padding_mobile'];
		$background_color_1      = $this->shortcode_atts['background_color_1'];
		$background_color_2      = $this->shortcode_atts['background_color_2'];
		$background_color_3      = $this->shortcode_atts['background_color_3'];
		$bg_img_1                = $this->shortcode_atts['bg_img_1'];
		$bg_img_2                = $this->shortcode_atts['bg_img_2'];
		$bg_img_3                = $this->shortcode_atts['bg_img_3'];
		$padding_top_1           = $this->shortcode_atts['padding_top_1'];
		$padding_right_1         = $this->shortcode_atts['padding_right_1'];
		$padding_bottom_1        = $this->shortcode_atts['padding_bottom_1'];
		$padding_left_1          = $this->shortcode_atts['padding_left_1'];
		$padding_top_2           = $this->shortcode_atts['padding_top_2'];
		$padding_right_2         = $this->shortcode_atts['padding_right_2'];
		$padding_bottom_2        = $this->shortcode_atts['padding_bottom_2'];
		$padding_left_2          = $this->shortcode_atts['padding_left_2'];
		$padding_top_3           = $this->shortcode_atts['padding_top_3'];
		$padding_right_3         = $this->shortcode_atts['padding_right_3'];
		$padding_bottom_3        = $this->shortcode_atts['padding_bottom_3'];
		$padding_left_3          = $this->shortcode_atts['padding_left_3'];
		$gutter_width            = $this->shortcode_atts['gutter_width'];
		$use_custom_width        = $this->shortcode_atts['use_custom_width'];
		$custom_width_px         = $this->shortcode_atts['custom_width_px'];
		$custom_width_percent    = $this->shortcode_atts['custom_width_percent'];
		$width_unit              = $this->shortcode_atts['width_unit'];
		$make_equal              = $this->shortcode_atts['make_equal'];
		$make_fullwidth          = $this->shortcode_atts['make_fullwidth'];
		$global_module           = $this->shortcode_atts['global_module'];
		$use_custom_gutter       = $this->shortcode_atts['use_custom_gutter'];

		if ( '' !== $global_module ) {
			$global_content = et_pb_load_global_module( $global_module );

			if ( '' !== $global_content ) {
				return do_shortcode( $global_content );
			}
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
		$gutter_class = '';

		if ( 'on' === $specialty ) {
			global $et_pb_column_backgrounds, $et_pb_column_paddings, $et_pb_columns_counter;

			$module_class .= 'on' === $make_equal ? ' et_pb_equal_columns' : '';

			if ( 'on' === $use_custom_gutter ) {
				$gutter_width = '0' === $gutter_width ? '1' : $gutter_width; // set the gutter to 1 if 0 entered by user
				$gutter_class .= ' et_pb_gutters' . $gutter_width;
			}

			$et_pb_columns_counter = 0;
			$et_pb_column_backgrounds = array(
				array( $background_color_1, $bg_img_1 ),
				array( $background_color_2, $bg_img_2 ),
				array( $background_color_3, $bg_img_3 ),
			);

			$et_pb_column_paddings = array(
				array(
					'padding-top'    => $padding_top_1,
					'padding-right'  => $padding_right_1,
					'padding-bottom' => $padding_bottom_1,
					'padding-left'   => $padding_left_1
				),
				array(
					'padding-top'    => $padding_top_2,
					'padding-right'  => $padding_right_2,
					'padding-bottom' => $padding_bottom_2,
					'padding-left'   => $padding_left_2
				),
				array(
					'padding-top'    => $padding_top_3,
					'padding-right'  => $padding_right_3,
					'padding-bottom' => $padding_bottom_3,
					'padding-left'   => $padding_left_3
				),
			);

			if ( 'on' === $make_fullwidth && 'off' === $use_custom_width ) {
				$module_class .= ' et_pb_specialty_fullwidth';
			}

			if ( 'on' === $use_custom_width ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% > .et_pb_row',
					'declaration' => sprintf(
						'max-width:%1$s !important;',
						'on' === $width_unit ? esc_attr( $custom_width_px ) : esc_attr( $custom_width_percent )
					),
				) );
			}
		}

		$background_video = '';

		if ( '' !== $background_video_mp4 || '' !== $background_video_webm ) {
			$background_video = sprintf(
				'<div class="et_pb_section_video_bg%2$s">
					%1$s
				</div>',
				do_shortcode( sprintf( '
					<video loop="loop" autoplay="autoplay"%3$s%4$s>
						%1$s
						%2$s
					</video>',
					( '' !== $background_video_mp4 ? sprintf( '<source type="video/mp4" src="%s" />', esc_attr( $background_video_mp4 ) ) : '' ),
					( '' !== $background_video_webm ? sprintf( '<source type="video/webm" src="%s" />', esc_attr( $background_video_webm ) ) : '' ),
					( '' !== $background_video_width ? sprintf( ' width="%s"', esc_attr( $background_video_width ) ) : '' ),
					( '' !== $background_video_height ? sprintf( ' height="%s"', esc_attr( $background_video_height ) ) : '' )
				) ),
				( 'on' === $allow_player_pause ? ' et_pb_allow_player_pause' : '' )
			);

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		// set the correct default value for $transparent_background option if plugin activated.
		if ( et_is_builder_plugin_active() && 'default' === $transparent_background ) {
			$transparent_background = '' !== $background_color ? 'off' : 'on';
		} elseif ( 'default' === $transparent_background ) {
			$transparent_background = 'off';
		}

		if ( '' !== $background_color && 'off' === $transparent_background ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'background-color:%s;',
					esc_attr( $background_color )
				),
			) );
		}

		if ( '' !== $background_image && 'on' !== $parallax ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'background-image:url(%s);',
					esc_attr( $background_image )
				),
			) );
		}

		$padding_values = explode( '|', $custom_padding );

		if ( ! empty( $padding_values ) ) {
			// old version of sections supports only top and bottom padding, so we need to handle it along with the full padding in the recent version
			if ( 2 === count( $padding_values ) ) {
				$padding_settings = array(
					'top' => isset( $padding_values[0] ) ? $padding_values[0] : '',
					'bottom' => isset( $padding_values[1] ) ? $padding_values[1] : '',
				);
			} else {
				$padding_settings = array(
					'top' => isset( $padding_values[0] ) ? $padding_values[0] : '',
					'right' => isset( $padding_values[1] ) ? $padding_values[1] : '',
					'bottom' => isset( $padding_values[2] ) ? $padding_values[2] : '',
					'left' => isset( $padding_values[3] ) ? $padding_values[3] : '',
				);
			}

			foreach( $padding_settings as $padding_side => $value ) {
				if ( '' !== $value ) {
					$element_style = array(
						'selector'    => '%%order_class%%',
						'declaration' => sprintf(
							'padding-%1$s: %2$s;',
							esc_html( $padding_side ),
							esc_html( $value )
						),
					);

					if ( 'on' !== $padding_mobile ) {
						$element_style['media_query'] = ET_Builder_Element::get_media_query( 'min_width_981' );
					}

					ET_Builder_Element::set_style( $function_name, $element_style );
				}
			}
		}

		if ( '' !== $background_video_mp4 || '' !== $background_video_webm || ( '' !== $background_color && 'off' === $transparent_background ) || '' !== $background_image ) {
			$module_class .= ' et_pb_with_background';
		}

		$output = sprintf(
			'<div%7$s class="et_pb_section%3$s%4$s%5$s%6$s%8$s%12$s%13$s">
				%11$s
				%9$s
					%2$s
					%1$s
				%10$s
			</div> <!-- .et_pb_section -->',
			do_shortcode( et_pb_fix_shortcodes( $content ) ),
			$background_video,
			( '' !== $background_video ? ' et_pb_section_video et_pb_preload' : '' ),
			( ( 'off' !== $inner_shadow && ! ( '' !== $background_image && 'on' === $parallax && 'off' === $parallax_method ) ) ? ' et_pb_inner_shadow' : '' ),
			( 'on' === $parallax ? ' et_pb_section_parallax' : '' ),
			( 'off' !== $fullwidth ? ' et_pb_fullwidth_section' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( 'on' === $specialty ?
				sprintf( '<div class="et_pb_row%1$s">', $gutter_class )
				: '' ),
			( 'on' === $specialty ? '</div> <!-- .et_pb_row -->' : '' ),
			( '' !== $background_image && 'on' === $parallax
				? sprintf(
					'<div class="et_parallax_bg%2$s%3$s" style="background-image: url(%1$s);"></div>',
					esc_attr( $background_image ),
					( 'off' === $parallax_method ? ' et_pb_parallax_css' : '' ),
					( ( 'off' !== $inner_shadow && 'off' === $parallax_method ) ? ' et_pb_inner_shadow' : '' )
				)
				: ''
			),
			( 'on' === $specialty ? ' et_section_specialty' : ' et_section_regular' ),
			( 'on' === $transparent_background ? ' et_section_transparent' : '' )
		);

		return $output;

	}

}
new ET_Builder_Section;

class ET_Builder_Row extends ET_Builder_Structure_Element {
	function init() {
		$this->name = __( 'Row', 'et_builder' );
		$this->slug = 'et_pb_row';

		$this->whitelisted_fields = array(
			'make_fullwidth',
			'use_custom_width',
			'width_unit',
			'custom_width_px',
			'custom_width_percent',
			'use_custom_gutter',
			'gutter_width',
			'custom_padding',
			'custom_margin',
			'padding_mobile',
			'column_padding_mobile',
			'module_id',
			'module_class',
			'background_image',
			'background_color',
			'background_video_mp4',
			'background_video_webm',
			'background_video_width',
			'background_video_height',
			'allow_player_pause',
			'parallax',
			'parallax_method',
			'make_equal',
			'columns',
			'background_color_1',
			'background_color_2',
			'background_color_3',
			'background_color_4',
			'bg_img_1',
			'bg_img_2',
			'bg_img_3',
			'bg_img_4',
			'padding_top_1',
			'padding_right_1',
			'padding_bottom_1',
			'padding_left_1',
			'padding_top_2',
			'padding_right_2',
			'padding_bottom_2',
			'padding_left_2',
			'padding_top_3',
			'padding_right_3',
			'padding_bottom_3',
			'padding_left_3',
			'padding_top_4',
			'padding_right_4',
			'padding_bottom_4',
			'padding_left_4',
			'admin_label',
			'parallax_1',
			'parallax_method_1',
			'parallax_2',
			'parallax_method_2',
			'parallax_3',
			'parallax_method_3',
			'parallax_4',
			'parallax_method_4',
		);

		$this->fields_defaults = array(
			'make_fullwidth'        => array( 'off' ),
			'use_custom_width'      => array( 'off' ),
			'width_unit'            => array( 'off' ),
			'custom_width_px'       => array( '1080px', 'only_default_setting' ),
			'custom_width_percent'  => array( '80%', 'only_default_setting' ),
			'use_custom_gutter'     => array( 'off' ),
			'gutter_width'          => array( '3', 'only_default_setting' ),
			'padding_mobile'        => array( 'off' ),
			'column_padding_mobile' => array( 'on' ),
			'background_color'      => array( '', 'only_default_setting' ),
			'allow_player_pause'    => array( 'off' ),
			'parallax'              => array( 'off' ),
			'parallax_method'       => array( 'on' ),
			'make_equal'            => array( 'off' ),
			'background_color_1'    => array( '' ),
			'background_color_2'    => array( '' ),
			'background_color_3'    => array( '' ),
			'background_color_4'    => array( '' ),
			'bg_img_1'              => array( '' ),
			'bg_img_2'              => array( '' ),
			'bg_img_3'              => array( '' ),
			'bg_img_4'              => array( '' ),
			'padding_top_1'         => array( '' ),
			'padding_right_1'       => array( '' ),
			'padding_bottom_1'      => array( '' ),
			'padding_left_1'        => array( '' ),
			'padding_top_2'         => array( '' ),
			'padding_right_2'       => array( '' ),
			'padding_bottom_2'      => array( '' ),
			'padding_left_2'        => array( '' ),
			'padding_top_3'         => array( '' ),
			'padding_right_3'       => array( '' ),
			'padding_bottom_3'      => array( '' ),
			'padding_left_3'        => array( '' ),
			'padding_top_4'         => array( '' ),
			'padding_right_4'       => array( '' ),
			'padding_bottom_4'      => array( '' ),
			'padding_left_4'        => array( '' ),
			'parallax_1'            => array( 'off' ),
			'parallax_method_1'     => array( 'on' ),
			'parallax_2'            => array( 'off' ),
			'parallax_method_2'     => array( 'on' ),
			'parallax_3'            => array( 'off' ),
			'parallax_method_3'     => array( 'on' ),
			'parallax_4'            => array( 'off' ),
			'parallax_method_4'     => array( 'on' ),
		);
	}

	function get_fields() {
		$fields = array(
			'make_fullwidth' => array(
				'label'             => __( 'Make This Row Fullwidth', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
				'description'       => __( 'Enable this option to extend the width of this row to the edge of the browser window.', 'et_builder' ),
			),
			'use_custom_width' => array(
				'label'             => __( 'Use Custom Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_make_fullwidth',
					'#et_pb_custom_width',
					'#et_pb_width_unit',
				),
				'description'       => __( 'Change to Yes if you would like to adjust the width of this row to a non-standard width.', 'et_builder' ),
			),
			'width_unit' => array(
				'label'             => __( 'Unit', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'px', 'et_builder' ),
					'off' => '%',
				),
				'button_options' => array(
						'button_type'       => 'equal',
				),
				'depends_show_if' => 'on',
				'affects'           => array(
					'#et_pb_custom_width_px',
					'#et_pb_custom_width_percent',
				),
			),
			'custom_width_px' => array(
				'label'           => __( 'Custom Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'depends_show_if' => 'on',
				'range_settings'  => array(
					'min'  => 500,
					'max'  => 2600,
					'step' => 1,
				),
				'description'     => __( 'Define custom width for this Row', 'et_builder' ),
			),
			'custom_width_percent' => array(
				'label'           => __( 'Custom Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'depends_show_if' => 'off',
				'range_settings'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'description'     => __( 'Define custom width for this Row', 'et_builder' ),
			),
			'use_custom_gutter' => array(
				'label'             => __( 'Use Custom Gutter Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_gutter_width',
				),
				'description'       => __( 'Enable this option to define custom gutter width for this row.', 'et_builder' ),
			),
			'gutter_width' => array(
				'label'           => __( 'Gutter Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => 1,
					'max'  => 4,
					'step' => 1,
				),
				'depends_show_if' => 'on',
				'description'     => __( 'Adjust the spacing between each column in this row.', 'et_builder' ),
			),
			'custom_padding' => array(
				'label'           => __( 'Custom Padding', 'et_builder' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'description'     => __( 'Adjust padding to specific values, or leave blank to use the default padding.', 'et_builder' ),
			),
			'padding_mobile' => array(
				'label'             => __( 'Keep Custom Padding on Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description'       => __( 'Allow custom padding to be retained on mobile screens', 'et_builder' ),
			),
			'module_id' => array(
				'label'             => __( 'CSS ID', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'             => __( 'CSS Class', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'custom_margin' => array(
				'label'           => __( 'Custom Margin', 'et_builder' ),
				'type'            => 'custom_margin',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
			),
			'background_image' => array(
				'label'              => __( 'Background Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'configuration',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Image', 'et_builder' ),
				'update_text'        => __( 'Set As Background', 'et_builder' ),
				'tab_slug'           => 'advanced',
			),
			'background_color' => array(
				'label'        => __( 'Background Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'background_video_mp4' => array(
				'label'              => __( 'Background Video MP4', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Video MP4 File', 'et_builder' ),
				'update_text'        => __( 'Set As Background Video', 'et_builder' ),
				'tab_slug'           => 'advanced',
			),
			'background_video_webm' => array(
				'label'              => __( 'Background Video Webm', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Video WEBM File', 'et_builder' ),
				'update_text'        => __( 'Set As Background Video', 'et_builder' ),
				'tab_slug'           => 'advanced',
			),
			'background_video_width' => array(
				'label'           => __( 'Background Video Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'background_video_height' => array(
				'label'           => __( 'Background Video Height', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'allow_player_pause' => array(
				'label'           => __( 'Pause Video', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'parallax' => array(
				'label'             => __( 'Use Parallax Effect', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_parallax_method',
				),
				'tab_slug'          => 'advanced',
			),
			'parallax_method' => array(
				'label'             => __( 'Parallax Method', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'CSS', 'et_builder' ),
					'on'  => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'tab_slug'          => 'advanced',
			),
			'make_equal' => array(
				'label'             => __( 'Equalize Column Heights', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'columns' => array(
				'type'            => 'column_settings',
				'option_category' => 'configuration',
				'tab_slug'        => 'advanced',
			),

			'column_padding_mobile' => array(
				'label'             => __( 'Keep Column Padding on Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'tab_slug'          => 'advanced',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the row in the builder for easy identification when collapsed.', 'et_builder' ),
			),
			'background_color_1' => array(
				'type' => 'skip',
			),
			'background_color_2' => array(
				'type' => 'skip',
			),
			'background_color_3' => array(
				'type' => 'skip',
			),
			'background_color_4' => array(
				'type' => 'skip',
			),
			'bg_img_1' => array(
				'type' => 'skip',
			),
			'bg_img_2' => array(
				'type' => 'skip',
			),
			'bg_img_3' => array(
				'type' => 'skip',
			),
			'bg_img_4' => array(
				'type' => 'skip',
			),
			'padding_top_1' => array(
				'type' => 'skip',
			),
			'padding_right_1' => array(
				'type' => 'skip',
			),
			'padding_bottom_1' => array(
				'type' => 'skip',
			),
			'padding_left_1' => array(
				'type' => 'skip',
			),
			'padding_top_2' => array(
				'type' => 'skip',
			),
			'padding_right_2' => array(
				'type' => 'skip',
			),
			'padding_bottom_2' => array(
				'type' => 'skip',
			),
			'padding_left_2' => array(
				'type' => 'skip',
			),
			'padding_top_3' => array(
				'type' => 'skip',
			),
			'padding_right_3' => array(
				'type' => 'skip',
			),
			'padding_bottom_3' => array(
				'type' => 'skip',
			),
			'padding_left_3' => array(
				'type' => 'skip',
			),
			'padding_top_4' => array(
				'type' => 'skip',
			),
			'padding_right_4' => array(
				'type' => 'skip',
			),
			'padding_bottom_4' => array(
				'type' => 'skip',
			),
			'padding_left_4' => array(
				'type' => 'skip',
			),
			'parallax_1' => array(
				'type' => 'skip',
			),
			'parallax_method_1' => array(
				'type' => 'skip',
			),
			'parallax_2' => array(
				'type' => 'skip',
			),
			'parallax_method_2' => array(
				'type' => 'skip',
			),
			'parallax_3' => array(
				'type' => 'skip',
			),
			'parallax_method_3' => array(
				'type' => 'skip',
			),
			'parallax_4' => array(
				'type' => 'skip',
			),
			'parallax_method_4' => array(
				'type' => 'skip',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$custom_padding          = $this->shortcode_atts['custom_padding'];
		$custom_margin           = $this->shortcode_atts['custom_margin'];
		$padding_mobile          = $this->shortcode_atts['padding_mobile'];
		$column_padding_mobile   = $this->shortcode_atts['column_padding_mobile'];
		$make_fullwidth          = $this->shortcode_atts['make_fullwidth'];
		$make_equal              = $this->shortcode_atts['make_equal'];
		$background_image        = $this->shortcode_atts['background_image'];
		$background_color        = $this->shortcode_atts['background_color'];
		$background_video_mp4    = $this->shortcode_atts['background_video_mp4'];
		$background_video_webm   = $this->shortcode_atts['background_video_webm'];
		$background_video_width  = $this->shortcode_atts['background_video_width'];
		$background_video_height = $this->shortcode_atts['background_video_height'];
		$allow_player_pause      = $this->shortcode_atts['allow_player_pause'];
		$parallax                = $this->shortcode_atts['parallax'];
		$parallax_method         = $this->shortcode_atts['parallax_method'];
		$background_color_1      = $this->shortcode_atts['background_color_1'];
		$background_color_2      = $this->shortcode_atts['background_color_2'];
		$background_color_3      = $this->shortcode_atts['background_color_3'];
		$background_color_4      = $this->shortcode_atts['background_color_4'];
		$bg_img_1                = $this->shortcode_atts['bg_img_1'];
		$bg_img_2                = $this->shortcode_atts['bg_img_2'];
		$bg_img_3                = $this->shortcode_atts['bg_img_3'];
		$bg_img_4                = $this->shortcode_atts['bg_img_4'];
		$padding_top_1           = $this->shortcode_atts['padding_top_1'];
		$padding_right_1         = $this->shortcode_atts['padding_right_1'];
		$padding_bottom_1        = $this->shortcode_atts['padding_bottom_1'];
		$padding_left_1          = $this->shortcode_atts['padding_left_1'];
		$padding_top_2           = $this->shortcode_atts['padding_top_2'];
		$padding_right_2         = $this->shortcode_atts['padding_right_2'];
		$padding_bottom_2        = $this->shortcode_atts['padding_bottom_2'];
		$padding_left_2          = $this->shortcode_atts['padding_left_2'];
		$padding_top_3           = $this->shortcode_atts['padding_top_3'];
		$padding_right_3         = $this->shortcode_atts['padding_right_3'];
		$padding_bottom_3        = $this->shortcode_atts['padding_bottom_3'];
		$padding_left_3          = $this->shortcode_atts['padding_left_3'];
		$padding_top_4           = $this->shortcode_atts['padding_top_4'];
		$padding_right_4         = $this->shortcode_atts['padding_right_4'];
		$padding_bottom_4        = $this->shortcode_atts['padding_bottom_4'];
		$padding_left_4          = $this->shortcode_atts['padding_left_4'];
		$gutter_width            = $this->shortcode_atts['gutter_width'];
		$use_custom_width        = $this->shortcode_atts['use_custom_width'];
		$custom_width_px         = $this->shortcode_atts['custom_width_px'];
		$custom_width_percent    = $this->shortcode_atts['custom_width_percent'];
		$width_unit              = $this->shortcode_atts['width_unit'];
		$global_module           = $this->shortcode_atts['global_module'];
		$use_custom_gutter       = $this->shortcode_atts['use_custom_gutter'];
		$parallax_1              = $this->shortcode_atts['parallax_1'];
		$parallax_method_1       = $this->shortcode_atts['parallax_method_1'];
		$parallax_2              = $this->shortcode_atts['parallax_2'];
		$parallax_method_2       = $this->shortcode_atts['parallax_method_2'];
		$parallax_3              = $this->shortcode_atts['parallax_3'];
		$parallax_method_3       = $this->shortcode_atts['parallax_method_3'];
		$parallax_4              = $this->shortcode_atts['parallax_4'];
		$parallax_method_4       = $this->shortcode_atts['parallax_method_4'];

		global $et_pb_column_backgrounds, $et_pb_column_paddings, $et_pb_columns_counter, $keep_column_padding_mobile, $et_pb_column_parallax;

		$keep_column_padding_mobile = $column_padding_mobile;

		if ( '' !== $global_module ) {
			$global_content = et_pb_load_global_module( $global_module, $function_name );

			if ( '' !== $global_content ) {
				return do_shortcode( $global_content );
			}
		}

		$et_pb_columns_counter = 0;
		$et_pb_column_backgrounds = array(
			array( $background_color_1, $bg_img_1 ),
			array( $background_color_2, $bg_img_2 ),
			array( $background_color_3, $bg_img_3 ),
			array( $background_color_4, $bg_img_4 ),
		);
		$et_pb_column_paddings = array(
			array(
				'padding-top'    => $padding_top_1,
				'padding-right'  => $padding_right_1,
				'padding-bottom' => $padding_bottom_1,
				'padding-left'   => $padding_left_1
			),
			array(
				'padding-top'    => $padding_top_2,
				'padding-right'  => $padding_right_2,
				'padding-bottom' => $padding_bottom_2,
				'padding-left'   => $padding_left_2
			),
			array(
				'padding-top'    => $padding_top_3,
				'padding-right'  => $padding_right_3,
				'padding-bottom' => $padding_bottom_3,
				'padding-left'   => $padding_left_3
			),
			array(
				'padding-top'    => $padding_top_4,
				'padding-right'  => $padding_right_4,
				'padding-bottom' => $padding_bottom_4,
				'padding-left'   => $padding_left_4
			),
		);

		$et_pb_column_parallax = array(
			array( $parallax_1, $parallax_method_1 ),
			array( $parallax_2, $parallax_method_2 ),
			array( $parallax_3, $parallax_method_3 ),
			array( $parallax_4, $parallax_method_4 ),
		);

		$background_video = '';

		$module_class .= ' et_pb_row';

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$inner_content = do_shortcode( et_pb_fix_shortcodes( $content ) );
		$module_class .= '' == trim( $inner_content ) ? ' et_pb_row_empty' : '';

		$module_class .= 'on' === $make_equal ? ' et_pb_equal_columns' : '';

		if ( 'on' === $use_custom_gutter ) {
			$gutter_width = '0' === $gutter_width ? '1' : $gutter_width; // set the gutter width to 1 if 0 entered by user
			$module_class .= ' et_pb_gutters' . $gutter_width;
		}


		$padding_values = explode( '|', $custom_padding );
		$margin_values = explode( '|', $custom_margin );

		if ( ! empty( $padding_values ) ) {
			// old version of Rows support only top and bottom padding, so we need to handle it along with the full padding in the recent version
			if ( 2 === count( $padding_values ) ) {
				$padding_settings = array(
					'top' => isset( $padding_values[0] ) ? $padding_values[0] : '',
					'bottom' => isset( $padding_values[1] ) ? $padding_values[1] : '',
				);
			} else {
				$padding_settings = array(
					'top' => isset( $padding_values[0] ) ? $padding_values[0] : '',
					'right' => isset( $padding_values[1] ) ? $padding_values[1] : '',
					'bottom' => isset( $padding_values[2] ) ? $padding_values[2] : '',
					'left' => isset( $padding_values[3] ) ? $padding_values[3] : '',
				);
			}

			foreach( $padding_settings as $padding_side => $value ) {
				if ( '' !== $value ) {
					$element_style = array(
						'selector'    => '%%order_class%%',
						'declaration' => sprintf(
							'padding-%1$s: %2$s;',
							esc_html( $padding_side ),
							esc_html( $value )
						),
					);

					if ( 'on' !== $padding_mobile ) {
						$element_style['media_query'] = ET_Builder_Element::get_media_query( 'min_width_981' );
					}

					ET_Builder_Element::set_style( $function_name, $element_style );
				}
			}
		}

		if ( ! empty( $margin_values ) ) {
			$margin_settings = array(
				'top' => isset( $margin_values[0] ) ? $margin_values[0] : '',
				'right' => isset( $margin_values[1] ) ? $margin_values[1] : '',
				'bottom' => isset( $margin_values[2] ) ? $margin_values[2] : '',
				'left' => isset( $margin_values[3] ) ? $margin_values[3] : '',
			);

			foreach( $margin_settings as $margin_side => $value ) {
				if ( '' !== $value ) {
					$element_style = array(
						'selector'    => '%%order_class%%',
						'declaration' => sprintf(
							'margin-%1$s: %2$s;',
							esc_html( $margin_side ),
							esc_html( $value )
						),
					);

					ET_Builder_Element::set_style( $function_name, $element_style );
				}
			}
		}

		if ( 'on' === $make_fullwidth && 'off' === $use_custom_width ) {
			$module_class .= ' et_pb_row_fullwidth';
		}

		if ( 'on' === $use_custom_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'max-width:%1$s !important;',
					'on' === $width_unit ? esc_attr( $custom_width_px ) : esc_attr( $custom_width_percent )
				),
			) );
		}

		if ( '' !== $background_video_mp4 || '' !== $background_video_webm ) {
			$background_video = sprintf(
				'<div class="et_pb_section_video_bg%2$s">
					%1$s
				</div>',
				do_shortcode( sprintf( '
					<video loop="loop" autoplay="autoplay"%3$s%4$s>
						%1$s
						%2$s
					</video>',
					( '' !== $background_video_mp4 ? sprintf( '<source type="video/mp4" src="%s" />', esc_attr( $background_video_mp4 ) ) : '' ),
					( '' !== $background_video_webm ? sprintf( '<source type="video/webm" src="%s" />', esc_attr( $background_video_webm ) ) : '' ),
					( '' !== $background_video_width ? sprintf( ' width="%s"', esc_attr( $background_video_width ) ) : '' ),
					( '' !== $background_video_height ? sprintf( ' height="%s"', esc_attr( $background_video_height ) ) : '' )
				) ),
				( 'on' === $allow_player_pause ? ' et_pb_allow_player_pause' : '' )
			);

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}


		if ( '' !== $background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'background-color:%s;',
					esc_attr( $background_color )
				),
			) );
		}

		if ( '' !== $background_image && 'on' !== $parallax ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'background-image:url(%s);',
					esc_attr( $background_image )
				),
			) );
		}

		$output = sprintf(
			'<div%4$s class="%2$s%6$s%7$s">
				%8$s
				%1$s
					%5$s
			</div> <!-- .%3$s -->',
			$inner_content,
			esc_attr( $module_class ),
			esc_html( $function_name ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			$background_video,
			( '' !== $background_video ? ' et_pb_section_video et_pb_preload' : '' ),
			( 'on' === $parallax ? ' et_pb_section_parallax' : '' ),
			( '' !== $background_image && 'on' === $parallax
				? sprintf(
					'<div class="et_parallax_bg%2$s" style="background-image: url(%1$s);"></div>',
					esc_attr( $background_image ),
					( 'off' === $parallax_method ? ' et_pb_parallax_css' : '' )
				)
				: ''
			)
		);

		return $output;
	}
}
new ET_Builder_Row;

class ET_Builder_Row_Inner extends ET_Builder_Structure_Element {
	function init() {
		$this->name = __( 'Row', 'et_builder' );
		$this->slug = 'et_pb_row_inner';

		$this->whitelisted_fields = array(
			'custom_padding',
			'custom_margin',
			'padding_mobile',
			'column_padding_mobile',
			'use_custom_gutter',
			'gutter_width',
			'module_id',
			'module_class',
			'make_equal',
			'columns',
			'background_color_1',
			'background_color_2',
			'background_color_3',
			'bg_img_1',
			'bg_img_2',
			'bg_img_3',
			'padding_top_1',
			'padding_right_1',
			'padding_bottom_1',
			'padding_left_1',
			'padding_top_2',
			'padding_right_2',
			'padding_bottom_2',
			'padding_left_2',
			'padding_top_3',
			'padding_right_3',
			'padding_bottom_3',
			'padding_left_3',
			'parallax_1',
			'parallax_method_1',
			'parallax_2',
			'parallax_method_2',
			'parallax_3',
			'parallax_method_3',
		);

		$this->fields_defaults = array(
			'padding_mobile'        => array( 'off' ),
			'column_padding_mobile' => array( 'off' ),
			'use_custom_gutter'     => array( 'off' ),
			'gutter_width'          => array( '3', 'only_default_setting' ),
			'make_equal'            => array( 'off' ),
			'background_color_1'    => array( '' ),
			'background_color_2'    => array( '' ),
			'background_color_3'    => array( '' ),
			'bg_img_1'              => array( '' ),
			'bg_img_2'              => array( '' ),
			'bg_img_3'              => array( '' ),
			'padding_top_1'         => array( '' ),
			'padding_right_1'       => array( '' ),
			'padding_bottom_1'      => array( '' ),
			'padding_left_1'        => array( '' ),
			'padding_top_2'         => array( '' ),
			'padding_right_2'       => array( '' ),
			'padding_bottom_2'      => array( '' ),
			'padding_left_2'        => array( '' ),
			'padding_top_3'         => array( '' ),
			'padding_right_3'       => array( '' ),
			'padding_bottom_3'      => array( '' ),
			'padding_left_3'        => array( '' ),
			'parallax_1'            => array( 'off' ),
			'parallax_method_1'     => array( 'on' ),
			'parallax_2'            => array( 'off' ),
			'parallax_method_2'     => array( 'on' ),
			'parallax_3'            => array( 'off' ),
			'parallax_method_3'     => array( 'on' ),
		);
	}

	function get_fields() {
		$fields = array(
			'custom_padding' => array(
				'label'           => __( 'Custom Padding', 'et_builder' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'description'     => __( 'Adjust padding to specific values, or leave blank to use the default padding.', 'et_builder' ),
			),
			'custom_margin' => array(
				'label'           => __( 'Custom Margin', 'et_builder' ),
				'type'            => 'custom_margin',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
			),
			'padding_mobile' => array(
				'label'             => __( 'Keep Custom Padding on Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description'       => __( 'Allow custom padding to be retained on mobile screens', 'et_builder' ),
			),
			'use_custom_gutter' => array(
				'label'             => __( 'Use Custom Gutter Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_gutter_width',
				),
				'description'       => __( 'Enable this option to define custom gutter width for this row.', 'et_builder' ),
			),
			'gutter_width' => array(
				'label'           => __( 'Gutter Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => 1,
					'max'  => 4,
					'step' => 1,
				),
				'depends_show_if' => 'on',
				'description'     => __( 'Adjust the spacing between each column in this row.', 'et_builder' ),
			),
			'module_id' => array(
				'label'             => __( 'CSS ID', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'             => __( 'CSS Class', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'make_equal' => array(
				'label'             => __( 'Equalize Column Heights', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'columns' => array(
				'type'            => 'column_settings',
				'option_category' => 'configuration',
				'tab_slug'        => 'advanced',
			),
			'column_padding_mobile' => array(
				'label'             => __( 'Keep Column Padding on Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
			),
			'background_color_1' => array(
				'type' => 'skip',
			),
			'background_color_2' => array(
				'type' => 'skip',
			),
			'background_color_3' => array(
				'type' => 'skip',
			),
			'bg_img_1' => array(
				'type' => 'skip',
			),
			'bg_img_2' => array(
				'type' => 'skip',
			),
			'bg_img_3' => array(
				'type' => 'skip',
			),
			'padding_top_1' => array(
				'type' => 'skip',
			),
			'padding_right_1' => array(
				'type' => 'skip',
			),
			'padding_bottom_1' => array(
				'type' => 'skip',
			),
			'padding_left_1' => array(
				'type' => 'skip',
			),
			'padding_top_2' => array(
				'type' => 'skip',
			),
			'padding_right_2' => array(
				'type' => 'skip',
			),
			'padding_bottom_2' => array(
				'type' => 'skip',
			),
			'padding_left_2' => array(
				'type' => 'skip',
			),
			'padding_top_3' => array(
				'type' => 'skip',
			),
			'padding_right_3' => array(
				'type' => 'skip',
			),
			'padding_bottom_3' => array(
				'type' => 'skip',
			),
			'padding_left_3' => array(
				'type' => 'skip',
			),
			'parallax_1' => array(
				'type' => 'skip',
			),
			'parallax_method_1' => array(
				'type' => 'skip',
			),
			'parallax_2' => array(
				'type' => 'skip',
			),
			'parallax_method_2' => array(
				'type' => 'skip',
			),
			'parallax_3' => array(
				'type' => 'skip',
			),
			'parallax_method_3' => array(
				'type' => 'skip',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$background_color_1      = $this->shortcode_atts['background_color_1'];
		$background_color_2      = $this->shortcode_atts['background_color_2'];
		$background_color_3      = $this->shortcode_atts['background_color_3'];
		$bg_img_1                = $this->shortcode_atts['bg_img_1'];
		$bg_img_2                = $this->shortcode_atts['bg_img_2'];
		$bg_img_3                = $this->shortcode_atts['bg_img_3'];
		$padding_top_1           = $this->shortcode_atts['padding_top_1'];
		$padding_right_1         = $this->shortcode_atts['padding_right_1'];
		$padding_bottom_1        = $this->shortcode_atts['padding_bottom_1'];
		$padding_left_1          = $this->shortcode_atts['padding_left_1'];
		$padding_top_2           = $this->shortcode_atts['padding_top_2'];
		$padding_right_2         = $this->shortcode_atts['padding_right_2'];
		$padding_bottom_2        = $this->shortcode_atts['padding_bottom_2'];
		$padding_left_2          = $this->shortcode_atts['padding_left_2'];
		$padding_top_3           = $this->shortcode_atts['padding_top_3'];
		$padding_right_3         = $this->shortcode_atts['padding_right_3'];
		$padding_bottom_3        = $this->shortcode_atts['padding_bottom_3'];
		$padding_left_3          = $this->shortcode_atts['padding_left_3'];
		$gutter_width            = $this->shortcode_atts['gutter_width'];
		$make_equal              = $this->shortcode_atts['make_equal'];
		$custom_padding          = $this->shortcode_atts['custom_padding'];
		$custom_margin           = $this->shortcode_atts['custom_margin'];
		$padding_mobile          = $this->shortcode_atts['padding_mobile'];
		$column_padding_mobile   = $this->shortcode_atts['column_padding_mobile'];
		$global_module           = $this->shortcode_atts['global_module'];
		$use_custom_gutter       = $this->shortcode_atts['use_custom_gutter'];
		$parallax_1              = $this->shortcode_atts['parallax_1'];
		$parallax_method_1       = $this->shortcode_atts['parallax_method_1'];
		$parallax_2              = $this->shortcode_atts['parallax_2'];
		$parallax_method_2       = $this->shortcode_atts['parallax_method_2'];
		$parallax_3              = $this->shortcode_atts['parallax_3'];
		$parallax_method_3       = $this->shortcode_atts['parallax_method_3'];

		global $et_pb_column_inner_backgrounds, $et_pb_column_inner_paddings, $et_pb_columns_inner_counter, $keep_column_padding_mobile, $et_pb_column_parallax;

		$keep_column_padding_mobile = $column_padding_mobile;

		if ( '' !== $global_module ) {
			$global_content = et_pb_load_global_module( $global_module, $function_name );

			if ( '' !== $global_content ) {
				return do_shortcode( $global_content );
			}
		}

		$et_pb_columns_inner_counter = 0;
		$et_pb_column_inner_backgrounds = array(
			array( $background_color_1, $bg_img_1 ),
			array( $background_color_2, $bg_img_2 ),
			array( $background_color_3, $bg_img_3 ),
		);
		$et_pb_column_inner_paddings = array(
			array(
				'padding-top'    => $padding_top_1,
				'padding-right'  => $padding_right_1,
				'padding-bottom' => $padding_bottom_1,
				'padding-left'   => $padding_left_1
			),
			array(
				'padding-top'    => $padding_top_2,
				'padding-right'  => $padding_right_2,
				'padding-bottom' => $padding_bottom_2,
				'padding-left'   => $padding_left_2
			),
			array(
				'padding-top'    => $padding_top_3,
				'padding-right'  => $padding_right_3,
				'padding-bottom' => $padding_bottom_3,
				'padding-left'   => $padding_left_3
			),
		);

		$et_pb_column_parallax = array(
			array( $parallax_1, $parallax_method_1 ),
			array( $parallax_2, $parallax_method_2 ),
			array( $parallax_3, $parallax_method_3 ),
		);

		$padding_values = explode( '|', $custom_padding );
		$margin_values = explode( '|', $custom_margin );

		if ( ! empty( $padding_values ) ) {
			// old version of Rows support only top and bottom padding, so we need to handle it along with the full padding in the recent version
			if ( 2 === count( $padding_values ) ) {
				$padding_settings = array(
					'top' => isset( $padding_values[0] ) ? $padding_values[0] : '',
					'bottom' => isset( $padding_values[1] ) ? $padding_values[1] : '',
				);
			} else {
				$padding_settings = array(
					'top' => isset( $padding_values[0] ) ? $padding_values[0] : '',
					'right' => isset( $padding_values[1] ) ? $padding_values[1] : '',
					'bottom' => isset( $padding_values[2] ) ? $padding_values[2] : '',
					'left' => isset( $padding_values[3] ) ? $padding_values[3] : '',
				);
			}

			foreach( $padding_settings as $padding_side => $value ) {
				if ( '' !== $value ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '.et_pb_column %%order_class%%',
						'declaration' => sprintf(
							'padding-%1$s:%2$s%3$s;',
							esc_html( $padding_side ),
							esc_html( $value ),
							'on' === $padding_mobile ? '!important' : ''
						),
					) );
				}
			}
		}

		if ( ! empty( $margin_values ) ) {
			$margin_settings = array(
				'top' => isset( $margin_values[0] ) ? $margin_values[0] : '',
				'right' => isset( $margin_values[1] ) ? $margin_values[1] : '',
				'bottom' => isset( $margin_values[2] ) ? $margin_values[2] : '',
				'left' => isset( $margin_values[3] ) ? $margin_values[3] : '',
			);

			foreach( $margin_settings as $margin_side => $value ) {
				if ( '' !== $value ) {
					$element_style = array(
						'selector'    => '%%order_class%%',
						'declaration' => sprintf(
							'margin-%1$s: %2$s;',
							esc_html( $margin_side ),
							esc_html( $value )
						),
					);

					ET_Builder_Element::set_style( $function_name, $element_style );
				}
			}
		}

		$module_class .= ' et_pb_row_inner';

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$inner_content = do_shortcode( et_pb_fix_shortcodes( $content ) );
		$module_class .= '' == trim( $inner_content ) ? ' et_pb_row_empty' : '';

		$module_class .= 'on' === $make_equal ? ' et_pb_equal_columns' : '';

		if ( 'on' === $use_custom_gutter ) {
			$gutter_width = '0' === $gutter_width ? '1' : $gutter_width; // set the gutter to 1 if 0 entered by user
			$module_class .= ' et_pb_gutters' . $gutter_width;
		}

		$output = sprintf(
			'<div%4$s class="%2$s">
				%1$s
			</div> <!-- .%3$s -->',
			$inner_content,
			esc_attr( $module_class ),
			esc_html( $function_name ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Row_Inner;

class ET_Builder_Column extends ET_Builder_Structure_Element {
	function init() {
		$this->name                       = __( 'Column', 'et_builder' );
		$this->slug                       = 'et_pb_column';
		$this->additional_shortcode_slugs = array( 'et_pb_column_inner' );

		$this->whitelisted_fields = array(
			'type',
			'specialty_columns',
			'saved_specialty_column_type',
		);

		$this->fields_defaults = array(
			'type'                        => array( '4_4' ),
			'specialty_columns'           => array( '' ),
			'saved_specialty_column_type' => array( '' ),
		);
	}

	function get_fields() {
		$fields = array(
			'type' => array(
				'type' => 'skip',
			),
			'specialty_columns' => array(
				'type' => 'skip',
			),
			'saved_specialty_column_type' => array(
				'type' => 'skip',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$type                        = $this->shortcode_atts['type'];
		$specialty_columns           = $this->shortcode_atts['specialty_columns'];
		$saved_specialty_column_type = $this->shortcode_atts['saved_specialty_column_type'];

		global $et_specialty_column_type, $et_pb_column_backgrounds, $et_pb_column_paddings, $et_pb_column_inner_backgrounds, $et_pb_column_inner_paddings, $et_pb_columns_counter, $et_pb_columns_inner_counter, $keep_column_padding_mobile, $et_pb_column_parallax;

		if ( 'et_pb_column_inner' !== $function_name ) {
			$et_specialty_column_type = $type;
			$array_index = $et_pb_columns_counter;
			$backgrounds_array = $et_pb_column_backgrounds;
			$paddings_array = $et_pb_column_paddings;
			$et_pb_columns_counter++;
		} else {
			$array_index = $et_pb_columns_inner_counter;
			$backgrounds_array = $et_pb_column_inner_backgrounds;
			$paddings_array = $et_pb_column_inner_paddings;
			$et_pb_columns_inner_counter++;
		}

		$background_color = isset( $backgrounds_array[$array_index][0] ) ? $backgrounds_array[$array_index][0] : '';
		$background_img = isset( $backgrounds_array[$array_index][1] ) ? $backgrounds_array[$array_index][1] : '';
		$padding_values = isset( $paddings_array[$array_index] ) ? $paddings_array[$array_index] : array();
		$parallax_method = isset( $et_pb_column_parallax[$array_index][0] ) && 'on' === $et_pb_column_parallax[$array_index][0] ? $et_pb_column_parallax[$array_index][1] : '';

		if ( '' !== $background_color && 'rgba(0,0,0,0)' !== $background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'background-color:%s;',
					esc_attr( $background_color )
				),
			) );
		}

		if ( '' !== $background_img && '' === $parallax_method ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'background-image:url(%s);',
					esc_attr( $background_img )
				),
			) );
		}

		if ( ! empty( $padding_values ) ) {
			foreach( $padding_values as $position => $value ) {
				if ( '' !== $value ) {
					$element_style = array(
						'selector'    => '%%order_class%%',
						'declaration' => sprintf(
							'%1$s:%2$s;',
							esc_html( $position ),
							esc_html( et_builder_process_range_value( $value ) )
						),
					);

					if ( 'on' !== $keep_column_padding_mobile ) {
						$element_style['media_query'] = ET_Builder_Element::get_media_query( 'min_width_981' );
					}

					ET_Builder_Element::set_style( $function_name, $element_style );
				}
			}
		}

		if ( 'et_pb_column_inner' === $function_name ) {
			$et_specialty_column_type = '' !== $saved_specialty_column_type ? $saved_specialty_column_type : $et_specialty_column_type;

			switch ( $et_specialty_column_type ) {
				case '1_2':
					if ( '1_2' === $type ) {
						$type = '1_4';
					}

					break;
				case '2_3':
					if ( '1_2' === $type ) {
						$type = '1_3';
					}

					break;
				case '3_4':
					if ( '1_2' === $type ) {
						$type = '3_8';
					} else if ( '1_3' === $type ) {
						$type = '1_4';
					}

					break;
			}
		}

		$inner_class = 'et_pb_column_inner' === $function_name ? ' et_pb_column_inner' : '';

		$class = 'et_pb_column_' . $type . $inner_class;

		$inner_content = do_shortcode( et_pb_fix_shortcodes( $content ) );
		$class .= '' == trim( $inner_content ) ? ' et_pb_column_empty' : '';

		$class = ET_Builder_Element::add_module_order_class( $class, $function_name );

		$class .= 'et_pb_column_inner' !== $function_name && '' !== $specialty_columns ? ' et_pb_specialty_column' : '';

		$output = sprintf(
			'<div class="et_pb_column %1$s%3$s">
				%4$s
				%2$s
			</div> <!-- .et_pb_column -->',
			esc_attr( $class ),
			$inner_content,
			( '' !== $parallax_method ? ' et_pb_section_parallax' : '' ),
			( '' !== $background_img && '' !== $parallax_method
				? sprintf(
					'<div class="et_parallax_bg%2$s" style="background-image: url(%1$s);"></div>',
					esc_attr( $background_img ),
					( 'off' === $parallax_method ? ' et_pb_parallax_css' : '' )
				)
				: ''
			)
		);

		return $output;

	}

}
new ET_Builder_Column;