<?php
class ET_Builder_Module_Image extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Image', 'et_builder' );
		$this->slug = 'et_pb_image';

		$this->whitelisted_fields = array(
			'src',
			'alt',
			'title_text',
			'show_in_lightbox',
			'url',
			'url_new_window',
			'animation',
			'sticky',
			'align',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
			'force_fullwidth',
			'always_center_on_mobile',
		);

		$this->fields_defaults = array(
			'show_in_lightbox'        => array( 'off' ),
			'url_new_window'          => array( 'off' ),
			'animation'               => array( 'left' ),
			'sticky'                  => array( 'off' ),
			'align'                   => array( 'left' ),
			'force_fullwidth'         => array( 'off' ),
			'always_center_on_mobile' => array( 'on' ),
		);

		$this->advanced_options = array(
			'border'                => array(),
			'custom_margin_padding' => array(
				'use_padding' => false,
				'css' => array(
					'important' => 'all',
				),
			),
		);
	}

	function get_fields() {
		// List of animation options
		$animation_options_list = array(
			'left'    => __( 'Left To Right', 'et_builder' ),
			'right'   => __( 'Right To Left', 'et_builder' ),
			'top'     => __( 'Top To Bottom', 'et_builder' ),
			'bottom'  => __( 'Bottom To Top', 'et_builder' ),
			'fade_in' => __( 'Fade In', 'et_builder' ),
			'off'     => __( 'No Animation', 'et_builder' ),
		);

		$animation_option_name       = sprintf( '%1$s-animation', $this->slug );
		$default_animation_direction = ET_Global_Settings::get_value( $animation_option_name );

		// If user modifies default animation option via Customizer, we'll need to change the order
		if ( 'left' !== $default_animation_direction && array_key_exists( $default_animation_direction, $animation_options_list ) ) {
			// The options, sans user's preferred direction
			$animation_options_wo_default = $animation_options_list;
			unset( $animation_options_wo_default[ $default_animation_direction ] );

			// All animation options
			$animation_options = array_merge(
				array( $default_animation_direction => $animation_options_list[$default_animation_direction] ),
				$animation_options_wo_default
			);
		} else {
			// Simply copy the animation options
			$animation_options = $animation_options_list;
		}

		$fields = array(
			'src' => array(
				'label'              => __( 'Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'alt' => array(
				'label'           => __( 'Image Alternative Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'et_builder' ),
			),
			'title_text' => array(
				'label'           => __( 'Image Title Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This defines the HTML Title text.', 'et_builder' ),
			),
			'show_in_lightbox' => array(
				'label'             => __( 'Open in Lightbox', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( "No", 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_url',
					'#et_pb_url_new_window',
				),
				'description'       => __( 'Here you can choose whether or not the image should open in Lightbox. Note: if you select to open the image in Lightbox, url options below will be ignored.', 'et_builder' ),
			),
			'url' => array(
				'label'           => __( 'Link URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'off',
				'description'     => __( 'If you would like your image to be a link, input your destination URL here. No link will be created if this field is left blank.', 'et_builder' ),
			),
			'url_new_window' => array(
				'label'             => __( 'Url Opens', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'In The Same Window', 'et_builder' ),
					'on'  => __( 'In The New Tab', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
				'description'       => __( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
			),
			'animation' => array(
				'label'             => __( 'Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $animation_options,
				'description'       => __( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
			),
			'sticky' => array(
				'label'             => __( 'Remove Space Below The Image', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off'     => __( 'No', 'et_builder' ),
					'on'      => __( 'Yes', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose whether or not the image should have a space below it.', 'et_builder' ),
			),
			'align' => array(
				'label'           => __( 'Image Alignment', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options' => array(
					'left'   => __( 'Left', 'et_builder' ),
					'center' => __( 'Center', 'et_builder' ),
					'right'  => __( 'Right', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the image alignment.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'max_width' => array(
				'label'           => __( 'Image Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'force_fullwidth' => array(
				'label'             => __( 'Force Fullwidth', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( "No", 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'    => 'advanced',
			),
			'always_center_on_mobile' => array(
				'label'             => __( 'Always Center Image On Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( "No", 'et_builder' ),
				),
				'tab_slug'    => 'advanced',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$src                     = $this->shortcode_atts['src'];
		$alt                     = $this->shortcode_atts['alt'];
		$title_text              = $this->shortcode_atts['title_text'];
		$animation               = $this->shortcode_atts['animation'];
		$url                     = $this->shortcode_atts['url'];
		$url_new_window          = $this->shortcode_atts['url_new_window'];
		$show_in_lightbox        = $this->shortcode_atts['show_in_lightbox'];
		$sticky                  = $this->shortcode_atts['sticky'];
		$align                   = $this->shortcode_atts['align'];
		$max_width               = $this->shortcode_atts['max_width'];
		$force_fullwidth         = $this->shortcode_atts['force_fullwidth'];
		$always_center_on_mobile = $this->shortcode_atts['always_center_on_mobile'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( 'on' === $always_center_on_mobile ) {
			$module_class .= ' et_always_center_on_mobile';
		}

		if ( '' !== $max_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'max-width: %1$s;',
					esc_html( et_builder_process_range_value( $max_width ) )
				),
			) );
		}

		if ( 'on' === $force_fullwidth ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% img',
				'declaration' => 'width: 100%;',
			) );
		}

		if ( $this->fields_defaults['align'][0] !== $align ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'text-align: %1$s;',
					esc_html( $align )
				),
			) );
		}

		if ( 'center' !== $align ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'margin-%1$s: 0;',
					esc_html( $align )
				),
			) );
		}

		$output = sprintf(
			'<img src="%1$s" alt="%2$s"%3$s />',
			esc_attr( $src ),
			esc_attr( $alt ),
			( '' !== $title_text ? sprintf( ' title="%1$s"', esc_attr( $title_text ) ) : '' )
		);

		if ( 'on' === $show_in_lightbox ) {
			$output = sprintf( '<a href="%1$s" class="et_pb_lightbox_image" title="%3$s">%2$s</a>',
				esc_url( $src ),
				$output,
				esc_attr( $alt )
			);
		} else if ( '' !== $url ) {
			$output = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
				esc_url( $url ),
				$output,
				( 'on' === $url_new_window ? ' target="_blank"' : '' )
			);
		}

		$animation = '' === $animation ? ET_Global_Settings::get_value( 'et_pb_image-animation' ) : $animation;

		$output = sprintf(
			'<div%5$s class="et_pb_module et-waypoint et_pb_image%2$s%3$s%4$s">
				%1$s
			</div>',
			$output,
			esc_attr( " et_pb_animation_{$animation}" ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( ltrim( $module_class ) ) ) : '' ),
			( 'on' === $sticky ? esc_attr( ' et_pb_image_sticky' ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Image;

class ET_Builder_Module_Gallery extends ET_Builder_Module {
	function init() {
		$this->name       = __( 'Gallery', 'et_builder' );
		$this->slug       = 'et_pb_gallery';

		$this->whitelisted_fields = array(
			'src',
			'gallery_ids',
			'gallery_orderby',
			'fullwidth',
			'posts_number',
			'show_title_and_caption',
			'show_pagination',
			'background_layout',
			'auto',
			'auto_speed',
			'admin_label',
			'module_id',
			'module_class',
			'zoom_icon_color',
			'hover_overlay_color',
			'hover_icon',
		);

		$this->fields_defaults = array(
			'fullwidth'              => array( 'off' ),
			'posts_number'           => array( 4, 'add_default_setting' ),
			'show_title_and_caption' => array( 'on' ),
			'show_pagination'        => array( 'on' ),
			'background_layout'      => array( 'light' ),
			'auto'                   => array( 'off' ),
			'auto_speed'             => array( '7000' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_gallery';
		$this->advanced_options = array(
			'fonts' => array(
				'caption' => array(
					'label'    => __( 'Caption', 'et_builder' ),
					'use_all_caps' => true,
					'css'      => array(
						'main' => "{$this->main_css_element} .mfp-title, {$this->main_css_element} .et_pb_gallery_caption",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'title'   => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_gallery_title",
					),
				),
			),
			'border' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_gallery_item",
				),
			),
		);

		$this->custom_css_options = array(
			'gallery_item' => array(
				'label'    => __( 'Gallery Item', 'et_builder' ),
				'selector' => '.et_pb_gallery_item',
			),
			'overlay' => array(
				'label'    => __( 'Overlay', 'et_builder' ),
				'selector' => '.et_overlay',
			),
			'overlay_icon' => array(
				'label'    => __( 'Overlay Icon', 'et_builder' ),
				'selector' => '.et_overlay:before',
			),
			'gallery_item_title' => array(
				'label'    => __( 'Gallery Item Title', 'et_builder' ),
				'selector' => '.et_pb_gallery_title',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'src' => array(
				'label'           => __( 'Gallery Images', 'et_builder' ),
				'renderer'        => 'et_builder_get_gallery_settings',
				'option_category' => 'basic_option',
			),
			'gallery_ids' => array(
				'type'  => 'hidden',
				'class' => array( 'et-pb-gallery-ids-field' ),
			),
			'gallery_orderby' => array(
				'label' => __( 'Gallery Images', 'et_builder' ),
				'type'  => 'hidden',
				'class' => array( 'et-pb-gallery-ids-field' ),
			),
			'fullwidth' => array(
				'label'             => __( 'Layout', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'Slider', 'et_builder' ),
					'off' => __( 'Grid', 'et_builder' ),
				),
				'description'       => __( 'Toggle between the various blog layout types.', 'et_builder' ),
				'affects'           => array(
					'#et_pb_zoom_icon_color',
					'#et_pb_caption_font',
					'#et_pb_caption_font_color',
					'#et_pb_caption_font_size',
					'#et_pb_hover_overlay_color',
					'#et_pb_auto',
					'#et_pb_posts_number',
				),
			),
			'posts_number' => array(
				'label'             => __( 'Images Number', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Define the number of images that should be displayed per page.', 'et_builder' ),
				'depends_show_if'   => 'off',
			),
			'show_title_and_caption' => array(
				'label'              => __( 'Show Title and Caption', 'et_builder' ),
				'type'               => 'yes_no_button',
				'option_category'    => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether to show the images title and caption, if the image has them.', 'et_builder' ),
			),
			'show_pagination' => array(
				'label'             => __( 'Show Pagination', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Enable or disable pagination for this feed.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'light'  => __( 'Dark', 'et_builder' ),
					'dark' => __( 'Light', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'auto' => array(
				'label'           => __( 'Automatic Animation', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'affects' => array(
					'#et_pb_auto_speed',
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
			),
			'auto_speed' => array(
				'label'             => __( 'Automatic Animation Speed (in ms)', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_default'   => true,
				'description'       => __( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation.", 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'zoom_icon_color' => array(
				'label'             => __( 'Zoom Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'depends_show_if'   => 'off',
				'tab_slug'          => 'advanced',
			),
			'hover_overlay_color' => array(
				'label'             => __( 'Hover Overlay Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'depends_show_if'   => 'off',
				'tab_slug'          => 'advanced',
			),
			'hover_icon' => array(
				'label'               => __( 'Hover Icon Picker', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'tab_slug'            => 'advanced',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id              = $this->shortcode_atts['module_id'];
		$module_class           = $this->shortcode_atts['module_class'];
		$gallery_ids            = $this->shortcode_atts['gallery_ids'];
		$fullwidth              = $this->shortcode_atts['fullwidth'];
		$show_title_and_caption = $this->shortcode_atts['show_title_and_caption'];
		$background_layout      = $this->shortcode_atts['background_layout'];
		$posts_number           = $this->shortcode_atts['posts_number'];
		$show_pagination        = $this->shortcode_atts['show_pagination'];
		$gallery_orderby        = $this->shortcode_atts['gallery_orderby'];
		$zoom_icon_color        = $this->shortcode_atts['zoom_icon_color'];
		$hover_overlay_color    = $this->shortcode_atts['hover_overlay_color'];
		$hover_icon             = $this->shortcode_atts['hover_icon'];
		$auto                   = $this->shortcode_atts['auto'];
		$auto_speed             = $this->shortcode_atts['auto_speed'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $zoom_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay:before',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $zoom_icon_color )
				),
			) );
		}

		if ( '' !== $hover_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay',
				'declaration' => sprintf(
					'background-color: %1$s;
					border-color: %1$s;',
					esc_html( $hover_overlay_color )
				),
			) );
		}

		$attachments = array();
		if ( ! empty( $gallery_ids ) ) {
			$attachments_args = array(
				'include'        => $gallery_ids,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'post__in',
			);

			if ( 'rand' === $gallery_orderby ) {
				$attachments_args['orderby'] = 'rand';
			}

			$_attachments = get_posts( $attachments_args );

			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		}

		if ( empty($attachments) )
			return '';

		wp_enqueue_script( 'hashchange' );

		$fullwidth_class = 'on' === $fullwidth ?  ' et_pb_slider et_pb_gallery_fullwidth' : ' et_pb_gallery_grid';
		$background_class = " et_pb_bg_layout_{$background_layout}";

		$module_class .= 'on' === $auto && 'on' === $fullwidth ? ' et_slider_auto et_slider_speed_' . esc_attr( $auto_speed ) : '';

		$output = sprintf(
			'<div%1$s class="et_pb_module et_pb_gallery%2$s%3$s%4$s clearfix">
				<div class="et_pb_gallery_items et_post_gallery" data-per_page="%5$d">',
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( ltrim( $module_class ) ) ) : '' ),
			esc_attr( $fullwidth_class ),
			esc_attr( $background_class ),
			esc_attr( $posts_number )
		);

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {

			$width = 'on' === $fullwidth ?  1080 : 400;
			$width = (int) apply_filters( 'et_pb_gallery_image_width', $width );

			$height = 'on' === $fullwidth ?  9999 : 284;
			$height = (int) apply_filters( 'et_pb_gallery_image_height', $height );

			list($full_src, $full_width, $full_height) = wp_get_attachment_image_src( $id, 'full' );
			list($thumb_src, $thumb_width, $thumb_height) = wp_get_attachment_image_src( $id, array( $width, $height ) );

			$data_icon = '' !== $hover_icon
				? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $hover_icon ) )
				)
				: '';

			$image_output = sprintf(
				'<a href="%1$s" title="%2$s">
					<img src="%3$s" alt="%2$s" />
					<span class="et_overlay%4$s"%5$s></span>
				</a>',
				esc_attr( $full_src ),
				esc_attr( $attachment->post_title ),
				esc_attr( $thumb_src ),
				( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
				$data_icon
			);

			$orientation = ( $thumb_height > $thumb_width ) ? 'portrait' : 'landscape';

			$output .= sprintf(
				'<div class="et_pb_gallery_item%2$s%1$s">',
				esc_attr( $background_class ),
				( 'on' !== $fullwidth ? ' et_pb_grid_item' : '' )
			);
			$output .= "
				<div class='et_pb_gallery_image {$orientation}'>
					$image_output
				</div>";

			if ( 'on' !== $fullwidth && 'on' === $show_title_and_caption ) {
				if ( trim($attachment->post_title) ) {
					$output .= "
						<h3 class='et_pb_gallery_title'>
						" . wptexturize($attachment->post_title) . "
						</h3>";
				}
				if ( trim($attachment->post_excerpt) ) {
				$output .= "
						<p class='et_pb_gallery_caption'>
						" . wptexturize($attachment->post_excerpt) . "
						</p>";
				}
			}
			$output .= "</div>";
		}

		$output .= "</div><!-- .et_pb_gallery_items -->";

		if ( 'on' !== $fullwidth && 'on' === $show_pagination ) {
			$output .= "<div class='et_pb_gallery_pagination'></div>";
		}

		$output .= "</div><!-- .et_pb_gallery -->";

		return $output;
	}
}
new ET_Builder_Module_Gallery;

class ET_Builder_Module_Video extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Video', 'et_builder' );
		$this->slug = 'et_pb_video';

		$this->whitelisted_fields = array(
			'src',
			'src_webm',
			'image_src',
			'play_icon_color',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->custom_css_options = array(
			'video_icon' => array(
				'label'    => __( 'Video Icon', 'et_builder' ),
				'selector' => '.et_pb_video_play',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'src' => array(
				'label'              => __( 'Video MP4/URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Video MP4 File', 'et_builder' ),
				'update_text'        => __( 'Set As Video', 'et_builder' ),
				'description'        => __( 'Upload your desired video in .MP4 format, or type in the URL to the video you would like to display', 'et_builder' ),
			),
			'src_webm' => array(
				'label'              => __( 'Video Webm', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Video WEBM File', 'et_builder' ),
				'update_text'        => __( 'Set As Video', 'et_builder' ),
				'description'        => __( 'Upload the .WEBM version of your video here. All uploaded videos should be in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers.', 'et_builder' ),
			),
			'image_src' => array(
				'label'              => __( 'Image Overlay URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'additional_button'  => sprintf(
					'<input type="button" class="button et-pb-video-image-button" value="%1$s" />',
					esc_attr__( 'Generate From Video', 'et_builder' )
				),
				'classes'            => 'et_pb_video_overlay',
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display over your video. You can also generate a still image from your video.', 'et_builder' ),
			),
			'play_icon_color' => array(
				'label'             => __( 'Play Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),

		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id       = $this->shortcode_atts['module_id'];
		$module_class    = $this->shortcode_atts['module_class'];
		$src             = $this->shortcode_atts['src'];
		$src_webm        = $this->shortcode_atts['src_webm'];
		$image_src       = $this->shortcode_atts['image_src'];
		$play_icon_color = $this->shortcode_atts['play_icon_color'];
		$video_src       = '';

		if ( '' !== $image_src ) {
			$image_output = $image_src;
		} else {
			$image_output = '';
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $play_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_video_play',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $play_icon_color )
				),
			) );
		}

		if ( '' !== $src ) {
			if ( false !== et_pb_check_oembed_provider( esc_url( $src ) ) ) {
				$video_src = wp_oembed_get( esc_url( $src ) );
			} else {
				$video_src = sprintf( '
					<video controls>
						%1$s
						%2$s
					</video>',
					( '' !== $src ? sprintf( '<source type="video/mp4" src="%s" />', esc_url( $src ) ) : '' ),
					( '' !== $src_webm ? sprintf( '<source type="video/webm" src="%s" />', esc_url( $src_webm ) ) : '' )
				);

				wp_enqueue_style( 'wp-mediaelement' );
				wp_enqueue_script( 'wp-mediaelement' );
			}
		}

		$output = sprintf(
			'<div%2$s class="et_pb_module et_pb_video%3$s">
				<div class="et_pb_video_box">
					%1$s
				</div>
				%4$s
			</div>',
			( '' !== $video_src ? $video_src : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $image_output
				? sprintf(
					'<div class="et_pb_video_overlay" style="background-image: url(%1$s);">
						<div class="et_pb_video_overlay_hover">
							<a href="#" class="et_pb_video_play"></a>
						</div>
					</div>',
					esc_attr( $image_output )
				)
				: ''
			)
		);

		return $output;
	}
}
new ET_Builder_Module_Video;

class ET_Builder_Module_Video_Slider extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Video Slider', 'et_builder' );
		$this->slug            = 'et_pb_video_slider';
		$this->child_slug      = 'et_pb_video_slider_item';
		$this->child_item_text = __( 'Video', 'et_builder' );

		$this->whitelisted_fields = array(
			'show_image_overlay',
			'show_arrows',
			'show_thumbnails',
			'controls_color',
			'admin_label',
			'module_id',
			'module_class',
			'play_icon_color',
			'thumbnail_overlay_color',
		);

		$this->fields_defaults = array(
			'show_image_overlay' => array( 'hide' ),
			'show_arrows'        => array( 'on' ),
			'show_thumbnails'    => array( 'on' ),
		);

		$this->custom_css_options = array(
			'play_button' => array(
				'label'    => __( 'Play Button', 'et_builder' ),
				'selector' => '.et_pb_video_play',
			),
			'thumbnail_item' => array(
				'label'    => __( 'Thumbnail Item', 'et_builder' ),
				'selector' => '.et_pb_carousel_item',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'show_image_overlay' => array(
				'label'           => __( 'Display Image Overlays on Main Video', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'hide' => __( 'Hide', 'et_builder' ),
					'show' => __( 'Show', 'et_builder' ),
				),
				'description'        => __( 'This option will cover the player UI on the main video. This image can either be uploaded in each video setting or auto-generated by Divi.', 'et_builder' ),
			),
			'show_arrows' => array(
				'label'           => __( 'Arrows', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Show Arrows', 'et_builder' ),
					'off' => __( 'Hide Arrows', 'et_builder' ),
				),
				'description'        => __( 'This setting will turn on and off the navigation arrows.', 'et_builder' ),
			),
			'show_thumbnails' => array(
				'label'             => __( 'Slider Controls', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Use Thumbnail Track', 'et_builder' ),
					'off' => __( 'Use Dot Navigation', 'et_builder' ),
				),
				'description'        => __( 'This setting will let you choose to use the thumbnail track controls below the slider or dot navigation at the bottom of the slider.', 'et_builder' ),
			),
			'controls_color' => array(
				'label'             => __( 'Slider Controls Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'light' => __( 'Light', 'et_builder' ),
					'dark'  => __( 'Dark', 'et_builder' ),
				),
				'description'       => __( 'This setting will make your slider controls either light or dark in color. Slider controls are either the arrows on the thumbnail track or the circles in dot navigation.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'play_icon_color' => array(
				'label'             => __( 'Play Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'thumbnail_overlay_color' => array(
				'label'             => __( 'Thumbnail Overlay Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_slider_image_overlay;

		$show_image_overlay = $this->shortcode_atts['show_image_overlay'];

		$et_pb_slider_image_overlay = $show_image_overlay;

	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$show_arrows        = $this->shortcode_atts['show_arrows'];
		$show_thumbnails    = $this->shortcode_atts['show_thumbnails'];
		$controls_color     = $this->shortcode_atts['controls_color'];
		$play_icon_color = $this->shortcode_atts['play_icon_color'];
		$thumbnail_overlay_color = $this->shortcode_atts['thumbnail_overlay_color'];

		global $et_pb_slider_image_overlay;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $play_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_video_play, %%order_class%% .et_pb_carousel .et_pb_video_play',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $play_icon_color )
				),
			) );
		}

		if ( '' !== $thumbnail_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_carousel_item .et_pb_video_overlay_hover:hover, %%order_class%%.et_pb_video_slider .et_pb_slider:hover .et_pb_video_overlay_hover, %%order_class%% .et_pb_carousel_item.et-pb-active-control .et_pb_video_overlay_hover',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $thumbnail_overlay_color )
				),
			) );
		}

		$class  = '';
		$class .= 'off' === $show_arrows ? ' et_pb_slider_no_arrows' : '';
		$class .= 'on' === $show_thumbnails ? ' et_pb_slider_carousel et_pb_slider_no_pagination' : '';
		$class .= 'off' === $show_thumbnails ? ' et_pb_slider_dots' : '';
		$class .= " et_pb_controls_{$controls_color}";

		$content = $this->shortcode_content;

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_video_slider%4$s">
				<div class="et_pb_slider et_pb_preload%1$s">
					<div class="et_pb_slides">
						%2$s
					</div> <!-- .et_pb_slides -->
				</div> <!-- .et_pb_slider -->
			</div> <!-- .et_pb_video_slider -->
			',
			esc_attr( $class ),
			$content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Video_Slider;

class ET_Builder_Module_Video_Slider_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Video', 'et_builder' );
		$this->slug                        = 'et_pb_video_slider_item';
		$this->type                        = 'child';
		$this->custom_css_tab              = false;
		$this->child_title_var             = 'admin_title';
		$this->advanced_setting_title_text = __( 'New Video', 'et_builder' );
		$this->settings_text               = __( 'Video Settings', 'et_builder' );

		$this->whitelisted_fields = array(
			'admin_title',
			'src',
			'src_webm',
			'image_src',
			'background_layout',
		);

		$this->fields_defaults = array(
			'background_layout' => array( 'dark' ),
		);
	}

	function get_fields() {
		$fields = array(
			'admin_title' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the video in the builder for easy identification.', 'et_builder' ),
			),
			'src' => array(
				'label'              => __( 'Video MP4/URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Video MP4 File', 'et_builder' ),
				'update_text'        => __( 'Set As Video', 'et_builder' ),
				'description'        => __( 'Upload your desired video in .MP4 format, or type in the URL to the video you would like to display', 'et_builder' ),
			),
			'src_webm' => array(
				'label'              => __( 'Video Webm', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Video WEBM File', 'et_builder' ),
				'update_text'        => __( 'Set As Video', 'et_builder' ),
				'description'        => __( 'Upload the .WEBM version of your video here. All uploaded videos should be in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers.', 'et_builder' ),
			),
			'image_src' => array(
				'label'              => __( 'Image Overlay URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'additional_button'  => sprintf(
					'<input type="button" class="button et-pb-video-image-button" value="%1$s" />',
					esc_attr__( 'Generate From Video', 'et_builder' )
				),
				'classes'            => 'et_pb_video_overlay',
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display over your video. You can also generate a still image from your video.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Slider Arrows Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'dark'  => __( 'Light', 'et_builder' ),
					'light' => __( 'Dark', 'et_builder' ),
				),
				'description' => __( 'This setting will make your slider arrows either light or dark in color.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$src               = $this->shortcode_atts['src'];
		$src_webm          = $this->shortcode_atts['src_webm'];
		$image_src         = $this->shortcode_atts['image_src'];
		$background_layout = $this->shortcode_atts['background_layout'];
		$video_src         = '';

		global $et_pb_slider_image_overlay;

		$class  = '';
		$class .= " et_pb_bg_layout_{$background_layout}";

		if ( '' !== $image_src ) {
			$image_overlay_output = $image_src;
			$thumbnail_track_output = $image_src;
		} else {
			$image_overlay_output = '';
			if ( false !== et_pb_check_oembed_provider( esc_url( $src ) ) ) {
				add_filter( 'oembed_dataparse', 'et_pb_video_oembed_data_parse', 10, 3 );
				// Save thumbnail
				$thumbnail_track_output = wp_oembed_get( esc_url( $src ) );
				// Set back to normal
				remove_filter( 'oembed_dataparse', 'et_pb_video_oembed_data_parse', 10, 3 );
			} else {
				$thumbnail_track_output = '';
			}
		}

		if ( '' !== $src ) {
			if ( false !== et_pb_check_oembed_provider( esc_url( $src ) ) ) {
				$video_src = wp_oembed_get( esc_url( $src ) );
			} else {
				$video_src = sprintf( '
					<video controls>
						%1$s
						%2$s
					</video>',
					( '' !== $src ? sprintf( '<source type="video/mp4" src="%s" />', esc_url( $src ) ) : '' ),
					( '' !== $src_webm ? sprintf( '<source type="video/webm" src="%s" />', esc_url( $src_webm ) ) : '' )
				);

				wp_enqueue_style( 'wp-mediaelement' );
				wp_enqueue_script( 'wp-mediaelement' );
			}
		}

		$video_output = sprintf(
			'<div class="et_pb_video_wrap">
				<div class="et_pb_video_box">
					%1$s
				</div>
				%2$s
			</div>',
			( '' !== $video_src ? $video_src : '' ),
			(
				( '' !== $image_overlay_output && $et_pb_slider_image_overlay == 'show' )
					? sprintf(
						'<div class="et_pb_video_overlay" style="background-image: url(%1$s);">
							<div class="et_pb_video_overlay_hover">
								<a href="#" class="et_pb_video_play"></a>
							</div>
						</div>',
						esc_attr( $image_overlay_output )
					)
					: ''
			)
		);

		$output = sprintf(
			'<div class="et_pb_slide%1$s"%3$s>
				%2$s
			</div> <!-- .et_pb_slide -->
			',
			esc_attr( $class ),
			( '' !== $video_output ? $video_output : '' ),
			( '' !== $thumbnail_track_output ? sprintf( ' data-image="%1$s"', esc_attr( $thumbnail_track_output ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Video_Slider_Item;

class ET_Builder_Module_Text extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Text', 'et_builder' );
		$this->slug = 'et_pb_text';

		$this->whitelisted_fields = array(
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
		);

		$this->fields_defaults = array(
			'background_layout' => array( 'light' ),
			'text_orientation'  => array( 'left' ),
		);

		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'fonts' => array(
				'text'   => array(
					'label'    => __( 'Text', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'background_layout' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This controls the how your text is aligned within the module.', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Here you can create the content that will be used within the module.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'max_width' => array(
				'label'           => __( 'Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id            = $this->shortcode_atts['module_id'];
		$module_class         = $this->shortcode_atts['module_class'];
		$background_layout    = $this->shortcode_atts['background_layout'];
		$text_orientation     = $this->shortcode_atts['text_orientation'];
		$max_width            = $this->shortcode_atts['max_width'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );

		if ( '' !== $max_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'max-width: %1$s;',
					esc_html( et_builder_process_range_value( $max_width ) )
				),
			) );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$output = sprintf(
			'<div%3$s class="et_pb_text%2$s%4$s">
				%1$s
			</div> <!-- .et_pb_text -->',
			$this->shortcode_content,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Text;

class ET_Builder_Module_Blurb extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Blurb', 'et_builder' );
		$this->slug = 'et_pb_blurb';
		$this->main_css_element = '%%order_class%%.et_pb_blurb';

		$this->whitelisted_fields = array(
			'title',
			'url',
			'url_new_window',
			'use_icon',
			'font_icon',
			'icon_color',
			'use_circle',
			'circle_color',
			'use_circle_border',
			'circle_border_color',
			'image',
			'alt',
			'icon_placement',
			'animation',
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
			'use_icon_font_size',
			'icon_font_size',
		);

		$et_accent_color = et_builder_accent_color();

		$this->fields_defaults = array(
			'url_new_window'      => array( 'off' ),
			'use_icon'            => array( 'off' ),
			'icon_color'          => array( $et_accent_color, 'add_default_setting' ),
			'use_circle'          => array( 'off' ),
			'circle_color'        => array( $et_accent_color, 'only_default_setting' ),
			'use_circle_border'   => array( 'off' ),
			'circle_border_color' => array( $et_accent_color, 'only_default_setting' ),
			'icon_placement'      => array( 'top' ),
			'animation'           => array( 'top' ),
			'background_layout'   => array( 'light' ),
			'text_orientation'    => array( 'center' ),
			'use_icon_font_size'  => array( 'off' ),
		);

		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h4, {$this->main_css_element} h4 a",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);
		$this->custom_css_options = array(
			'blurb_image' => array(
				'label'    => __( 'Blurb Image', 'et_builder' ),
				'selector' => '.et_pb_main_blurb_image',
			),
			'blurb_title' => array(
				'label'    => __( 'Blurb Title', 'et_builder' ),
				'selector' => 'h4',
			),
			'blurb_content' => array(
				'label'    => __( 'Blurb Content', 'et_builder' ),
				'selector' => '.et_pb_blurb_content',
			),
		);
	}

	function get_fields() {
		$et_accent_color = et_builder_accent_color();

		$image_icon_placement = array(
			'top' => __( 'Top', 'et_builder' ),
		);

		if ( ! is_rtl() ) {
			$image_icon_placement['left'] = __( 'Left', 'et_builder' );
		} else {
			$image_icon_placement['right'] = __( 'Right', 'et_builder' );
		}

		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'The title of your blurb will appear in bold below your blurb image.', 'et_builder' ),
			),
			'url' => array(
				'label'           => __( 'Url', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'If you would like to make your blurb a link, input your destination URL here.', 'et_builder' ),
			),
			'url_new_window' => array(
				'label'           => __( 'Url Opens', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'In The Same Window', 'et_builder' ),
					'on'  => __( 'In The New Tab', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
			),
			'use_icon' => array(
				'label'           => __( 'Use Icon', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_font_icon',
					'#et_pb_use_circle',
					'#et_pb_icon_color',
					'#et_pb_image',
					'#et_pb_alt',
				),
				'description' => __( 'Here you can choose whether icon set below should be used.', 'et_builder' ),
			),
			'font_icon' => array(
				'label'               => __( 'Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'basic_option',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'description'         => __( 'Choose an icon to display with your blurb.', 'et_builder' ),
				'depends_default'     => true,
			),
			'icon_color' => array(
				'label'             => __( 'Icon Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'Here you can define a custom color for your icon.', 'et_builder' ),
				'depends_default'   => true,
			),
			'use_circle' => array(
				'label'           => __( 'Circle Icon', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_use_circle_border',
					'#et_pb_circle_color',
				),
				'description' => __( 'Here you can choose whether icon set above should display within a circle.', 'et_builder' ),
				'depends_default'   => true,
			),
			'circle_color' => array(
				'label'           => __( 'Circle Color', 'et_builder' ),
				'type'            => 'color',
				'description'     => __( 'Here you can define a custom color for the icon circle.', 'et_builder' ),
				'depends_default' => true,
			),
			'use_circle_border' => array(
				'label'           => __( 'Show Circle Border', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_circle_border_color',
				),
				'description' => __( 'Here you can choose whether if the icon circle border should display.', 'et_builder' ),
				'depends_default'   => true,
			),
			'circle_border_color' => array(
				'label'           => __( 'Circle Border Color', 'et_builder' ),
				'type'            => 'color',
				'description'     => __( 'Here you can define a custom color for the icon circle border.', 'et_builder' ),
				'depends_default' => true,
			),
			'image' => array(
				'label'              => __( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'depends_show_if'    => 'off',
				'description'        => __( 'Upload an image to display at the top of your blurb.', 'et_builder' ),
			),
			'alt' => array(
				'label'           => __( 'Image Alt Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define the HTML ALT text for your image here.', 'et_builder' ),
				'depends_show_if' => 'off',
			),
			'icon_placement' => array(
				'label'             => __( 'Image/Icon Placement', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => $image_icon_placement,
				'description'       => __( 'Here you can choose where to place the icon.', 'et_builder' ),
			),
			'animation' => array(
				'label'             => __( 'Image/Icon Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'top'    => __( 'Top To Bottom', 'et_builder' ),
					'left'   => __( 'Left To Right', 'et_builder' ),
					'right'  => __( 'Right To Left', 'et_builder' ),
					'bottom' => __( 'Bottom To Top', 'et_builder' ),
					'off'    => __( 'No Animation', 'et_builder' ),
				),
				'description'       => __( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This will control how your blurb text is aligned.', 'et_builder' ),
			),
			'content_new' => array(
				'label'             => __( 'Content', 'et_builder' ),
				'type'              => 'tiny_mce',
				'option_category'   => 'basic_option',
				'description'       => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'             => __( 'Admin Label', 'et_builder' ),
				'type'              => 'text',
				'description'       => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
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
			'max_width' => array(
				'label'           => __( 'Image Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'use_icon_font_size' => array(
				'label'           => __( 'Use Icon Font Size', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'font_option',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_icon_font_size',
				),
				'tab_slug' => 'advanced',
			),
			'icon_font_size' => array(
				'label'           => __( 'Icon Font Size', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'depends_default' => true,
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id             = $this->shortcode_atts['module_id'];
		$module_class          = $this->shortcode_atts['module_class'];
		$title                 = $this->shortcode_atts['title'];
		$url                   = $this->shortcode_atts['url'];
		$image                 = $this->shortcode_atts['image'];
		$url_new_window        = $this->shortcode_atts['url_new_window'];
		$alt                   = $this->shortcode_atts['alt'];
		$background_layout     = $this->shortcode_atts['background_layout'];
		$text_orientation      = $this->shortcode_atts['text_orientation'];
		$animation             = $this->shortcode_atts['animation'];
		$icon_placement        = $this->shortcode_atts['icon_placement'];
		$font_icon             = $this->shortcode_atts['font_icon'];
		$use_icon              = $this->shortcode_atts['use_icon'];
		$use_circle            = $this->shortcode_atts['use_circle'];
		$use_circle_border     = $this->shortcode_atts['use_circle_border'];
		$icon_color            = $this->shortcode_atts['icon_color'];
		$circle_color          = $this->shortcode_atts['circle_color'];
		$circle_border_color   = $this->shortcode_atts['circle_border_color'];
		$max_width             = $this->shortcode_atts['max_width'];
		$use_icon_font_size    = $this->shortcode_atts['use_icon_font_size'];
		$icon_font_size        = $this->shortcode_atts['icon_font_size'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( 'off' !== $use_icon_font_size ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et-pb-icon',
				'declaration' => sprintf(
					'font-size: %1$s;',
					esc_html( et_builder_process_range_value( $icon_font_size ) )
				),
			) );
		}

		if ( '' !== $max_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_main_blurb_image img',
				'declaration' => sprintf(
					'max-width: %1$s;',
					esc_html( et_builder_process_range_value( $max_width ) )
				),
			) );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		if ( is_rtl() && 'left' === $icon_placement ) {
			$icon_placement = 'right';
		}

		if ( '' !== $title && '' !== $url ) {
			$title = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
				esc_url( $url ),
				esc_html( $title ),
				( 'on' === $url_new_window ? ' target="_blank"' : '' )
			);
		}

		if ( '' !== $title ) {
			$title = "<h4>{$title}</h4>";
		}

		if ( '' !== trim( $image ) || '' !== $font_icon ) {
			if ( 'off' === $use_icon ) {
				$image = sprintf(
					'<img src="%1$s" alt="%2$s" class="et-waypoint%3$s" />',
					esc_attr( $image ),
					esc_attr( $alt ),
					esc_attr( " et_pb_animation_{$animation}" )
				);
			} else {
				$icon_style = sprintf( 'color: %1$s;', esc_attr( $icon_color ) );

				if ( 'on' === $use_circle ) {
					$icon_style .= sprintf( ' background-color: %1$s;', esc_attr( $circle_color ) );

					if ( 'on' === $use_circle_border ) {
						$icon_style .= sprintf( ' border-color: %1$s;', esc_attr( $circle_border_color ) );
					}
				}

				$image = sprintf(
					'<span class="et-pb-icon et-waypoint%2$s%3$s%4$s" style="%5$s">%1$s</span>',
					esc_attr( et_pb_process_font_icon( $font_icon ) ),
					esc_attr( " et_pb_animation_{$animation}" ),
					( 'on' === $use_circle ? ' et-pb-icon-circle' : '' ),
					( 'on' === $use_circle && 'on' === $use_circle_border ? ' et-pb-icon-circle-border' : '' ),
					$icon_style
				);
			}

			$image = sprintf(
				'<div class="et_pb_main_blurb_image">%1$s</div>',
				( '' !== $url
					? sprintf(
						'<a href="%1$s"%3$s>%2$s</a>',
						esc_url( $url ),
						$image,
						( 'on' === $url_new_window ? ' target="_blank"' : '' )
					)
					: $image
				)
			);
		}

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$output = sprintf(
			'<div%5$s class="et_pb_blurb%4$s%6$s%7$s">
				<div class="et_pb_blurb_content">
					%2$s
					<div class="et_pb_blurb_container">
						%3$s
						%1$s
					</div>
				</div> <!-- .et_pb_blurb_content -->
			</div> <!-- .et_pb_blurb -->',
			$this->shortcode_content,
			$image,
			$title,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			sprintf( ' et_pb_blurb_position_%1$s', esc_attr( $icon_placement ) )
		);

		return $output;
	}
}
new ET_Builder_Module_Blurb;

class ET_Builder_Module_Tabs extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Tabs', 'et_builder' );
		$this->slug            = 'et_pb_tabs';
		$this->child_slug      = 'et_pb_tab';
		$this->child_item_text = __( 'Tab', 'et_builder' );

		$this->whitelisted_fields = array(
			'admin_label',
			'module_id',
			'module_class',
			'active_tab_background_color',
			'inactive_tab_background_color',
		);

		$this->main_css_element = '%%order_class%%.et_pb_tabs';
		$this->advanced_options = array(
			'fonts' => array(
				'tab' => array(
					'label'    => __( 'Tab', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_tabs_controls li",
						'color' => "{$this->main_css_element} .et_pb_tabs_controls li a",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_all_tabs",
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_all_tabs",
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
		);
		$this->custom_css_options = array(
			'tabs_controls' => array(
				'label'    => __( 'Tabs Controls', 'et_builder' ),
				'selector' => '.et_pb_tabs_controls',
			),
			'tab' => array(
				'label'    => __( 'Tab', 'et_builder' ),
				'selector' => '.et_pb_tabs_controls li',
			),
			'active_tab' => array(
				'label'    => __( 'Active Tab', 'et_builder' ),
				'selector' => '.et_pb_tabs_controls li.et_pb_tab_active',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'active_tab_background_color' => array(
				'label'             => __( 'Active Tab Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'inactive_tab_background_color' => array(
				'label'             => __( 'Inactive Tab Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                         = $this->shortcode_atts['module_id'];
		$module_class                      = $this->shortcode_atts['module_class'];
		$active_tab_background_color       = $this->shortcode_atts['active_tab_background_color'];
		$inactive_tab_background_color     = $this->shortcode_atts['inactive_tab_background_color'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$all_tabs_content = $this->shortcode_content;

		global $et_pb_tab_titles;
		global $et_pb_tab_classes;

		if ( '' !== $inactive_tab_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_tabs_controls li',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $inactive_tab_background_color )
				),
			) );
		}

		if ( '' !== $active_tab_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_tabs_controls li.et_pb_tab_active',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $active_tab_background_color )
				),
			) );
		}

		$tabs = '';

		$i = 0;
		if ( ! empty( $et_pb_tab_titles ) ) {
			foreach ( $et_pb_tab_titles as $tab_title ){
				++$i;
				$tabs .= sprintf( '<li class="%3$s%1$s"><a href="#">%2$s</a></li>',
					( 1 == $i ? ' et_pb_tab_active' : '' ),
					esc_html( $tab_title ),
					esc_attr( ltrim( $et_pb_tab_classes[ $i-1 ] ) )
				);
			}
		}

		$et_pb_tab_titles = $et_pb_tab_classes = array();

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_tabs%4$s">
				<ul class="et_pb_tabs_controls clearfix">
					%1$s
				</ul>
				<div class="et_pb_all_tabs">
					%2$s
				</div> <!-- .et_pb_all_tabs -->
			</div> <!-- .et_pb_tabs -->',
			$tabs,
			$all_tabs_content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Tabs;

class ET_Builder_Module_Tabs_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Tab', 'et_builder' );
		$this->slug                        = 'et_pb_tab';
		$this->type                        = 'child';
		$this->child_title_var             = 'title';

		$this->whitelisted_fields = array(
			'title',
			'content_new',
		);

		$this->advanced_setting_title_text = __( 'New Tab', 'et_builder' );
		$this->settings_text               = __( 'Tab Settings', 'et_builder' );
		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'fonts' => array(
				'tab' => array(
					'label'    => __( 'Tab', 'et_builder' ),
					'css'      => array(
						'main'      => ".et_pb_tabs .et_pb_tabs_controls li{$this->main_css_element}",
						'color'     => ".et_pb_tabs .et_pb_tabs_controls li{$this->main_css_element} a",
						'important' => 'all',
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
			'background' => array(
				'css' => array(
					'main' => "div{$this->main_css_element}",
					'important' => 'all',
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'       => __( 'Title', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'The title will be used within the tab button for this tab.', 'et_builder' ),
			),
			'content_new' => array(
				'label'       => __( 'Content', 'et_builder' ),
				'type'        => 'tiny_mce',
				'description' => __( 'Here you can define the content that will be placed within the current tab.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_tab_titles;
		global $et_pb_tab_classes;

		$title = $this->shortcode_atts['title'];

		$module_class = ET_Builder_Element::add_module_order_class( '', $function_name );

		$i = 0;

		$et_pb_tab_titles[]  = '' !== $title ? $title : __( 'Tab', 'et_builder' );
		$et_pb_tab_classes[] = $module_class;

		$output = sprintf(
			'<div class="et_pb_tab clearfix%2$s%3$s">
				%1$s
			</div> <!-- .et_pb_tab -->',
			$this->shortcode_content,
			( 1 === count( $et_pb_tab_titles ) ? ' et_pb_active_content' : '' ),
			esc_attr( $module_class )
		);

		return $output;
	}
}
new ET_Builder_Module_Tabs_Item;

class ET_Builder_Module_Slider extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Slider', 'et_builder' );
		$this->slug            = 'et_pb_slider';
		$this->child_slug      = 'et_pb_slide';
		$this->child_item_text = __( 'Slide', 'et_builder' );

		$this->whitelisted_fields = array(
			'show_arrows',
			'show_pagination',
			'auto',
			'auto_speed',
			'auto_ignore_hover',
			'parallax',
			'parallax_method',
			'remove_inner_shadow',
			'background_position',
			'background_size',
			'admin_label',
			'module_id',
			'module_class',
			'top_padding',
			'bottom_padding',
			'hide_content_on_mobile',
			'hide_cta_on_mobile',
			'show_image_video_mobile',
		);

		$this->fields_defaults = array(
			'show_arrows'             => array( 'on' ),
			'show_pagination'         => array( 'on' ),
			'auto'                    => array( 'off' ),
			'auto_speed'              => array( '7000' ),
			'auto_ignore_hover'       => array( 'off' ),
			'parallax'                => array( 'off' ),
			'parallax_method'         => array( 'off' ),
			'remove_inner_shadow'     => array( 'off' ),
			'background_position'     => array( 'default' ),
			'background_size'         => array( 'default' ),
			'hide_content_on_mobile'  => array( 'off' ),
			'hide_cta_on_mobile'      => array( 'off' ),
			'show_image_video_mobile' => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_slider';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_slide_description .et_pb_slide_title",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element}",
						'main' => "{$this->main_css_element} .et_pb_slide_content",
					),
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
		$this->custom_css_options = array(
			'slide_description' => array(
				'label'    => __( 'Slide Description', 'et_builder' ),
				'selector' => '.et_pb_slide_description',
			),
			'slide_title' => array(
				'label'    => __( 'Slide Title', 'et_builder' ),
				'selector' => '.et_pb_slide_description .et_pb_slide_title',
			),
			'slide_button' => array(
				'label'    => __( 'Slide Button', 'et_builder' ),
				'selector' => 'a.et_pb_more_button',
			),
			'slide_controllers' => array(
				'label'    => __( 'Slide Controllers', 'et_builder' ),
				'selector' => '.et-pb-controllers',
			),
			'slide_active_controller' => array(
				'label'    => __( 'Slide Active Controller', 'et_builder' ),
				'selector' => '.et-pb-controllers .et-pb-active-control',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'show_arrows'         => array(
				'label'           => __( 'Arrows', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Show Arrows', 'et_builder' ),
					'off' => __( 'Hide Arrows', 'et_builder' ),
				),
				'description'     => __( 'This setting will turn on and off the navigation arrows.', 'et_builder' ),
			),
			'show_pagination' => array(
				'label'             => __( 'Controls', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Show Slider Controls', 'et_builder' ),
					'off' => __( 'Hide Slider Controls', 'et_builder' ),
				),
				'description'       => __( 'This setting will turn on and off the circle buttons at the bottom of the slider.', 'et_builder' ),
			),
			'auto' => array(
				'label'           => __( 'Automatic Animation', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'affects' => array(
					'#et_pb_auto_speed, #et_pb_auto_ignore_hover',
				),
				'description'        => __( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
			),
			'auto_speed' => array(
				'label'             => __( 'Automatic Animation Speed (in ms)', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_default'   => true,
				'description'       => __( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation.", 'et_builder' ),
			),
			'auto_ignore_hover' => array(
				'label'           => __( 'Continue Automatic Slide on Hover', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'depends_default' => true,
				'options'         => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'description' => __( 'Turning this on will allow automatic sliding to continue on mouse hover.', 'et_builder' ),
			),
			'parallax' => array(
				'label'           => __( 'Use Parallax effect', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_parallax_method',
					'#et_pb_background_position',
					'#et_pb_background_size',
				),
				'description'        => __( 'Enabling this option will give your background images a fixed position as you scroll.', 'et_builder' ),
			),
			'parallax_method' => array(
				'label'           => __( 'Parallax method', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'CSS', 'et_builder' ),
					'on'  => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Define the method, used for the parallax effect.', 'et_builder' ),
			),
			'remove_inner_shadow' => array(
				'label'           => __( 'Remove Inner Shadow', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
			),
			'background_position' => array(
				'label'           => __( 'Background Image Position', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options' => array(
					'default'       => __( 'Default', 'et_builder' ),
					'top_left'      => __( 'Top Left', 'et_builder' ),
					'top_center'    => __( 'Top Center', 'et_builder' ),
					'top_right'     => __( 'Top Right', 'et_builder' ),
					'center_right'  => __( 'Center Right', 'et_builder' ),
					'center_left'   => __( 'Center Left', 'et_builder' ),
					'bottom_left'   => __( 'Bottom Left', 'et_builder' ),
					'bottom_center' => __( 'Bottom Center', 'et_builder' ),
					'bottom_right'  => __( 'Bottom Right', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
			),
			'background_size' => array(
				'label'           => __( 'Background Image Size', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'default' => __( 'Default', 'et_builder' ),
					'contain' => __( 'Fit', 'et_builder' ),
					'initial' => __( 'Actual Size', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'top_padding' => array(
				'label'           => __( 'Top Padding', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'bottom_padding' => array(
				'label'           => __( 'Bottom Padding', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'hide_content_on_mobile' => array(
				'label'           => __( 'Hide Content On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'hide_cta_on_mobile' => array(
				'label'           => __( 'Hide CTA On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'show_image_video_mobile' => array(
				'label'           => __( 'Show Image / Video On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'        => 'advanced',
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

		$et_pb_slider_item_num = 0;

		$parallax        = $this->shortcode_atts['parallax'];
		$parallax_method = $this->shortcode_atts['parallax_method'];
		$hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
		$hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
		$button_custom           = $this->shortcode_atts['custom_button'];
		$custom_icon             = $this->shortcode_atts['button_icon'];

		$et_pb_slider_has_video = false;

		$et_pb_slider_parallax = $parallax;

		$et_pb_slider_parallax_method = $parallax_method;

		$et_pb_slider_hide_mobile = array(
			'hide_content_on_mobile'  => $hide_content_on_mobile,
			'hide_cta_on_mobile'      => $hide_cta_on_mobile,
		);

		$et_pb_slider_custom_icon = 'on' === $button_custom ? $custom_icon : '';

	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$show_arrows             = $this->shortcode_atts['show_arrows'];
		$show_pagination         = $this->shortcode_atts['show_pagination'];
		$parallax                = $this->shortcode_atts['parallax'];
		$parallax_method         = $this->shortcode_atts['parallax_method'];
		$auto                    = $this->shortcode_atts['auto'];
		$auto_speed              = $this->shortcode_atts['auto_speed'];
		$auto_ignore_hover       = $this->shortcode_atts['auto_ignore_hover'];
		$top_padding             = $this->shortcode_atts['top_padding'];
		$body_font_size 		 = $this->shortcode_atts['body_font_size'];
		$bottom_padding          = $this->shortcode_atts['bottom_padding'];
		$remove_inner_shadow     = $this->shortcode_atts['remove_inner_shadow'];
		$hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
		$hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
		$show_image_video_mobile = $this->shortcode_atts['show_image_video_mobile'];
		$background_position     = $this->shortcode_atts['background_position'];
		$background_size         = $this->shortcode_atts['background_size'];

		global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon;

		$content = $this->shortcode_content;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $top_padding ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
				'declaration' => sprintf(
					'padding-top: %1$s;',
					esc_html( et_builder_process_range_value( $top_padding ) )
				),
			) );
		}

		if ( '' !== $bottom_padding ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
				'declaration' => sprintf(
					'padding-bottom: %1$s;',
					esc_html( et_builder_process_range_value( $bottom_padding ) )
				),
			) );
		}

		if ( '' !== $bottom_padding || '' !== $top_padding ) {
			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
				'declaration' => 'padding-right: 0; padding-left: 0;',
			) );
		}

		if ( 'default' !== $background_position && 'off' === $parallax ) {
			$processed_position = str_replace( '_', ' ', $background_position );

			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide',
				'declaration' => sprintf(
					'background-position: %1$s;',
					esc_html( $processed_position )
				),
			) );
		}

		if ( 'default' !== $background_size && 'off' === $parallax ) {
			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide',
				'declaration' => sprintf(
					'-moz-background-size: %1$s;
					-webkit-background-size: %1$s;
					background-size: %1$s;',
					esc_html( $background_size )
				),
			) );
		}

		$fullwidth = 'et_pb_fullwidth_slider' === $function_name ? 'on' : 'off';

		$class  = '';
		$class .= 'off' === $fullwidth ? ' et_pb_slider_fullwidth_off' : '';
		$class .= 'off' === $show_arrows ? ' et_pb_slider_no_arrows' : '';
		$class .= 'off' === $show_pagination ? ' et_pb_slider_no_pagination' : '';
		$class .= 'on' === $parallax ? ' et_pb_slider_parallax' : '';
		$class .= 'on' === $auto ? ' et_slider_auto et_slider_speed_' . esc_attr( $auto_speed ) : '';
		$class .= 'on' === $auto_ignore_hover ? ' et_slider_auto_ignore_hover' : '';
		$class .= 'on' === $remove_inner_shadow ? ' et_pb_slider_no_shadow' : '';
		$class .= 'on' === $show_image_video_mobile ? ' et_pb_slider_show_image' : '';

		$output = sprintf(
			'<div%4$s class="et_pb_module et_pb_slider%1$s%3$s%5$s">
				<div class="et_pb_slides">
					%2$s
				</div> <!-- .et_pb_slides -->
			</div> <!-- .et_pb_slider -->
			',
			$class,
			$content,
			( $et_pb_slider_has_video ? ' et_pb_preload' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Slider;

class ET_Builder_Module_Slider_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Slide', 'et_builder' );
		$this->slug                        = 'et_pb_slide';
		$this->type                        = 'child';
		$this->child_title_var             = 'admin_title';
		$this->child_title_fallback_var    = 'heading';

		$this->whitelisted_fields = array(
			'heading',
			'admin_title',
			'button_text',
			'button_link',
			'background_image',
			'background_position',
			'background_size',
			'background_color',
			'image',
			'alignment',
			'video_url',
			'image_alt',
			'background_layout',
			'video_bg_mp4',
			'video_bg_webm',
			'video_bg_width',
			'video_bg_height',
			'allow_player_pause',
			'content_new',
			'arrows_custom_color',
			'dot_nav_custom_color',
		);

		$this->fields_defaults = array(
			'button_link'         => array( '#' ),
			'background_position' => array( 'default' ),
			'background_size'     => array( 'default' ),
			'background_color'    => array( '#ffffff', 'only_default_setting' ),
			'alignment'           => array( 'center' ),
			'background_layout'   => array( 'dark' ),
			'allow_player_pause'  => array( 'off' ),
		);

		$this->advanced_setting_title_text = __( 'New Slide', 'et_builder' );
		$this->settings_text               = __( 'Slide Settings', 'et_builder' );
		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => ".et_pb_slider {$this->main_css_element} .et_pb_slide_description .et_pb_slide_title",
						'important' => 'all',
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main'        => "{$this->main_css_element} .et_pb_slide_content",
						'line_height' => "{$this->main_css_element} p",
						'important'   => 'all',
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
					'css'      => array(
						'main' => ".et_pb_slider {$this->main_css_element}.et_pb_slide .et_pb_button",
					),
				),
			),
		);

		$this->custom_css_options = array(
			'slide_title' => array(
				'label'    => __( 'Slide Title', 'et_builder' ),
				'selector' => '.et_pb_slide_description h2',
			),
			'slide_description' => array(
				'label'    => __( 'Slide Description', 'et_builder' ),
				'selector' => '.et_pb_slide_description',
			),
			'slide_button' => array(
				'label'    => __( 'Slide Button', 'et_builder' ),
				'selector' => 'a.et_pb_more_button',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'heading' => array(
				'label'           => __( 'Heading', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define the title text for your slide.', 'et_builder' ),
			),
			'button_text' => array(
				'label'           => __( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define the text for the slide button', 'et_builder' ),
			),
			'button_link' => array(
				'label'           => __( 'Button URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input a destination URL for the slide button.', 'et_builder' ),
			),
			'background_image' => array(
				'label'              => __( 'Background Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Image', 'et_builder' ),
				'update_text'        => __( 'Set As Background', 'et_builder' ),
				'description'        => __( 'If defined, this image will be used as the background for this module. To remove a background image, simply delete the URL from the settings field.', 'et_builder' ),
			),
			'background_position' => array(
				'label'           => __( 'Background Image Position', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'default'       => __( 'Default', 'et_builder' ),
					'center'        => __( 'Center', 'et_builder' ),
					'top_left'      => __( 'Top Left', 'et_builder' ),
					'top_center'    => __( 'Top Center', 'et_builder' ),
					'top_right'     => __( 'Top Right', 'et_builder' ),
					'center_right'  => __( 'Center Right', 'et_builder' ),
					'center_left'   => __( 'Center Left', 'et_builder' ),
					'bottom_left'   => __( 'Bottom Left', 'et_builder' ),
					'bottom_center' => __( 'Bottom Center', 'et_builder' ),
					'bottom_right'  => __( 'Bottom Right', 'et_builder' ),
				),
			),
			'background_size' => array(
				'label'           => __( 'Background Image Size', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'default' => __( 'Default', 'et_builder' ),
					'cover'   => __( 'Cover', 'et_builder' ),
					'contain' => __( 'Fit', 'et_builder' ),
					'initial' => __( 'Actual Size', 'et_builder' ),
				),
			),
			'background_color' => array(
				'label'       => __( 'Background Color', 'et_builder' ),
				'type'        => 'color-alpha',
				'description' => __( 'Use the color picker to choose a background color for this module.', 'et_builder' ),
			),
			'image' => array(
				'label'              => __( 'Slide Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'configuration',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose a Slide Image', 'et_builder' ),
				'update_text'        => __( 'Set As Slide Image', 'et_builder' ),
				'description'        => __( 'If defined, this slide image will appear to the left of your slide text. Upload an image, or leave blank for a text-only slide.', 'et_builder' ),
			),
			'alignment' => array(
				'label'           => __( 'Slide Image Vertical Alignment', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'center' => __( 'Center', 'et_builder' ),
					'bottom' => __( 'Bottom', 'et_builder' ),
				),
				'description' => __( 'This setting determines the vertical alignment of your slide image. Your image can either be vertically centered, or aligned to the bottom of your slide.', 'et_builder' ),
			),
			'video_url' => array(
				'label'           => __( 'Slide Video', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'If defined, this video will appear to the left of your slide text. Enter youtube or vimeo page url, or leave blank for a text-only slide.', 'et_builder' ),
			),
			'image_alt' => array(
				'label'           => __( 'Image Alternative Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'If you have a slide image defined, input your HTML ALT text for the image here.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'dark'  => __( 'Light', 'et_builder' ),
					'light' => __( 'Dark', 'et_builder' ),
				),
				'description'     => __( 'Here you can choose whether your text is light or dark. If you have a slide with a dark background, then choose light text. If you have a light background, then use dark text.' , 'et_builder' ),
			),
			'video_bg_mp4' => array(
				'label'              => __( 'Background Video MP4', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Video MP4 File', 'et_builder' ),
				'update_text'        => __( 'Set As Background Video', 'et_builder' ),
				'description'        => __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .MP4 version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ),
			),
			'video_bg_webm' => array(
				'label'              => __( 'Background Video Webm', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => __( 'Upload a video', 'et_builder' ),
				'choose_text'        => __( 'Choose a Background Video WEBM File', 'et_builder' ),
				'update_text'        => __( 'Set As Background Video', 'et_builder' ),
				'description'        => __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .WEBM version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ),
			),
			'video_bg_width' => array(
				'label'           => __( 'Background Video Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'In order for videos to be sized correctly, you must input the exact width (in pixels) of your video here.' ,'et_builder' ),
				'validate_unit'   => true,
			),
			'video_bg_height' => array(
				'label'           => __( 'Background Video Height', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'In order for videos to be sized correctly, you must input the exact height (in pixels) of your video here.' ,'et_builder' ),
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
				'description'     => __( 'Allow video to be paused by other players when they begin playing' ,'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Input your main slide text content here.', 'et_builder' ),
			),
			'arrows_custom_color' => array(
				'label'        => __( 'Arrows Custom Color', 'et_builder' ),
				'type'         => 'color',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'dot_nav_custom_color' => array(
				'label'        => __( 'Dot Nav Custom Color', 'et_builder' ),
				'type'         => 'color',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'admin_title' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the slide in the builder for easy identification.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$alignment            = $this->shortcode_atts['alignment'];
		$heading              = $this->shortcode_atts['heading'];
		$button_text          = $this->shortcode_atts['button_text'];
		$button_link          = $this->shortcode_atts['button_link'];
		$background_color     = $this->shortcode_atts['background_color'];
		$background_image     = $this->shortcode_atts['background_image'];
		$image                = $this->shortcode_atts['image'];
		$image_alt            = $this->shortcode_atts['image_alt'];
		$background_layout    = $this->shortcode_atts['background_layout'];
		$video_bg_webm        = $this->shortcode_atts['video_bg_webm'];
		$video_bg_mp4         = $this->shortcode_atts['video_bg_mp4'];
		$video_bg_width       = $this->shortcode_atts['video_bg_width'];
		$video_bg_height      = $this->shortcode_atts['video_bg_height'];
		$video_url            = $this->shortcode_atts['video_url'];
		$allow_player_pause   = $this->shortcode_atts['allow_player_pause'];
		$dot_nav_custom_color = $this->shortcode_atts['dot_nav_custom_color'];
		$arrows_custom_color  = $this->shortcode_atts['arrows_custom_color'];
		$custom_icon          = $this->shortcode_atts['button_icon'];
		$button_custom        = $this->shortcode_atts['custom_button'];
		$background_position  = $this->shortcode_atts['background_position'];
		$background_size      = $this->shortcode_atts['background_size'];

		global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

		$background_video = '';

		$et_pb_slider_item_num++;

		$hide_on_mobile_class = self::HIDE_ON_MOBILE;

		$first_video = false;

		$custom_slide_icon = 'on' === $button_custom && '' !== $custom_icon ? $custom_icon : $et_pb_slider_custom_icon;

		if ( '' !== $video_bg_mp4 || '' !== $video_bg_webm ) {
			if ( ! $et_pb_slider_has_video )
				$first_video = true;

			$background_video = sprintf(
				'<div class="et_pb_section_video_bg%2$s%3$s">
					%1$s
				</div>',
				do_shortcode( sprintf( '
					<video loop="loop" autoplay="autoplay"%3$s%4$s>
						%1$s
						%2$s
					</video>',
					( '' !== $video_bg_mp4 ? sprintf( '<source type="video/mp4" src="%s" />', esc_attr( $video_bg_mp4 ) ) : '' ),
					( '' !== $video_bg_webm ? sprintf( '<source type="video/webm" src="%s" />', esc_attr( $video_bg_webm ) ) : '' ),
					( '' !== $video_bg_width ? sprintf( ' width="%s"', esc_attr( $video_bg_width ) ) : '' ),
					( '' !== $video_bg_height ? sprintf( ' height="%s"', esc_attr( $video_bg_height ) ) : '' ),
					( '' !== $background_image ? sprintf( ' poster="%s"', esc_attr( $background_image ) ) : '' )
				) ),
				( $first_video ? ' et_pb_first_video' : '' ),
				( 'on' === $allow_player_pause ? ' et_pb_allow_player_pause' : '' )
			);

			$et_pb_slider_has_video = true;

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		if ( '' !== $heading ) {
			if ( '#' !== $button_link ) {
				$heading = sprintf( '<a href="%1$s">%2$s</a>',
					esc_url( $button_link ),
					$heading
				);
			}

			$heading = '<h2 class="et_pb_slide_title">' . $heading . '</h2>';
		}

		$button = '';
		if ( '' !== $button_text ) {
			$button = sprintf( '<a href="%1$s" class="et_pb_more_button et_pb_button%3$s%5$s"%4$s>%2$s</a>',
				esc_attr( $button_link ),
				esc_html( $button_text ),
				( 'on' === $et_pb_slider_hide_mobile['hide_cta_on_mobile'] ? esc_attr( " {$hide_on_mobile_class}" ) : '' ),
				'' !== $custom_slide_icon ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_slide_icon ) )
				) : '',
				'' !== $custom_slide_icon ? ' et_pb_custom_button_icon' : ''
			);
		}

		$style = $class = '';

		if ( '' !== $background_color ) {
			$style .= sprintf( 'background-color:%s;',
				esc_attr( $background_color )
			);
		}

		if ( '' !== $background_image && 'on' !== $et_pb_slider_parallax ) {
			$style .= sprintf( 'background-image:url(%s);',
				esc_attr( $background_image )
			);
		}

		$style = '' !== $style ? " style='{$style}'" : '';

		$image = '' !== $image
			? sprintf( '<div class="et_pb_slide_image"><img src="%1$s" alt="%2$s" /></div>',
				esc_attr( $image ),
				esc_attr( $image_alt )
			)
			: '';

		if ( '' !== $video_url ) {
			global $wp_embed;

			$video_embed = apply_filters( 'the_content', $wp_embed->shortcode( '', esc_url( $video_url ) ) );

			$video_embed = preg_replace('/<embed /','<embed wmode="transparent" ',$video_embed);
			$video_embed = preg_replace('/<\/object>/','<param name="wmode" value="transparent" /></object>',$video_embed);

			$image = sprintf( '<div class="et_pb_slide_video">%1$s</div>',
				$video_embed
			);
		}

		if ( '' !== $image ) $class = ' et_pb_slide_with_image';

		if ( '' !== $video_url ) $class .= ' et_pb_slide_with_video';

		$class .= " et_pb_bg_layout_{$background_layout}";

		if ( 'bottom' !== $alignment ) {
			$class .= " et_pb_media_alignment_{$alignment}";
		}

		$data_dot_nav_custom_color = '' !== $dot_nav_custom_color
			? sprintf( ' data-dots_color="%1$s"', esc_attr( $dot_nav_custom_color ) )
			: '';

		$data_arrows_custom_color = '' !== $arrows_custom_color
			? sprintf( ' data-arrows_color="%1$s"', esc_attr( $arrows_custom_color ) )
			: '';

		if ( 'default' !== $background_position && 'off' === $et_pb_slider_parallax ) {
			$processed_position = str_replace( '_', ' ', $background_position );

			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '.et_pb_slider %%order_class%%',
				'declaration' => sprintf(
					'background-position: %1$s;',
					esc_html( $processed_position )
				),
			) );
		}

		if ( 'default' !== $background_size && 'off' === $et_pb_slider_parallax ) {
			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '.et_pb_slider %%order_class%%',
				'declaration' => sprintf(
					'-moz-background-size: %1$s;
					-webkit-background-size: %1$s;
					background-size: %1$s;',
					esc_html( $background_size )
				),
			) );
		}

		$class = ET_Builder_Element::add_module_order_class( $class, $function_name );

		if ( 1 === $et_pb_slider_item_num ) {
			$class .= " et-pb-active-slide";
		}

		$output = sprintf(
			'<div class="et_pb_slide%6$s"%4$s%10$s%11$s>
				%8$s
				<div class="et_pb_container clearfix">
					%5$s
					<div class="et_pb_slide_description">
						%1$s
						<div class="et_pb_slide_content%9$s">%2$s</div>
						%3$s
					</div> <!-- .et_pb_slide_description -->
				</div> <!-- .et_pb_container -->
				%7$s
			</div> <!-- .et_pb_slide -->
			',
			$heading,
			$this->shortcode_content,
			$button,
			$style,
			$image,
			esc_attr( $class ),
			( '' !== $background_video ? $background_video : '' ),
			( '' !== $background_image && 'on' === $et_pb_slider_parallax ? sprintf( '<div class="et_parallax_bg%2$s" style="background-image: url(%1$s);"></div>', esc_attr( $background_image ), ( 'off' === $et_pb_slider_parallax_method ? ' et_pb_parallax_css' : '' ) ) : '' ),
			( 'on' === $et_pb_slider_hide_mobile['hide_content_on_mobile'] ? esc_attr( " {$hide_on_mobile_class}" ) : '' ),
			$data_dot_nav_custom_color,
			$data_arrows_custom_color
		);

		return $output;
	}
}
new ET_Builder_Module_Slider_Item;

class ET_Builder_Module_Testimonial extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Testimonial', 'et_builder' );
		$this->slug = 'et_pb_testimonial';

		$this->whitelisted_fields = array(
			'author',
			'job_title',
			'company_name',
			'url',
			'url_new_window',
			'portrait_url',
			'quote_icon',
			'use_background_color',
			'background_color',
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'quote_icon_color',
			'portrait_border_radius',
			'portrait_width',
			'portrait_height',
		);

		$this->fields_defaults = array(
			'url_new_window'       => array( 'off' ),
			'quote_icon'           => array( 'on' ),
			'use_background_color' => array( 'on' ),
			'background_color'     => array( '#f5f5f5', 'add_default_setting' ),
			'background_layout'    => array( 'dark' ),
			'text_orientation'     => array( 'left' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_testimonial';

		$this->advanced_options = array(
			'fonts' => array(
				'body'   => array(
					'label' => __( 'Body', 'et_builder' ),
					'css'   => array(
						'main' => "{$this->main_css_element} *",
					),
				),
			),
			'background' => array(
				'use_background_color' => false,
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);

		$this->custom_css_options = array(
			'testimonial_portrait' => array(
				'label'    => __( 'Testimonial Portrait', 'et_builder' ),
				'selector' => '.et_pb_testimonial_portrait',
			),
			'testimonial_description' => array(
				'label'    => __( 'Testimonial Description', 'et_builder' ),
				'selector' => '.et_pb_testimonial_description',
			),
			'testimonial_author' => array(
				'label'    => __( 'Testimonial Author', 'et_builder' ),
				'selector' => 'et_pb_testimonial_author',
			),
			'testimonial_meta' => array(
				'label'    => __( 'Testimonial Meta', 'et_builder' ),
				'selector' => '.et_pb_testimonial p:last-of-type',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'author' => array(
				'label'           => __( 'Author Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the name of the testimonial author.', 'et_builder' ),
			),
			'job_title' => array(
				'label'           => __( 'Job Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the job title.', 'et_builder' ),
			),
			'company_name' => array(
				'label'           => __( 'Company Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the name of the company.', 'et_builder' ),
			),
			'url' => array(
				'label'           => __( 'Author/Company URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the website of the author or leave blank for no link.', 'et_builder' ),
			),
			'url_new_window' => array(
				'label'           => __( 'URLs Open', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'In The Same Window', 'et_builder' ),
					'on'  => __( 'In The New Tab', 'et_builder' ),
				),
				'description'     => __( 'Choose whether or not the URL should open in a new window.', 'et_builder' ),
			),
			'portrait_url' => array(
				'label'              => __( 'Portrait Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'quote_icon' => array(
				'label'           => __( 'Quote Icon', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Visible', 'et_builder' ),
					'off' => __( 'Hidden', 'et_builder' ),
				),
				'description'     => __( 'Choose whether or not the quote icon should be visible.', 'et_builder' ),
			),
			'use_background_color' => array(
				'label'           => __( 'Use Background Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_background_color',
				),
				'description'     => __( 'Here you can choose whether background color setting below should be used or not.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'Here you can define a custom background color for your CTA.', 'et_builder' ),
				'depends_default'   => true,
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This will adjust the alignment of the module text.', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'quote_icon_color' => array(
				'label'             => __( 'Quote Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'portrait_border_radius' => array(
				'label'           => __( 'Portrait Border Radius', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
			),
			'portrait_width' => array(
				'label'           => __( 'Portrait Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '200',
					'step' => '1',
				),
			),
			'portrait_height' => array(
				'label'           => __( 'Portrait Height', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '200',
					'step' => '1',
				),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id              = $this->shortcode_atts['module_id'];
		$module_class           = $this->shortcode_atts['module_class'];
		$author                 = $this->shortcode_atts['author'];
		$job_title              = $this->shortcode_atts['job_title'];
		$portrait_url           = $this->shortcode_atts['portrait_url'];
		$company_name           = $this->shortcode_atts['company_name'];
		$url                    = $this->shortcode_atts['url'];
		$quote_icon             = $this->shortcode_atts['quote_icon'];
		$url_new_window         = $this->shortcode_atts['url_new_window'];
		$use_background_color   = $this->shortcode_atts['use_background_color'];
		$background_color       = $this->shortcode_atts['background_color'];
		$background_layout      = $this->shortcode_atts['background_layout'];
		$text_orientation       = $this->shortcode_atts['text_orientation'];
		$quote_icon_color       = $this->shortcode_atts['quote_icon_color'];
		$portrait_border_radius = $this->shortcode_atts['portrait_border_radius'];
		$portrait_width         = $this->shortcode_atts['portrait_width'];
		$portrait_height        = $this->shortcode_atts['portrait_height'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $portrait_border_radius ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_testimonial_portrait, %%order_class%% .et_pb_testimonial_portrait:before',
				'declaration' => sprintf(
					'-webkit-border-radius: %1$s; -moz-border-radius: %1$s; border-radius: %1$s;',
					esc_html( et_builder_process_range_value( $portrait_border_radius ) )
				),
			) );
		}

		if ( '' !== $portrait_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_testimonial_portrait',
				'declaration' => sprintf(
					'width: %1$s;',
					esc_html( et_builder_process_range_value( $portrait_width ) )
				),
			) );
		}

		if ( '' !== $portrait_height ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_testimonial_portrait',
				'declaration' => sprintf(
					'height: %1$s;',
					esc_html( et_builder_process_range_value( $portrait_height ) )
				),
			) );
		}

		$style = '';

		if ( 'on' === $use_background_color && $this->fields_defaults['background_color'][0] !== $background_color ) {
			$style .= sprintf(
				'background-color: %1$s !important; ',
				esc_html( $background_color )
			);
		}

		if ( '' !== $style ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_testimonial',
				'declaration' => rtrim( $style ),
			) );
		}

		if ( '' !== $quote_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_testimonial:before',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $quote_icon_color )
				),
			) );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$portrait_image = '';

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		if ( ! isset( $atts['quote_icon'] ) ) {
			$class .= "	et_pb_testimonial_old_layout";
		}

		if ( '' !== $portrait_url ) {
			$portrait_image = sprintf(
				'<div class="et_pb_testimonial_portrait" style="background-image: url(%1$s);">
				</div>',
				esc_attr( $portrait_url )
			);
		}

		if ( '' !== $url && ( '' !== $company_name || '' !== $author ) ) {
			$link_output = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
				esc_url( $url ),
				( '' !== $company_name ? esc_html( $company_name ) : esc_html( $author ) ),
				( 'on' === $url_new_window ? ' target="_blank"' : '' )
			);

			if ( '' !== $company_name ) {
				$company_name = $link_output;
			} else {
				$author = $link_output;
			}
		}

		$output = sprintf(
			'<div%3$s class="et_pb_testimonial%4$s%5$s%9$s%10$s%12$s clearfix"%11$s>
				%8$s
				<div class="et_pb_testimonial_description">
					<div class="et_pb_testimonial_description_inner">
					%1$s
					<strong class="et_pb_testimonial_author">%2$s</strong>
					<p class="et_pb_testimonial_meta">%6$s%7$s</p>
					</div> <!-- .et_pb_testimonial_description_inner -->
				</div> <!-- .et_pb_testimonial_description -->
			</div> <!-- .et_pb_testimonial -->',
			$this->shortcode_content,
			$author,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( 'off' === $quote_icon ? ' et_pb_icon_off' : '' ),
			( '' !== $job_title ? esc_html( $job_title ) : '' ),
			( '' !== $company_name
				? sprintf( '%2$s%1$s',
					$company_name,
					( '' !== $job_title ? ', ' : '' )
				)
				: ''
			),
			( '' !== $portrait_image ? $portrait_image : '' ),
			( '' === $portrait_image ? ' et_pb_testimonial_no_image' : '' ),
			esc_attr( $class ),
			( 'on' === $use_background_color
				? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
				: ''
			),
			( 'off' === $use_background_color ? ' et_pb_testimonial_no_bg' : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Testimonial;

class ET_Builder_Module_Pricing_Tables extends ET_Builder_Module {
	function init() {
		$this->name                 = __( 'Pricing Tables', 'et_builder' );
		$this->slug                 = 'et_pb_pricing_tables';
		$this->main_css_element 	= '%%order_class%%.et_pb_pricing';
		$this->child_slug           = 'et_pb_pricing_table';
		$this->child_item_text      = __( 'Pricing Table', 'et_builder' );

		$this->whitelisted_fields = array(
			'admin_label',
			'module_id',
			'module_class',
			'featured_table_background_color',
			'header_background_color',
			'featured_table_header_background_color',
			'featured_table_header_text_color',
			'featured_table_subheader_text_color',
			'featured_table_price_color',
			'featured_table_text_color',
			'show_bullet',
			'bullet_color',
			'featured_table_bullet_color',
			'remove_featured_drop_shadow',
			'center_list_items',
		);

		$this->fields_defaults = array(
			'show_bullet'                 => array( 'on' ),
			'remove_featured_drop_shadow' => array( 'off' ),
			'center_list_items'           => array( 'off' ),
		);

		$this->additional_shortcode = 'et_pb_pricing_item';
		$this->main_css_element = '%%order_class%%';
		$this->custom_css_options = array(
			'pricing_heading' => array(
				'label'    => __( 'Pricing Heading', 'et_builder' ),
				'selector' => '.et_pb_pricing_heading',
			),
			'pricing_title' => array(
				'label'    => __( 'Pricing Title', 'et_builder' ),
				'selector' => '.et_pb_pricing_heading h2',
			),
			'pricing_top' => array(
				'label'    => __( 'Pricing Top', 'et_builder' ),
				'selector' => '.et_pb_pricing_content_top',
			),
			'price' => array(
				'label'    => __( 'Price', 'et_builder' ),
				'selector' => '.et_pb_et_price',
			),
			'pricing_content' => array(
				'label'    => __( 'Pricing Content', 'et_builder' ),
				'selector' => '.et_pb_pricing_content',
			),
			'pricing_button' => array(
				'label'    => __( 'Pricing Button', 'et_builder' ),
				'selector' => '.et_pb_pricing_table_button',
			),
			'featured_table' => array(
				'label'    => __( 'Featured Table', 'et_builder' ),
				'selector' => '.et_pb_featured_table',
			),
		);
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_pricing_heading h2",
						'important' => 'all',
					),
					'letter_spacing' => array(
						'default' => '0px',
					),
				),
				'subheader' => array(
					'label'    => __( 'Subheader', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_best_value",
					),
					'letter_spacing' => array(
						'default' => '0px',
					),
					'line_height' => array(
						'default' => '1em',
					),
				),
				'currency_frequency' => array(
					'label'    => __( 'Currency &amp; Frequency', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_dollar_sign, {$this->main_css_element} .et_pb_frequency",
					),
				),
				'price' => array(
					'label'    => __( 'Price', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_sum",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_pricing li",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
					'font_size' => array(
						'default' => '14px',
					),
					'letter_spacing' => array(
						'default' => '0px',
					),
				),
			),
			'background' => array(
				'use_background_image' => false,
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_pricing_table",
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_pricing_table",
				),
				'additional_elements' => array(
					"{$this->main_css_element} .et_pb_pricing_content_top" => array( 'bottom' ),
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'featured_table_background_color' => array(
				'label'             => __( 'Featured Table Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 23,
			),
			'header_background_color' => array(
				'label'             => __( 'Table Header Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'featured_table_header_background_color' => array(
				'label'             => __( 'Featured Table Header Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 21,
			),
			'featured_table_header_text_color' => array(
				'label'             => __( 'Featured Table Header Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 20,
			),
			'featured_table_subheader_text_color' => array(
				'label'             => __( 'Featured Table Subheader Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 20,
			),
			'featured_table_price_color' => array(
				'label'             => __( 'Featured Table Price Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 20,
			),
			'featured_table_text_color' => array(
				'label'             => __( 'Featured Table Body Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 22,
			),
			'show_bullet' => array(
				'label'           => __( 'Show Bullet', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'tab_slug' => 'advanced',
				'affects'           => array(
					'#et_pb_bullet_color',
				),
			),
			'bullet_color' => array(
				'label'             => __( 'Bullet Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'depends_show_if'   => 'on',
			),
			'featured_table_bullet_color' => array(
				'label'             => __( 'Featured Table Bullet Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'priority'          => 22,
			),
			'remove_featured_drop_shadow' => array(
				'label'           => __( 'Remove Featured Table Drop Shadow', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug' => 'advanced',
				'priority'          => 24,
			),
			'center_list_items' => array(
				'label'           => __( 'Center List Items', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug' => 'advanced',
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_pricing_tables_num, $et_pb_pricing_tables_icon;

		$button_custom = $this->shortcode_atts['custom_button'];
		$custom_icon   = $this->shortcode_atts['button_icon'];

		$et_pb_pricing_tables_num = 0;

		$et_pb_pricing_tables_icon = 'on' === $button_custom ? $custom_icon : '';
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                              = $this->shortcode_atts['module_id'];
		$module_class                           = $this->shortcode_atts['module_class'];
		$featured_table_background_color        = $this->shortcode_atts['featured_table_background_color'];
		$featured_table_text_color              = $this->shortcode_atts['featured_table_text_color'];
		$header_background_color                = $this->shortcode_atts['header_background_color'];
		$featured_table_header_background_color = $this->shortcode_atts['featured_table_header_background_color'];
		$featured_table_header_text_color       = $this->shortcode_atts['featured_table_header_text_color'];
		$featured_table_subheader_text_color    = $this->shortcode_atts['featured_table_subheader_text_color'];
		$featured_table_price_color             = $this->shortcode_atts['featured_table_price_color'];
		$bullet_color                           = $this->shortcode_atts['bullet_color'];
		$featured_table_bullet_color            = $this->shortcode_atts['featured_table_bullet_color'];
		$remove_featured_drop_shadow            = $this->shortcode_atts['remove_featured_drop_shadow'];
		$center_list_items                      = $this->shortcode_atts['center_list_items'];
		$show_bullet                            = $this->shortcode_atts['show_bullet'];

		global $et_pb_pricing_tables_num, $et_pb_pricing_tables_icon;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( 'on' === $remove_featured_drop_shadow ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table',
				'declaration' => '-moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none;',
			) );
		}

		if ( 'off' === $show_bullet ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_pricing li span:before',
				'declaration' => 'display: none;',
			) );
		}

		if ( 'on' === $center_list_items ) {
			$module_class .= ' et_pb_centered_pricing_items';
		}

		if ( '' !== $featured_table_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $featured_table_background_color )
				),
			) );
		}

		if ( '' !== $header_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_pricing_heading',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $header_background_color )
				),
			) );
		}

		if ( '' !== $featured_table_header_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table .et_pb_pricing_heading',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $featured_table_header_background_color )
				),
			) );
		}

		if ( '' !== $featured_table_header_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table .et_pb_pricing_heading h2',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $featured_table_header_text_color )
				),
			) );
		}

		if ( '' !== $featured_table_subheader_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table .et_pb_best_value',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $featured_table_subheader_text_color )
				),
			) );
		}

		if ( '' !== $featured_table_price_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table .et_pb_sum',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $featured_table_price_color )
				),
			) );
		}

		if ( '' !== $featured_table_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table .et_pb_pricing_content',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $featured_table_text_color )
				),
			) );
		}

		if ( '' !== $bullet_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_pricing li span:before',
				'declaration' => sprintf(
					'border-color: %1$s;',
					esc_html( $bullet_color )
				),
			) );
		}

		if ( '' !== $featured_table_bullet_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_featured_table .et_pb_pricing li span:before',
				'declaration' => sprintf(
					'border-color: %1$s;',
					esc_html( $featured_table_bullet_color )
				),
			) );
		}

		$content = $this->shortcode_content;

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_pricing clearfix%2$s%4$s">
				%1$s
			</div>',
			$content,
			esc_attr( " et_pb_pricing_{$et_pb_pricing_tables_num}" ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( ltrim( $module_class ) ) ) : '' )
		);

		return $output;
	}

	function additional_shortcode_callback( $atts, $content = null, $function_name ) {
		$attributes = shortcode_atts( array(
			'available' => 'on',
		), $atts );

		$output = sprintf( '<li%2$s><span>%1$s</span></li>',
			$content,
			( 'on' !== $attributes['available'] ? ' class="et_pb_not_available"' : '' )
		);
		return $output;
	}
}
new ET_Builder_Module_Pricing_Tables;

class ET_Builder_Module_Pricing_Tables_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Pricing Table', 'et_builder' );
		$this->slug                        = 'et_pb_pricing_table';
		$this->main_css_element 		   = '%%order_class%%.et_pb_pricing';
		$this->type                        = 'child';
		$this->child_title_var             = 'title';

		$this->whitelisted_fields = array(
			'featured',
			'title',
			'subtitle',
			'currency',
			'per',
			'sum',
			'button_url',
			'button_text',
			'content_new',
		);

		$this->fields_defaults = array(
			'featured' => array( 'off' ),
		);

		$this->advanced_setting_title_text = __( 'New Pricing Table', 'et_builder' );
		$this->settings_text               = __( 'Pricing Table Settings', 'et_builder' );
		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_pricing_heading h2",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'subheader' => array(
					'label'    => __( 'Subheader', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_best_value",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'currency_frequency' => array(
					'label'    => __( 'Currency &amp; Frequency', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_dollar_sign, {$this->main_css_element} .et_pb_frequency",
					),
				),
				'price' => array(
					'label'    => __( 'Price', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_sum",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_pricing li",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
			'background' => array(
				'use_background_image' => false,
				'css' => array(
					'main' => "{$this->main_css_element}.et_pb_pricing_table",
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
					'css'      => array(
						'main' => ".et_pb_pricing {$this->main_css_element} .et_pb_button",
					),
				),
			),
		);

		$this->custom_css_options = array(
			'pricing_heading' => array(
				'label'    => __( 'Pricing Heading', 'et_builder' ),
				'selector' => '.et_pb_pricing_heading',
			),
			'pricing_title' => array(
				'label'    => __( 'Pricing Title', 'et_builder' ),
				'selector' => '.et_pb_pricing_heading h2',
			),
			'pricing_top' => array(
				'label'    => __( 'Pricing Top', 'et_builder' ),
				'selector' => '.et_pb_pricing_content_top',
			),
			'price' => array(
				'label'    => __( 'Price', 'et_builder' ),
				'selector' => '.et_pb_et_price',
			),
			'pricing_content' => array(
				'label'    => __( 'Pricing Content', 'et_builder' ),
				'selector' => '.et_pb_pricing_content',
			),
			'pricing_button' => array(
				'label'    => __( 'Pricing Button', 'et_builder' ),
				'selector' => '.et_pb_pricing_table_button',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'featured' => array(
				'label'           => __( 'Make This Table Featured', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description' => __( 'Featuring a table will make it stand out from the rest.', 'et_builder' ),
			),
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define a title for the pricing table.', 'et_builder' ),
			),
			'subtitle' => array(
				'label'           => __( 'Subtitle', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define a sub title for the table if desired.', 'et_builder' ),
			),
			'currency' => array(
				'label'           => __( 'Currency', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input your desired currency symbol here.', 'et_builder' ),
			),
			'per' => array(
				'label'           => __( 'Per', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'If your pricing is subscription based, input the subscription payment cycle here.', 'et_builder' ),
			),
			'sum' => array(
				'label'           => __( 'Price', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the value of the product here.', 'et_builder' ),
			),
			'button_url' => array(
				'label'           => __( 'Button URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the destination URL for the signup button.', 'et_builder' ),
			),
			'button_text' => array(
				'label'           => __( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Adjust the text used from the signup button.', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => sprintf(
					'%1$s<br/> + %2$s<br/> - %3$s',
					esc_html__( 'Input a list of features that are/are not included in the product. Separate items on a new line, and begin with either a + or - symbol: ', 'et_builder' ),
					esc_html__( 'Included option', 'et_builder' ),
					esc_html__( 'Excluded option', 'et_builder' )
				),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_pricing_tables_num, $et_pb_pricing_tables_icon;

		$featured      = $this->shortcode_atts['featured'];
		$title         = $this->shortcode_atts['title'];
		$subtitle      = $this->shortcode_atts['subtitle'];
		$currency      = $this->shortcode_atts['currency'];
		$per           = $this->shortcode_atts['per'];
		$sum           = $this->shortcode_atts['sum'];
		$button_url    = $this->shortcode_atts['button_url'];
		$button_text   = $this->shortcode_atts['button_text'];
		$button_custom = $this->shortcode_atts['custom_button'];
		$custom_icon   = $this->shortcode_atts['button_icon'];

		$et_pb_pricing_tables_num++;

		$module_class = ET_Builder_Element::add_module_order_class( '', $function_name );

		$custom_table_icon = 'on' === $button_custom && '' !== $custom_icon ? $custom_icon : $et_pb_pricing_tables_icon;

		if ( '' !== $button_url && '' !== $button_text ) {
			$button_text = sprintf( '<a class="et_pb_pricing_table_button et_pb_button%4$s" href="%1$s"%3$s>%2$s</a>',
				esc_url( $button_url ),
				esc_html( $button_text ),
				'' !== $custom_table_icon ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_table_icon ) )
				) : '',
				'' !== $custom_table_icon ? ' et_pb_custom_button_icon' : ''
			);
		}

		$output = sprintf(
			'<div class="et_pb_pricing_table%1$s%9$s">
				<div class="et_pb_pricing_heading">
					%2$s
					%3$s
				</div> <!-- .et_pb_pricing_heading -->
				<div class="et_pb_pricing_content_top">
					<span class="et_pb_et_price">%6$s%7$s%8$s</span>
				</div> <!-- .et_pb_pricing_content_top -->
				<div class="et_pb_pricing_content">
					<ul class="et_pb_pricing">
						%4$s
					</ul>
				</div> <!-- .et_pb_pricing_content -->
				%5$s
			</div>',
			( 'off' !== $featured ? ' et_pb_featured_table' : '' ),
			( '' !== $title ? sprintf( '<h2 class="et_pb_pricing_title">%1$s</h2>', esc_html( $title ) ) : '' ),
			( '' !== $subtitle ? sprintf( '<span class="et_pb_best_value">%1$s</span>', esc_html( $subtitle ) ) : '' ),
			do_shortcode( et_pb_fix_shortcodes( et_pb_extract_items( $content ) ) ),
			$button_text,
			( '' !== $currency ? sprintf( '<span class="et_pb_dollar_sign">%1$s</span>', esc_html( $currency ) ) : '' ),
			( '' !== $sum ? sprintf( '<span class="et_pb_sum">%1$s</span>', esc_html( $sum ) ) : '' ),
			( '' !== $per ? sprintf( '<span class="et_pb_frequency">/%1$s</span>', esc_html( $per ) ) : '' ),
			esc_attr( $module_class )
		);

		return $output;
	}
}
new ET_Builder_Module_Pricing_Tables_Item;

class ET_Builder_Module_CTA extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Call To Action', 'et_builder' );
		$this->slug = 'et_pb_cta';

		$this->whitelisted_fields = array(
			'title',
			'button_url',
			'url_new_window',
			'button_text',
			'use_background_color',
			'background_color',
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
		);

		$this->fields_defaults = array(
			'url_new_window'       => array( 'off' ),
			'use_background_color' => array( 'on' ),
			'background_color'     => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout'    => array( 'dark' ),
			'text_orientation'     => array( 'center' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_promo';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'use_background_color' => false,
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
		$this->custom_css_options = array(
			'promo_description' => array(
				'label'    => __( 'Promo Description', 'et_builder' ),
				'selector' => '.et_pb_promo_description',
			),
			'promo_button' => array(
				'label'    => __( 'Promo Button', 'et_builder' ),
				'selector' => '.et_pb_promo_button',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input your value to action title here.', 'et_builder' ),
			),
			'button_url' => array(
				'label'           => __( 'Button URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the destination URL for your CTA button.', 'et_builder' ),
			),
			'url_new_window' => array(
				'label'           => __( 'Url Opens', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'In The Same Window', 'et_builder' ),
					'on'  => __( 'In The New Tab', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
			),
			'button_text' => array(
				'label'           => __( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input your desired button text, or leave blank for no button.', 'et_builder' ),
			),
			'use_background_color' => array(
				'label'           => __( 'Use Background Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_background_color',
				),
				'description'        => __( 'Here you can choose whether background color setting below should be used or not.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'depends_default'   => true,
				'description'       => __( 'Here you can define a custom background color for your CTA.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'dark'  => __( 'Light', 'et_builder' ),
					'light' => __( 'Dark', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This will adjust the alignment of the module text.', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'max_width' => array(
				'label'           => __( 'Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id            = $this->shortcode_atts['module_id'];
		$module_class         = $this->shortcode_atts['module_class'];
		$title                = $this->shortcode_atts['title'];
		$button_url           = $this->shortcode_atts['button_url'];
		$button_text          = $this->shortcode_atts['button_text'];
		$background_color     = $this->shortcode_atts['background_color'];
		$background_layout    = $this->shortcode_atts['background_layout'];
		$text_orientation     = $this->shortcode_atts['text_orientation'];
		$use_background_color = $this->shortcode_atts['use_background_color'];
		$url_new_window       = $this->shortcode_atts['url_new_window'];
		$max_width            = $this->shortcode_atts['max_width'];
		$custom_icon          = $this->shortcode_atts['button_icon'];
		$button_custom        = $this->shortcode_atts['custom_button'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		if ( '' !== $max_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'max-width: %1$s;%2$s',
					esc_html( et_builder_process_range_value( $max_width ) ),
					( 'center' === $text_orientation ? ' margin: 0 auto;' : '' )
				),
			) );
		}

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$output = sprintf(
			'<div%6$s class="et_pb_promo%4$s%7$s%8$s"%5$s>
				<div class="et_pb_promo_description">
					%1$s
					%2$s
				</div>
				%3$s
			</div>',
			( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
			$this->shortcode_content,
			(
				'' !== $button_url && '' !== $button_text
					? sprintf( '<a class="et_pb_promo_button et_pb_button%5$s" href="%1$s"%3$s%4$s>%2$s</a>',
						esc_url( $button_url ),
						esc_html( $button_text ),
						( 'on' === $url_new_window ? ' target="_blank"' : '' ),
						'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
						) : '',
						'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : ''
					)
					: ''
			),
			esc_attr( $class ),
			( 'on' === $use_background_color
				? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
				: ''
			),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( 'on' !== $use_background_color ? ' et_pb_no_bg' : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_CTA;

class ET_Builder_Module_Audio extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Audio', 'et_builder' );
		$this->slug = 'et_pb_audio';

		$this->whitelisted_fields = array(
			'audio',
			'title',
			'artist_name',
			'album_name',
			'image_url',
			'background_color',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'background_color'  => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout' => array( 'dark' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_audio_module';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
					),
				),
				'caption'   => array(
					'label'    => __( 'Caption', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
						'main' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);
		$this->custom_css_options = array(
			'audio_cover_art' => array(
				'label'    => __( 'Audio Cover Art', 'et_builder' ),
				'selector' => '.et_pb_audio_cover_art',
			),
			'audio_content' => array(
				'label'    => __( 'Audio Content', 'et_builder' ),
				'selector' => '.et_pb_audio_module_content',
			),
			'audio_meta' => array(
				'label'    => __( 'Audio Meta', 'et_builder' ),
				'selector' => '.et_audio_module_meta',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'audio' => array(
				'label'              => __( 'Audio', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'audio',
				'upload_button_text' => __( 'Upload an audio file', 'et_builder' ),
				'choose_text'        => __( 'Choose an Audio file', 'et_builder' ),
				'update_text'        => __( 'Set As Audio for the module', 'et_builder' ),
				'description'        => __( 'Define the audio file for use in the module. To remove an audio file from the module, simply delete the URL from the settings field.', 'et_builder' ),
			),
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define a title.', 'et_builder' ),
			),
			'artist_name' => array(
				'label'           => __( 'Artist Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define an artist name.', 'et_builder' ),
			),
			'album_name' => array(
				'label'           => __( 'Album name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define an album name.', 'et_builder' ),
			),
			'image_url' => array(
				'label'              => __( 'Cover Art Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'Define a custom background color for your module, or leave blank to use the default color.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'dark'  => __( 'Light', 'et_builder' ),
					'light' => __( 'Dark', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),

		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$audio             = $this->shortcode_atts['audio'];
		$title             = $this->shortcode_atts['title'];
		$artist_name       = $this->shortcode_atts['artist_name'];
		$album_name        = $this->shortcode_atts['album_name'];
		$image_url         = $this->shortcode_atts['image_url'];
		$background_color  = "" !== $this->shortcode_atts['background_color'] ? $this->shortcode_atts['background_color'] : $this->fields_defaults['background_color'][0];
		$background_layout = $this->shortcode_atts['background_layout'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$meta = $cover_art = '';
		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		if ( 'light' === $background_layout ) {
			$class .= " et_pb_text_color_dark";
		}

		if ( '' !== $artist_name || '' !== $album_name ) {
			if ( '' !== $artist_name && '' !== $album_name ) {
				$album_name = ' | ' . $album_name;
			}

			if ( '' !== $artist_name ) {
				$artist_name = sprintf( _x( 'by <strong>%1$s</strong>', 'Audio Module meta information', 'et_builder' ),
					esc_html( $artist_name )
				);
			}

			$meta = sprintf( '%1$s%2$s',
				$artist_name,
				esc_html( $album_name )
			);

			$meta = sprintf( '<p class="et_audio_module_meta">%1$s</p>', $meta );
		}

		if ( '' !== $image_url ) {
			$cover_art = sprintf(
				'<div class="et_pb_audio_cover_art" style="background-image: url(%1$s);">
				</div>',
				esc_attr( $image_url )
			);
		}

		// some themes do not include these styles/scripts so we need to enqueue them in this module
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'et-builder-mediaelement' );

		// remove all filters from WP audio shortcode to make sure current theme doesn't add any elements into audio module
		remove_all_filters( 'wp_audio_shortcode_library' );
		remove_all_filters( 'wp_audio_shortcode' );
		remove_all_filters( 'wp_audio_shortcode_class');

		$output = sprintf(
			'<div%8$s class="et_pb_audio_module clearfix%4$s%7$s%9$s"%5$s>
				%6$s

				<div class="et_pb_audio_module_content et_audio_container">
					%1$s
					%2$s
					%3$s
				</div>
			</div>',
			( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
			$meta,
			do_shortcode(
				sprintf( '[audio src="%1$s" /]', esc_attr( $audio ) )
			),
			esc_attr( $class ),
			sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) ),
			$cover_art,
			( '' === $image_url ? ' et_pb_audio_no_image' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Audio;

class ET_Builder_Module_Signup extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Email Optin', 'et_builder' );
		$this->slug = 'et_pb_signup';

		$this->whitelisted_fields = array(
			'provider',
			'feedburner_uri',
			'mailchimp_list',
			'aweber_list',
			'title',
			'button_text',
			'use_background_color',
			'background_color',
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'form_field_background_color',
			'form_field_text_color',
			'focus_background_color',
			'focus_text_color',
			'use_focus_border_color',
			'focus_border_color',
		);

		$this->fields_defaults = array(
			'provider'               => array( 'mailchimp' ),
			'button_text'            => array( __( 'Subscribe', 'et_builder' ) ),
			'use_background_color'   => array( 'on' ),
			'background_color'       => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout'      => array( 'dark' ),
			'text_orientation'       => array( 'left' ),
			'use_focus_border_color' => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_subscribe';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
		$this->custom_css_options = array(
			'newsletter_description' => array(
				'label'    => __( 'Newsletter Description', 'et_builder' ),
				'selector' => '.et_pb_newsletter_description',
			),
			'newsletter_form' => array(
				'label'    => __( 'Newsletter Form', 'et_builder' ),
				'selector' => '.et_pb_newsletter_form',
			),
			'newsletter_button' => array(
				'label'    => __( 'Newsletter Button', 'et_builder' ),
				'selector' => '.et_pb_newsletter_button',
			),
		);
	}

	function get_fields() {
		$et_pb_mailchimp_lists_options = array( 'none' => __( 'Select the list', 'et_builder' ) );
		$et_pb_aweber_lists_options = $et_pb_mailchimp_lists_options;

		$et_pb_mailchimp_lists = et_pb_get_mailchimp_lists();

		if ( $et_pb_mailchimp_lists ) {
			foreach ( $et_pb_mailchimp_lists as $et_pb_mailchimp_list_key => $et_pb_mailchimp_list_name ) {
				$et_pb_mailchimp_lists_options[ $et_pb_mailchimp_list_key ] = $et_pb_mailchimp_list_name;
			}
		}

		$et_pb_aweber_lists = et_pb_get_aweber_lists();

		if ( $et_pb_aweber_lists ) {
			foreach ( $et_pb_aweber_lists as $et_pb_aweber_list_key => $et_pb_aweber_list_name ) {
				$et_pb_aweber_lists_options[ $et_pb_aweber_list_key ] = $et_pb_aweber_list_name;
			}
		}

		$fields = array(
			'provider' => array(
				'label'           => __( 'Service Provider', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'mailchimp'  => __( 'MailChimp', 'et_builder' ),
					'feedburner' => __( 'FeedBurner', 'et_builder' ),
					'aweber'     => __( 'Aweber', 'et_builder' ),
				),
				'affects' => array(
					'#et_pb_feedburner_uri',
					'#et_pb_mailchimp_list',
					'#et_pb_aweber_list',
				),
				'description'       => __( 'Here you can choose a service provider.', 'et_builder' ),
			),
			'feedburner_uri' => array(
				'label'           => __( 'Feed Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'feedburner',
				'description'     => __( sprintf( 'Enter <a href="%1$s" target="_blank">Feed Title</a>.', esc_url( 'http://feedburner.google.com/fb/a/myfeeds' ) ), 'et_builder' ),
			),
			'mailchimp_list' => array(
				'label'           => __( 'MailChimp lists', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => $et_pb_mailchimp_lists_options,
				'description'     => sprintf(
					__( 'Here you can choose MailChimp list to add customers to. If you don\'t see any lists here, you need to make sure MailChimp API key is set in %1$s and you have at least one list on a MailChimp account. If you added new list, but it doesn\'t appear here, activate \'Regenerate MailChimp Lists\' option in %1$s.%2$s', 'et_builder' ),
						et_is_builder_plugin_active() ? __( 'Divi Plugin Options', 'et_builder' ) : __( 'ePanel', 'et_builder' ),
						! et_is_builder_plugin_active() ? __( 'Don\'t forget to disable it once the list has been regenerated.', 'et_builder' ) : ''
					),
				'depends_show_if' => 'mailchimp',
			),
			'aweber_list' => array(
				'label'           => __( 'Aweber lists', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => $et_pb_aweber_lists_options,
				'description'     => sprintf(
					__( 'Here you can choose Aweber list to add customers to. If you don\'t see any lists here, you need to make sure Aweber is set up properly in %1$s and you have at least one list on a Aweber account. If you added new list, but it doesn\'t appear here, activate \'Regenerate Aweber Lists\' option in %1$s.%2$s', 'et_builder' ),
						et_is_builder_plugin_active() ? __( 'Divi Plugin Options', 'et_builder' ) : __( 'ePanel', 'et_builder' ),
						! et_is_builder_plugin_active() ? __( 'Don\'t forget to disable it once the list has been regenerated.', 'et_builder' ) : ''
					),
				'depends_show_if' => 'aweber',
			),
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Choose a title of your signup box.', 'et_builder' ),
			),
			'button_text' => array(
				'label'             => __( 'Button Text', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'description'       => __( 'Here you can change the text used for the signup button.', 'et_builder' ),
			),
			'use_background_color' => array(
				'label'             => __( 'Use Background Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_background_color',
				),
				'description'       => __( 'Here you can choose whether background color setting below should be used or not.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'Define a custom background color for your module, or leave blank to use the default color.', 'et_builder' ),
				'depends_default'   => true,
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'dark'  => __( 'Light', 'et_builder' ),
					'light' => __( 'Dark', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'Here you can adjust the alignment of your text.', 'et_builder' ),
			),
			'content_new' => array(
				'label'             => __( 'Content', 'et_builder' ),
				'type'              => 'tiny_mce',
				'option_category'   => 'basic_option',
				'description'       => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'form_field_background_color' => array(
				'label'             => __( 'Form Field Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'form_field_text_color' => array(
				'label'             => __( 'Form Field Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'focus_background_color' => array(
				'label'             => __( 'Focus Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'focus_text_color' => array(
				'label'             => __( 'Focus Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'use_focus_border_color' => array(
				'label'           => __( 'Use Focus Border Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_focus_border_color',
				),
				'tab_slug' => 'advanced',
			),
			'focus_border_color' => array(
				'label'             => __( 'Focus Border Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'depends_default'   => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                   = $this->shortcode_atts['module_id'];
		$module_class                = $this->shortcode_atts['module_class'];
		$title                       = $this->shortcode_atts['title'];
		$button_text                 = $this->shortcode_atts['button_text'];
		$background_color            = $this->shortcode_atts['background_color'];
		$mailchimp_list              = $this->shortcode_atts['mailchimp_list'];
		$aweber_list                 = $this->shortcode_atts['aweber_list'];
		$text_orientation            = $this->shortcode_atts['text_orientation'];
		$use_background_color        = $this->shortcode_atts['use_background_color'];
		$provider                    = $this->shortcode_atts['provider'];
		$feedburner_uri              = $this->shortcode_atts['feedburner_uri'];
		$background_layout           = $this->shortcode_atts['background_layout'];
		$form_field_background_color = $this->shortcode_atts['form_field_background_color'];
		$form_field_text_color       = $this->shortcode_atts['form_field_text_color'];
		$focus_background_color      = $this->shortcode_atts['focus_background_color'];
		$focus_text_color            = $this->shortcode_atts['focus_text_color'];
		$use_focus_border_color      = $this->shortcode_atts['use_focus_border_color'];
		$focus_border_color          = $this->shortcode_atts['focus_border_color'];
		$button_custom               = $this->shortcode_atts['custom_button'];
		$custom_icon                 = $this->shortcode_atts['button_icon'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $focus_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_newsletter_form p input.input:focus',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $focus_background_color )
				),
			) );
		}

		if ( '' !== $focus_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_newsletter_form p input.input:focus',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $focus_text_color )
				),
			) );
		}

		if ( 'off' !== $use_focus_border_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_newsletter_form p input.input:focus',
				'declaration' => sprintf(
					'border: 1px solid %1$s !important;',
					esc_html( $focus_border_color )
				),
			) );
		}

		if ( '' !== $form_field_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% input[type="text"], %%order_class%% textarea',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $form_field_background_color )
				),
			) );
		}

		if ( '' !== $form_field_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% input[type="text"], %%order_class%% textarea',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $form_field_text_color )
				),
			) );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$form = '';

		$firstname     = __( 'First Name', 'et_builder' );
		$lastname      = __( 'Last Name', 'et_builder' );
		$email_address = __( 'Email Address', 'et_builder' );

		switch ( $provider ) {
			case 'mailchimp' :
				if ( ! in_array( $mailchimp_list, array( '', 'none' ) ) ) {
					$form = sprintf( '
						<div class="et_pb_newsletter_form">
							<div class="et_pb_newsletter_result"></div>
							<p>
								<label class="et_pb_contact_form_label" for="et_pb_signup_firstname" style="display: none;">%3$s</label>
								<input id="et_pb_signup_firstname" class="input" type="text" value="%4$s" name="et_pb_signup_firstname">
							</p>
							<p>
								<label class="et_pb_contact_form_label" for="et_pb_signup_lastname" style="display: none;">%5$s</label>
								<input id="et_pb_signup_lastname" class="input" type="text" value="%6$s" name="et_pb_signup_lastname">
							</p>
							<p>
								<label class="et_pb_contact_form_label" for="et_pb_signup_email" style="display: none;">%7$s</label>
								<input id="et_pb_signup_email" class="input" type="text" value="%8$s" name="et_pb_signup_email">
							</p>
							<p><a class="et_pb_newsletter_button et_pb_button%10$s" href="#"%9$s><span class="et_subscribe_loader"></span><span class="et_pb_newsletter_button_text">%1$s</span></a></p>
							<input type="hidden" value="%2$s" name="et_pb_signup_list_id" />
						</div>',
						esc_html( $button_text ),
						( ! in_array( $mailchimp_list, array( '', 'none' ) ) ? esc_attr( $mailchimp_list ) : '' ),
						esc_html( $firstname ),
						esc_attr( $firstname ),
						esc_html( $lastname ),
						esc_attr( $lastname ),
						esc_html( $email_address ),
						esc_attr( $email_address ),
						'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
						) : '',
						'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : ''
					);
				}

				break;
			case 'feedburner':
				$form = sprintf( '
					<div class="et_pb_newsletter_form et_pb_feedburner_form">
						<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri=%4$s\', \'popupwindow\', \'scrollbars=yes,width=550,height=520\'); return true">
						<p>
							<label class="et_pb_contact_form_label" for="email" style="display: none;">%2$s</label>
							<input id="email" class="input" type="text" value="%3$s" name="email">
						</p>
						<p><button class="et_pb_newsletter_button et_pb_button%7$s" type="submit"%6$s>%1$s</button></p>
						<input type="hidden" value="%4$s" name="uri" />
						<input type="hidden" name="loc" value="%5$s" />
						</form>
					</div>',
					esc_html( $button_text ),
					esc_html( $email_address ),
					esc_attr( $email_address ),
					esc_attr( $feedburner_uri ),
					esc_attr( get_locale() ),
					'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
					) : '',
					'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : ''
				);

				break;
			case 'aweber' :
				$firstname = __( 'Name', 'et_builder' );

				if ( ! in_array( $aweber_list, array( '', 'none' ) ) ) {
					$form = sprintf( '
						<div class="et_pb_newsletter_form" data-service="aweber">
							<div class="et_pb_newsletter_result"></div>
							<p>
								<label class="et_pb_contact_form_label" for="et_pb_signup_firstname" style="display: none;">%3$s</label>
								<input id="et_pb_signup_firstname" class="input" type="text" value="%4$s" name="et_pb_signup_firstname">
							</p>
							<p>
								<label class="et_pb_contact_form_label" for="et_pb_signup_email" style="display: none;">%5$s</label>
								<input id="et_pb_signup_email" class="input" type="text" value="%6$s" name="et_pb_signup_email">
							</p>
							<p><a class="et_pb_newsletter_button et_pb_button%8$s" href="#"%7$s><span class="et_subscribe_loader"></span><span class="et_pb_newsletter_button_text">%1$s</span></a></p>
							<input type="hidden" value="%2$s" name="et_pb_signup_list_id" />
						</div>',
						esc_html( $button_text ),
						( ! in_array( $aweber_list, array( '', 'none' ) ) ? esc_attr( $aweber_list ) : '' ),
						esc_html( $firstname ),
						esc_attr( $firstname ),
						esc_html( $email_address ),
						esc_attr( $email_address ),
						'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
						) : '',
						'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : ''
					);
				}

				break;
		}

		$output = sprintf(
			'<div%6$s class="et_pb_newsletter et_pb_subscribe clearfix%4$s%7$s%8$s"%5$s>
				<div class="et_pb_newsletter_description">
					%1$s
					%2$s
				</div>
				%3$s
			</div>',
			( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
			$this->shortcode_content,
			$form,
			esc_attr( $class ),
			( 'on' === $use_background_color
				? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
				: ''
			),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( 'on' !== $use_background_color ? ' et_pb_no_bg' : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Signup;

class ET_Builder_Module_Login extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Login', 'et_builder' );
		$this->slug = 'et_pb_login';

		$this->whitelisted_fields = array(
			'title',
			'current_page_redirect',
			'use_background_color',
			'background_color',
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'form_field_background_color',
			'form_field_text_color',
			'focus_background_color',
			'focus_text_color',
			'use_focus_border_color',
			'focus_border_color',
		);

		$this->fields_defaults = array(
			'current_page_redirect'  => array( 'off' ),
			'use_background_color'   => array( 'on' ),
			'background_color'       => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout'      => array( 'dark' ),
			'text_orientation'       => array( 'left' ),
			'use_focus_border_color' => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_login';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
		$this->custom_css_options = array(
			'newsletter_description' => array(
				'label'    => __( 'Newsletter Description', 'et_builder' ),
				'selector' => '.et_pb_newsletter_description',
			),
			'newsletter_form' => array(
				'label'    => __( 'Newsletter Form', 'et_builder' ),
				'selector' => '.et_pb_newsletter_form',
			),
			'newsletter_button' => array(
				'label'    => __( 'Newsletter Button', 'et_builder' ),
				'selector' => '.et_pb_newsletter_button',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Choose a title of your login box.', 'et_builder' ),
			),
			'current_page_redirect' => array(
				'label'           => __( 'Redirect To The Current Page', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether the user should be redirected to the current page.', 'et_builder' ),
			),
			'use_background_color' => array(
				'label'           => __( 'Use Background Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'on'          => __( 'Yes', 'et_builder' ),
					'off'         => __( 'No', 'et_builder' ),
				),
				'affects' => array(
					'#et_pb_background_color',
				),
				'description' => __( 'Here you can choose whether background color setting below should be used or not.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'Define a custom background color for your module, or leave blank to use the default color.', 'et_builder' ),
				'depends_default'   => true,
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'      	  => array(
					'dark'  => __( 'Light', 'et_builder' ),
					'light' => __( 'Dark', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'Here you can adjust the alignment of your text.', 'et_builder' ),
			),
			'content_new' => array(
				'label'             => __( 'Content', 'et_builder' ),
				'type'              => 'tiny_mce',
				'option_category'   => 'basic_option',
				'description'       => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'form_field_background_color' => array(
				'label'             => __( 'Form Field Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'form_field_text_color' => array(
				'label'             => __( 'Form Field Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'focus_background_color' => array(
				'label'             => __( 'Focus Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'focus_text_color' => array(
				'label'             => __( 'Focus Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'use_focus_border_color' => array(
				'label'           => __( 'Use Focus Border Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_focus_border_color',
				),
				'tab_slug' => 'advanced',
			),
			'focus_border_color' => array(
				'label'             => __( 'Focus Border Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'depends_default'   => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                   = $this->shortcode_atts['module_id'];
		$module_class                = $this->shortcode_atts['module_class'];
		$title                       = $this->shortcode_atts['title'];
		$background_color            = $this->shortcode_atts['background_color'];
		$background_layout           = $this->shortcode_atts['background_layout'];
		$text_orientation            = $this->shortcode_atts['text_orientation'];
		$use_background_color        = $this->shortcode_atts['use_background_color'];
		$current_page_redirect       = $this->shortcode_atts['current_page_redirect'];
		$form_field_background_color = $this->shortcode_atts['form_field_background_color'];
		$form_field_text_color       = $this->shortcode_atts['form_field_text_color'];
		$focus_background_color      = $this->shortcode_atts['focus_background_color'];
		$focus_text_color            = $this->shortcode_atts['focus_text_color'];
		$use_focus_border_color      = $this->shortcode_atts['use_focus_border_color'];
		$focus_border_color          = $this->shortcode_atts['focus_border_color'];
		$button_custom               = $this->shortcode_atts['custom_button'];
		$custom_icon                 = $this->shortcode_atts['button_icon'];
		$content                     = $this->shortcode_content;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $focus_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_newsletter_form p input:focus',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $focus_background_color )
				),
			) );
		}

		if ( '' !== $focus_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_newsletter_form p input:focus',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $focus_text_color )
				),
			) );
		}

		if ( 'off' !== $use_focus_border_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_newsletter_form p input:focus',
				'declaration' => sprintf(
					'border: 1px solid %1$s !important;',
					esc_html( $focus_border_color )
				),
			) );
		}

		if ( '' !== $form_field_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% input[type="text"], %%order_class%% textarea, %%order_class%% .input',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $form_field_background_color )
				),
			) );
		}

		if ( '' !== $form_field_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% input[type="text"], %%order_class%% textarea, %%order_class%% .input',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $form_field_text_color )
				),
			) );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$redirect_url = 'on' === $current_page_redirect
			? ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
			: '';

		if ( is_user_logged_in() ) {
			global $current_user;
			get_currentuserinfo();

			$content .= sprintf( '<br/>%1$s <a href="%2$s">%3$s</a>',
				sprintf( __( 'Logged in as %1$s', 'et_builder' ), esc_html( $current_user->display_name ) ),
				esc_url( wp_logout_url( $redirect_url ) ),
				esc_html__( 'Log out', 'et_builder' )
			);
		}

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$form = '';

		if ( !is_user_logged_in() ) {
			$username = __( 'Username', 'et_builder' );
			$password = __( 'Password', 'et_builder' );

			$form = sprintf( '
				<div class="et_pb_newsletter_form et_pb_login_form">
					<form action="%7$s" method="post">
						<p>
							<label class="et_pb_contact_form_label" for="user_login" style="display: none;">%3$s</label>
							<input id="user_login" placeholder="%4$s" class="input" type="text" value="" name="log" />
						</p>
						<p>
							<label class="et_pb_contact_form_label" for="user_pass" style="display: none;">%5$s</label>
							<input id="user_pass" placeholder="%6$s" class="input" type="password" value="" name="pwd" />
						</p>
						<p class="et_pb_forgot_password"><a href="%2$s">%1$s</a></p>
						<p>
							<button type="submit" class="et_pb_newsletter_button et_pb_button%11$s"%10$s>%8$s</button>
							%9$s
						</p>
					</form>
				</div>',
				__( 'Forgot your password?', 'et_builder' ),
				esc_url( wp_lostpassword_url() ),
				esc_html( $username ),
				esc_attr( $username ),
				esc_html( $password ),
				esc_attr( $password ),
				esc_url( site_url( 'wp-login.php', 'login_post' ) ),
				__( 'Login', 'et_builder' ),
				( 'on' === $current_page_redirect
					? sprintf( '<input type="hidden" name="redirect_to" value="%1$s" />',  $redirect_url )
					: ''
				),
				'' !== $custom_icon && 'on' === $button_custom ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon ) )
				) : '',
				'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : ''
			);
		}

		$output = sprintf(
			'<div%6$s class="et_pb_newsletter et_pb_login clearfix%4$s%7$s"%5$s>
				<div class="et_pb_newsletter_description">
					%1$s
					%2$s
				</div>
				%3$s
			</div>',
			( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
			$content,
			$form,
			esc_attr( $class ),
			( 'on' === $use_background_color
				? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
				: ''
			),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Login;

class ET_Builder_Module_Portfolio extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Portfolio', 'et_builder' );
		$this->slug = 'et_pb_portfolio';

		$this->whitelisted_fields = array(
			'fullwidth',
			'posts_number',
			'include_categories',
			'show_title',
			'show_categories',
			'show_pagination',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
			'zoom_icon_color',
			'hover_overlay_color',
			'hover_icon',
		);

		$this->fields_defaults = array(
			'fullwidth'         => array( 'on' ),
			'posts_number'      => array( 10, 'add_default_setting' ),
			'show_title'        => array( 'on' ),
			'show_categories'   => array( 'on' ),
			'show_pagination'   => array( 'on' ),
			'background_layout' => array( 'light' ),
		);

		$this->main_css_element = '%%order_class%% .et_pb_portfolio_item';
		$this->advanced_options = array(
			'fonts' => array(
				'title'   => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'caption' => array(
					'label'    => __( 'Meta', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .post-meta, {$this->main_css_element} .post-meta a",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
		);
		$this->custom_css_options = array(
			'portfolio_image' => array(
				'label'    => __( 'Portfolio Image', 'et_builder' ),
				'selector' => '.et_portfolio_image',
			),
			'overlay' => array(
				'label'    => __( 'Overlay', 'et_builder' ),
				'selector' => '.et_overlay',
			),
			'overlay_icon' => array(
				'label'    => __( 'Overlay Icon', 'et_builder' ),
				'selector' => '.et_overlay:before',
			),
			'portfolio_title' => array(
				'label'    => __( 'Portfolio Title', 'et_builder' ),
				'selector' => '.et_pb_portfolio_item h2',
			),
			'portfolio_post_meta' => array(
				'label'    => __( 'Portfolio Post Meta', 'et_builder' ),
				'selector' => '.et_pb_portfolio_item .post-meta',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'fullwidth' => array(
				'label'           => __( 'Layout', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => __( 'Fullwidth', 'et_builder' ),
					'off' => __( 'Grid', 'et_builder' ),
				),
				'description'       => __( 'Choose your desired portfolio layout style.', 'et_builder' ),
			),
			'posts_number' => array(
				'label'             => __( 'Posts Number', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Define the number of projects that should be displayed per page.', 'et_builder' ),
			),
			'include_categories' => array(
				'label'            => __( 'Include Categories', 'et_builder' ),
				'renderer'         => 'et_builder_include_categories_option',
				'option_category'  => 'basic_option',
				'description'      => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
			),
			'show_title' => array(
				'label'           => __( 'Show Title', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'       => __( 'Turn project titles on or off.', 'et_builder' ),
			),
			'show_categories' => array(
				'label'           => __( 'Show Categories', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn the category links on or off.', 'et_builder' ),
			),
			'show_pagination' => array(
				'label'           => __( 'Show Pagination', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Enable or disable pagination for this feed.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light'  => __( 'Dark', 'et_builder' ),
					'dark' => __( 'Light', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'zoom_icon_color' => array(
				'label'             => __( 'Zoom Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'hover_overlay_color' => array(
				'label'             => __( 'Hover Overlay Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'hover_icon' => array(
				'label'               => __( 'Hover Icon Picker', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'tab_slug'            => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$fullwidth          = $this->shortcode_atts['fullwidth'];
		$posts_number       = $this->shortcode_atts['posts_number'];
		$include_categories = $this->shortcode_atts['include_categories'];
		$show_title         = $this->shortcode_atts['show_title'];
		$show_categories    = $this->shortcode_atts['show_categories'];
		$show_pagination    = $this->shortcode_atts['show_pagination'];
		$background_layout  = $this->shortcode_atts['background_layout'];
		$zoom_icon_color     = $this->shortcode_atts['zoom_icon_color'];
		$hover_overlay_color = $this->shortcode_atts['hover_overlay_color'];
		$hover_icon          = $this->shortcode_atts['hover_icon'];

		global $paged;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $zoom_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay:before',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $zoom_icon_color )
				),
			) );
		}

		if ( '' !== $hover_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay',
				'declaration' => sprintf(
					'background-color: %1$s;
					border-color: %1$s;',
					esc_html( $hover_overlay_color )
				),
			) );
		}

		$container_is_closed = false;

		$args = array(
			'posts_per_page' => (int) $posts_number,
			'post_type'      => 'project',
		);

		$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );

		if ( is_front_page() ) {
			$paged = $et_paged;
		}

		if ( '' !== $include_categories )
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => explode( ',', $include_categories ),
					'operator' => 'IN',
				)
			);

		if ( ! is_search() ) {
			$args['paged'] = $et_paged;
		}

		$main_post_class = sprintf(
			'et_pb_portfolio_item%1$s',
			( 'on' !== $fullwidth ? ' et_pb_grid_item' : '' )
		);

		ob_start();

		query_posts( $args );

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class( $main_post_class ); ?>>

			<?php
				$thumb = '';

				$width = 'on' === $fullwidth ?  1080 : 400;
				$width = (int) apply_filters( 'et_pb_portfolio_image_width', $width );

				$height = 'on' === $fullwidth ?  9999 : 284;
				$height = (int) apply_filters( 'et_pb_portfolio_image_height', $height );
				$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
				$titletext = get_the_title();
				$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
				$thumb = $thumbnail["thumb"];

				if ( '' !== $thumb ) : ?>
					<a href="<?php the_permalink(); ?>">
					<?php if ( 'on' !== $fullwidth ) : ?>
						<span class="et_portfolio_image">
					<?php endif; ?>
							<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
					<?php if ( 'on' !== $fullwidth ) :

							$data_icon = '' !== $hover_icon
								? sprintf(
									' data-icon="%1$s"',
									esc_attr( et_pb_process_font_icon( $hover_icon ) )
								)
								: '';

							printf( '<span class="et_overlay%1$s"%2$s></span>',
								( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
								$data_icon
							);

					?>
						</span>
					<?php endif; ?>
					</a>
			<?php
				endif;
			?>

				<?php if ( 'on' === $show_title ) : ?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php endif; ?>

				<?php if ( 'on' === $show_categories ) : ?>
					<p class="post-meta"><?php echo get_the_term_list( get_the_ID(), 'project_category', '', ', ' ); ?></p>
				<?php endif; ?>

				</div> <!-- .et_pb_portfolio_item -->
	<?php	}

			if ( 'on' === $show_pagination && ! is_search() ) {
				echo '</div> <!-- .et_pb_portfolio -->';

				$container_is_closed = true;

				if ( function_exists( 'wp_pagenavi' ) ) {
					wp_pagenavi();
				} else {
					if ( et_is_builder_plugin_active() ) {
						include( ET_BUILDER_PLUGIN_DIR . 'includes/navigation.php' );
					} else {
						get_template_part( 'includes/navigation', 'index' );
					}
				}
			}

			wp_reset_query();
		} else {
			if ( et_is_builder_plugin_active() ) {
				include( ET_BUILDER_PLUGIN_DIR . 'includes/no-results.php' );
			} else {
				get_template_part( 'includes/no-results', 'index' );
			}
		}

		$posts = ob_get_contents();

		ob_end_clean();

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%5$s class="%1$s%3$s%6$s">
				%2$s
			%4$s',
			( 'on' === $fullwidth ? 'et_pb_portfolio' : 'et_pb_portfolio_grid clearfix' ),
			$posts,
			esc_attr( $class ),
			( ! $container_is_closed ? '</div> <!-- .et_pb_portfolio -->' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Portfolio;

class ET_Builder_Module_Filterable_Portfolio extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Filterable Portfolio', 'et_builder' );
		$this->slug = 'et_pb_filterable_portfolio';

		$this->whitelisted_fields = array(
			'fullwidth',
			'posts_number',
			'include_categories',
			'show_title',
			'show_categories',
			'show_pagination',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
			'hover_icon',
			'zoom_icon_color',
			'hover_overlay_color',
		);

		$this->fields_defaults = array(
			'fullwidth'         => array( 'on' ),
			'posts_number'      => array( 10, 'add_default_setting' ),
			'show_title'        => array( 'on' ),
			'show_categories'   => array( 'on' ),
			'show_pagination'   => array( 'on' ),
			'background_layout' => array( 'light' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_filterable_portfolio';
		$this->advanced_options = array(
			'fonts' => array(
				'title'   => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'caption' => array(
					'label'    => __( 'Meta', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .post-meta, {$this->main_css_element} .post-meta a",
					),
				),
				'filter' => array(
					'label'    => __( 'Filter', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_portfolio_filter",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_portfolio_item",
				),
			),
		);
		$this->custom_css_options = array(
			'portfolio_filters' => array(
				'label'    => __( 'Portfolio Filters', 'et_builder' ),
				'selector' => '.et_pb_filterable_portfolio .et_pb_portfolio_filters',
			),
			'active_portfolio_filter' => array(
				'label'    => __( 'Active Portfolio Filter', 'et_builder' ),
				'selector' => '.et_pb_filterable_portfolio .et_pb_portfolio_filters li a.active',
			),
			'portfolio_image' => array(
				'label'    => __( 'Portfolio Image', 'et_builder' ),
				'selector' => '.et_portfolio_image',
			),
			'overlay' => array(
				'label'    => __( 'Overlay', 'et_builder' ),
				'selector' => '.et_overlay',
			),
			'overlay_icon' => array(
				'label'    => __( 'Overlay Icon', 'et_builder' ),
				'selector' => '.et_overlay:before',
			),
			'portfolio_title' => array(
				'label'    => __( 'Portfolio Title', 'et_builder' ),
				'selector' => '.et_pb_portfolio_item h2',
			),
			'portfolio_post_meta' => array(
				'label'    => __( 'Portfolio Post Meta', 'et_builder' ),
				'selector' => '.et_pb_portfolio_item .post-meta',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'fullwidth' => array(
				'label'           => __( 'Layout', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => __( 'Fullwidth', 'et_builder' ),
					'off' => __( 'Grid', 'et_builder' ),
				),
				'description'        => __( 'Choose your desired portfolio layout style.', 'et_builder' ),
			),
			'posts_number' => array(
				'label'             => __( 'Posts Number', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Define the number of projects that should be displayed per page.', 'et_builder' ),
			),
			'include_categories' => array(
				'label'           => __( 'Include Categories', 'et_builder' ),
				'renderer'        => 'et_builder_include_categories_option',
				'option_category' => 'basic_option',
				'description'     => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
			),
			'show_title' => array(
				'label'             => __( 'Show Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn project titles on or off.', 'et_builder' ),
			),
			'show_categories' => array(
				'label'             => __( 'Show Categories', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn the category links on or off.', 'et_builder' ),
			),
			'show_pagination' => array(
				'label'             => __( 'Show Pagination', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Enable or disable pagination for this feed.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options' => array(
					'light'  => __( 'Dark', 'et_builder' ),
					'dark' => __( 'Light', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'hover_icon' => array(
				'label'               => __( 'Hover Icon Picker', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'tab_slug'            => 'advanced',
			),
			'zoom_icon_color' => array(
				'label'             => __( 'Zoom Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'hover_overlay_color' => array(
				'label'             => __( 'Hover Overlay Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$fullwidth          = $this->shortcode_atts['fullwidth'];
		$posts_number       = $this->shortcode_atts['posts_number'];
		$include_categories = $this->shortcode_atts['include_categories'];
		$show_title         = $this->shortcode_atts['show_title'];
		$show_categories    = $this->shortcode_atts['show_categories'];
		$show_pagination    = $this->shortcode_atts['show_pagination'];
		$background_layout  = $this->shortcode_atts['background_layout'];
		$hover_icon          = $this->shortcode_atts['hover_icon'];
		$zoom_icon_color     = $this->shortcode_atts['zoom_icon_color'];
		$hover_overlay_color = $this->shortcode_atts['hover_overlay_color'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		wp_enqueue_script( 'hashchange' );

		$args = array();

		if ( '' !== $zoom_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay:before',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $zoom_icon_color )
				),
			) );
		}

		if ( '' !== $hover_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay',
				'declaration' => sprintf(
					'background-color: %1$s;
					border-color: %1$s;',
					esc_html( $hover_overlay_color )
				),
			) );
		}

		if( 'on' === $show_pagination ) {
			$args['nopaging'] = true;
		} else {
			$args['posts_per_page'] = (int) $posts_number;
		}

		if ( '' !== $include_categories ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => explode( ',', $include_categories ),
					'operator' => 'IN',
				)
			);
		}

		$projects = et_divi_get_projects( $args );

		$categories_included = array();
		ob_start();
		if( $projects->post_count > 0 ) {
			while ( $projects->have_posts() ) {
				$projects->the_post();

				$category_classes = array();
				$categories = get_the_terms( get_the_ID(), 'project_category' );
				if ( $categories ) {
					foreach ( $categories as $category ) {
						$category_classes[] = 'project_category_' . urldecode( $category->slug );
						$categories_included[] = $category->term_id;
					}
				}

				$category_classes = implode( ' ', $category_classes );

				$main_post_class = sprintf(
					'et_pb_portfolio_item%1$s %2$s',
					( 'on' !== $fullwidth ? ' et_pb_grid_item' : '' ),
					$category_classes
				);

				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( $main_post_class ); ?>>
				<?php
					$thumb = '';

					$width = 'on' === $fullwidth ?  1080 : 400;
					$width = (int) apply_filters( 'et_pb_portfolio_image_width', $width );

					$height = 'on' === $fullwidth ?  9999 : 284;
					$height = (int) apply_filters( 'et_pb_portfolio_image_height', $height );
					$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( '' !== $thumb ) : ?>
						<a href="<?php the_permalink(); ?>">
						<?php if ( 'on' !== $fullwidth ) : ?>
							<span class="et_portfolio_image">
						<?php endif; ?>
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
						<?php if ( 'on' !== $fullwidth ) :

								$data_icon = '' !== $hover_icon
									? sprintf(
										' data-icon="%1$s"',
										esc_attr( et_pb_process_font_icon( $hover_icon ) )
									)
									: '';

								printf( '<span class="et_overlay%1$s"%2$s></span>',
									( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
									$data_icon
								);

						?>
							</span>
						<?php endif; ?>
						</a>
				<?php
					endif;
				?>

				<?php if ( 'on' === $show_title ) : ?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php endif; ?>

				<?php if ( 'on' === $show_categories ) : ?>
					<p class="post-meta"><?php echo get_the_term_list( get_the_ID(), 'project_category', '', ', ' ); ?></p>
				<?php endif; ?>

				</div><!-- .et_pb_portfolio_item -->
				<?php
			}
		}

		wp_reset_postdata();

		$posts = ob_get_clean();

		$categories_included = explode ( ',', $include_categories );
		$terms_args = array(
			'include' => $categories_included,
			'orderby' => 'name',
			'order' => 'ASC',
		);
		$terms = get_terms( 'project_category', $terms_args );

		$category_filters = '<ul class="clearfix">';
		$category_filters .= sprintf( '<li class="et_pb_portfolio_filter et_pb_portfolio_filter_all"><a href="#" class="active" data-category-slug="all">%1$s</a></li>',
			esc_html__( 'All', 'et_builder' )
		);
		foreach ( $terms as $term  ) {
			$category_filters .= sprintf( '<li class="et_pb_portfolio_filter"><a href="#" data-category-slug="%1$s">%2$s</a></li>',
				esc_attr( urldecode( $term->slug ) ),
				esc_html( $term->name )
			);
		}
		$category_filters .= '</ul>';

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%5$s class="et_pb_filterable_portfolio %1$s%4$s%6$s" data-posts-number="%7$d"%10$s>
				<div class="et_pb_portfolio_filters clearfix">%2$s</div><!-- .et_pb_portfolio_filters -->

				<div class="et_pb_portfolio_items_wrapper %8$s">
					<div class="et_pb_portfolio_items">%3$s</div><!-- .et_pb_portfolio_items -->
				</div>
				%9$s
			</div> <!-- .et_pb_filterable_portfolio -->',
			( 'on' === $fullwidth ? 'et_pb_filterable_portfolio_fullwidth' : 'et_pb_filterable_portfolio_grid clearfix' ),
			$category_filters,
			$posts,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			esc_attr( $posts_number),
			('on' === $show_pagination ? '' : 'no_pagination' ),
			('on' === $show_pagination ? '<div class="et_pb_portofolio_pagination"></div>' : '' ),
			is_rtl() ? ' data-rtl="true"' : ''
		);

		return $output;
	}
}
new ET_Builder_Module_Filterable_Portfolio;

class ET_Builder_Module_Bar_Counters extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Bar Counters', 'et_builder' );
		$this->slug            = 'et_pb_counters';
		$this->child_slug      = 'et_pb_counter';
		$this->child_item_text = __( 'Bar Counter', 'et_builder' );

		$this->whitelisted_fields = array(
			'background_layout',
			'background_color',
			'bar_bg_color',
			'use_percentages',
			'admin_label',
			'module_id',
			'module_class',
			'bar_top_padding',
			'bar_bottom_padding',
			'border_radius',
		);

		$this->fields_defaults = array(
			'background_layout' => array( 'light' ),
			'background_color'  => array( '#dddddd', 'add_default_setting' ),
			'bar_bg_color'      => array( et_builder_accent_color(), 'add_default_setting' ),
			'use_percentages'   => array( 'on' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_counters';
		$this->defaults         = array(
			'border_radius' => '0',
		);
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_counter_title",
					),
				),
				'percent'   => array(
					'label'    => __( 'Percent', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_counter_amount",
					),
				),
			),
			'border' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_counter_container",
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
		);
		$this->custom_css_options = array(
			'counter_title' => array(
				'label'    => __( 'Counter Title', 'et_builder' ),
				'selector' => '.et_pb_counter_title',
			),
			'counter_container' => array(
				'label'    => __( 'Counter Container', 'et_builder' ),
				'selector' => '.et_pb_counter_container',
			),
			'counter_amount' => array(
				'label'    => __( 'Counter Amount', 'et_builder' ),
				'selector' => '.et_pb_counter_amount',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'This will adjust the color of the empty space in the bar (currently gray).', 'et_builder' ),
			),
			'bar_bg_color' => array(
				'label'             => __( 'Bar Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'This will change the fill color for the bar.', 'et_builder' ),
			),
			'use_percentages' => array(
				'label'             => __( 'Use Percentages', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'On', 'et_builder' ),
					'off' => __( 'Off', 'et_builder' ),
				),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'bar_top_padding' => array(
				'label'           => __( 'Bar Top Padding', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'bar_bottom_padding' => array(
				'label'           => __( 'Bar Bottom Padding', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'border_radius' => array(
				'label'             => __( 'Border Radius', 'et_builder' ),
				'type'              => 'range',
				'option_category'   => 'layout',
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_counters_settings;

		$background_color   = $this->shortcode_atts['background_color'];
		$bar_bg_color       = $this->shortcode_atts['bar_bg_color'];
		$use_percentages    = $this->shortcode_atts['use_percentages'];
		$bar_top_padding    = $this->shortcode_atts['bar_top_padding'];
		$bar_bottom_padding = $this->shortcode_atts['bar_bottom_padding'];
		$border_radius      = $this->shortcode_atts['border_radius'];

		$et_pb_counters_settings = array(
			'background_color'   => $background_color,
			'bar_bg_color'       => $bar_bg_color,
			'use_percentages'    => $use_percentages,
			'bar_top_padding'    => $bar_top_padding,
			'bar_bottom_padding' => $bar_bottom_padding,
			'border_radius'      => $border_radius,
		);
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$background_layout  = $this->shortcode_atts['background_layout'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<ul%3$s class="et_pb_counters et-waypoint%2$s%4$s">
				%1$s
			</ul> <!-- .et_pb_counters -->',
			$this->shortcode_content,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Bar_Counters;

class ET_Builder_Module_Bar_Counters_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Bar Counter', 'et_builder' );
		$this->slug                        = 'et_pb_counter';
		$this->type                        = 'child';
		$this->child_title_var             = 'content_new';

		$this->whitelisted_fields = array(
			'content_new',
			'percent',
			'background_color',
			'bar_background_color',
			'label_color',
			'percentage_color',
		);

		$this->fields_defaults = array(
			'percent' => array( '0' ),
		);

		$this->advanced_setting_title_text = __( 'New Bar Counter', 'et_builder' );
		$this->settings_text               = __( 'Bar Counter Settings', 'et_builder' );
		$this->defaults                    = array(
			'border_radius' => '0',
		);

		$this->custom_css_options = array(
			'counter_title' => array(
				'label'    => __( 'Counter Title', 'et_builder' ),
				'selector' => '.et_pb_counter_title',
			),
			'counter_container' => array(
				'label'    => __( 'Counter Container', 'et_builder' ),
				'selector' => '.et_pb_counter_container',
			),
			'counter_amount' => array(
				'label'    => __( 'Counter Amount', 'et_builder' ),
				'selector' => '.et_pb_counter_amount',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'content_new' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input a title for your bar.', 'et_builder' ),
			),
			'percent' => array(
				'label'           => __( 'Percent', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define a percentage for this bar.', 'et_builder' ),
			),
			'background_color' => array(
				'label'        => __( 'Background Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'bar_background_color' => array(
				'label'        => __( 'Bar Background Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'label_color' => array(
				'label'        => __( 'Label Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'percentage_color' => array(
				'label'        => __( 'Percentage Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_counters_settings;

		$percent              = $this->shortcode_atts['percent'];
		$background_color     = $this->shortcode_atts['background_color'];
		$bar_background_color = $this->shortcode_atts['bar_background_color'];
		$label_color          = $this->shortcode_atts['label_color'];
		$percentage_color     = $this->shortcode_atts['percentage_color'];

		$module_class = ET_Builder_Element::add_module_order_class( '', $function_name );

		// Add % only if it hasn't been added to the attribute
		if ( '%' !== substr( trim( $percent ), -1 ) ) {
			$percent .= '%';
		}

		$background_color_style = $bar_bg_color_style = '';

		if ( '' === $background_color && isset( $et_pb_counters_settings['background_color'] ) && '' !== $et_pb_counters_settings['background_color'] ) {
			$background_color_style = sprintf( ' style="background-color: %1$s;"', esc_attr( $et_pb_counters_settings['background_color'] ) );
		}

		if ( '' === $bar_background_color && isset( $et_pb_counters_settings['bar_bg_color'] ) && '' !== $et_pb_counters_settings['bar_bg_color'] ) {
			$bar_bg_color_style = sprintf( ' background-color: %1$s;', esc_attr( $et_pb_counters_settings['bar_bg_color'] ) );
		}

		if ( ! empty( $et_pb_counters_settings['border_radius'] ) && $this->defaults['border_radius'] !== $et_pb_counters_settings['border_radius'] ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_container, %%order_class%% .et_pb_counter_amount',
				'declaration' => sprintf(
					'-moz-border-radius: %1$s; -webkit-border-radius: %1$s; border-radius: %1$s;',
					esc_html( et_builder_process_range_value( $et_pb_counters_settings['border_radius'] ) )
				),
			) );
		}

		if ( isset( $et_pb_counters_settings['bar_top_padding'] ) && '' !== $et_pb_counters_settings['bar_top_padding'] ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_amount',
				'declaration' => sprintf(
					'padding-top: %1$s;',
					esc_html( et_builder_process_range_value( $et_pb_counters_settings['bar_top_padding'] ) )
				),
			) );
		}

		if ( isset( $et_pb_counters_settings['bar_bottom_padding'] ) && '' !== $et_pb_counters_settings['bar_bottom_padding'] ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_amount',
				'declaration' => sprintf(
					'padding-bottom: %1$s;',
					esc_html( et_builder_process_range_value( $et_pb_counters_settings['bar_bottom_padding'] ) )
				),
			) );
		}

		if ( '' !== $background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_container',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $background_color )
				),
			) );
		}

		if ( '' !== $bar_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_amount',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $bar_background_color )
				),
			) );
		}

		if ( '' !== $label_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_title',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $label_color )
				),
			) );
		}

		if ( '' !== $percentage_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_counter_amount',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $percentage_color )
				),
			) );
		}

		$output = sprintf(
			'<li class="%6$s">
				<span class="et_pb_counter_title">%1$s</span>
				<span class="et_pb_counter_container"%4$s>
					<span class="et_pb_counter_amount" style="%5$s" data-width="%3$s"><span class="et_pb_counter_amount_number">%2$s</span></span>
				</span>
			</li>',
			sanitize_text_field( $content ),
			( isset( $et_pb_counters_settings['use_percentages'] ) && 'on' === $et_pb_counters_settings['use_percentages'] ? esc_html( $percent ) : '' ),
			esc_attr( $percent ),
			$background_color_style,
			$bar_bg_color_style,
			esc_attr( ltrim( $module_class ) )
		);

		return $output;
	}
}
new ET_Builder_Module_Bar_Counters_Item;

class ET_Builder_Module_Circle_Counter extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Circle Counter', 'et_builder' );
		$this->slug = 'et_pb_circle_counter';

		$this->whitelisted_fields = array(
			'title',
			'number',
			'percent_sign',
			'background_layout',
			'bar_bg_color',
			'admin_label',
			'module_id',
			'module_class',
			'circle_color',
			'circle_color_alpha',
		);

		$this->fields_defaults = array(
			'number'            => array( '0' ),
			'percent_sign'      => array( 'on' ),
			'background_layout' => array( 'light' ),
			'bar_bg_color'      => array( et_builder_accent_color(), 'add_default_setting' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_circle_counter';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h3",
					),
				),
				'number'   => array(
					'label'    => __( 'Number', 'et_builder' ),
					'hide_line_height' => true,
					'css'      => array(
						'main' => "{$this->main_css_element} .percent p",
					),
				),
			),
		);
		$this->custom_css_options = array(
			'percent' => array(
				'label'    => __( 'Percent', 'et_builder' ),
				'selector' => '.percent',
			),
			'circle_counter_title' => array(
				'label'    => __( 'Circle Counter Title', 'et_builder' ),
				'selector' => 'h3',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description' => __( 'Input a title for the circle counter.', 'et_builder' ),
			),
			'number' => array(
				'label'             => __( 'Number', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'number_validation' => true,
				'description'       => __( "Define a number for the circle counter. (Don't include the percentage sign, use the option below.). <strong>Note: You can use only natural numbers from 0 to 100</strong>", 'et_builder' ),
			),
			'percent_sign' => array(
				'label'           => __( 'Percent Sign', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'On', 'et_builder' ),
					'off' => __( 'Off', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether the percent sign should be added after the number set above.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'bar_bg_color' => array(
				'label'             => __( 'Bar Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => __( 'This will change the fill color for the bar.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'circle_color' => array(
				'label'             => __( 'Circle Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'circle_color_alpha' => array(
				'label'           => __( 'Circle Color Opacity', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'configuration',
				'range_settings'  => array(
					'min'  => '0.1',
					'max'  => '1.0',
					'step' => '0.05',
				),
				'tab_slug' => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		wp_enqueue_script( 'easypiechart' );
		$number                      = $this->shortcode_atts['number'];
		$percent_sign                = $this->shortcode_atts['percent_sign'];
		$title                       = $this->shortcode_atts['title'];
		$module_id                   = $this->shortcode_atts['module_id'];
		$module_class                = $this->shortcode_atts['module_class'];
		$background_layout           = $this->shortcode_atts['background_layout'];
		$bar_bg_color                = $this->shortcode_atts['bar_bg_color'];
		$circle_color                = $this->shortcode_atts['circle_color'];
		$circle_color_alpha          = $this->shortcode_atts['circle_color_alpha'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$number = str_ireplace( '%', '', $number );

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$circle_color_data = '' !== $circle_color ?
			sprintf( ' data-color="%1$s"', esc_attr( $circle_color ) )
			: '';
		$circle_color_alpha_data = '' !== $circle_color_alpha ?
			sprintf( ' data-alpha="%1$s"', esc_attr( $circle_color_alpha ) )
			: '';

		$output = sprintf(
			'<div%1$s class="et_pb_circle_counter container-width-change-notify%2$s%3$s" data-number-value="%4$s" data-bar-bg-color="%5$s"%8$s%9$s>
					<div class="percent"><p><span class="percent-value"></span>%6$s</p></div>
					%7$s
			</div><!-- .et_pb_circle_counter -->',
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			esc_attr( $class ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			esc_attr( $number ),
			esc_attr( $bar_bg_color ),
			( 'on' == $percent_sign ? '%' : ''),
			( '' !== $title ? '<h3>' . esc_html( $title ) . '</h3>' : '' ),
			$circle_color_data,
			$circle_color_alpha_data
		);

		return $output;
	}
}
new ET_Builder_Module_Circle_Counter;

class ET_Builder_Module_Number_Counter extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Number Counter', 'et_builder' );
		$this->slug = 'et_pb_number_counter';

		$this->whitelisted_fields = array(
			'title',
			'number',
			'percent_sign',
			'counter_color',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'number'            => array( '0' ),
			'percent_sign'      => array( 'on' ),
			'counter_color'     => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout' => array( 'light' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_number_counter';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h3",
					),
				),
				'number'   => array(
					'label'    => __( 'Number', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .percent p",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'use_margin' => false,
				'css' => array(
					'important' => 'all',
				),
			),
		);

		if ( et_is_builder_plugin_active() ) {
			$this->advanced_options['fonts']['number']['css']['important'] = 'all';
		}

		$this->custom_css_options = array(
			'percent' => array(
				'label'    => __( 'Percent', 'et_builder' ),
				'selector' => '.percent',
			),
			'number_counter_title' => array(
				'label'    => __( 'Number Counter Title', 'et_builder' ),
				'selector' => 'h3',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input a title for the counter.', 'et_builder' ),
			),
			'number' => array(
				'label'           => __( 'Number', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( "Define a number for the counter. (Don't include the percentage sign, use the option below.)", 'et_builder' ),
			),
			'percent_sign' => array(
				'label'             => __( 'Percent Sign', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'On', 'et_builder' ),
					'off' => __( 'Off', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether the percent sign should be added after the number set above.', 'et_builder' ),
			),
			'counter_color' => array(
				'label'             => __( 'Counter Text Color', 'et_builder' ),
				'type'              => 'color',
				'description'       => __( 'This will change the fill color for the bar.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your title text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		wp_enqueue_script( 'easypiechart' );
		$number            = $this->shortcode_atts['number'];
		$percent_sign      = $this->shortcode_atts['percent_sign'];
		$title             = $this->shortcode_atts['title'];
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$counter_color     = $this->shortcode_atts['counter_color'];
		$background_layout = $this->shortcode_atts['background_layout'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( et_is_builder_plugin_active() ) {
			wp_enqueue_script( 'fittext' );
		}

		$number = str_ireplace( '%', '', $number );

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%1$s class="et_pb_number_counter%2$s%3$s" data-number-value="%4$s">
				<div class="percent" style="%5$s"><p><span class="percent-value"></span>%6$s</p></div>
				%7$s
			</div><!-- .et_pb_number_counter -->',
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			esc_attr( $class ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			esc_attr( $number ),
			sprintf( 'color:%s', esc_attr( $counter_color ) ),
			( 'on' == $percent_sign ? '%' : ''),
			( '' !== $title ? '<h3>' . esc_html( $title ) . '</h3>' : '' )
		 );

		return $output;
	}
}
new ET_Builder_Module_Number_Counter;

class ET_Builder_Module_Accordion extends ET_Builder_Module {
	function init() {
		$this->name       = __( 'Accordion', 'et_builder' );
		$this->slug       = 'et_pb_accordion';
		$this->child_slug = 'et_pb_accordion_item';

		$this->whitelisted_fields = array(
			'admin_label',
			'module_id',
			'module_class',
			'open_toggle_background_color',
			'closed_toggle_background_color',
			'icon_color',
		);

		$this->main_css_element = '%%order_class%%.et_pb_accordion';
		$this->advanced_options = array(
			'fonts' => array(
				'toggle' => array(
					'label'    => __( 'Toggle', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h5.et_pb_toggle_title",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main'        => "{$this->main_css_element} .et_pb_toggle_content",
						'line_height' => "{$this->main_css_element} .et_pb_toggle_content p",
					),
				),
			),
			'background' => array(
				'use_background_color' => false,
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_toggle_content",
				),
			),
			'border' => array(
				'css'        => array(
					'main' => "{$this->main_css_element} .et_pb_toggle",
				),
			),
			'custom_margin_padding' => array(
				'use_margin' => false,
				'css'        => array(
					'main' => "{$this->main_css_element} .et_pb_toggle_content",
					'important' => 'all',
				),
			),
		);
		$this->custom_css_options = array(
			'toggle' => array(
				'label'    => __( 'Toggle', 'et_builder' ),
				'selector' => '.et_pb_toggle',
			),
			'open_toggle' => array(
				'label'    => __( 'Open Toggle', 'et_builder' ),
				'selector' => '.et_pb_toggle_open',
			),
			'toggle_title' => array(
				'label'    => __( 'Toggle Title', 'et_builder' ),
				'selector' => '.et_pb_toggle_title',
			),
			'toggle_icon' => array(
				'label'    => __( 'Toggle Icon', 'et_builder' ),
				'selector' => '.et_pb_toggle_title:before',
			),
			'toggle_content' => array(
				'label'    => __( 'Toggle Content', 'et_builder' ),
				'selector' => '.et_pb_toggle_content',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'open_toggle_background_color' => array(
				'label'             => __( 'Open Toggle Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'closed_toggle_background_color' => array(
				'label'             => __( 'Closed Toggle Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'icon_color' => array(
				'label'             => __( 'Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_accordion_item_number;

		$et_pb_accordion_item_number = 1;

	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                      = $this->shortcode_atts['module_id'];
		$module_class                   = $this->shortcode_atts['module_class'];
		$open_toggle_background_color   = $this->shortcode_atts['open_toggle_background_color'];
		$closed_toggle_background_color = $this->shortcode_atts['closed_toggle_background_color'];
		$icon_color                     = $this->shortcode_atts['icon_color'];

		global $et_pb_accordion_item_number;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $open_toggle_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_toggle_open',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $open_toggle_background_color )
				),
			) );
		}

		if ( '' !== $closed_toggle_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_toggle_close',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $closed_toggle_background_color )
				),
			) );
		}

		if ( '' !== $icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_toggle_title:before',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $icon_color )
				),
			) );
		}

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_accordion%2$s">
				%1$s
			</div> <!-- .et_pb_accordion -->',
			$this->shortcode_content,
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Accordion;

class ET_Builder_Module_Accordion_Item extends ET_Builder_Module {
	function init() {
		$this->name                  = __( 'Accordion', 'et_builder' );
		$this->slug                  = 'et_pb_accordion_item';
		$this->type                  = 'child';
		$this->child_title_var       = 'title';
		$this->no_shortcode_callback = true;

		$this->whitelisted_fields = array(
			'title',
			'content_new',
			'open_toggle_background_color',
			'open_toggle_text_color',
			'closed_toggle_background_color',
			'closed_toggle_text_color',
			'icon_color',
		);

		$this->advanced_options      = array(
			'background'            => array(
				'use_background_color' => false,
			),
			'custom_margin_padding' => array(
				'use_margin' => false,
				'css' => array(
					'important' => 'all'
				)
			),
		);

		$this->custom_css_options = array(
			'toggle' => array(
				'label'    => __( 'Toggle', 'et_builder' ),
			),
			'open_toggle' => array(
				'label'    => __( 'Open Toggle', 'et_builder' ),
				'selector' => '.et_pb_toggle_open',
			),
			'toggle_title' => array(
				'label'    => __( 'Toggle Title', 'et_builder' ),
				'selector' => '.et_pb_toggle_title',
			),
			'toggle_icon' => array(
				'label'    => __( 'Toggle Icon', 'et_builder' ),
				'selector' => '.et_pb_toggle_title:before',
			),
			'toggle_content' => array(
				'label'    => __( 'Toggle Content', 'et_builder' ),
				'selector' => '.et_pb_toggle_content',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'The toggle title will appear above the content and when the toggle is closed.', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Here you can define the content that will be placed within the current tab.', 'et_builder' ),
			),
			'open_toggle_background_color' => array(
				'label'             => __( 'Open Toggle Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'open_toggle_text_color' => array(
				'label'             => __( 'Open Toggle Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'closed_toggle_background_color' => array(
				'label'             => __( 'Closed Toggle Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'closed_toggle_text_color' => array(
				'label'             => __( 'Closed Toggle Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'icon_color' => array(
				'label'             => __( 'Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}
}
new ET_Builder_Module_Accordion_Item;

class ET_Builder_Module_Toggle extends ET_Builder_Module {
	function init() {
		$this->name                       = __( 'Toggle', 'et_builder' );
		$this->slug                       = 'et_pb_toggle';
		$this->additional_shortcode_slugs = array( 'et_pb_accordion_item' );

		$this->whitelisted_fields = array(
			'title',
			'open',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'open_toggle_background_color',
			'closed_toggle_background_color',
			'icon_color',
		);

		$this->fields_defaults = array(
			'open' => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_toggle';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h5",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'use_margin' => false,
				'css' => array(
					'important' => 'all',
				),
			),
		);
		$this->custom_css_options = array(
			'open_toggle' => array(
				'label'    => __( 'Open Toggle', 'et_builder' ),
				'selector' => '.et_pb_toggle_open',
			),
			'toggle_title' => array(
				'label'    => __( 'Toggle Title', 'et_builder' ),
				'selector' => '.et_pb_toggle_title',
			),
			'toggle_icon' => array(
				'label'    => __( 'Toggle Icon', 'et_builder' ),
				'selector' => '.et_pb_toggle_title:before',
			),
			'toggle_content' => array(
				'label'    => __( 'Toggle Content', 'et_builder' ),
				'selector' => '.et_pb_toggle_content',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'The toggle title will appear above the content and when the toggle is closed.', 'et_builder' ),
			),
			'open' => array(
				'label'           => __( 'State', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => __( 'Close', 'et_builder' ),
					'on'  => __( 'Open', 'et_builder' ),
				),
				'description' => __( 'Choose whether or not this toggle should start in an open or closed state.', 'et_builder' ),
			),
			'content_new' => array(
				'label'             => __( 'Content', 'et_builder' ),
				'type'              => 'tiny_mce',
				'option_category'   => 'basic_option',
				'description'       => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'open_toggle_background_color' => array(
				'label'             => __( 'Open Toggle Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'closed_toggle_background_color' => array(
				'label'             => __( 'Closed Toggle Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'icon_color' => array(
				'label'             => __( 'Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id    = $this->shortcode_atts['module_id'];
		$module_class = $this->shortcode_atts['module_class'];
		$title        = $this->shortcode_atts['title'];
		$open         = $this->shortcode_atts['open'];
		$open_toggle_background_color = $this->shortcode_atts['open_toggle_background_color'];
		$closed_toggle_background_color = $this->shortcode_atts['closed_toggle_background_color'];
		$icon_color = $this->shortcode_atts['icon_color'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $open_toggle_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_toggle_open',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $open_toggle_background_color )
				),
			) );
		}

		if ( '' !== $closed_toggle_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_toggle_close',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $closed_toggle_background_color )
				),
			) );
		}

		if ( '' !== $icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_toggle_title:before',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $icon_color )
				),
			) );
		}

		if ( 'et_pb_accordion_item' === $function_name ) {
			global $et_pb_accordion_item_number;

			$open = 1 === $et_pb_accordion_item_number ? 'on' : 'off';

			$et_pb_accordion_item_number++;
		}

		// Adding "_item" class for toggle module for customizer targetting. There's no proper selector
		// for toggle module styles since both accordion and toggle module use the same selector
		if( 'et_pb_toggle' === $function_name ){
			$module_class .= " et_pb_toggle_item";
		}

		$output = sprintf(
			'<div%4$s class="et_pb_module et_pb_toggle %2$s%5$s">
				<h5 class="et_pb_toggle_title">%1$s</h5>
				<div class="et_pb_toggle_content clearfix">
					%3$s
				</div> <!-- .et_pb_toggle_content -->
			</div> <!-- .et_pb_toggle -->',
			esc_html( $title ),
			( 'on' === $open ? 'et_pb_toggle_open' : 'et_pb_toggle_close' ),
			$this->shortcode_content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Toggle;

class ET_Builder_Module_Contact_Form extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Contact Form', 'et_builder' );
		$this->slug = 'et_pb_contact_form';

		$this->whitelisted_fields = array(
			'captcha',
			'email',
			'title',
			'admin_label',
			'module_id',
			'module_class',
			'form_background_color',
			'input_border_radius',
		);

		$this->fields_defaults = array(
			'captcha' => array( 'on' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_contact_form_container';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h1",
					),
				),
				'form_field'   => array(
					'label'    => __( 'Form Field', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .input",
					),
				),
			),
			'border' => array(
				'css'      => array(
					'main' => "{$this->main_css_element} .input",
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
		$this->custom_css_options = array(
			'contact_title' => array(
				'label'    => __( 'Contact Title', 'et_builder' ),
				'selector' => '.et_pb_contact_main_title',
			),
			'contact_button' => array(
				'label'    => __( 'Contact Button', 'et_builder' ),
				'selector' => '.et_pb_contact_submit',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'captcha' => array(
				'label'           => __( 'Display Captcha', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description' => __( 'Turn the captcha on or off using this option.', 'et_builder' ),
			),
			'email' => array(
				'label'           => __( 'Email', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the email address where messages should be sent.', 'et_builder' ),
			),
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Define a title for your contact form.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'form_background_color' => array(
				'label'             => __( 'Form Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'input_border_radius'   => array(
				'label'             => __( 'Input Border Radius', 'et_builder' ),
				'type'              => 'range',
				'option_category'   => 'layout',
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id             = $this->shortcode_atts['module_id'];
		$module_class          = $this->shortcode_atts['module_class'];
		$captcha               = $this->shortcode_atts['captcha'];
		$email                 = $this->shortcode_atts['email'];
		$title                 = $this->shortcode_atts['title'];
		$form_background_color = $this->shortcode_atts['form_background_color'];
		$input_border_radius   = $this->shortcode_atts['input_border_radius'];
		$button_custom         = $this->shortcode_atts['custom_button'];
		$custom_icon           = $this->shortcode_atts['button_icon'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $form_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .input',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $form_background_color )
				),
			) );
		}

		if ( ! in_array( $input_border_radius, array( '', '0' ) ) ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .input',
				'declaration' => sprintf(
					'-moz-border-radius: %1$s; -webkit-border-radius: %1$s; border-radius: %1$s;',
					esc_html( et_builder_process_range_value( $input_border_radius ) )
				),
			) );
		}

		$et_pb_contact_form_num = $this->shortcode_callback_num();

		$et_error_message = '';
		$et_contact_error = false;
		$contact_email = isset( $_POST['et_pb_contact_email_' . $et_pb_contact_form_num] ) ? sanitize_email( $_POST['et_pb_contact_email_' . $et_pb_contact_form_num] ) : '';

		if ( isset( $_POST['et_pb_contactform_submit_' . $et_pb_contact_form_num] ) ) {
			if ( empty( $_POST['et_pb_contact_name_' . $et_pb_contact_form_num] ) || empty( $contact_email ) || empty( $_POST['et_pb_contact_message_' . $et_pb_contact_form_num] ) ) {
				$et_error_message .= sprintf( '<p>%1$s</p>', esc_html__( 'Make sure you fill all fields.', 'et_builder' ) );
				$et_contact_error = true;
			}

			if ( 'on' === $captcha && ( ! isset( $_POST['et_pb_contact_captcha_' . $et_pb_contact_form_num] ) || empty( $_POST['et_pb_contact_captcha_' . $et_pb_contact_form_num] ) ) ) {
				$et_error_message .= sprintf( '<p>%1$s</p>', esc_html__( 'Make sure you entered the captcha.', 'et_builder' ) );
				$et_contact_error = true;
			}

			if ( ! is_email( $contact_email ) ) {
				$et_error_message .= sprintf( '<p>%1$s</p>', esc_html__( 'Invalid Email.', 'et_builder' ) );
				$et_contact_error = true;
			}
		} else {
			$et_contact_error = true;
		}

		// generate digits for captcha
		$et_pb_first_digit = rand( 1, 15 );
		$et_pb_second_digit = rand( 1, 15 );

		if ( ! $et_contact_error && isset( $_POST['_wpnonce-et-pb-contact-form-submitted'] ) && wp_verify_nonce( $_POST['_wpnonce-et-pb-contact-form-submitted'], 'et-pb-contact-form-submit' ) ) {
			$et_email_to = '' !== $email
				? $email
				: get_site_option( 'admin_email' );

			$et_site_name = get_option( 'blogname' );

			$contact_name 	= stripslashes( sanitize_text_field( $_POST['et_pb_contact_name_' . $et_pb_contact_form_num] ) );

			$headers[] = "From: \"{$contact_name}\" <{$contact_email}>";
			$headers[] = "Reply-To: <{$contact_email}>";

			wp_mail( apply_filters( 'et_contact_page_email_to', $et_email_to ),
				sprintf( __( 'New Message From %1$s%2$s', 'et_builder' ),
					sanitize_text_field( html_entity_decode( $et_site_name ) ),
					( '' !== $title ? sprintf( _x( ' - %s', 'contact form title separator', 'et_builder' ), sanitize_text_field( html_entity_decode( $title ) ) ) : '' )
				), stripslashes( wp_strip_all_tags( $_POST['et_pb_contact_message_' . $et_pb_contact_form_num] ) ), apply_filters( 'et_contact_page_headers', $headers, $contact_name, $contact_email ) );

			$et_error_message = sprintf( '<p>%1$s</p>', esc_html__( 'Thanks for contacting us', 'et_builder' ) );
		}

		$form = '';

		$name_label = __( 'Name', 'et_builder' );
		$email_label = __( 'Email Address', 'et_builder' );
		$message_label = __( 'Message', 'et_builder' );

		$et_pb_captcha = sprintf( '
			<div class="et_pb_contact_right">
				<p class="clearfix">
					<span class="et_pb_contact_captcha_question">%1$s</span> = <input type="text" size="2" class="input et_pb_contact_captcha" data-first_digit="%3$s" data-second_digit="%4$s" value="" name="et_pb_contact_captcha_%2$s">
				</p>
			</div> <!-- .et_pb_contact_right -->',
			sprintf( '%1$s + %2$s', esc_html( $et_pb_first_digit ), esc_html( $et_pb_second_digit ) ),
			esc_attr( $et_pb_contact_form_num ),
			esc_attr( $et_pb_first_digit ),
			esc_attr( $et_pb_second_digit )
		);

		if ( $et_contact_error )
			$form = sprintf( '
				<div class="et_pb_contact">
					<form class="et_pb_contact_form clearfix" method="post" action="%1$s">
						<div class="et_pb_contact_left">
							<p class="clearfix">
								<label for="et_pb_contact_name_%13$s" class="et_pb_contact_form_label">%2$s</label>
								<input type="text" id="et_pb_contact_name_%13$s" class="input et_pb_contact_name" value="%3$s" name="et_pb_contact_name_%13$s">
							</p>
							<p class="clearfix">
								<label for="et_pb_contact_email_%13$s" class="et_pb_contact_form_label">%4$s</label>
								<input type="text" id="et_pb_contact_email_%13$s" class="input et_pb_contact_email" value="%5$s" name="et_pb_contact_email_%13$s">
							</p>
						</div> <!-- .et_pb_contact_left -->

						<div class="clear"></div>
						<p class="clearfix">
							<label for="et_pb_contact_message_%13$s" class="et_pb_contact_form_label">%7$s</label>
							<textarea name="et_pb_contact_message_%13$s" id="et_pb_contact_message_%13$s" class="et_pb_contact_message input">%8$s</textarea>
						</p>

						<input type="hidden" value="et_contact_proccess" name="et_pb_contactform_submit_%13$s">

						<button type="submit" class="et_pb_contact_submit et_pb_button%12$s"%11$s>%9$s</button>

						%6$s

						%10$s
					</form>
				</div> <!-- .et_pb_contact -->',
				esc_url( get_permalink( get_the_ID() ) ),
				$name_label,
				( isset( $_POST['et_pb_contact_name_' . $et_pb_contact_form_num] ) ? esc_attr( $_POST['et_pb_contact_name_' . $et_pb_contact_form_num] ) : $name_label ),
				$email_label,
				( isset( $_POST['et_pb_contact_email_' . $et_pb_contact_form_num] ) ? esc_attr( $_POST['et_pb_contact_email_' . $et_pb_contact_form_num] ) : $email_label ),
				(  'on' === $captcha ? $et_pb_captcha : '' ),
				$message_label,
				( isset( $_POST['et_pb_contact_message_' . $et_pb_contact_form_num] ) ? esc_attr( $_POST['et_pb_contact_message_' . $et_pb_contact_form_num] ) : $message_label ),
				__( 'Submit', 'et_builder' ),
				wp_nonce_field( 'et-pb-contact-form-submit', '_wpnonce-et-pb-contact-form-submitted', true, false ),
				'' !== $custom_icon && 'on' === $button_custom ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon ) )
				) : '',
				'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
				esc_attr( $et_pb_contact_form_num )
			);

		$output = sprintf( '
			<div id="%4$s" class="et_pb_module et_pb_contact_form_container clearfix%5$s">
				%1$s
				<div class="et-pb-contact-message">%2$s</div>
				%3$s
			</div> <!-- .et_pb_contact_form_container -->
			',
			( '' !== $title ? sprintf( '<h1 class="et_pb_contact_main_title">%1$s</h1>', esc_html( $title ) ) : '' ),
			'' !== $et_error_message ? $et_error_message : '',
			$form,
			( '' !== $module_id
				? esc_attr( $module_id )
				: esc_attr( 'et_pb_contact_form_' . $et_pb_contact_form_num )
			),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Contact_Form;

class ET_Builder_Module_Sidebar extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Sidebar', 'et_builder' );
		$this->slug = 'et_pb_sidebar';

		$this->whitelisted_fields = array(
			'orientation',
			'area',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
			'remove_border',
		);

		$this->fields_defaults = array(
			'orientation'       => array( 'left' ),
			'background_layout' => array( 'light' ),
			'remove_border'     => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_widget_area';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h3, {$this->main_css_element} h4, {$this->main_css_element} .widget-title",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element}, {$this->main_css_element} li, {$this->main_css_element} li:before, {$this->main_css_element} a",
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
		);
		$this->custom_css_options = array(
			'widget' => array(
				'label'    => __( 'Widget', 'et_builder' ),
				'selector' => '.et_pb_widget',
			),
			'title' => array(
				'label'    => __( 'Title', 'et_builder' ),
				'selector' => 'h4.widgettitle',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'orientation' => array(
				'label'             => __( 'Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'left'  => __( 'Left', 'et_builder' ),
					'right' => __( 'Right', 'et_builder' ),
				),
				'description'        => __( 'Choose which side of the page your sidebar will be on. This setting controls text orientation and border position.', 'et_builder' ),
			),
			'area' => array(
				'label'           => __( 'Widget Area', 'et_builder' ),
				'renderer'        => 'et_builder_get_widget_areas',
				'option_category' => 'basic_option',
				'description'     => __( 'Select a widget-area that you would like to display. You can create new widget areas within the Appearances > Widgets tab.', 'et_builder' )
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'remove_border' => array(
				'label'           => __( 'Remove Border Separator', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug' => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$orientation       = $this->shortcode_atts['orientation'];
		$area              = "" === $this->shortcode_atts['area'] ? $this->get_default_area() : $this->shortcode_atts['area'];
		$background_layout = $this->shortcode_atts['background_layout'];
		$remove_border     = $this->shortcode_atts['remove_border'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$widgets = '';

		ob_start();

		if ( 'on' === $remove_border ) {
			$module_class = rtrim( $module_class ) . ' et_pb_sidebar_no_border';
		}

		if ( is_active_sidebar( $area ) )
			dynamic_sidebar( $area );

		$widgets = ob_get_contents();

		ob_end_clean();

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%4$s class="et_pb_widget_area %2$s clearfix%3$s%5$s">
				%1$s
			</div> <!-- .et_pb_widget_area -->',
			$widgets,
			esc_attr( "et_pb_widget_area_{$orientation}" ),
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}

	function get_default_area() {
		global $wp_registered_sidebars;

		if ( ! empty( $wp_registered_sidebars ) ) {
			// Pluck sidebar ids
			$sidebar_ids = wp_list_pluck( $wp_registered_sidebars, 'id' );

			// Return first sidebar id
			return array_shift( $sidebar_ids );
		}

		return "";
	}
}
new ET_Builder_Module_Sidebar;

class ET_Builder_Module_Divider extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Divider', 'et_builder' );
		$this->slug = 'et_pb_divider';

		$this->defaults = array(
			'divider_style'    => 'solid',
			'divider_position' => 'top',
			'divider_weight'   => '1px',
		);

		// Show divider options is modifieable via customizer
		$this->show_divider_options = array(
			'off' => __( "Don't Show Divider", 'et_builder' ),
			'on'  => __( 'Show Divider', 'et_builder' ),
		);

		if ( ! et_is_builder_plugin_active() && true === et_get_option( 'et_pb_divider-show_divider', false ) ) {
			$this->show_divider_options = array_reverse( $this->show_divider_options );
			$show_divider_default = 'on';
		} else {
			$show_divider_default = 'off';
		}

		$this->whitelisted_fields = array(
			'color',
			'show_divider',
			'height',
			'admin_label',
			'module_id',
			'module_class',
			'divider_style',
			'divider_position',
			'divider_weight',
			'hide_on_mobile',
		);

		$this->fields_defaults = array(
			'color'          => array( '#ffffff', 'only_default_setting' ),
			'show_divider'   => array( $show_divider_default ),
			'hide_on_mobile' => array( 'on' ),
		);
	}

	function get_fields() {
		$fields = array(
			'color' => array(
				'label'       => __( 'Color', 'et_builder' ),
				'type'        => 'color-alpha',
				'description' => __( 'This will adjust the color of the 1px divider line.', 'et_builder' ),
			),
			'show_divider' => array(
				'label'             => __( 'Visibility', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->show_divider_options,
				'affects' => array(
					'#et_pb_divider_style',
					'#et_pb_divider_position',
					'#et_pb_divider_weight',
				),
				'description'        => __( 'This settings turns on and off the 1px divider line, but does not affect the divider height.', 'et_builder' ),
			),
			'height' => array(
				'label'           => __( 'Height', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'description'     => __( 'Define how much space should be added below the divider.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'divider_style' => array(
				'label'             => __( 'Divider Style', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_border_styles(),
				'depends_show_if'   => 'on',
				'tab_slug'          => 'advanced',
			),
			'divider_position' => array(
				'label'           => __( 'Divider Position', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'top'    => __( 'Top', 'et_builder' ),
					'center' => __( 'Vertically Centered', 'et_builder' ),
					'bottom' => __( 'Bottom', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'tab_slug'          => 'advanced',
			),
			'divider_weight' => array(
				'label'             => __( 'Divider Weight', 'et_builder' ),
				'type'              => 'range',
				'option_category'   => 'layout',
				'depends_show_if'   => 'on',
				'tab_slug'          => 'advanced',
			),
			'hide_on_mobile' => array(
				'label'             => __( 'Hide On Mobile', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id        = $this->shortcode_atts['module_id'];
		$module_class     = $this->shortcode_atts['module_class'];
		$color            = $this->shortcode_atts['color'];
		$show_divider     = $this->shortcode_atts['show_divider'];
		$height           = $this->shortcode_atts['height'];
		$divider_style    = $this->shortcode_atts['divider_style'];
		$divider_position = $this->shortcode_atts['divider_position'];
		$divider_position_customizer = ! et_is_builder_plugin_active() ? et_get_option( 'et_pb_divider-divider_position', 'top' ) : 'top';
		$divider_weight   = $this->shortcode_atts['divider_weight'];
		$hide_on_mobile   = $this->shortcode_atts['hide_on_mobile'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$style = '';

		if ( '' !== $color && 'on' === $show_divider ) {
			$style .= sprintf( ' border-top-color: %s;',
				esc_attr( $color )
			);

			if ( $this->defaults['divider_style'] !== $divider_style ) {
				$style .= sprintf( ' border-top-style: %s;',
					esc_attr( $divider_style )
				);
			}

			if ( $this->defaults['divider_weight'] !== $divider_weight ) {
				$style .= sprintf( ' border-top-width: %1$spx;',
					esc_attr( $divider_weight )
				);
			}

			if ( '' !== $style ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%:before',
					'declaration' => ltrim( $style )
				) );
			}

			if ( $this->defaults['divider_position'] !== $divider_position ) {
				$module_class .= " et_pb_divider_position_{$divider_position}";
			} elseif ( $this->defaults['divider_position'] !== $divider_position_customizer ) {
				$module_class .= " et_pb_divider_position_{$divider_position_customizer} customized_et_pb_divider_position";
			}
		}

		if ( '' !== $height ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'height: %s;',
					esc_attr( et_builder_process_range_value( $height ) )
				),
			) );
		}

		if ( 'on' === $hide_on_mobile ) {
			$module_class .= ' ' . self::HIDE_ON_MOBILE;
		}

		$output = sprintf(
			'<hr%2$s class="et_pb_module et_pb_space%1$s%3$s" />',
			( 'on' === $show_divider ? ' et_pb_divider' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( ltrim( $module_class ) ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Divider;

class ET_Builder_Module_Team_Member extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Person', 'et_builder' );
		$this->slug = 'et_pb_team_member';

		$this->whitelisted_fields = array(
			'name',
			'position',
			'image_url',
			'animation',
			'background_layout',
			'facebook_url',
			'twitter_url',
			'google_url',
			'linkedin_url',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'icon_color',
			'icon_hover_color',
		);

		$this->fields_defaults = array(
			'animation'         => array( 'off' ),
			'background_layout' => array( 'light' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_team_member';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h4",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} *",
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);
		$this->custom_css_options = array(
			'member_image' => array(
				'label'    => __( 'Member Image', 'et_builder' ),
				'selector' => '.et_pb_team_member_image',
			),
			'member_description' => array(
				'label'    => __( 'Member Description', 'et_builder' ),
				'selector' => '.et_pb_team_member_description',
			),
			'title' => array(
				'label'    => __( 'Title', 'et_builder' ),
				'selector' => '.et_pb_team_member_description h4',
			),
			'member_position' => array(
				'label'    => __( 'Member Position', 'et_builder' ),
				'selector' => '.et_pb_member_position',
			),
			'member_social_links' => array(
				'label'    => __( 'Member Social Links', 'et_builder' ),
				'selector' => '.et_pb_member_social_links',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'name' => array(
				'label'           => __( 'Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the name of the person', 'et_builder' ),
			),
			'position' => array(
				'label'           => __( 'Position', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( "Input the person's position.", 'et_builder' ),
			),
			'image_url' => array(
				'label'              => __( 'Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'animation' => array(
				'label'             => __( 'Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'off'     => __( 'No Animation', 'et_builder' ),
					'fade_in' => __( 'Fade In', 'et_builder' ),
					'left'    => __( 'Left To Right', 'et_builder' ),
					'right'   => __( 'Right To Left', 'et_builder' ),
					'top'     => __( 'Top To Bottom', 'et_builder' ),
					'bottom'  => __( 'Bottom To Top', 'et_builder' ),
				),
				'description'       => __( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'           => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
			),
			'facebook_url' => array(
				'label'           => __( 'Facebook Profile Url', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input Facebook Profile Url.', 'et_builder' ),
			),
			'twitter_url' => array(
				'label'           => __( 'Twitter Profile Url', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input Twitter Profile Url', 'et_builder' ),
			),
			'google_url' => array(
				'label'           => __( 'Google+ Profile Url', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input Google+ Profile Url', 'et_builder' ),
			),
			'linkedin_url' => array(
				'label'           => __( 'LinkedIn Profile Url', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input LinkedIn Profile Url', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => __( 'Description', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'icon_color' => array(
				'label'             => __( 'Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'icon_hover_color' => array(
				'label'             => __( 'Icon Hover Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$name              = $this->shortcode_atts['name'];
		$position          = $this->shortcode_atts['position'];
		$image_url         = $this->shortcode_atts['image_url'];
		$animation         = $this->shortcode_atts['animation'];
		$facebook_url      = $this->shortcode_atts['facebook_url'];
		$twitter_url       = $this->shortcode_atts['twitter_url'];
		$google_url        = $this->shortcode_atts['google_url'];
		$linkedin_url      = $this->shortcode_atts['linkedin_url'];
		$background_layout = $this->shortcode_atts['background_layout'];
		$icon_color        = $this->shortcode_atts['icon_color'];
		$icon_hover_color  = $this->shortcode_atts['icon_hover_color'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$image = $social_links = '';

		if ( '' !== $icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_member_social_links a',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $icon_color )
				),
			) );
		}

		if ( '' !== $icon_hover_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_member_social_links a:hover',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $icon_hover_color )
				),
			) );
		}

		if ( '' !== $facebook_url ) {
			$social_links .= sprintf(
				'<li><a href="%1$s" class="et_pb_font_icon et_pb_facebook_icon"><span>%2$s</span></a></li>',
				esc_url( $facebook_url ),
				esc_html__( 'Facebook', 'et_builder' )
			);
		}

		if ( '' !== $twitter_url ) {
			$social_links .= sprintf(
				'<li><a href="%1$s" class="et_pb_font_icon et_pb_twitter_icon"><span>%2$s</span></a></li>',
				esc_url( $twitter_url ),
				esc_html__( 'Twitter', 'et_builder' )
			);
		}

		if ( '' !== $google_url ) {
			$social_links .= sprintf(
				'<li><a href="%1$s" class="et_pb_font_icon et_pb_google_icon"><span>%2$s</span></a></li>',
				esc_url( $google_url ),
				esc_html__( 'Google+', 'et_builder' )
			);
		}

		if ( '' !== $linkedin_url ) {
			$social_links .= sprintf(
				'<li><a href="%1$s" class="et_pb_font_icon et_pb_linkedin_icon"><span>%2$s</span></a></li>',
				esc_url( $linkedin_url ),
				esc_html__( 'LinkedIn', 'et_builder' )
			);
		}

		if ( '' !== $social_links ) {
			$social_links = sprintf( '<ul class="et_pb_member_social_links">%1$s</ul>', $social_links );
		}

		if ( '' !== $image_url ) {
			$image = sprintf(
				'<div class="et_pb_team_member_image et-waypoint%3$s">
					<img src="%1$s" alt="%2$s" />
				</div>',
				esc_attr( $image_url ),
				esc_attr( $name ),
				esc_attr( " et_pb_animation_{$animation}" )
			);
		}

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_team_member%4$s%9$s et_pb_bg_layout_%8$s clearfix">
				%2$s
				<div class="et_pb_team_member_description">
					%5$s
					%6$s
					%1$s
					%7$s
				</div> <!-- .et_pb_team_member_description -->
			</div> <!-- .et_pb_team_member -->',
			$this->shortcode_content,
			( '' !== $image ? $image : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $name ? sprintf( '<h4>%1$s</h4>', esc_html( $name ) ) : '' ),
			( '' !== $position ? sprintf( '<p class="et_pb_member_position">%1$s</p>', esc_html( $position ) ) : '' ),
			$social_links,
			$background_layout,
			( '' === $image ? ' et_pb_team_member_no_image' : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Team_Member;

class ET_Builder_Module_Blog extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Blog', 'et_builder' );
		$this->slug = 'et_pb_blog';

		$this->whitelisted_fields = array(
			'fullwidth',
			'posts_number',
			'include_categories',
			'meta_date',
			'show_thumbnail',
			'show_content',
			'show_more',
			'show_author',
			'show_date',
			'show_categories',
			'show_comments',
			'show_pagination',
			'offset_number',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
			'masonry_tile_background_color',
			'use_dropshadow',
		);

		$this->fields_defaults = array(
			'fullwidth'         => array( 'on' ),
			'posts_number'      => array( 10, 'add_default_setting' ),
			'meta_date'         => array( 'M j, Y', 'add_default_setting' ),
			'show_thumbnail'    => array( 'on' ),
			'show_content'      => array( 'off' ),
			'show_more'         => array( 'off' ),
			'show_author'       => array( 'on' ),
			'show_date'         => array( 'on' ),
			'show_categories'   => array( 'on' ),
			'show_comments'     => array( 'off' ),
			'show_pagination'   => array( 'on' ),
			'offset_number'     => array( 0, 'only_default_setting' ),
			'background_layout' => array( 'light' ),
			'use_dropshadow'    => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%% .et_pb_post';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'meta' => array(
					'label'    => __( 'Meta', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .post-meta",
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'border' => array(),
		);
		$this->custom_css_options = array(
			'title' => array(
				'label'    => __( 'Title', 'et_builder' ),
				'selector' => '.et_pb_post h2',
			),
			'post_meta' => array(
				'label'    => __( 'Post Meta', 'et_builder' ),
				'selector' => '.et_pb_post .post-meta',
			),
			'pagenavi' => array(
				'label'    => __( 'Pagenavi', 'et_builder' ),
				'selector' => '.wp_pagenavi',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'fullwidth' => array(
				'label'             => __( 'Layout', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'Fullwidth', 'et_builder' ),
					'off' => __( 'Grid', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_background_layout',
					'#et_pb_use_dropshadow',
					'#et_pb_masonry_tile_background_color',
				),
				'description'        => __( 'Toggle between the various blog layout types.', 'et_builder' ),
			),
			'posts_number' => array(
				'label'             => __( 'Posts Number', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Choose how much posts you would like to display per page.', 'et_builder' ),
			),
			'include_categories' => array(
				'label'            => __( 'Include Categories', 'et_builder' ),
				'renderer'         => 'et_builder_include_categories_option',
				'option_category'  => 'basic_option',
				'renderer_options' => array(
					'use_terms' => false,
				),
				'description'      => __( 'Choose which categories you would like to include in the feed.', 'et_builder' ),
			),
			'meta_date' => array(
				'label'             => __( 'Meta Date Format', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'If you would like to adjust the date format, input the appropriate PHP date format here.', 'et_builder' ),
			),
			'show_thumbnail' => array(
				'label'             => __( 'Show Featured Image', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'This will turn thumbnails on and off.', 'et_builder' ),
			),
			'show_content' => array(
				'label'             => __( 'Content', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'Show Excerpt', 'et_builder' ),
					'on'  => __( 'Show Content', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_show_more',
				),
				'description'        => __( 'Showing the full content will not truncate your posts on the index page. Showing the excerpt will only display your excerpt text.', 'et_builder' ),
			),
			'show_more' => array(
				'label'             => __( 'Read More Button', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
				'description'       => __( 'Here you can define whether to show "read more" link after the excerpts or not.', 'et_builder' ),
			),
			'show_author' => array(
				'label'             => __( 'Show Author', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn on or off the author link.', 'et_builder' ),
			),
			'show_date' => array(
				'label'             => __( 'Show Date', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn the date on or off.', 'et_builder' ),
			),
			'show_categories' => array(
				'label'             => __( 'Show Categories', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn the category links on or off.', 'et_builder' ),
			),
			'show_comments' => array(
				'label'             => __( 'Show Comment Count', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn comment count on and off.', 'et_builder' ),
			),
			'show_pagination' => array(
				'label'             => __( 'Show Pagination', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn pagination on and off.', 'et_builder' ),
			),
			'offset_number' => array(
				'label'           => __( 'Offset Number', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Choose how many posts you would like to offset by', 'et_builder' ),
			),
			'background_layout' => array(
				'label'       => __( 'Text Color', 'et_builder' ),
				'type'        => 'select',
				'option_category' => 'color_option',
				'options'           => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'depends_default' => true,
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'masonry_tile_background_color' => array(
				'label'             => __( 'Grid Tile Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'depends_show_if'   => 'off',
			),
			'use_dropshadow' => array(
				'label'             => __( 'Use Dropshadow', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
				'depends_show_if'   => 'off',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$fullwidth          = $this->shortcode_atts['fullwidth'];
		$posts_number       = $this->shortcode_atts['posts_number'];
		$include_categories = $this->shortcode_atts['include_categories'];
		$meta_date          = $this->shortcode_atts['meta_date'];
		$show_thumbnail     = $this->shortcode_atts['show_thumbnail'];
		$show_content       = $this->shortcode_atts['show_content'];
		$show_author        = $this->shortcode_atts['show_author'];
		$show_date          = $this->shortcode_atts['show_date'];
		$show_categories    = $this->shortcode_atts['show_categories'];
		$show_comments      = $this->shortcode_atts['show_comments'];
		$show_pagination    = $this->shortcode_atts['show_pagination'];
		$background_layout  = $this->shortcode_atts['background_layout'];
		$show_more          = $this->shortcode_atts['show_more'];
		$offset_number      = $this->shortcode_atts['offset_number'];
		$masonry_tile_background_color = $this->shortcode_atts['masonry_tile_background_color'];
		$use_dropshadow     = $this->shortcode_atts['use_dropshadow'];

		global $paged;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$container_is_closed = false;

		// remove all filters from WP audio shortcode to make sure current theme doesn't add any elements into audio module
		remove_all_filters( 'wp_audio_shortcode_library' );
		remove_all_filters( 'wp_audio_shortcode' );
		remove_all_filters( 'wp_audio_shortcode_class');

		if ( '' !== $masonry_tile_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_blog_grid .et_pb_post',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $masonry_tile_background_color )
				),
			) );
		}

		if ( 'on' !== $fullwidth ){
			if ( 'on' === $use_dropshadow ) {
				$module_class .= ' et_pb_blog_grid_dropshadow';
			}

			wp_enqueue_script( 'salvattore' );

			$background_layout = 'light';
		}

		$args = array( 'posts_per_page' => (int) $posts_number );

		$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );

		if ( is_front_page() ) {
			$paged = $et_paged;
		}

		if ( '' !== $include_categories )
			$args['cat'] = $include_categories;

		if ( ! is_search() ) {
			$args['paged'] = $et_paged;
		}

		if ( '' !== $offset_number && ! empty( $offset_number ) ) {
			/**
			 * Offset + pagination don't play well. Manual offset calculation required
			 * @see: https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
			 */
			if ( $paged > 1 ) {
				$args['offset'] = ( ( $et_paged - 1 ) * intval( $posts_number ) ) + intval( $offset_number );
			} else {
				$args['offset'] = intval( $offset_number );
			}
		}

		if ( is_single() && ! isset( $args['post__not_in'] ) ) {
			$args['post__not_in'] = array( get_the_ID() );
		}

		ob_start();

		query_posts( $args );

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				$post_format = et_pb_post_format();

				$thumb = '';

				$width = 'on' === $fullwidth ? 1080 : 400;
				$width = (int) apply_filters( 'et_pb_blog_image_width', $width );

				$height = 'on' === $fullwidth ? 675 : 250;
				$height = (int) apply_filters( 'et_pb_blog_image_height', $height );
				$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
				$titletext = get_the_title();
				$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
				$thumb = $thumbnail["thumb"];

				$no_thumb_class = '' === $thumb || 'off' === $show_thumbnail ? ' et_pb_no_thumb' : '';

				if ( in_array( $post_format, array( 'video', 'gallery' ) ) ) {
					$no_thumb_class = '';
				} ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $no_thumb_class ); ?>>

			<?php
				et_divi_post_format_content();

				if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
					if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
						printf(
							'<div class="et_main_video_container">
								%1$s
							</div>',
							$first_video
						);
					elseif ( 'gallery' === $post_format ) :
						et_pb_gallery_images( 'slider' );
					elseif ( '' !== $thumb && 'on' === $show_thumbnail ) :
						if ( 'on' !== $fullwidth ) echo '<div class="et_pb_image_container">'; ?>
							<a href="<?php the_permalink(); ?>">
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
							</a>
					<?php
						if ( 'on' !== $fullwidth ) echo '</div> <!-- .et_pb_image_container -->';
					endif;
				} ?>

			<?php if ( 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) { ?>
				<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) { ?>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php } ?>

				<?php
					if ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_categories || 'on' === $show_comments ) {
						printf( '<p class="post-meta">%1$s %2$s %3$s %4$s %5$s %6$s %7$s</p>',
							(
								'on' === $show_author
									? sprintf( __( 'by %s', 'et_builder' ), '<span class="author vcard">' .  et_pb_get_the_author_posts_link() . '</span>' )
									: ''
							),
							(
								( 'on' === $show_author && 'on' === $show_date )
									? ' | '
									: ''
							),
							(
								'on' === $show_date
									? sprintf( __( '%s', 'et_builder' ), '<span class="published">' . get_the_date( $meta_date ) . '</span>' )
									: ''
							),
							(
								(( 'on' === $show_author || 'on' === $show_date ) && 'on' === $show_categories)
									? ' | '
									: ''
							),
							(
								'on' === $show_categories
									? get_the_category_list(', ')
									: ''
							),
							(
								(( 'on' === $show_author || 'on' === $show_date || 'on' === $show_categories ) && 'on' === $show_comments)
									? ' | '
									: ''
							),
							(
								'on' === $show_comments
									? sprintf( _nx( '1 Comment', '%s Comments', get_comments_number(), 'number of comments', 'et_builder' ), number_format_i18n( get_comments_number() ) )
									: ''
							)
						);
					}

					$post_content = get_the_content();

					// do not display the content if it contains Blog or Portfolio modules to avoid infinite loops
					if ( ! has_shortcode( $post_content, 'et_pb_blog' ) && ! has_shortcode( $post_content, 'et_pb_portfolio' ) ) {
						if ( 'on' === $show_content ) {
							global $more;

							// page builder doesn't support more tag, so display the_content() in case of post made with page builder
							if ( et_pb_is_pagebuilder_used( get_the_ID() ) ) {
								$more = 1;
								the_content();
							} else {
								$more = null;
								the_content( __( 'read more...', 'et_builder' ) );
							}
						} else {
							if ( has_excerpt() ) {
								the_excerpt();
							} else {
								truncate_post( 270 );
							}
						}
					} else if ( has_excerpt() ) {
						the_excerpt();
					}

					if ( 'on' !== $show_content ) {
						$more = 'on' == $show_more ? sprintf( ' <a href="%1$s" class="more-link" >%2$s</a>' , esc_url( get_permalink() ), __( 'read more', 'et_builder' ) )  : '';
						echo $more;
					}
					?>
			<?php } // 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ?>

			</article> <!-- .et_pb_post -->
	<?php
			} // endwhile

			if ( 'on' === $show_pagination && ! is_search() ) {
				echo '</div> <!-- .et_pb_posts -->';

				$container_is_closed = true;

				if ( function_exists( 'wp_pagenavi' ) ) {
					wp_pagenavi();
				} else {
					if ( et_is_builder_plugin_active() ) {
						include( ET_BUILDER_PLUGIN_DIR . 'includes/navigation.php' );
					} else {
						get_template_part( 'includes/navigation', 'index' );
					}
				}
			}

			wp_reset_query();
		} else {
			if ( et_is_builder_plugin_active() ) {
				include( ET_BUILDER_PLUGIN_DIR . 'includes/no-results.php' );
			} else {
				get_template_part( 'includes/no-results', 'index' );
			}
		}

		$posts = ob_get_contents();

		ob_end_clean();

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%5$s class="%1$s%3$s%6$s"%7$s>
				%2$s
			%4$s',
			( 'on' === $fullwidth ? 'et_pb_posts' : 'et_pb_blog_grid clearfix' ),
			$posts,
			esc_attr( $class ),
			( ! $container_is_closed ? '</div> <!-- .et_pb_posts -->' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( 'on' !== $fullwidth ? ' data-columns' : '' )
		);

		if ( 'on' !== $fullwidth )
			$output = sprintf( '<div class="et_pb_blog_grid_wrapper">%1$s</div>', $output );

		return $output;
	}
}
new ET_Builder_Module_Blog;

class ET_Builder_Module_Shop extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Shop', 'et_builder' );
		$this->slug = 'et_pb_shop';

		$this->whitelisted_fields = array(
			'type',
			'posts_number',
			'columns_number',
			'include_categories',
			'orderby',
			'admin_label',
			'module_id',
			'module_class',
			'sale_badge_color',
			'icon_hover_color',
			'hover_overlay_color',
			'hover_icon',
		);

		$this->fields_defaults = array(
			'type'           => array( 'recent' ),
			'posts_number'   => array( '12', 'add_default_setting' ),
			'columns_number' => array( '0' ),
			'orderby'        => array( 'menu_order' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_shop';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .woocommerce ul.products li.product h3",
					),
				),
				'price' => array(
					'label'    => __( 'Price', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .woocommerce ul.products li.product .price, {$this->main_css_element} .woocommerce ul.products li.product .price .amount",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
		);
		$this->custom_css_options = array(
			'product' => array(
				'label'    => __( 'Product', 'et_builder' ),
				'selector' => 'li.product',
			),
			'onsale' => array(
				'label'    => __( 'Onsale', 'et_builder' ),
				'selector' => 'li.product .onsale',
			),
			'image' => array(
				'label'    => __( 'Image', 'et_builder' ),
				'selector' => '.et_shop_image',
			),
			'overlay' => array(
				'label'    => __( 'Overlay', 'et_builder' ),
				'selector' => '.et_overlay',
			),
			'title' => array(
				'label'    => __( 'Title', 'et_builder' ),
				'selector' => 'li.product h3',
			),
			'rating' => array(
				'label'    => __( 'Rating', 'et_builder' ),
				'selector' => '.star-rating',
			),
			'price' => array(
				'label'    => __( 'Price', 'et_builder' ),
				'selector' => 'li.product .price',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'type' => array(
				'label'           => __( 'Type', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'recent'  => __( 'Recent Products', 'et_builder' ),
					'featured' => __( 'Featured Products', 'et_builder' ),
					'sale' => __( 'Sale Products', 'et_builder' ),
					'best_selling' => __( 'Best Selling Products', 'et_builder' ),
					'top_rated' => __( 'Top Rated Products', 'et_builder' ),
					'product_category' => __( 'Product Category', 'et_builder' ),
				),
				'affects'            => array(
					'input[name="et_pb_include_categories"]',
				),
				'description'        => __( 'Choose which type of products you would like to display.', 'et_builder' ),
			),
			'posts_number' => array(
				'label'             => __( 'Posts Number', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => __( 'Control how many products are displayed.', 'et_builder' ),
			),
			'include_categories'   => array(
				'label'            => __( 'Include Categories', 'et_builder' ),
				'type'             => 'basic_option',
				'renderer'         => 'et_builder_include_categories_shop_option',
				'renderer_options' => array(
					'use_terms'    => true,
					'term_name'    => 'product_cat',
				),
				'depends_show_if'  => 'product_category',
				'description'      => __( 'Choose which categories you would like to include.', 'et_builder' ),
			),
			'columns_number' => array(
				'label'             => __( 'Columns Number', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'0' => __( 'default', 'et_builder' ),
					'6' => sprintf( __( '%1$s Columns', 'et_builder' ), esc_html( '6' ) ),
					'5' => sprintf( __( '%1$s Columns', 'et_builder' ), esc_html( '5' ) ),
					'4' => sprintf( __( '%1$s Columns', 'et_builder' ), esc_html( '4' ) ),
					'3' => sprintf( __( '%1$s Columns', 'et_builder' ), esc_html( '3' ) ),
					'2' => sprintf( __( '%1$s Columns', 'et_builder' ), esc_html( '2' ) ),
					'1' => __( '1 Column', 'et_builder' ),
				),
				'description'        => __( 'Choose how many columns to display.', 'et_builder' ),
			),
			'orderby' => array(
				'label'             => __( 'Order By', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'menu_order'  => __( 'Default Sorting', 'et_builder' ),
					'popularity' => __( 'Sort By Popularity', 'et_builder' ),
					'rating' => __( 'Sort By Rating', 'et_builder' ),
					'date' => __( 'Sort By Date', 'et_builder' ),
					'price' => __( 'Sort By Price: Low To High', 'et_builder' ),
					'price-desc' => __( 'Sort By Price: High To Low', 'et_builder' ),
				),
				'description'        => __( 'Choose how your products should be ordered.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'sale_badge_color' => array(
				'label'             => __( 'Sale Badge Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'icon_hover_color' => array(
				'label'             => __( 'Icon Hover Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'hover_overlay_color' => array(
				'label'             => __( 'Hover Overlay Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'hover_icon' => array(
				'label'               => __( 'Hover Icon Picker', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'tab_slug'            => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$type                    = $this->shortcode_atts['type'];
		$include_categories      = $this->shortcode_atts['include_categories'];
		$posts_number            = $this->shortcode_atts['posts_number'];
		$orderby                 = $this->shortcode_atts['orderby'];
		$columns                 = $this->shortcode_atts['columns_number'];
		$sale_badge_color        = $this->shortcode_atts['sale_badge_color'];
		$icon_hover_color        = $this->shortcode_atts['icon_hover_color'];
		$hover_overlay_color     = $this->shortcode_atts['hover_overlay_color'];
		$hover_icon              = $this->shortcode_atts['hover_icon'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $sale_badge_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% span.onsale',
				'declaration' => sprintf(
					'background-color: %1$s !important;',
					esc_html( $sale_badge_color )
				),
			) );
		}

		if ( '' !== $icon_hover_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay:before',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $icon_hover_color )
				),
			) );
		}

		if ( '' !== $hover_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay',
				'declaration' => sprintf(
					'background-color: %1$s !important;
					border-color: %1$s;',
					esc_html( $hover_overlay_color )
				),
			) );
		}

		$data_icon = '' !== $hover_icon
			? sprintf(
				' data-icon="%1$s"',
				esc_attr( et_pb_process_font_icon( $hover_icon ) )
			)
			: '';

		$woocommerce_shortcodes_types = array(
			'recent'       => 'recent_products',
			'featured'     => 'featured_products',
			'sale'         => 'sale_products',
			'best_selling' => 'best_selling_products',
			'top_rated'    => 'top_rated_products',
			'product_category' => 'product_category',
		);

		/**
		 * Actually, orderby parameter used by WooCommerce shortcode is equal to orderby parameter used by WP_Query
		 * Hence customize WooCommerce' product query via modify_woocommerce_shortcode_products_query method
		 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/#section-5
		 */
		$modify_woocommerce_query = in_array( $orderby, array( 'price', 'price-desc', 'rating', 'popularity' ) );

		if ( $modify_woocommerce_query ) {
			add_filter( 'woocommerce_shortcode_products_query', array( $this, 'modify_woocommerce_shortcode_products_query' ), 10, 2 );
		}

		$output = sprintf(
			'<div%2$s class="et_pb_module et_pb_shop%3$s%4$s"%5$s>
				%1$s
			</div>',
			do_shortcode(
				sprintf( '[%1$s per_page="%2$s" orderby="%3$s" columns="%4$s" category="%5$s"]',
					esc_html( $woocommerce_shortcodes_types[$type] ),
					esc_attr( $posts_number ),
					esc_attr( $orderby ),
					esc_attr( $columns ),
					esc_attr( $include_categories )
				)
			),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			'0' === $columns ? ' et_pb_shop_grid' : '',
			$data_icon
		);

		/**
		 * Remove modify_woocommerce_shortcode_products_query method after being used
		 */
		if ( $modify_woocommerce_query ) {
			remove_filter( 'woocommerce_shortcode_products_query', array( $this, 'modify_woocommerce_shortcode_products_query' ) );

			if ( function_exists( 'WC' ) ) {
				WC()->query->remove_ordering_args(); // remove args added by woocommerce to avoid errors in sql queries performed afterwards
			}
		}

		return $output;
	}

	/**
	 * Modifying WooCommerce' product query filter based on $orderby value given
	 * @see WC_Query->get_catalog_ordering_args()
	 */
	function modify_woocommerce_shortcode_products_query( $args, $atts ) {

		if ( function_exists( 'WC' ) ) {
			// By default, all order is ASC except for price-desc
			$order = 'price-desc' === $this->shortcode_atts['orderby'] ? 'DESC' : 'ASC';

			// Supported orderby arguments (as defined by WC_Query->get_catalog_ordering_args() ): rand | date | price | popularity | rating | title
			$orderby = in_array( $this->shortcode_atts['orderby'], array( 'price-desc' ) ) ? 'price' : $this->shortcode_atts['orderby'];

			// Get arguments for the given non-native orderby
			$query_args = WC()->query->get_catalog_ordering_args( $orderby, $order );

			// Confirm that returned argument isn't empty then merge returned argument with default argument
			if( is_array( $query_args ) && ! empty( $query_args ) ) {
				$args = array_merge( $args, $query_args );
			}
		}

		return $args;
	}
}
new ET_Builder_Module_Shop;

class ET_Builder_Module_Countdown_Timer extends ET_Builder_Module {
	function init() {
		$this->name = __( 'Countdown Timer', 'et_builder' );
		$this->slug = 'et_pb_countdown_timer';

		$this->whitelisted_fields = array(
			'title',
			'date_time',
			'background_layout',
			'use_background_color',
			'background_color',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'background_layout'    => array( 'dark' ),
			'use_background_color' => array( 'on' ),
			'background_color'     => array( et_builder_accent_color(), 'only_default_setting' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_countdown_timer';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h4",
					),
				),
				'numbers' => array(
					'label'    => __( 'Numbers', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .section p",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'label' => array(
					'label'    => __( 'Label', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .section p.label",
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
			'background' => array(
				'use_background_color' => false,
			),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);
		$this->custom_css_options = array(
			'container' => array(
				'label'    => __( 'Container', 'et_builder' ),
				'selector' => '.et_pb_countdown_timer_container',
			),
			'title' => array(
				'label'    => __( 'Title', 'et_builder' ),
				'selector' => '.title',
			),
			'timer_section' => array(
				'label'    => __( 'Timer Section', 'et_builder' ),
				'selector' => '.section',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Countdown Timer Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This is the title displayed for the countdown timer.', 'et_builder' ),
			),
			'date_time' => array(
				'label'           => __( 'Countdown To', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => sprintf( __( 'This is the date the countdown timer is counting down to. Your countdown timer is based on your timezone settings in your <a href="%1$s" target="_blank" title="WordPress General Settings">WordPress General Settings</a>', 'et_builder' ), esc_url( admin_url( 'options-general.php' ) ) ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'use_background_color' => array(
				'label'           => __( 'Use Background Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'on' => __( 'Yes', 'et_builder' ),
					'off'  => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_background_color',
				),
				'description' => __( 'Here you can choose whether background color setting below should be used or not.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'depends_default'   => true,
				'description'       => __( 'Here you can define a custom background color for your countdown timer.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id            = $this->shortcode_atts['module_id'];
		$module_class         = $this->shortcode_atts['module_class'];
		$title                = $this->shortcode_atts['title'];
		$date_time            = $this->shortcode_atts['date_time'];
		$background_layout    = $this->shortcode_atts['background_layout'];
		$background_color     = $this->shortcode_atts['background_color'];
		$use_background_color = $this->shortcode_atts['use_background_color'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$module_id = '' !== $module_id ? sprintf( ' id="%s"', esc_attr( $module_id ) ) : '';
		$module_class = '' !== $module_class ? sprintf( ' %s', esc_attr( $module_class ) ) : '';

		$background_layout = sprintf( ' et_pb_bg_layout_%s', esc_attr( $background_layout ) );

		$end_date = gmdate( 'M d, Y H:i:s', strtotime( $date_time ) );
		$gmt_offset        = get_option( 'gmt_offset' );
		$gmt_divider       = '-' === substr( $gmt_offset, 0, 1 ) ? '-' : '+';
		$gmt_offset_hour   = str_pad( abs( intval( $gmt_offset ) ), 2, "0", STR_PAD_LEFT );
		$gmt_offset_minute = str_pad( ( ( abs( $gmt_offset ) * 100 ) % 100 ) * ( 60 / 100 ), 2, "0", STR_PAD_LEFT );
		$gmt               = "GMT{$gmt_divider}{$gmt_offset_hour}{$gmt_offset_minute}";

		if ( '' !== $title ) {
			$title = sprintf( '<h4 class="title">%s</h4>', esc_html( $title ) );
		}

		$background_color_style = '';
		if ( ! empty( $background_color ) && 'on' == $use_background_color ) {
			$background_color_style = sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) );
		}

		$output = sprintf(
			'<div%1$s class="et_pb_module et_pb_countdown_timer%2$s%3$s"%4$s data-end-timestamp="%5$s">
				<div class="et_pb_countdown_timer_container clearfix">
					%6$s
					<div class="days section values" data-short="%14$s" data-full="%7$s">
						<p class="value"></p>
						<p class="label">%7$s</p>
					</div>
					<div class="sep section"><p>:</p></div>
					<div class="hours section values" data-short="%9$s" data-full="%8$s">
						<p class="value"></p>
						<p class="label">%8$s</p>
					</div>
					<div class="sep section"><p>:</p></div>
					<div class="minutes section values" data-short="%11$s" data-full="%10$s">
						<p class="value"></p>
						<p class="label">%10$s</p>
					</div>
					<div class="sep section"><p>:</p></div>
					<div class="seconds section values" data-short="%13$s" data-full="%12$s">
						<p class="value"></p>
						<p class="label">%12$s</p>
					</div>
				</div>
			</div>',
			$module_id,
			$background_layout,
			$module_class,
			$background_color_style,
			esc_attr( strtotime( "{$end_date} {$gmt}" ) ),
			$title,
			esc_html__( 'Day(s)', 'et_builder' ),
			esc_html__( 'Hour(s)', 'et_builder' ),
			esc_attr__( 'Hrs', 'et_builder' ),
			esc_html__( 'Minute(s)', 'et_builder' ),
			esc_attr__( 'Min', 'et_builder' ),
			esc_html__( 'Second(s)', 'et_builder' ),
			esc_attr__( 'Sec', 'et_builder' ),
			esc_attr__( 'Day', 'et_builder' )
		);

		return $output;
	}
}
new ET_Builder_Module_Countdown_Timer;

class ET_Builder_Module_Map extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Map', 'et_builder' );
		$this->slug            = 'et_pb_map';
		$this->child_slug      = 'et_pb_map_pin';
		$this->child_item_text = __( 'Pin', 'et_builder' );

		$this->whitelisted_fields = array(
			'address',
			'zoom_level',
			'address_lat',
			'address_lng',
			'map_center_map',
			'mouse_wheel',
			'admin_label',
			'module_id',
			'module_class',
			'use_grayscale_filter',
			'grayscale_filter_amount',
		);

		$this->fields_defaults = array(
			'zoom_level'           => array( '18', 'only_default_setting' ),
			'mouse_wheel'          => array( 'on' ),
			'use_grayscale_filter' => array( 'off' ),
		);
	}

	function get_fields() {
		$fields = array(
			'address' => array(
				'label'             => __( 'Map Center Address', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'additional_button' => sprintf(
					' <a href="#" class="et_pb_find_address button">%1$s</a>',
					esc_html__( 'Find', 'et_builder' )
				),
				'class' => array( 'et_pb_address' ),
				'description'       => __( 'Enter an address for the map center point, and the address will be geocoded and displayed on the map below.', 'et_builder' ),
			),
			'zoom_level' => array(
				'type'    => 'hidden',
				'class'   => array( 'et_pb_zoom_level' ),
			),
			'address_lat' => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_address_lat' ),
			),
			'address_lng' => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_address_lng' ),
			),
			'map_center_map' => array(
				'renderer'              => 'et_builder_generate_center_map_setting',
				'use_container_wrapper' => false,
				'option_category'       => 'basic_option',
			),
			'mouse_wheel' => array(
				'label'           => __( 'Mouse Wheel Zoom', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options' => array(
					'on'  => __( 'On', 'et_builder' ),
					'off' => __( 'Off', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether the zoom level will be controlled by mouse wheel or not.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'use_grayscale_filter' => array(
				'label'           => __( 'Use Grayscale Filter', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_grayscale_filter_amount',
				),
				'tab_slug' => 'advanced',
			),
			'grayscale_filter_amount' => array(
				'label'           => __( 'Grayscale Filter Amount (%)', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'configuration',
				'tab_slug'        => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$address_lat             = $this->shortcode_atts['address_lat'];
		$address_lng             = $this->shortcode_atts['address_lng'];
		$zoom_level              = $this->shortcode_atts['zoom_level'];
		$mouse_wheel             = $this->shortcode_atts['mouse_wheel'];
		$use_grayscale_filter    = $this->shortcode_atts['use_grayscale_filter'];
		$grayscale_filter_amount = $this->shortcode_atts['grayscale_filter_amount'];

		wp_enqueue_script( 'google-maps-api' );

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$all_pins_content = $this->shortcode_content;

		$grayscale_filter_data = '';
		if ( 'on' === $use_grayscale_filter && '' !== $grayscale_filter_amount ) {
			$grayscale_filter_data = sprintf( ' data-grayscale="%1$s"', esc_attr( $grayscale_filter_amount ) );
		}

		$output = sprintf(
			'<div%5$s class="et_pb_module et_pb_map_container%6$s"%8$s>
				<div class="et_pb_map" data-center-lat="%1$s" data-center-lng="%2$s" data-zoom="%3$d" data-mouse-wheel="%7$s"></div>
				%4$s
			</div>',
			esc_attr( $address_lat ),
			esc_attr( $address_lng ),
			esc_attr( $zoom_level ),
			$all_pins_content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			esc_attr( $mouse_wheel ),
			$grayscale_filter_data
		);

		return $output;
	}
}
new ET_Builder_Module_Map;

class ET_Builder_Module_Map_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Pin', 'et_builder' );
		$this->slug                        = 'et_pb_map_pin';
		$this->type                        = 'child';
		$this->child_title_var             = 'title';
		$this->custom_css_tab              = false;

		$this->whitelisted_fields = array(
			'title',
			'pin_address',
			'zoom_level',
			'pin_address_lat',
			'pin_address_lng',
			'map_center_map',
			'content_new',
		);

		$this->advanced_setting_title_text = __( 'New Pin', 'et_builder' );
		$this->settings_text               = __( 'Pin Settings', 'et_builder' );
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'The title will be used within the tab button for this tab.', 'et_builder' ),
			),
			'pin_address' => array(
				'label'             => __( 'Map Pin Address', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'class'             => array( 'et_pb_pin_address' ),
				'description'       => __( 'Enter an address for this map pin, and the address will be geocoded and displayed on the map below.', 'et_builder' ),
				'additional_button' => sprintf(
					'<a href="#" class="et_pb_find_address button">%1$s</a>',
					esc_html__( 'Find', 'et_builder' )
				),
			),
			'zoom_level' => array(
				'renderer'        => 'et_builder_generate_pin_zoom_level_input',
				'option_category' => 'basic_option',
				'class'           => array( 'et_pb_zoom_level' ),
			),
			'pin_address_lat' => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_pin_address_lat' ),
			),
			'pin_address_lng' => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_pin_address_lng' ),
			),
			'map_center_map' => array(
				'renderer'              => 'et_builder_generate_center_map_setting',
				'option_category'       => 'basic_option',
				'use_container_wrapper' => false,
			),
			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Here you can define the content that will be placed within the infobox for the pin.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_tab_titles;

		$title = $this->shortcode_atts['title'];
		$pin_address_lat = $this->shortcode_atts['pin_address_lat'];
		$pin_address_lng = $this->shortcode_atts['pin_address_lng'];

		$replace_htmlentities = array( '&#8221;' => '', '&#8243;' => '' );

		if ( ! empty( $pin_address_lat ) ) {
			$pin_address_lat = strtr( $pin_address_lat, $replace_htmlentities );
		}
		if ( ! empty( $pin_address_lng ) ) {
			$pin_address_lng = strtr( $pin_address_lng, $replace_htmlentities );
		}

		$content = $this->shortcode_content;

		$output = sprintf(
			'<div class="et_pb_map_pin" data-lat="%1$s" data-lng="%2$s" data-title="%3$s">
				%4$s
			</div>',
			esc_attr( $pin_address_lat ),
			esc_attr( $pin_address_lng ),
			esc_html( $title ),
			( '' != $content ? sprintf( '<div class="infowindow">%1$s</div>', $content ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Map_Item;

class ET_Builder_Module_Social_Media_Follow extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Social Media Follow', 'et_builder' );
		$this->slug            = 'et_pb_social_media_follow';
		$this->child_slug      = 'et_pb_social_media_follow_network';
		$this->child_item_text = __( 'Social Network', 'et_builder' );

		$this->whitelisted_fields = array(
			'link_shape',
			'background_layout',
			'url_new_window',
			'follow_button',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'link_shape'        => array( 'rounded_rectangle' ),
			'background_layout' => array( 'light' ),
			'url_new_window'    => array( 'on' ),
			'follow_button'     => array( 'off' ),
		);

		$this->custom_css_options = array(
			'social_follow' => array(
				'label'    => __( 'Social Follow', 'et_builder' ),
				'selector' => 'li',
			),
			'social_icon' => array(
				'label'    => __( 'Social Icon', 'et_builder' ),
				'selector' => 'li a.icon',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'link_shape' => array(
				'label'           => __( 'Link Shape', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'rounded_rectangle' => __( 'Rounded Rectangle', 'et_builder' ),
					'circle'            => __( 'Circle', 'et_builder' ),
				),
				'description' => __( 'Here you can choose the shape of your social network icons.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'url_new_window' => array(
				'label'           => __( 'Url Opens', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'In The Same Window', 'et_builder' ),
					'on'  => __( 'In The New Tab', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
			),
			'follow_button' => array(
				'label'           => __( 'Follow Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'           => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether or not to include the follow button next to the icon.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_social_media_follow_link;

		$link_shape        = $this->shortcode_atts['link_shape'];
		$url_new_window    = $this->shortcode_atts['url_new_window'];
		$follow_button     = $this->shortcode_atts['follow_button'];

		$et_pb_social_media_follow_link = array(
			'url_new_window' => $url_new_window,
			'shape'          => $link_shape,
			'follow_button'  => $follow_button,
		);
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_social_media_follow_link;

		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$background_layout = $this->shortcode_atts['background_layout'];

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$output = sprintf(
			'<ul%3$s class="et_pb_social_media_follow%2$s%4$s%5$s clearfix">
				%1$s
			</ul> <!-- .et_pb_counters -->',
			$this->shortcode_content,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( 'on' === $et_pb_social_media_follow_link['follow_button'] ? ' has_follow_button' : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Social_Media_Follow;

class ET_Builder_Module_Social_Media_Follow_Item extends ET_Builder_Module {
	function init() {
		$this->name                        = __( 'Social Network', 'et_builder' );
		$this->slug                        = 'et_pb_social_media_follow_network';
		$this->type                        = 'child';
		$this->child_title_var             = 'content_new';

		$this->whitelisted_fields = array(
			'social_network',
			'content_new',
			'url',
			'bg_color',
			'skype_url',
			'skype_action',
		);

		$this->fields_defaults = array(
			'url'          => array( '#' ),
			'bg_color'     => array( et_builder_accent_color(), 'only_default_setting' ),
			'skype_action' => array( 'call' ),
		);

		$this->advanced_setting_title_text = __( 'New Social Network', 'et_builder' );
		$this->settings_text               = __( 'Social Network Settings', 'et_builder' );

		$this->custom_css_options = array(
			'social_icon' => array(
				'label'    => __( 'Social Icon', 'et_builder' ),
				'selector' => 'a.icon',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'social_network' => array(
				'label'           => __( 'Social Network', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'class'           => 'et-pb-social-network',
				'options' => array(
					''            => __( 'Select a Network', 'et_builder' ),
					'facebook'    => array(
						'value' => __( 'facebook', 'et_builder' ),
						'data'  => array( 'color' => '#3b5998' ),
					),
					'twitter'     => array(
						'value' => __( 'Twitter', 'et_builder' ),
						'data'  => array( 'color' => '#00aced' ),
					),
					'google-plus' => array(
						'value' => __( 'Google+', 'et_builder' ),
						'data'  => array( 'color' => '#dd4b39' ),
					),
					'pinterest'   => array(
						'value' => __( 'Pinterest', 'et_builder' ),
						'data'  => array( 'color' => '#cb2027' ),
					),
					'linkedin'    => array(
						'value' => __( 'LinkedIn', 'et_builder' ),
						'data'  => array( 'color' => '#007bb6' ),
					),
					'tumblr'      => array(
						'value' => __( 'tumblr', 'et_builder' ),
						'data'  => array( 'color' => '#32506d' ),
					),
					'instagram'   => array(
						'value' => __( 'Instagram', 'et_builder' ),
						'data'  => array( 'color' => '#517fa4' ),
					),
					'skype'       => array(
						'value' => __( 'skype', 'et_builder' ),
						'data'  => array( 'color' => '#12A5F4' ),
					),
					'flikr'       => array(
						'value' => __( 'flikr', 'et_builder' ),
						'data'  => array( 'color' => '#ff0084' ),
					),
					'myspace'     => array(
						'value' => __( 'MySpace', 'et_builder' ),
						'data'  => array( 'color' => '#3b5998' ),
					),
					'dribbble'    => array(
						'value' => __( 'dribbble', 'et_builder' ),
						'data'  => array( 'color' => '#ea4c8d' ),
					),
					'youtube'     => array(
						'value' => __( 'Youtube', 'et_builder' ),
						'data'  => array( 'color' => '#a82400' ),
					),
					'vimeo'       => array(
						'value' => __( 'Vimeo', 'et_builder' ),
						'data'  => array( 'color' => '#45bbff' ),
					),
					'rss'         => array(
						'value' => __( 'RSS', 'et_builder' ),
						'data'  => array( 'color' => '#ff8a3c' ),
					),
				),
				'affects'           => array(
					'#et_pb_url',
					'#et_pb_skype_url',
					'#et_pb_skype_action',
				),
				'description' => __( 'Choose the social network', 'et_builder' ),
			),
			'content_new' => array(
				'label' => __( 'Content', 'et_builder' ),
				'type'  => 'hidden',
			),
			'url' => array(
				'label'               => __( 'Account URL', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'basic_option',
				'description'         => __( 'The URL for this social network link.', 'et_builder' ),
				'depends_show_if_not' => 'skype',
			),
			'skype_url' => array(
				'label'           => __( 'Account Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'The Skype account name.', 'et_builder' ),
				'depends_show_if' => 'skype',
			),
			'skype_action' => array(
				'label'           => __( 'Skype Button Action', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'call' => __( 'Call', 'et_builder' ),
					'chat' => __( 'Chat', 'et_builder' ),
				),
				'depends_show_if' => 'skype',
				'description'     => __( 'Here you can choose which action to execute on button click', 'et_builder' ),
			),
			'bg_color' => array(
				'label'           => __( 'Icon Color', 'et_builder' ),
				'type'            => 'color-alpha',
				'description'     => __( 'This will change the icon color.', 'et_builder' ),
				'additional_code' => '<span class="et-pb-reset-setting reset-default-color" style="display: none;"></span>',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_social_media_follow_link;

		$social_network = $this->shortcode_atts['social_network'];
		$url            = $this->shortcode_atts['url'];
		$bg_color       = $this->shortcode_atts['bg_color'];
		$skype_url      = $this->shortcode_atts['skype_url'];
		$skype_action   = $this->shortcode_atts['skype_action'];
		$follow_button  = '';

		if ( isset( $bg_color ) && '' !== $bg_color ) {
			$bg_color_style = sprintf( 'background-color: %1$s;', esc_attr( $bg_color ) );
		}

		if ( 'skype' === $social_network ) {
			$skype_url = sprintf(
				'skype:%1$s?%2$s',
				sanitize_text_field( $skype_url ),
				sanitize_text_field( $skype_action )
			);
		}

		if ( 'on' === $et_pb_social_media_follow_link['follow_button'] ) {
			$follow_button = sprintf(
				'<a href="%1$s" class="follow_button" title="%2$s"%3$s>%4$s</a>',
				'skype' !== $social_network ? esc_url( $url ) : $skype_url,
				esc_attr( $content ),
				( 'on' === $et_pb_social_media_follow_link['url_new_window'] ? ' target="_blank"' : '' ),
				esc_html__( 'Follow', 'et_builder' )
			);
		}

		$social_network = ET_Builder_Element::add_module_order_class( $social_network, $function_name );

		$output = sprintf(
			'<li class="et_pb_social_icon et_pb_social_network_link%1$s">
				<a href="%4$s" class="icon%2$s" title="%5$s"%7$s style="%3$s"><span>%6$s</span></a>
				%8$s
			</li>',
			( '' !== $social_network ? sprintf( ' et-social-%s', esc_attr( $social_network ) ) : '' ),
			( '' !== $et_pb_social_media_follow_link['shape'] ? sprintf( ' %s', esc_attr( $et_pb_social_media_follow_link['shape'] ) ) : '' ),
			$bg_color_style,
			'skype' !== $social_network ? esc_url( $url ) : $skype_url,
			esc_attr( $content ),
			sanitize_text_field( $content ),
			( 'on' === $et_pb_social_media_follow_link['url_new_window'] ? ' target="_blank"' : '' ),
			$follow_button
		);

		return $output;
	}
}
new ET_Builder_Module_Social_Media_Follow_Item;

class ET_Builder_Module_Post_Title extends ET_Builder_Module {
	function init() {
		$this->name             = __( 'Post Title', 'et_builder' );
		$this->slug             = 'et_pb_post_title';
		$this->defaults         = array();

		$this->whitelisted_fields = array(
			'title',
			'meta',
			'author',
			'date',
			'date_format',
			'categories',
			'comments',
			'featured_image',
			'featured_placement',
			'parallax_effect',
			'parallax_method',
			'text_orientation',
			'text_color',
			'text_background',
			'text_bg_color',
			'module_bg_color',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'title'              => array( 'on' ),
			'meta'               => array( 'on' ),
			'author'             => array( 'on' ),
			'date'               => array( 'on' ),
			'date_format'        => array( 'M j, Y' ),
			'categories'         => array( 'on' ),
			'comments'           => array( 'on' ),
			'featured_image'     => array( 'on' ),
			'featured_placement' => array( 'below' ),
			'parallax_effect'    => array( 'off' ),
			'parallax_method'    => array( 'on' ),
			'text_orientation'   => array( 'left' ),
			'text_color'         => array( 'dark' ),
			'text_background'    => array( 'off' ),
			'text_bg_color'      => array( 'rgba(255,255,255,0.9)', 'only_default_setting' ),
		);

		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'border'                => array(
				'css' => array(
					'main' => "{$this->main_css_element}.et_pb_featured_bg, {$this->main_css_element}",
				),
			),
			'custom_margin_padding' => array(
				'css' => array(
					'main' => ".et_pb_section {$this->main_css_element}.et_pb_post_title",
					'important' => 'all',
				),
			),
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'use_all_caps' => true,
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_title_container h1",
					),
				),
				'meta'   => array(
					'label'    => __( 'Meta', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_title_container .et_pb_title_meta_container, {$this->main_css_element} .et_pb_title_container .et_pb_title_meta_container a",
					),
				),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'             => __( 'Show Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose whether or not display the Post Title', 'et_builder' ),
			),
			'meta' => array(
				'label'             => __( 'Show Meta', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_author',
					'#et_pb_date',
					'#et_pb_categories',
					'#et_pb_comments',
				),
				'description'       => __( 'Here you can choose whether or not display the Post Meta', 'et_builder' ),
			),
			'author' => array(
				'label'             => __( 'Show Author', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose whether or not display the Author Name in Post Meta', 'et_builder' ),
			),
			'date' => array(
				'label'             => __( 'Show Date', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'#et_pb_date_format'
				),
				'description'       => __( 'Here you can choose whether or not display the Date in Post Meta', 'et_builder' ),
			),

			'date_format' => array(
				'label'             => __( 'Date Format', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can define the Date Format in Post Meta. Default is \'M j, Y\'', 'et_builder' ),
			),

			'categories' => array(
				'label'             => __( 'Show Post Categories', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose whether or not display the Categories in Post Meta. Note: This option doesn\'t work with custom post types.', 'et_builder' ),
			),
			'comments' => array(
				'label'             => __( 'Show Comments Count', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose whether or not display the Comments Count in Post Meta.', 'et_builder' ),
			),
			'featured_image' => array(
				'label'             => __( 'Show Featured Image', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_featured_placement',
				),
				'description'       => __( 'Here you can choose whether or not display the Featured Image', 'et_builder' ),
			),
			'featured_placement' => array(
				'label'             => __( 'Featured Image Placement', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'below'      => __( 'Below Title', 'et_builder' ),
					'above'      => __( 'Above Title', 'et_builder' ),
					'background' => __( 'Title/Meta Background Image', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'#et_pb_parallax_effect',
				),
				'description'       => __( 'Here you can choose where to place the Featured Image', 'et_builder' ),
			),
			'parallax_effect' => array(
				'label'             => __( 'Use Parallax Effect', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'background',
				'affects'           => array(
					'#et_pb_parallax_method',
				),
				'description'       => __( 'Here you can choose whether or not use parallax effect for the featured image', 'et_builder' ),
			),
			'parallax_method' => array(
				'label'             => __( 'Parallax Method', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'CSS', 'et_builder' ),
					'off' => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose which parallax method to use for the featured image', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'left'   => __( 'Left', 'et_builder' ),
					'center' => __( 'Center', 'et_builder' ),
					'right'  => __( 'Right', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the orientation for the Title/Meta text', 'et_builder' ),
			),
			'text_color' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'dark'  => __( 'Dark', 'et_builder' ),
					'light' => __( 'Light', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the color for the Title/Meta text', 'et_builder' ),
			),
			'text_background' => array(
				'label'             => __( 'Use Text Background Color', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'color_option',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_text_bg_color',
				),
				'description'       => __( 'Here you can choose whether or not use the background color for the Title/Meta text', 'et_builder' ),
			),
			'text_bg_color' => array(
				'label'             => __( 'Text Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'depends_show_if'   => 'on',
			),
			'module_bg_color' => array(
				'label'    => __( 'Background Color', 'et_builder' ),
				'type'     => 'color-alpha',
				'custom_color'      => true,
				'tab_slug' => 'advanced',
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$title              = $this->shortcode_atts['title'];
		$meta               = $this->shortcode_atts['meta'];
		$author             = $this->shortcode_atts['author'];
		$date               = $this->shortcode_atts['date'];
		$date_format        = $this->shortcode_atts['date_format'];
		$categories         = $this->shortcode_atts['categories'];
		$comments           = $this->shortcode_atts['comments'];
		$featured_image     = $this->shortcode_atts['featured_image'];
		$featured_placement = $this->shortcode_atts['featured_placement'];
		$parallax_effect    = $this->shortcode_atts['parallax_effect'];
		$parallax_method    = $this->shortcode_atts['parallax_method'];
		$text_orientation   = $this->shortcode_atts['text_orientation'];
		$text_color         = $this->shortcode_atts['text_color'];
		$text_background    = $this->shortcode_atts['text_background'];
		$text_bg_color      = $this->shortcode_atts['text_bg_color'];
		$module_bg_color    = $this->shortcode_atts['module_bg_color'];

		// display the shortcode only on singlular pages
		if ( ! is_singular() ) {
			return;
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
		$this->process_additional_options( $function_name );

		$output = '';
		$featured_image_output = '';
		$parallax_background_contaier = '';

		if ( 'on' === $featured_image && ( 'above' === $featured_placement || 'below' === $featured_placement ) ) {
			$featured_image_output = sprintf( '<div class="et_pb_title_featured_container">%1$s</div>',
				get_the_post_thumbnail( get_the_ID(), 'large' )
			);
		}

		if ( 'on' === $title ) {
			if ( is_et_pb_preview() && isset( $_POST['post_title'] ) && wp_verify_nonce( $_POST['et_pb_preview_nonce'], 'et_pb_preview_nonce' ) ) {
				$post_title = sanitize_text_field( wp_unslash( $_POST['post_title'] ) );
			} else {
				$post_title = get_the_title();
			}

			$output .= sprintf( '<h1>%s</h1>',
				$post_title
			);
		}

		if ( 'on' === $meta ) {
			$meta_array = array();
			foreach( array( 'author', 'date', 'categories', 'comments' ) as $single_meta ) {
				if ( 'on' === $$single_meta && ( 'categories' !== $single_meta || ( 'categories' === $single_meta && is_singular( 'post' ) ) ) ) {
					 $meta_array[] = $single_meta;
				}
			}

			$output .= sprintf( '<p class="et_pb_title_meta_container">%1$s</p>',
				et_pb_postinfo_meta( $meta_array, $date_format, esc_html__( '0 comments', 'et_builder' ), esc_html__( '1 comment', 'et_builder' ), '% ' . esc_html__( 'comments', 'et_builder' ) )
			);
		}

		if ( 'on' === $featured_image && 'background' === $featured_placement ) {
			$featured_image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => sprintf(
					'%%order_class%% %1$s',
					( 'on' === $parallax_effect ? '.et_parallax_bg' : '' )
				),
				'declaration' => sprintf(
					'background-image: url("%1$s");',
					esc_url( $featured_image_src[0] )
				),
			) );

			if ( 'on' === $parallax_effect ) {
				$parallax_background_contaier = sprintf( '<div class="et_parallax_bg%1$s"></div>',
					'on' === $parallax_method ? ' et_pb_parallax_css' : ''
				);
			}
		}

		if ( 'on' === $text_background ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_title_container',
				'declaration' => sprintf(
					'background-color: %1$s; padding: 1em 1.5em;',
					esc_html( $text_bg_color )
				),
			) );
		}

		ET_Builder_Element::set_style( $function_name, array(
			'selector'    => '%%order_class%%',
			'declaration' => sprintf(
				'text-align: %1$s;',
				esc_html( $text_orientation )
			),
		) );

		$background_layout = 'dark' === $text_color ? 'light' : 'dark';
		$module_class .= ' et_pb_bg_layout_' . $background_layout;

		ET_Builder_Element::set_style( $function_name, array(
			'selector'    => '%%order_class%%',
			'declaration' => sprintf(
				'background-color: %1$s;',
				esc_html( $module_bg_color )
			),
		) );

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_post_title %2$s%4$s">
				%5$s
				%6$s
				<div class="et_pb_title_container">
					%1$s
				</div>
				%7$s
			</div>',
			$output,
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			'on' === $featured_image && 'background' === $featured_placement ? ' et_pb_featured_bg' : '',
			$parallax_background_contaier,
			'on' === $featured_image && 'above' === $featured_placement ? $featured_image_output : '',
			'on' === $featured_image && 'below' === $featured_placement ? $featured_image_output : ''
		);

		return $output;
	}
}
new ET_Builder_Module_Post_Title;

class ET_Builder_Module_Fullwidth_Header extends ET_Builder_Module {
	function init() {
		$this->name             = __( 'Fullwidth Header', 'et_builder' );
		$this->slug             = 'et_pb_fullwidth_header';
		$this->fullwidth        = true;
		$this->main_css_element = '%%order_class%%';

		$this->whitelisted_fields = array(
			'title',
			'subhead',
			'background_layout',
			'text_orientation',
			'header_fullscreen',
			'header_scroll_down',
			'scroll_down_icon',
			'scroll_down_icon_color',
			'scroll_down_icon_size',
			'title_font',
			'title_font_color',
			'title_font_size',
			'subhead_font',
			'subhead_font_color',
			'subhead_font_size',
			'content_font',
			'content_font_color',
			'content_font_size',
			'max_width',
			'button_one_text',
			'button_one_url',
			'button_two_text',
			'button_two_url',
			'background_url',
			'background_color',
			'background_overlay_color',
			'parallax',
			'parallax_method',
			'logo_image_url',
			'logo_title',
			'logo_alt_text',
			'content_orientation',
			'header_image_url',
			'image_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'background_layout'   => array( 'light' ),
			'text_orientation'    => array( 'left' ),
			'header_fullscreen'   => array( 'off' ),
			'header_scroll_down'  => array( 'off' ),
			'scroll_down_icon'    => array( '%%3%%', 'add_default_setting' ),
			'parallax'            => array( 'off' ),
			'parallax_method'     => array( 'off' ),
			'content_orientation' => array( 'center' ),
			'image_orientation'   => array( 'center' ),
		);

		$this->options_toggles = array(
			'advanced' => array(
				'settings' => array(
					'toggles_disabled' => true,
				),
				'toggles' => array(
					'title_styles'   => __( 'Title Styling', 'et_builder' ),
					'subhead_styles' => __( 'Subhead Styling', 'et_builder' ),
					'content_styles' => __( 'Content Styling', 'et_builder' ),
				),
			),
		);
		$this->advanced_options = array(
			'button' => array(
				'button_one' => array(
					'label' => __( 'Button One', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_button_one.et_pb_button",
					),
				),
				'button_two' => array(
					'label' => __( 'Button Two', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_button_two.et_pb_button",
					),
				),
			),
		);

		$this->custom_css_options = array(
			'header_container' => array(
				'label'    => __( 'Header Container', 'et_builder' ),
				'selector' => '.et_pb_fullwidth_header_container',
			),
			'header_image' => array(
				'label'    => __( 'Header Image', 'et_builder' ),
				'selector' => '.et_pb_fullwidth_header_container .header-image img',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Enter your page title here.', 'et_builder' ),
			),
			'subhead' => array(
				'label'           => __( 'Subheading Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'If you would like to use a subhead, add it here. Your subhead will appear below your title in a small font.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text & Logo Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This controls the how your text is aligned within the module.', 'et_builder' ),
			),

			'header_fullscreen' => array(
				'label'           => __( 'Make Fullscreen', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_content_orientation',
				),
				'description'       => __( 'Here you can choose whether the header is expanded to fullscreen size.', 'et_builder' ),
			),
			'header_scroll_down' => array(
				'label'           => __( 'Show Scroll Down Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_scroll_down_icon',
				),
				'description'       => __( 'Here you can choose whether the scroll down button is shown.', 'et_builder' ),
			),
			'scroll_down_icon' => array(
				'label'               => __( 'Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_down_icon_list',
				'renderer_with_field' => true,
				'description'         => __( 'Choose an icon to display for the scroll down button.', 'et_builder' ),
				'depends_show_if'     => 'on',
			),
			'scroll_down_icon_color' => array(
				'label'             => __( 'Scroll Down Icon Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'scroll_down_icon_size' => array(
				'label'           => __( 'Scroll Down Icon Size', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
			),
			'title_font' => array(
				'label'           => __( 'Title Font', 'et_builder' ),
				'type'            => 'font',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'title_styles',
			),
			'title_font_color' => array(
				'label'             => __( 'Title Font Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'title_styles',
			),
			'title_font_size' => array(
				'label'           => __( 'Title Font Size', 'et_builder' ),
				'type'            => 'range',
				'default'         => '30px',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'title_styles',
			),
			'subhead_font' => array(
				'label'           => __( 'Subhead Font', 'et_builder' ),
				'type'            => 'font',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'subhead_styles',
			),
			'subhead_font_color' => array(
				'label'             => __( 'Subhead Font Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'subhead_styles',
			),
			'subhead_font_size' => array(
				'label'           => __( 'Subhead Font Size', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'subhead_styles',
			),
			'content_font' => array(
				'label'           => __( 'Content Font', 'et_builder' ),
				'type'            => 'font',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'content_styles',
			),
			'content_font_color' => array(
				'label'             => __( 'Content Font Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'content_styles',
			),
			'content_font_size' => array(
				'label'           => __( 'Content Font Size', 'et_builder' ),
				'type'            => 'range',
				'default'         => '14px',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'content_styles',
			),
			'max_width' => array(
				'label'           => __( 'Text Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'button_one_text' => array(
				'label'           => sprintf( __( 'Button %1$s Text', 'et_builder' ), '#1' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Enter the text for the Button.', 'et_builder' ),
			),
			'button_one_url' => array(
				'label'           => sprintf( __( 'Button %1$s URL', 'et_builder' ), '#1' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Enter the URL for the Button.', 'et_builder' ),
			),
			'button_two_text' => array(
				'label'           => sprintf( __( 'Button %1$s Text', 'et_builder' ), '#2' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Enter the text for the Button.', 'et_builder' ),
			),
			'button_two_url' => array(
				'label'           => sprintf( __( 'Button %1$s URL', 'et_builder' ), '#2' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Enter the URL for the Button.', 'et_builder' ),
			),
			'background_url' => array(
				'label'              => __( 'Background Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => __( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
			),
			'background_overlay_color' => array(
				'label'             => __( 'Background Overlay Color', 'et_builder' ),
				'type'              => 'color-alpha',
			),
			'parallax' => array(
				'label'           => __( 'Use Parallax effect', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off'  => __( 'No', 'et_builder' ),
					'on' => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_parallax_method',
				),
				'description'        => __( 'If enabled, your background images will have a fixed position as your scroll, creating a fun parallax-like effect.', 'et_builder' ),
			),
			'parallax_method' => array(
				'label'           => __( 'Parallax method', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'CSS', 'et_builder' ),
					'on'  => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Define the method, used for the parallax effect.', 'et_builder' ),
			),

			'logo_image_url' => array(
				'label'              => __( 'Logo Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'logo_alt_text' => array(
				'label'           => __( 'Logo Image Alternative Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'et_builder' ),
			),
			'logo_title' => array(
				'label'           => __( 'Logo Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This defines the HTML Title text.', 'et_builder' ),
			),
			'content_orientation' => array(
				'label'           => __( 'Text Vertical Alignment', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'center'  => __( 'Center', 'et_builder' ),
					'bottom' => __( 'Bottom', 'et_builder' ),
				),
				'description'        => __( 'This setting determines the vertical alignment of your content. Your content can either be vertically centered, or aligned to the bottom.', 'et_builder' ),
				'depends_show_if'    => 'on',
			),

			'header_image_url' => array(
				'label'              => __( 'Header Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'image_orientation' => array(
				'label'           => __( 'Image Vertical Alignment', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'center'  => __( 'Vertically Centered', 'et_builder' ),
					'bottom' => __( 'Bottom', 'et_builder' ),
				),
				'description'        => __( 'This controls the orientation of the image within the module.', 'et_builder' ),
			),

			'content_new' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Here you can define the content that will be placed within the infobox for the pin.', 'et_builder' ),
			),

			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),

		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                   = $this->shortcode_atts['module_id'];
		$module_class                = $this->shortcode_atts['module_class'];
		$title                       = $this->shortcode_atts['title'];
		$subhead                     = $this->shortcode_atts['subhead'];
		$background_layout           = $this->shortcode_atts['background_layout'];
		$text_orientation            = $this->shortcode_atts['text_orientation'];
		$title_font                  = $this->shortcode_atts['title_font'];
		$title_font_color            = $this->shortcode_atts['title_font_color'];
		$title_font_size             = $this->shortcode_atts['title_font_size'];
		$subhead_font                = $this->shortcode_atts['subhead_font'];
		$subhead_font_color          = $this->shortcode_atts['subhead_font_color'];
		$subhead_font_size           = $this->shortcode_atts['subhead_font_size'];
		$content_font                = $this->shortcode_atts['content_font'];
		$content_font_color          = $this->shortcode_atts['content_font_color'];
		$content_font_size           = $this->shortcode_atts['content_font_size'];
		$button_one_text             = $this->shortcode_atts['button_one_text'];
		$button_one_url              = $this->shortcode_atts['button_one_url'];
		$button_two_text             = $this->shortcode_atts['button_two_text'];
		$button_two_url              = $this->shortcode_atts['button_two_url'];
		$header_fullscreen           = $this->shortcode_atts['header_fullscreen'];
		$header_scroll_down          = $this->shortcode_atts['header_scroll_down'];
		$scroll_down_icon            = $this->shortcode_atts['scroll_down_icon'];
		$scroll_down_icon_color      = $this->shortcode_atts['scroll_down_icon_color'];
		$scroll_down_icon_size       = $this->shortcode_atts['scroll_down_icon_size'];
		$background_url              = $this->shortcode_atts['background_url'];
		$background_color            = $this->shortcode_atts['background_color'];
		$background_overlay_color    = $this->shortcode_atts['background_overlay_color'];
		$parallax                    = $this->shortcode_atts['parallax'];
		$parallax_method             = $this->shortcode_atts['parallax_method'];
		$logo_image_url              = $this->shortcode_atts['logo_image_url'];
		$header_image_url            = $this->shortcode_atts['header_image_url'];
		$content_orientation         = $this->shortcode_atts['content_orientation'];
		$image_orientation           = $this->shortcode_atts['image_orientation'];
		$custom_icon_1               = $this->shortcode_atts['button_one_icon'];
		$button_custom_1             = $this->shortcode_atts['custom_button_one'];
		$custom_icon_2               = $this->shortcode_atts['button_two_icon'];
		$button_custom_2             = $this->shortcode_atts['custom_button_two'];
		$max_width                   = $this->shortcode_atts['max_width'];
		$logo_title                  = $this->shortcode_atts['logo_title'];
		$logo_alt_text               = $this->shortcode_atts['logo_alt_text'];

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $max_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .header-content',
				'declaration' => sprintf(
					'max-width: %1$s !important;',
					esc_html( et_builder_process_range_value( $max_width ) )
				),
			) );
		}

		if ( '' !== $title_font ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .header-content h1',
				'declaration' => sprintf(
					'%1$s',
					et_builder_set_element_font( $title_font )
				),
			) );
		}

		if ( '' !== $title_font_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .header-content h1',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $title_font_color )
				),
			) );
		}

		if ( '' !== $title_font_size ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .header-content h1',
				'declaration' => sprintf(
					'font-size: %1$s;',
					esc_html( et_builder_process_range_value( $title_font_size ) )
				),
			) );
		}

		if ( '' !== $subhead_font ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .et_pb_fullwidth_header_subhead',
				'declaration' => sprintf(
					'%1$s',
					et_builder_set_element_font( $subhead_font )
				),
			) );
		}

		if ( '' !== $subhead_font_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .et_pb_fullwidth_header_subhead',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $subhead_font_color )
				),
			) );
		}

		if ( '' !== $subhead_font_size ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .et_pb_fullwidth_header_subhead',
				'declaration' => sprintf(
					'font-size: %1$s;',
					esc_html( et_builder_process_range_value( $subhead_font_size ) )
				),
			) );
		}

		if ( '' !== $content_font ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header p',
				'declaration' => sprintf(
					'%1$s',
					et_builder_set_element_font( $content_font )
				),
			) );
		}

		if ( '' !== $content_font_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header p',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $content_font_color )
				),
			) );
		}

		if ( '' !== $content_font_size ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header p',
				'declaration' => sprintf(
					'font-size: %1$s;',
					esc_html( et_builder_process_range_value( $content_font_size ) )
				),
			) );
		}

		if ( '' !== $scroll_down_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .et_pb_fullwidth_header_scroll a .et-pb-icon',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $scroll_down_icon_color )
				),
			) );
		}

		if ( '' !== $scroll_down_icon_size ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .et_pb_fullwidth_header_scroll a .et-pb-icon',
				'declaration' => sprintf(
					'font-size: %1$s;',
					esc_html( et_builder_process_range_value( $scroll_down_icon_size ) )
				),
			) );
		}

		if ( '' !== $background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $background_color )
				),
			) );
		}

		if ( '' !== $background_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header .et_pb_fullwidth_header_overlay',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $background_overlay_color )
				),
			) );
		}

		if ( '' !== $background_url && 'off' === $parallax ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_header',
				'declaration' => sprintf(
					'background-image: url(%1$s);',
					esc_html( $background_url )
				),
			) );
		}

		$button_output = '';
		if ( '' !== $button_one_text ) {
			$button_output .= sprintf(
				'<a href="%2$s" class="et_pb_more_button et_pb_button et_pb_button_one%4$s"%3$s>%1$s</a>',
				( '' !== $button_one_text ? esc_attr( $button_one_text ) : '' ),
				( '' !== $button_one_url ? esc_attr( $button_one_url ) : '#' ),
				'' !== $custom_icon_1 && 'on' === $button_custom_1 ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon_1 ) )
				) : '',
				'' !== $custom_icon_1 && 'on' === $button_custom_1 ? ' et_pb_custom_button_icon' : ''
			);
		}

		if ( '' !== $button_two_text ) {
			$button_output .= sprintf(
				'<a href="%2$s" class="et_pb_more_button et_pb_button et_pb_button_two%4$s"%3$s>%1$s</a>',
				( '' !== $button_two_text ? esc_attr( $button_two_text ) : '' ),
				( '' !== $button_two_url ? esc_attr( $button_two_url ) : '#' ),
				'' !== $custom_icon_2 && 'on' === $button_custom_2 ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon_2 ) )
				) : '',
				'' !== $custom_icon_2 && 'on' === $button_custom_2 ? ' et_pb_custom_button_icon' : ''
			);
		}

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$header_content = '';
		if ( '' !== $title || '' !== $subhead || '' !== $content || '' !== $button_output || '' !== $logo_image_url ) {
			$logo_image = '';
			if ( '' !== $logo_image_url ){
				$logo_image = sprintf(
					'<img src="%1$s" alt="%2$s"%3$s />',
					esc_attr( $logo_image_url ),
					esc_attr( $logo_alt_text ),
					( '' !== $logo_title ? sprintf( ' title="%1$s"', esc_attr( $logo_title ) ) : '' )
				);
			}
			$header_content = sprintf(
				'<div class="header-content-container%6$s">
					<div class="header-content">
						%3$s
						%1$s
						%2$s
						%4$s
						%5$s
					</div>
				</div>',
				( $title ? sprintf( '<h1>%1$s</h1>', $title ) : '' ),
				( $subhead ? sprintf( '<span class="et_pb_fullwidth_header_subhead">%1$s</span>', $subhead ) : '' ),
				$logo_image,
				( '' !== $content ? sprintf( '<p>%1$s</p>', $this->shortcode_content ) : '' ),
				( '' !== $button_output ? $button_output : '' ),
				( '' !== $content_orientation ? sprintf( ' %1$s', $content_orientation ) : '' )
			);
		}

		$header_image = '';
		if ( '' !== $header_image_url ) {
			$header_image = sprintf(
				'<div class="header-image-container%2$s">
					<div class="header-image">
						<img src="%1$s" />
					</div>
				</div>',
				( '' !== $header_image_url ? esc_attr( $header_image_url ) : ''),
				( '' !== $image_orientation ? sprintf( ' %1$s', $image_orientation ) : '' )
			);

			$module_class .= ' et_pb_header_with_image';

		}

		$scroll_down_output = '';
		if ( 'off' !== $header_scroll_down || '' !== $scroll_down_icon ) {
			$scroll_down_output .= sprintf(
				'<a href="#"><span class="scroll-down et-pb-icon">%1$s</span></a>',
				esc_html( et_pb_process_font_icon( $scroll_down_icon, 'et_pb_get_font_down_icon_symbols' ) )
			);
		}

		$output = sprintf(
			'<section%9$s class="et_pb_fullwidth_header%1$s%7$s%8$s%10$s">
				%6$s
				<div class="et_pb_fullwidth_header_container%5$s">
					%2$s
					%3$s
				</div>
				<div class="et_pb_fullwidth_header_overlay"></div>
				<div class="et_pb_fullwidth_header_scroll">%4$s</div>
			</section>',
			( 'off' !== $header_fullscreen ? ' et_pb_fullscreen' : '' ),
			( '' !== $header_content ? $header_content : '' ),
			( '' !== $header_image ? $header_image : '' ),
			( 'off' !== $header_scroll_down ? $scroll_down_output : '' ),
			( '' !== $text_orientation ? sprintf( ' %1$s', esc_attr( $text_orientation ) ) : '' ),
			( '' !== $background_url && 'on' === $parallax
				? sprintf(
					'<div class="et_parallax_bg%2$s" style="background-image: url(%1$s);"></div>',
					esc_attr( $background_url ),
					( 'off' === $parallax_method ? ' et_pb_parallax_css' : '' )
				)
				: ''
			),
			( '' !== $background_url && 'on' === $parallax ? ' et_pb_section_parallax' : '' ),
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Header;

class ET_Builder_Module_Fullwidth_Menu extends ET_Builder_Module {
	function init() {
		$this->name       = __( 'Fullwidth Menu', 'et_builder' );
		$this->slug       = 'et_pb_fullwidth_menu';
		$this->fullwidth  = true;

		$this->whitelisted_fields = array(
			'menu_id',
			'background_color',
			'background_layout',
			'text_orientation',
			'submenu_direction',
			'admin_label',
			'module_id',
			'module_class',
			'fullwidth_menu',
			'active_link_color',
			'dropdown_menu_bg_color',
			'dropdown_menu_line_color',
			'dropdown_menu_text_color',
			'dropdown_menu_animation',
			'mobile_menu_bg_color',
			'mobile_menu_text_color',
		);

		$this->main_css_element = '%%order_class%%.et_pb_fullwidth_menu';

		$this->advanced_options = array(
			'fonts' => array(
				'menu' => array(
					'label'    => __( 'Menu', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} ul li a",
					),
					'line_height' => array(
						'default' => '1em',
					),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '12',
							'max'  => '24',
							'step' => '1',
						),
					),
					'letter_spacing' => array(
						'default' => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '8',
							'step' => '1',
						),
					),
				),
			),
		);

		$this->fields_defaults = array(
			'background_color'  => array( '#ffffff', 'only_default_setting' ),
			'background_layout' => array( 'light' ),
			'text_orientation'  => array( 'left' ),
		);
	}

	function get_fields() {
		$fields = array(
			'menu_id' => array(
				'label'           => __( 'Menu', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => et_builder_get_nav_menus_options(),
				'description'     => sprintf(
					'<p class="description">%2$s. <a href="%1$s" target="_blank">%3$s</a>.</p>',
					esc_url( admin_url( 'nav-menus.php' ) ),
					esc_html__( 'Select a menu that should be used in the module', 'et_builder' ),
					esc_html__( 'Click here to create new menu', 'et_builder' )
				),
			),
			'background_color' => array(
				'label'       => __( 'Background Color', 'et_builder' ),
				'type'        => 'color-alpha',
				'description' => __( 'Use the color picker to choose a background color for this module.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => __( 'Dark', 'et_builder' ),
					'dark'  => __( 'Light', 'et_builder' ),
				),
				'description' => __( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This controls the how your text is aligned within the module.', 'et_builder' ),
			),
			'submenu_direction' => array(
				'label'           => __( 'Sub-Menus Open', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'downwards' => __( 'Downwards', 'et_builder' ),
					'upwards'   => __( 'Upwards', 'et_builder' ),
				),
				'description' => __( 'Here you can choose the direction that your sub-menus will open. You can choose to have them open downwards or upwards.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'fullwidth_menu' => array(
				'label'           => __( 'Make Menu Links Fullwidth', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'active_link_color' => array(
				'label'        => __( 'Active Link Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'dropdown_menu_bg_color' => array(
				'label'        => __( 'Dropdown Menu Background Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'dropdown_menu_line_color' => array(
				'label'        => __( 'Dropdown Menu Line Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'dropdown_menu_text_color' => array(
				'label'        => __( 'Dropdown Menu Text Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'dropdown_menu_animation' => array(
				'label'             => __( 'Dropdown Menu Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'fade'     => __( 'Fade', 'et_builder' ),
					'expand'   => __( 'Expand', 'et_builder' ),
					'slide'	   => __( 'Slide', 'et_builder' ),
					'flip'	   => __( 'Flip', 'et_builder' ),
				),
				'tab_slug'     => 'advanced',
			),
			'mobile_menu_bg_color' => array(
				'label'        => __( 'Mobile Menu Background Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),
			'mobile_menu_text_color' => array(
				'label'        => __( 'Mobile Menu Text Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
			),

		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$background_color  = $this->shortcode_atts['background_color'];
		$background_layout = $this->shortcode_atts['background_layout'];
		$text_orientation  = $this->shortcode_atts['text_orientation'];
		$menu_id           = $this->shortcode_atts['menu_id'];
		$submenu_direction = $this->shortcode_atts['submenu_direction'];
		$fullwidth_menu           = $this->shortcode_atts['fullwidth_menu'] === 'on' ? ' et_pb_fullwidth_menu_fullwidth' : '';
		$active_link_color        = $this->shortcode_atts['active_link_color'];
		$dropdown_menu_bg_color   = $this->shortcode_atts['dropdown_menu_bg_color'];
		$dropdown_menu_line_color = $this->shortcode_atts['dropdown_menu_line_color'];
		$dropdown_menu_text_color = $this->shortcode_atts['dropdown_menu_text_color'];
		$dropdown_menu_animation  = $this->shortcode_atts['dropdown_menu_animation'];
		$mobile_menu_bg_color     = $this->shortcode_atts['mobile_menu_bg_color'];
		$mobile_menu_text_color   = $this->shortcode_atts['mobile_menu_text_color'];

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$style = '';

		if ( '' !== $background_color ) {
			$style .= sprintf( ' style="background-color: %s;"',
				esc_attr( $background_color )
			);
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation} et_dropdown_animation_{$dropdown_menu_animation}{$fullwidth_menu}";

		$menu = '<nav class="fullwidth-menu-nav">';
		$menuClass = 'fullwidth-menu nav';
		if ( ! et_is_builder_plugin_active() && 'on' == et_get_option( 'divi_disable_toptier' ) ) {
			$menuClass .= ' et_disable_top_tier';
		}
		$menuClass .= ( '' !== $submenu_direction ? sprintf( ' %s', esc_attr( $submenu_direction ) ) : '' );

		$primaryNav = '';

		$menu_args = array(
			'theme_location' => 'primary-menu',
			'container'      => '',
			'fallback_cb'    => '',
			'menu_class'     => $menuClass,
			'menu_id'        => '',
			'echo'           => false,
		);

		if ( '' !== $menu_id ) {
			$menu_args['menu'] = (int) $menu_id;
		}

		$primaryNav = wp_nav_menu( apply_filters( 'et_fullwidth_menu_args', $menu_args ) );

		if ( '' == $primaryNav ) {
			$menu .= sprintf(
				'<ul class="%1$s">
					%2$s',
				esc_attr( $menuClass ),
				( ! et_is_builder_plugin_active() && 'on' === et_get_option( 'divi_home_link' )
					? sprintf( '<li%1$s><a href="%2$s">%3$s</a></li>',
						( is_home() ? ' class="current_page_item"' : '' ),
						esc_url( home_url( '/' ) ),
						esc_html__( 'Home', 'et_builder' )
					)
					: ''
				)
			);

			ob_start();

			// @todo: check if Fullwidth Menu module works fine with no menu selected in settings
			if ( et_is_builder_plugin_active() ) {
				wp_page_menu();
			} else {
				show_page_menu( $menuClass, false, false );
				show_categories_menu( $menuClass, false );
			}

			$menu .= ob_get_contents();

			$menu .= '</ul>';

			ob_end_clean();
		} else {
			$menu .= $primaryNav;
		}

		$menu .= '</nav>';

		if ( '' !== $active_link_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_menu ul li a:active',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $active_link_color )
				),
			) );
		}

		if ( '' !== $dropdown_menu_bg_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_menu .nav li ul',
				'declaration' => sprintf(
					'background-color: %1$s !important;',
					esc_html( $dropdown_menu_bg_color )
				),
			) );
		}

		if ( '' !== $dropdown_menu_line_color ) {

			$dropdown_menu_line_color_selector = 'upwards' === $submenu_direction ? '%%order_class%%.et_pb_fullwidth_menu .fullwidth-menu-nav > ul.upwards li ul' : '%%order_class%%.et_pb_fullwidth_menu .nav li ul';

			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => $dropdown_menu_line_color_selector,
				'declaration' => sprintf(
					'border-color: %1$s;',
					esc_html( $dropdown_menu_line_color )
				),
			) );

			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_menu .et_mobile_menu',
				'declaration' => sprintf(
					'border-color: %1$s;',
					esc_html( $dropdown_menu_line_color )
				),
			) );
		}

		if ( '' !== $dropdown_menu_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_menu .nav li ul a',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $dropdown_menu_text_color )
				),
			) );
		}

		if ( '' !== $mobile_menu_bg_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_menu .et_mobile_menu, %%order_class%%.et_pb_fullwidth_menu .et_mobile_menu ul',
				'declaration' => sprintf(
					'background-color: %1$s !important;',
					esc_html( $mobile_menu_bg_color )
				),
			) );
		}

		if ( '' !== $mobile_menu_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_fullwidth_menu .et_mobile_menu a',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $mobile_menu_text_color )
				),
			) );
		}

		$output = sprintf(
			'<div%4$s class="et_pb_fullwidth_menu%3$s%5$s"%2$s%6$s>
				<div class="et_pb_row clearfix">
					%1$s
					<div class="et_mobile_nav_menu">
						<a href="#" class="mobile_nav closed">
							<span class="mobile_menu_bar"></span>
						</a>
					</div>
				</div>
			</div>',
			$menu,
			$style,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $style ? sprintf( ' data-bg_color=%1$s', esc_attr( $background_color ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Menu;

class ET_Builder_Module_Fullwidth_Slider extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Fullwidth Slider', 'et_builder' );
		$this->slug            = 'et_pb_fullwidth_slider';
		$this->fullwidth       = true;
		$this->child_slug      = 'et_pb_slide';
		$this->child_item_text = __( 'Slide', 'et_builder' );

		$this->whitelisted_fields = array(
			'show_arrows',
			'show_pagination',
			'auto',
			'auto_speed',
			'auto_ignore_hover',
			'parallax',
			'parallax_method',
			'remove_inner_shadow',
			'background_position',
			'background_size',
			'admin_label',
			'module_id',
			'module_class',
			'top_padding',
			'bottom_padding',
			'hide_content_on_mobile',
			'hide_cta_on_mobile',
			'show_image_video_mobile',
		);

		$this->fields_defaults = array(
			'show_arrows'             => array( 'on' ),
			'show_pagination'         => array( 'on' ),
			'auto'                    => array( 'off' ),
			'auto_speed'              => array( '7000' ),
			'auto_ignore_hover'       => array( 'off' ),
			'parallax'                => array( 'off' ),
			'parallax_method'         => array( 'off' ),
			'remove_inner_shadow'     => array( 'off' ),
			'background_position'     => array( 'default' ),
			'background_size'         => array( 'default' ),
			'hide_content_on_mobile'  => array( 'off' ),
			'hide_cta_on_mobile'      => array( 'off' ),
			'show_image_video_mobile' => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_slider';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_slide_description .et_pb_slide_title",
						'important' => array(
							'color',
						),
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'et_builder' ),
					'css'      => array(
						'main'        => "{$this->main_css_element} .et_pb_slide_content",
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'et_builder' ),
				),
			),
		);
		$this->custom_css_options = array(
			'slide_description' => array(
				'label'    => __( 'Slide Description', 'et_builder' ),
				'selector' => '.et_pb_slide_description',
			),
			'slide_title' => array(
				'label'    => __( 'Slide Title', 'et_builder' ),
				'selector' => '.et_pb_slide_description .et_pb_slide_title',
			),
			'slide_button' => array(
				'label'    => __( 'Slide Button', 'et_builder' ),
				'selector' => 'a.et_pb_more_button',
			),
			'slide_controllers' => array(
				'label'    => __( 'Slide Controllers', 'et_builder' ),
				'selector' => '.et-pb-controllers',
			),
			'slide_active_controller' => array(
				'label'    => __( 'Slide Active Controller', 'et_builder' ),
				'selector' => '.et-pb-controllers .et-pb-active-control',
			),
		);
	}

	function get_fields() {
		$fields = array(
			'show_arrows' => array(
				'label'           => __( 'Arrows', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Show Arrows', 'et_builder' ),
					'off' => __( 'Hide Arrows', 'et_builder' ),
				),
				'description'        => __( 'This setting allows you to turn the navigation arrows on or off.', 'et_builder' ),
			),
			'show_pagination' => array(
				'label'           => __( 'Controls', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'Show Slider Controls', 'et_builder' ),
					'off' => __( 'Hide Slider Controls', 'et_builder' ),
				),
				'description'        => __( 'Disabling this option will remove the circle button at the bottom of the slider.', 'et_builder' ),
			),
			'auto' => array(
				'label'             => __( 'Automatic Animation', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off'  => __( 'Off', 'et_builder' ),
					'on' => __( 'On', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_auto_speed, #et_pb_auto_ignore_hover',
				),
				'description'        => __( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
			),
			'auto_speed' => array(
				'label'             => __( 'Automatic Animation Speed (in ms)', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_default'   => true,
				'description'       => __( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation.", 'et_builder' ),
			),
			'auto_ignore_hover' => array(
				'label'           => __( 'Continue Automatic Slide on Hover', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'depends_default' => true,
				'options' => array(
					'off' => __( 'Off', 'et_builder' ),
					'on'  => __( 'On', 'et_builder' ),
				),
				'description' => __( 'Turning this on will allow automatic sliding to continue on mouse hover.', 'et_builder' ),
			),
			'parallax' => array(
				'label'           => __( 'Use Parallax effect', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off'  => __( 'No', 'et_builder' ),
					'on' => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_parallax_method',
				),
				'description'        => __( 'If enabled, your background images will have a fixed position as your scroll, creating a fun parallax-like effect.', 'et_builder' ),
			),
			'parallax_method' => array(
				'label'           => __( 'Parallax method', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'CSS', 'et_builder' ),
					'on'  => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Define the method, used for the parallax effect.', 'et_builder' ),
			),
			'remove_inner_shadow' => array(
				'label'           => __( 'Remove Inner Shadow', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
			),
			'background_position' => array(
				'label'           => __( 'Background Image Position', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'default'       => __( 'Default', 'et_builder' ),
					'top_left'      => __( 'Top Left', 'et_builder' ),
					'top_center'    => __( 'Top Center', 'et_builder' ),
					'top_right'     => __( 'Top Right', 'et_builder' ),
					'center_right'  => __( 'Center Right', 'et_builder' ),
					'center_left'   => __( 'Center Left', 'et_builder' ),
					'bottom_left'   => __( 'Bottom Left', 'et_builder' ),
					'bottom_center' => __( 'Bottom Center', 'et_builder' ),
					'bottom_right'  => __( 'Bottom Right', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
			),
			'background_size' => array(
				'label'           => __( 'Background Image Size', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'default' => __( 'Default', 'et_builder' ),
					'contain' => __( 'Fit', 'et_builder' ),
					'initial' => __( 'Actual Size', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'top_padding' => array(
				'label'           => __( 'Top Padding', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'bottom_padding' => array(
				'label'           => __( 'Bottom Padding', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'hide_content_on_mobile' => array(
				'label'           => __( 'Hide Content On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'hide_cta_on_mobile' => array(
				'label'           => __( 'Hide CTA On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
			'show_image_video_mobile' => array(
				'label'            => __( 'Show Image / Video On Mobile', 'et_builder' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

		$et_pb_slider_item_num = 0;

		$parallax        = $this->shortcode_atts['parallax'];
		$parallax_method = $this->shortcode_atts['parallax_method'];
		$hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
		$hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
		$button_custom           = $this->shortcode_atts['custom_button'];
		$custom_icon             = $this->shortcode_atts['button_icon'];

		$et_pb_slider_has_video = false;

		$et_pb_slider_parallax = $parallax;

		$et_pb_slider_parallax_method = $parallax_method;

		$et_pb_slider_hide_mobile = array(
			'hide_content_on_mobile'  => $hide_content_on_mobile,
			'hide_cta_on_mobile'      => $hide_cta_on_mobile,
		);

		$et_pb_slider_custom_icon = 'on' === $button_custom ? $custom_icon : '';

	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id               = $this->shortcode_atts['module_id'];
		$module_class            = $this->shortcode_atts['module_class'];
		$show_arrows             = $this->shortcode_atts['show_arrows'];
		$show_pagination         = $this->shortcode_atts['show_pagination'];
		$parallax                = $this->shortcode_atts['parallax'];
		$parallax_method         = $this->shortcode_atts['parallax_method'];
		$auto                    = $this->shortcode_atts['auto'];
		$auto_speed              = $this->shortcode_atts['auto_speed'];
		$auto_ignore_hover       = $this->shortcode_atts['auto_ignore_hover'];
		$top_padding             = $this->shortcode_atts['top_padding'];
		$bottom_padding          = $this->shortcode_atts['bottom_padding'];
		$remove_inner_shadow     = $this->shortcode_atts['remove_inner_shadow'];
		$show_image_video_mobile = $this->shortcode_atts['show_image_video_mobile'];
		$background_position     = $this->shortcode_atts['background_position'];
		$background_size         = $this->shortcode_atts['background_size'];

		global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon;

		$content = $this->shortcode_content;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $top_padding ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide_description',
				'declaration' => sprintf(
					'padding-top: %1$s;',
					esc_html( et_builder_process_range_value( $top_padding ) )
				),
			) );
		}

		if ( '' !== $bottom_padding ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide_description',
				'declaration' => sprintf(
					'padding-bottom: %1$s;',
					esc_html( et_builder_process_range_value( $bottom_padding ) )
				),
			) );
		}

		if ( 'default' !== $background_position && 'off' === $parallax ) {
			$processed_position = str_replace( '_', ' ', $background_position );

			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide',
				'declaration' => sprintf(
					'background-position: %1$s;',
					esc_html( $processed_position )
				),
			) );
		}

		if ( 'default' !== $background_size && 'off' === $parallax ) {
			ET_Builder_Module::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_slide',
				'declaration' => sprintf(
					'-moz-background-size: %1$s;
					-webkit-background-size: %1$s;
					background-size: %1$s;',
					esc_html( $background_size )
				),
			) );
		}

		$fullwidth = 'et_pb_fullwidth_slider' === $function_name ? 'on' : 'off';

		$class  = '';
		$class .= 'off' === $fullwidth ? ' et_pb_slider_fullwidth_off' : '';
		$class .= 'off' === $show_arrows ? ' et_pb_slider_no_arrows' : '';
		$class .= 'off' === $show_pagination ? ' et_pb_slider_no_pagination' : '';
		$class .= 'on' === $parallax ? ' et_pb_slider_parallax' : '';
		$class .= 'on' === $auto ? ' et_slider_auto et_slider_speed_' . esc_attr( $auto_speed ) : '';
		$class .= 'on' === $auto_ignore_hover ? ' et_slider_auto_ignore_hover' : '';
		$class .= 'on' === $remove_inner_shadow ? ' et_pb_slider_no_shadow' : '';
		$class .= 'on' === $show_image_video_mobile ? ' et_pb_slider_show_image' : '';

		$output = sprintf(
			'<div%4$s class="et_pb_module et_pb_slider%1$s%3$s%5$s">
				<div class="et_pb_slides">
					%2$s
				</div> <!-- .et_pb_slides -->
			</div> <!-- .et_pb_slider -->
			',
			$class,
			$content,
			( $et_pb_slider_has_video ? ' et_pb_preload' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Slider;

class ET_Builder_Module_Fullwidth_Portfolio extends ET_Builder_Module {
	function init() {
		$this->name       = __( 'Fullwidth Portfolio', 'et_builder' );
		$this->slug       = 'et_pb_fullwidth_portfolio';
		$this->fullwidth  = true;

		$this->whitelisted_fields = array(
			'title',
			'fullwidth',
			'include_categories',
			'posts_number',
			'show_title',
			'show_date',
			'background_layout',
			'auto',
			'auto_speed',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'fullwidth'         => array( 'on' ),
			'show_title'        => array( 'on' ),
			'show_date'         => array( 'on' ),
			'background_layout' => array( 'light' ),
			'auto'              => array( 'off' ),
			'auto_speed'        => array( '7000' ),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Portfolio Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Title displayed above the portfolio.', 'et_builder' ),
			),
			'fullwidth' => array(
				'label'             => __( 'Layout', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => __( 'Carousel', 'et_builder' ),
					'off' => __( 'Grid', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_auto',
				),
				'description'        => __( 'Choose your desired portfolio layout style.', 'et_builder' ),
			),
			'include_categories' => array(
				'label'           => __( 'Include Categories', 'et_builder' ),
				'renderer'        => 'et_builder_include_categories_option',
				'option_category' => 'basic_option',
				'description'     => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
			),
			'posts_number' => array(
				'label'           => __( 'Posts Number', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Control how many projects are displayed. Leave blank or use 0 to not limit the amount.', 'et_builder' ),
			),
			'show_title' => array(
				'label'             => __( 'Show Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn project titles on or off.', 'et_builder' ),
			),
			'show_date' => array(
				'label'             => __( 'Show Date', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn the date display on or off.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'light'  => __( 'Dark', 'et_builder' ),
					'dark' => __( 'Light', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'auto' => array(
				'label'             => __( 'Automatic Carousel Rotation', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off'  => __( 'Off', 'et_builder' ),
					'on' => __( 'On', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_auto_speed',
				),
				'depends_show_if' => 'on',
				'description'        => __( 'If you the carousel layout option is chosen and you would like the carousel to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
			),
			'auto_speed' => array(
				'label'             => __( 'Automatic Carousel Rotation Speed (in ms)', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_default'   => true,
				'description'       => __( "Here you can designate how fast the carousel rotates, if 'Automatic Carousel Rotation' option is enabled above. The higher the number the longer the pause between each rotation. (Ex. 1000 = 1 sec)", 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$title              = $this->shortcode_atts['title'];
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$fullwidth          = $this->shortcode_atts['fullwidth'];
		$include_categories = $this->shortcode_atts['include_categories'];
		$posts_number       = $this->shortcode_atts['posts_number'];
		$show_title         = $this->shortcode_atts['show_title'];
		$show_date          = $this->shortcode_atts['show_date'];
		$background_layout  = $this->shortcode_atts['background_layout'];
		$auto               = $this->shortcode_atts['auto'];
		$auto_speed         = $this->shortcode_atts['auto_speed'];

		$args = array();
		if ( is_numeric( $posts_number ) && $posts_number > 0 ) {
			$args['posts_per_page'] = $posts_number;
		} else {
			$args['nopaging'] = true;
		}

		if ( '' !== $include_categories ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => explode( ',', $include_categories ),
					'operator' => 'IN'
				)
			);
		}

		$projects = et_divi_get_projects( $args );

		ob_start();
		if( $projects->post_count > 0 ) {
			while ( $projects->have_posts() ) {
				$projects->the_post();
				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_portfolio_item et_pb_grid_item ' ); ?>>
				<?php
					$thumb = '';

					$width = 320;
					$width = (int) apply_filters( 'et_pb_portfolio_image_width', $width );

					$height = 241;
					$height = (int) apply_filters( 'et_pb_portfolio_image_height', $height );

					list($thumb_src, $thumb_width, $thumb_height) = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), array( $width, $height ) );

					$orientation = ( $thumb_height > $thumb_width ) ? 'portrait' : 'landscape';

					if ( '' !== $thumb_src ) : ?>
						<div class="et_pb_portfolio_image <?php echo esc_attr( $orientation ); ?>">
							<a href="<?php the_permalink(); ?>">
								<img src="<?php echo esc_attr( $thumb_src ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"/>
								<div class="meta">
									<span class="et_overlay"></span>

									<?php if ( 'on' === $show_title ) : ?>
										<h3><?php the_title(); ?></h3>
									<?php endif; ?>

									<?php if ( 'on' === $show_date ) : ?>
										<p class="post-meta"><?php echo get_the_date(); ?></p>
									<?php endif; ?>
								</div>
							</a>
						</div>
				<?php endif; ?>
				</div>
				<?php
			}
		}

		wp_reset_postdata();

		$posts = ob_get_clean();

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%4$s class="et_pb_fullwidth_portfolio %1$s%3$s%5$s" data-auto-rotate="%6$s" data-auto-rotate-speed="%7$s">
				%8$s
				<div class="et_pb_portfolio_items clearfix" data-portfolio-columns="">
					%2$s
				</div><!-- .et_pb_portfolio_items -->
			</div> <!-- .et_pb_fullwidth_portfolio -->',
			( 'on' === $fullwidth ? 'et_pb_fullwidth_portfolio_carousel' : 'et_pb_fullwidth_portfolio_grid clearfix' ),
			$posts,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $auto && in_array( $auto, array('on', 'off') ) ? esc_attr( $auto ) : 'off' ),
			( '' !== $auto_speed && is_numeric( $auto_speed ) ? esc_attr( $auto_speed ) : '7000' ),
			( '' !== $title ? sprintf( '<h2>%s</h2>', esc_html( $title ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Portfolio;

class ET_Builder_Module_Fullwidth_Map extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Fullwidth Map', 'et_builder' );
		$this->slug            = 'et_pb_fullwidth_map';
		$this->fullwidth       = true;
		$this->child_slug      = 'et_pb_map_pin';
		$this->child_item_text = __( 'Pin', 'et_builder' );

		$this->whitelisted_fields = array(
			'address',
			'zoom_level',
			'address_lat',
			'address_lng',
			'map_center_map',
			'mouse_wheel',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'zoom_level'  => array( '18', 'only_default_setting' ),
			'mouse_wheel' => array( 'on' ),
		);
	}

	function get_fields() {
		$fields = array(
			'address' => array(
				'label'             => __( 'Map Center Address', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'additional_button' => sprintf(
					' <a href="#" class="et_pb_find_address button">%1$s</a>',
					esc_html__( 'Find', 'et_builder' )
				),
				'class'       => array( 'et_pb_address' ),
				'description' => __( 'Enter an address for the map center point, and the address will be geocoded and displayed on the map below.', 'et_builder' ),
			),
			'zoom_level' => array(
				'type'    => 'hidden',
				'class'   => array( 'et_pb_zoom_level' ),
			),
			'address_lat' => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_address_lat' ),
			),
			'address_lng' => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_address_lng' ),
			),
			'map_center_map' => array(
				'renderer'              => 'et_builder_generate_center_map_setting',
				'use_container_wrapper' => false,
				'option_category'       => 'basic_option',
			),
			'mouse_wheel' => array(
				'label'           => __( 'Mouse Wheel Zoom', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => __( 'On', 'et_builder' ),
					'off' => __( 'Off', 'et_builder' ),
				),
				'description' => __( 'Here you can choose whether the zoom level will be controlled by mouse wheel or not.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id    = $this->shortcode_atts['module_id'];
		$module_class = $this->shortcode_atts['module_class'];
		$address_lat  = $this->shortcode_atts['address_lat'];
		$address_lng  = $this->shortcode_atts['address_lng'];
		$zoom_level   = $this->shortcode_atts['zoom_level'];
		$mouse_wheel  = $this->shortcode_atts['mouse_wheel'];

		wp_enqueue_script( 'google-maps-api' );

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$all_pins_content = $this->shortcode_content;

		$output = sprintf(
			'<div%5$s class="et_pb_module et_pb_map_container%6$s">
				<div class="et_pb_map" data-center-lat="%1$s" data-center-lng="%2$s" data-zoom="%3$d" data-mouse-wheel="%7$s"></div>
				%4$s
			</div>',
			esc_attr( $address_lat ),
			esc_attr( $address_lng ),
			esc_attr( $zoom_level ),
			$all_pins_content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			esc_attr( $mouse_wheel )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Map;

class ET_Builder_Module_Code extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Code', 'et_builder' );
		$this->slug            = 'et_pb_code';
		$this->use_row_content = true;
		$this->decode_entities = true;

		$this->whitelisted_fields = array(
			'raw_content',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
		);
	}

	function get_fields() {
		$fields = array(
			'raw_content' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'description'     => __( 'Here you can create the content that will be used within the module.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
			'max_width' => array(
				'label'           => __( 'Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id    = $this->shortcode_atts['module_id'];
		$module_class = $this->shortcode_atts['module_class'];
		$max_width        = $this->shortcode_atts['max_width'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );

		if ( '' !== $max_width ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%',
				'declaration' => sprintf(
					'max-width: %1$s;',
					esc_html( et_builder_process_range_value( $max_width ) )
				),
			) );
		}

		$output = sprintf(
			'<div%2$s class="et_pb_code et_pb_module%3$s">
				%1$s
			</div> <!-- .et_pb_code -->',
			$this->shortcode_content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Code;

class ET_Builder_Module_Fullwidth_Code extends ET_Builder_Module {
	function init() {
		$this->name            = __( 'Fullwidth Code', 'et_builder' );
		$this->slug            = 'et_pb_fullwidth_code';
		$this->fullwidth       = true;
		$this->use_row_content = true;
		$this->decode_entities = true;

		$this->whitelisted_fields = array(
			'raw_content',
			'admin_label',
			'module_id',
			'module_class',
		);
	}

	function get_fields() {
		$fields = array(
			'raw_content' => array(
				'label'           => __( 'Content', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'description'     => __( 'Here you can create the content that will be used within the module.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id    = $this->shortcode_atts['module_id'];
		$module_class = $this->shortcode_atts['module_class'];

		$this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );

		$output = sprintf(
			'<div%2$s class="et_pb_fullwidth_code et_pb_module%3$s">
				%1$s
			</div> <!-- .et_pb_fullwidth_code -->',
			$this->shortcode_content,
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Code;

class ET_Builder_Module_Fullwidth_Image extends ET_Builder_Module {
	function init() {
		$this->name       = __( 'Fullwidth Image', 'et_builder' );
		$this->slug       = 'et_pb_fullwidth_image';
		$this->fullwidth  = true;
		$this->defaults   = array(
			'align' => 'left',
		);

		$this->whitelisted_fields = array(
			'src',
			'alt',
			'title_text',
			'show_in_lightbox',
			'url',
			'url_new_window',
			'animation',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'show_in_lightbox' => array( 'off' ),
			'url_new_window'   => array( 'off' ),
			'animation'        => array( 'left' ),
		);

		$this->advanced_options = array(
			'border'                => array(),
			'custom_margin_padding' => array(
				'use_padding' => false,
				'css' => array(
					'important' => 'all',
				),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'src' => array(
				'label'              => __( 'Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => __( 'Upload an image', 'et_builder' ),
				'choose_text'        => __( 'Choose an Image', 'et_builder' ),
				'update_text'        => __( 'Set As Image', 'et_builder' ),
				'description'        => __( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
			),
			'alt' => array(
				'label'           => __( 'Image Alternative Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'et_builder' ),
			),
			'title_text' => array(
				'label'           => __( 'Image Title Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'This defines the HTML Title text.', 'et_builder' ),
			),
			'show_in_lightbox' => array(
				'label'             => __( 'Open In Lightbox', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_url',
					'#et_pb_url_new_window',
				),
				'description'       => __( 'Here you can choose whether or not the image should open in Lightbox. Note: if you select to open the image in Lightbox, url options below will be ignored.', 'et_builder' ),
			),
			'url' => array(
				'label'           => __( 'Link URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'off',
				'description'     => __( 'If you would like your image to be a link, input your destination URL here. No link will be created if this field is left blank.', 'et_builder' ),
			),
			'url_new_window' => array(
				'label'             => __( 'Url Opens', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => __( 'In The Same Window', 'et_builder' ),
					'on'  => __( 'In The New Tab', 'et_builder' ),
				),
				'depends_show_if'   => 'off',
				'description'       => __( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
			),
			'animation' => array(
				'label'             => __( 'Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'left'    => __( 'Left To Right', 'et_builder' ),
					'right'   => __( 'Right To Left', 'et_builder' ),
					'top'     => __( 'Top To Bottom', 'et_builder' ),
					'bottom'  => __( 'Bottom To Top', 'et_builder' ),
					'fade_in' => __( 'Fade In', 'et_builder' ),
					'off'     => __( 'No Animation', 'et_builder' ),
				),
				'description'       => __( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id        = $this->shortcode_atts['module_id'];
		$module_class     = $this->shortcode_atts['module_class'];
		$src              = $this->shortcode_atts['src'];
		$alt              = $this->shortcode_atts['alt'];
		$title_text       = $this->shortcode_atts['title_text'];
		$animation        = $this->shortcode_atts['animation'];
		$url              = $this->shortcode_atts['url'];
		$url_new_window   = $this->shortcode_atts['url_new_window'];
		$show_in_lightbox = $this->shortcode_atts['show_in_lightbox'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$output = sprintf(
			'<img src="%1$s" alt="%2$s"%3$s />',
			esc_attr( $src ),
			esc_attr( $alt ),
			( '' !== $title_text ? sprintf( ' title="%1$s"', esc_attr( $title_text ) ) : '' )
		);

		if ( 'on' === $show_in_lightbox ) {
			$output = sprintf( '<a href="%1$s" class="et_pb_lightbox_image" title="%3$s">%2$s</a>',
				esc_url( $src ),
				$output,
				esc_attr( $alt )
			);
		} else if ( '' !== $url ) {
			$output = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
				esc_url( $url ),
				$output,
				( 'on' === $url_new_window ? ' target="_blank"' : '' )
			);
		}

		$output = sprintf(
			'<div%4$s class="et_pb_module et-waypoint et_pb_fullwidth_image%2$s%3$s">
				%1$s
			</div>',
			$output,
			esc_attr( " et_pb_animation_{$animation}" ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Image;

class ET_Builder_Module_Fullwidth_Post_Title extends ET_Builder_Module {
	function init() {
		$this->name             = __( 'Fullwidth Post Title', 'et_builder' );
		$this->slug             = 'et_pb_fullwidth_post_title';
		$this->fullwidth        = true;
		$this->defaults         = array();

		$this->whitelisted_fields = array(
			'title',
			'meta',
			'author',
			'date',
			'date_format',
			'categories',
			'comments',
			'featured_image',
			'featured_placement',
			'parallax_effect',
			'parallax_method',
			'text_orientation',
			'text_color',
			'text_background',
			'text_bg_color',
			'module_bg_color',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'title'              => array( 'on' ),
			'meta'               => array( 'on' ),
			'author'             => array( 'on' ),
			'date'               => array( 'on' ),
			'date_format'        => array( 'M j, Y' ),
			'categories'         => array( 'on' ),
			'comments'           => array( 'on' ),
			'featured_image'     => array( 'on' ),
			'featured_placement' => array( 'below' ),
			'parallax_effect'    => array( 'off' ),
			'parallax_method'    => array( 'on' ),
			'text_orientation'   => array( 'left' ),
			'text_color'         => array( 'dark' ),
			'text_background'    => array( 'off' ),
			'text_bg_color'      => array( 'rgba(255,255,255,0.9)', 'only_default_setting' ),
		);

		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'border'                => array(
				'css' => array(
					'main' => "{$this->main_css_element}.et_pb_featured_bg, {$this->main_css_element}",
				),
			),
			'custom_margin_padding' => array(
				'css' => array(
					'main' => ".et_pb_fullwidth_section {$this->main_css_element}.et_pb_post_title",
					'important' => 'all',
				),
			),
			'fonts' => array(
				'title' => array(
					'label'    => __( 'Title', 'et_builder' ),
					'use_all_caps' => true,
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_title_container h1",
					),
				),
				'meta'   => array(
					'label'    => __( 'Meta', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_title_container .et_pb_title_meta_container, {$this->main_css_element} .et_pb_title_container .et_pb_title_meta_container a",
					),
				),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'             => __( 'Show Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose whether or not display the Post Title', 'et_builder' ),
			),
			'meta' => array(
				'label'             => __( 'Show Meta', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_author',
					'#et_pb_date',
					'#et_pb_categories',
					'#et_pb_comments',
				),
				'description'       => __( 'Here you can choose whether or not display the Post Meta', 'et_builder' ),
			),
			'author' => array(
				'label'             => __( 'Show Author', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose whether or not display the Author Name in Post Meta', 'et_builder' ),
			),
			'date' => array(
				'label'             => __( 'Show Date', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'#et_pb_date_format'
				),
				'description'       => __( 'Here you can choose whether or not display the Date in Post Meta', 'et_builder' ),
			),

			'date_format' => array(
				'label'             => __( 'Date Format', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can define the Date Format in Post Meta. Default is \'M j, Y\'', 'et_builder' ),
			),

			'categories' => array(
				'label'             => __( 'Show Post Categories', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose whether or not display the Categories in Post Meta. Note: This option doesn\'t work with custom post types.', 'et_builder' ),
			),
			'comments' => array(
				'label'             => __( 'Show Comments Count', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose whether or not display the Comments Count in Post Meta.', 'et_builder' ),
			),
			'featured_image' => array(
				'label'             => __( 'Show Featured Image', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_featured_placement',
				),
				'description'       => __( 'Here you can choose whether or not display the Featured Image', 'et_builder' ),
			),
			'featured_placement' => array(
				'label'             => __( 'Featured Image Placement', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'below'      => __( 'Below Title', 'et_builder' ),
					'above'      => __( 'Above Title', 'et_builder' ),
					'background' => __( 'Title/Meta Background Image', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'#et_pb_parallax_effect',
				),
				'description'       => __( 'Here you can choose where to place the Featured Image', 'et_builder' ),
			),
			'parallax_effect' => array(
				'label'             => __( 'Use Parallax Effect', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'background',
				'affects'           => array(
					'#et_pb_parallax_method',
				),
				'description'       => __( 'Here you can choose whether or not use parallax effect for the featured image', 'et_builder' ),
			),
			'parallax_method' => array(
				'label'             => __( 'Parallax Method', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => __( 'CSS', 'et_builder' ),
					'off' => __( 'True Parallax', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'description'       => __( 'Here you can choose which parallax method to use for the featured image', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'left'   => __( 'Left', 'et_builder' ),
					'center' => __( 'Center', 'et_builder' ),
					'right'  => __( 'Right', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the orientation for the Title/Meta text', 'et_builder' ),
			),
			'text_color' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'dark'  => __( 'Dark', 'et_builder' ),
					'light' => __( 'Light', 'et_builder' ),
				),
				'description'       => __( 'Here you can choose the color for the Title/Meta text', 'et_builder' ),
			),
			'text_background' => array(
				'label'             => __( 'Use Text Background Color', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'color_option',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_text_bg_color',
				),
				'description'       => __( 'Here you can choose whether or not use the background color for the Title/Meta text', 'et_builder' ),
			),
			'text_bg_color' => array(
				'label'             => __( 'Text Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'depends_show_if'   => 'on',
			),
			'module_bg_color' => array(
				'label'    => __( 'Background Color', 'et_builder' ),
				'type'     => 'color-alpha',
				'custom_color'      => true,
				'tab_slug' => 'advanced',
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$title              = $this->shortcode_atts['title'];
		$meta               = $this->shortcode_atts['meta'];
		$author             = $this->shortcode_atts['author'];
		$date               = $this->shortcode_atts['date'];
		$date_format        = $this->shortcode_atts['date_format'];
		$categories         = $this->shortcode_atts['categories'];
		$comments           = $this->shortcode_atts['comments'];
		$featured_image     = $this->shortcode_atts['featured_image'];
		$featured_placement = $this->shortcode_atts['featured_placement'];
		$parallax_effect    = $this->shortcode_atts['parallax_effect'];
		$parallax_method    = $this->shortcode_atts['parallax_method'];
		$text_orientation   = $this->shortcode_atts['text_orientation'];
		$text_color         = $this->shortcode_atts['text_color'];
		$text_background    = $this->shortcode_atts['text_background'];
		$text_bg_color      = $this->shortcode_atts['text_bg_color'];
		$module_bg_color    = $this->shortcode_atts['module_bg_color'];

		// display the shortcode only on singlular pages
		if ( ! is_singular() ) {
			return;
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$output = '';
		$featured_image_output = '';
		$parallax_background_contaier = '';

		if ( 'on' === $featured_image && ( 'above' === $featured_placement || 'below' === $featured_placement ) ) {
			$featured_image_output = sprintf( '<div class="et_pb_title_featured_container">%1$s</div>',
				get_the_post_thumbnail( get_the_ID(), 'large' )
			);
		}

		if ( 'on' === $title ) {
			if ( is_et_pb_preview() && isset( $_POST['post_title'] ) && wp_verify_nonce( $_POST['et_pb_preview_nonce'], 'et_pb_preview_nonce' ) ) {
				$post_title = sanitize_text_field( wp_unslash( $_POST['post_title'] ) );
			} else {
				$post_title = get_the_title();
			}

			$output .= sprintf( '<h1>%s</h1>',
				$post_title
			);
		}

		if ( 'on' === $meta ) {
			$meta_array = array();
			foreach( array( 'author', 'date', 'categories', 'comments' ) as $single_meta ) {
				if ( 'on' === $$single_meta && ( 'categories' !== $single_meta || ( 'categories' === $single_meta && is_singular( 'post' ) ) ) ) {
					 $meta_array[] = $single_meta;
				}
			}

			$output .= sprintf( '<p class="et_pb_title_meta_container">%1$s</p>',
				et_pb_postinfo_meta( $meta_array, $date_format, esc_html__( '0 comments', 'et_builder' ), esc_html__( '1 comment', 'et_builder' ), '% ' . esc_html__( 'comments', 'et_builder' ) )
			);
		}

		if ( 'on' === $featured_image && 'background' === $featured_placement ) {
			$featured_image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => sprintf(
					'%%order_class%% %1$s',
					( 'on' === $parallax_effect ? '.et_parallax_bg' : '' )
				),
				'declaration' => sprintf(
					'background-image: url("%1$s");',
					esc_url( $featured_image_src[0] )
				),
			) );

			if ( 'on' === $parallax_effect ) {
				$parallax_background_contaier = sprintf( '<div class="et_parallax_bg%1$s"></div>',
					'on' === $parallax_method ? ' et_pb_parallax_css' : ''
				);
			}
		}

		if ( 'on' === $text_background ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_title_container',
				'declaration' => sprintf(
					'background-color: %1$s; padding: 1em 1.5em;',
					esc_html( $text_bg_color )
				),
			) );
		}

		ET_Builder_Element::set_style( $function_name, array(
			'selector'    => '%%order_class%%',
			'declaration' => sprintf(
				'text-align: %1$s;',
				esc_html( $text_orientation )
			),
		) );

		$background_layout = 'dark' === $text_color ? 'light' : 'dark';
		$module_class .= ' et_pb_bg_layout_' . $background_layout;

		$module_class .= 'above' === $featured_placement ? ' et_pb_image_above' : '';
		$module_class .= 'below' === $featured_placement ? ' et_pb_image_below' : '';

		ET_Builder_Element::set_style( $function_name, array(
			'selector'    => '%%order_class%%',
			'declaration' => sprintf(
				'background-color: %1$s;',
				esc_html( $module_bg_color )
			),
		) );

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_post_title %2$s%4$s">
				%5$s
				%6$s
				<div class="et_pb_title_container">
					%1$s
				</div>
				%7$s
			</div>',
			$output,
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			'on' === $featured_image && 'background' === $featured_placement ? ' et_pb_featured_bg' : '',
			$parallax_background_contaier,
			'on' === $featured_image && 'above' === $featured_placement ? $featured_image_output : '',
			'on' === $featured_image && 'below' === $featured_placement ? $featured_image_output : ''
		);

		return $output;
	}
}
new ET_Builder_Module_Fullwidth_Post_Title;