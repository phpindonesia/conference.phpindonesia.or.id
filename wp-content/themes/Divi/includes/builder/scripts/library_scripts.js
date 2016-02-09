(function($){
	$( document ).ready( function() {
		var url = window.location.href,
			tab_link = url.split( 'edit.php' )[1];

		if ( typeof tab_link !== 'undefined' ) {
			var $menu_items = $( '#toplevel_page_et_divi_library' ).find( '.wp-submenu li' );
			$menu_items.removeClass( 'current' );
			$menu_items.find( 'a' ).each( function() {
				var $this_el = $( this ),
					this_href = $this_el.attr( 'href' ),
					full_tab_link = 'edit.php' + tab_link;
				if ( -1 !== full_tab_link.indexOf( this_href ) ) {
					$this_el.closest( 'li' ).addClass( 'current' );
				}
			});
			$( '#toplevel_page_et_divi_library' ).removeClass( 'wp-not-current-submenu' ).addClass( 'wp-has-current-submenu' );
			$( 'a.toplevel_page_et_divi_library' ).removeClass( 'wp-not-current-submenu' ).addClass( 'wp-has-current-submenu wp-menu-open' );
		}

		$( 'body' ).on( 'click', '.add-new-h2, a.page-title-action', function() {
			var all_cats      = $.parseJSON( et_pb_new_template_options.layout_cats ),
				$modal        = '',
				cats_selector = '<label>' + et_pb_new_template_options.cats_label + '</label>';

				if( ! $.isEmptyObject( all_cats ) ) {
					cats_selector += '<div class="layout_cats_container">';

					$.each( all_cats, function( i, single_cat ) {
						if ( ! $.isEmptyObject( single_cat ) ) {
							cats_selector += '<label>' + single_cat.name + '<input type="checkbox" value="' + single_cat.id + '"/></label>';
						}
					});

					cats_selector += '</div>';
				}

				cats_selector += '<input type="text" value="" id="et_pb_new_cat_name" class="regular-text">';

				$modal = "<div class='et_pb_modal_overlay et_modal_on_top et_pb_new_template_modal'>\
				<div class='et_pb_prompt_modal'>\
				<h2>" + et_pb_new_template_options.modal_text + "</h2>\
				<div class='et_pb_prompt_modal_inside'>\
				<label>" + et_pb_new_template_options.modal_name +	"</label> \
					<input type='text' value='' id='et_pb_new_template_name' class='regular-text'>\
				<label>" + et_pb_new_template_options.modal_type + "</label> \
				<select id='new_template_type'>\
					<option value='module'>" + et_pb_new_template_options.module_text + "</option>\
					<option value='fullwidth_module'>" + et_pb_new_template_options.fw_module_text + "</option>\
					<option value='row'>" + et_pb_new_template_options.row_text + "</option>\
					<option value='section'>" + et_pb_new_template_options.section_text + "</option>\
					<option value='fullwidth_section'>" + et_pb_new_template_options.fw_section_text + "</option>\
					<option value='specialty_section'>" + et_pb_new_template_options.sp_section_text + "</option>\
					<option value='layout'>" + et_pb_new_template_options.layout_text + "</option>\
				</select>\
				<div class='et_module_tabs_options'>\
					<label>" + et_pb_new_template_options.general_text + "<input type='checkbox' value='general' id='et_pb_template_general' checked /></label> \
					<label>" + et_pb_new_template_options.adv_text + "<input type='checkbox' value='advanced' id='et_pb_template_general' checked /></label> \
					<label>" + et_pb_new_template_options.css_text + "<input type='checkbox' value='css' id='et_pb_template_general' checked /></label> \
					<p class='et_pb_error_message_save_template' style='display: none;'>" + et_pb_new_template_options.tabs_error + "</p> \
				</div>\
				<label>" + et_pb_new_template_options.global_text + "<input type='checkbox' value='' id='et_pb_template_global'></label>\
				" + cats_selector + " \
				<input id='et_builder_layout_built_for_post_type' type='hidden' value='et_pb_layout'>\
				</div>\
				<a href='#' class='et_pb_prompt_dont_proceed et-pb-modal-close'></a>\
				<div class='et_pb_prompt_buttons'>\
				<br>\
				<span class='spinner'></span>\
				<input type='submit' class='et_pb_create_template button-primary et_pb_prompt_proceed'>\
				</div>";

			$( 'body' ).append( $modal );
			return false;
		} );

		$( 'body' ).on( 'click', '.et_pb_prompt_dont_proceed', function() {
			$( this ).closest( '.et_pb_modal_overlay' ).remove();
		} );

		$( 'body' ).on( 'change', '#new_template_type', function() {
			var selected_type = $( this ).val();

			if ( 'module' === selected_type || 'fullwidth_module' === selected_type ) {
				$( '.et_module_tabs_options' ).css( 'display', 'block' );
			} else {
				$( '.et_module_tabs_options' ).css( 'display', 'none' );
			}
		} );

		$( 'body' ).on( 'click', '.et_pb_create_template:not(.clicked_button)', function() {
			var $this_button = $( this ),
				$this_form = $this_button.closest( '.et_pb_prompt_modal' ),
				template_name = $this_form.find( '#et_pb_new_template_name' ).val();

			if ( '' === template_name ) {
				$this_form.find( '#et_pb_new_template_name' ).focus();
			} else {
				var	template_shortcode = '',
					layout_scope = $this_form.find( $( '#et_pb_template_global' ) ).is( ':checked' ) ? 'global' : 'not_global',
					layout_type = $this_form.find( '#new_template_type' ).val(),
					module_width = 'regular',
					template_built_for_post_type = '',
					selected_tabs = '',
					selected_cats = '',
					new_cat = $this_form.find( '#et_pb_new_cat_name' ).val();

				if ( 'module' === layout_type || 'fullwidth_module' === layout_type ) {
					if ( ! $( '.et_module_tabs_options input' ).is( ':checked' ) ) {
						$( '.et_pb_error_message_save_template' ).css( "display", "block" );
						return;
					} else {
						selected_tabs = '';

						$( '.et_module_tabs_options input' ).each( function() {
							var this_input = $( this );

							if ( this_input.is( ':checked' ) ) {
								selected_tabs += '' !== selected_tabs ? ',' + this_input.val() : this_input.val();
							}

						});

						selected_tabs = 'general,advanced,css' === selected_tabs ? 'all' : selected_tabs;
					}
				}

				if ( $( '.layout_cats_container input' ).is( ':checked' ) ) {

					$( '.layout_cats_container input' ).each( function() {
						var this_input = $( this );

						if ( this_input.is( ':checked' ) ) {
							selected_cats += '' !== selected_cats ? ',' + this_input.val() : this_input.val();
						}
					});

				}

				switch ( layout_type ) {
					case 'row' :
						template_shortcode = '[et_pb_row template_type="row"][/et_pb_row]';
						break;
					case 'section' :
						template_shortcode = '[et_pb_section template_type="section"][et_pb_row][/et_pb_row][/et_pb_section]';
						break;
					case 'module' :
						template_shortcode = '[et_pb_module_placeholder selected_tabs="' + selected_tabs + '"]';
						break;
					case 'fullwidth_module' :
						template_shortcode = '[et_pb_fullwidth_module_placeholder selected_tabs="' + selected_tabs + '"]';
						module_width = 'fullwidth';
						layout_type = 'module';
						break;
					case 'fullwidth_section' :
						template_shortcode = '[et_pb_section template_type="section" fullwidth="on"][/et_pb_section]';
						layout_type = 'section';
						break;
					case 'specialty_section' :
						template_shortcode = '[et_pb_section template_type="section" specialty="on" skip_module="true" specialty_placeholder="true"][/et_pb_section]';
						layout_type = 'section';
						break;
				}

				$this_button.addClass( 'clicked_button' );
				$this_button.closest( '.et_pb_prompt_buttons' ).find( '.spinner' ).addClass( 'et_pb_visible_spinner' );

				$.ajax( {
					type: "POST",
					url: et_pb_new_template_options.ajaxurl,
					dataType: 'json',
					data:
					{
						action : 'et_pb_save_layout',
						et_load_nonce : et_pb_new_template_options.et_load_nonce,
						et_layout_name : template_name,
						et_layout_built_for_post_type: template_built_for_post_type,
						et_layout_content : template_shortcode,
						et_layout_scope : layout_scope,
						et_layout_type : layout_type,
						et_module_width : module_width,
						et_layout_cats : selected_cats,
						et_layout_new_cat : new_cat
					},
					success: function( data ) {
						window.location.href = decodeURIComponent( unescape( data.edit_link ) );
					}
				} );
			}
		} );

		$( '#et_show_export_section' ).click( function() {
			var this_link = $( this ),
				max_height_value = this_link.hasClass( 'et_pb_opened' ) ? '0' : '1000px';

			$( '.et_pb_export_section' ).animate( { maxHeight: max_height_value }, 500 );
			this_link.toggleClass( 'et_pb_opened' );
		});
	});
})(jQuery)