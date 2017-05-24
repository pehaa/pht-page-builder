<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link        http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/admin
 * @author     PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder_MB_Templates {

	public function __construct() {

		$this->config_data = PeHaa_Themes_Page_Builder::$phtpb_config_data;
		include_once PHTPB_PLUGIN_DIR . 'fonts/fonts-readable.php';
		$this->fonts_data = isset( $fonts ) ?  $fonts : NULL;		
	}

	private function generate_modal_template() {

		$return_string = '<script type="text/template" id="phtpb_builder-modal-template">';
		$return_string .= '<a href="#" class="js-phtpb_cancel-modal-action phtpb_cancel-modal-action phtpb_transition phtpbcss_icon--large" title=' . esc_html__( 'Close modal window', 'phtpb' ) . '><i class= "fa fa-close"></i></a>';

		$return_string .= "<% if ( typeof modal_window !== 'undefined' && ( modal_window === 'settings' ) ) { %>";
		$return_string .= '<div class="phtpb_modal-bottom-container">';
		$return_string .= '<a href="#" class="phtpb_do-modal-action phtpb_save-button phtpb_transition"><span>' . esc_html__( 'Save Changes & Exit', 'phtpb' ) . '</span></a>';
		$return_string .= '</div>';
		$return_string .= '	<% } %>';
		$return_string .= '</script>';
		return $return_string;

	}

	private function generate_gmaps_send_to_settings_modal_template() {

		$return_string = '<script type="text/template" id="phtpb_builder-gmaps-send-to-settings-modal-template">';
		$return_string .= '<a href="#" class="js-phtpb_cancel-modal-action phtpb_cancel-modal-action phtpb_transition phtpbcss_icon--large" title=' . esc_html__( 'Close modal window', 'phtpb' ) . '><i class= "fa fa-close"></i></a>';

		$return_string .= "<% if ( typeof modal_window !== 'undefined' && ( modal_window === 'settings' ) ) { %>";
		$return_string .= '<div class="phtpb_modal-bottom-container">';
		$return_string .= '<h3 class="phtpb_settings-heading phtpb_heading phtpb_truncate"><span> ' . esc_html__( 'Missing Google Maps API key', 'phtpb' ) . ' </span></h3>';
		$return_string .= '<div class="phtpb_main-settings">';
		$return_string .= '<div class="phtpb_main-settings__info">';
		$return_string .= '<p>';
		$return_string .= esc_html__( 'From June 22, 2016 the Google Maps Javascript API no longer supports keyless access (any request that doesn\'t include an API key).', 'phtpb' );
		$return_string .= '</p>';
		$return_string .= '<p>';
		$return_string .= esc_html__( 'Don’t be afraid, getting an API key is fast and simple.', 'phtpb' );
		$return_string .= '</p>';
		$return_string .= '<p>';
		$return_string .= sprintf( __( 'To get started using the Google Maps JavaScript API follow <a href="%s" target="_blank">this link.</a>', 'phtpb' ), esc_url( 'https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true&pli=1' ) );
		$return_string .= '</p>';
		$return_string .= '<p>';
		$return_string .= __( 'Once you have got your Google Maps JavaScript API key generated - all you have to do is to <strong>paste it into the "Google Maps Api Key" field in the page builder settings.</strong>', 'phtpb' );
		$return_string .= '</p>';
		$return_string .= '</div>';
		$return_string .= '</div>';
		$return_string .= sprintf( '<a href="%1$s" class="js-phtpb_cancel-modal-action_and_do_default phtpb_save-button phtpb_transition" target="_blank"><span>%2$s</span></a>',
			esc_url( PeHaa_Themes_Page_Builder_Options_Page::settings_url() . '#phtpb-gmaps-auth' ),
			esc_html__( 'Open Settings', 'phtpb' )
		);
		$return_string .= '</div>';
		$return_string .= '	<% } %>';
		$return_string .= '</script>';
		return $return_string;
	}

	private function form_element( $key, $field, $custom_class = '', $element_label = '' ) {
		
		if ( isset( $field['class'] ) ) {
			$custom_class .= ' ' . $field['class'];
		}
		$with_label = isset( $field['title'] ) ? 'phtpb_option--with-label' : '';
		$start_string = "<div class='phtpb_option phtpb_$key-option $custom_class $with_label'>";
		$label_string = '';
		if ( $with_label ) {
			$label_string = sprintf( '<label for="%1$s">%2$s: </label>', $key, $field['title'] );
		}
		if ( ! isset( $field['type'] ) ) {
			return;
		}

		if ( isset( $field['default'] ) ) {
			$default = $field['default'];
			$value = "<%= typeof( phtpb_attributes['$key'] ) !== 'undefined' ? _.unescape( phtpb_attributes['$key'] ) : '$default' %>";

		} else {
			$value = "<%= typeof( phtpb_attributes['$key'] ) !== 'undefined' ? _.unescape( phtpb_attributes['$key'] ) : '' %>";
		}
		if ( 'r_w' === $key ) {
			$value = "<%= typeof( phtpb_attributes['d_w'] ) !== 'undefined' && phtpb_attributes['d_w'] && typeof( phtpb_attributes['r_w'] ) !== 'undefined' && 2 == parseInt(phtpb_attributes['r_w']/ phtpb_attributes['d_w']) ? parseInt( phtpb_attributes['r_w']/2 ) : ( typeof( phtpb_attributes['r_w'] ) !== 'undefined' && parseInt( phtpb_attributes['r_w'] ) ? phtpb_attributes['r_w'] : '$default' ) %>";
		} elseif ( 'r_h' === $key ) {
			$value = "<%= typeof( phtpb_attributes['d_w'] ) !== 'undefined' && phtpb_attributes['d_w'] && typeof( phtpb_attributes['r_w'] ) !== 'undefined' && 2 == parseInt(phtpb_attributes['r_w']/ phtpb_attributes['d_w']) ? parseInt( phtpb_attributes['r_h']/2 ) : ( typeof( phtpb_attributes['r_h'] ) !== 'undefined' && parseInt( phtpb_attributes['r_h'] ) ? parseInt( phtpb_attributes['r_h'] ) : '$default' ) %>";
		} elseif ( 'd_w' === $key ) {
			$value = '';
		}
		$return_string = '';
		$data_default = isset( $default ) ? "data-default='$default'" : '';
		switch ( $field['type'] ) {
		case 'color' :
			$return_string .= "<input id='$key' class='phtpb_color-picker-hex phtpb_shortcode-attribute' type='text' maxlength='7' placeholder='Hex Value' value='$value' data-default-color='$default' $data_default/>";
			break;
		case 'text' :
			$return_string .= <<<END
			<input id='$key' class='$key regular-text phtpb_shortcode-attribute' type='text' value="$value" $data_default/>
END;
			break;
		case 'datepicker' :
			$return_string .= <<<END
			<input id='$key' class='$key regular-text phtpb_datepicker phtpb_shortcode-attribute' type='text' value="$value" $data_default/>
END;
			break;
		case 'hidden' :
			$return_string .= "<input id='$key' class='$key phtpb_shortcode-attribute' type='hidden' value='$value' $data_default/>";
			
			break;
		case 'hidden_2_skip' :
			$return_string .= "<input id='$key' class='$key' type='hidden' value='$value' />";
			break;
		case 'find_address' :
			$return_string .= "<input id='$key' class='$key regular-text phtpb_shortcode-attribute' type='text' value='$value' />";
			$return_string .= '<input type="button"" class="button phtpb_address-to-geocode" value="' . esc_html__( 'Find', 'phtpb' ) . '" />';
			break;
		case 'wysiwyg' :
			$return_string .= '<div id="phtpb_content_new"><%= typeof( phtpb_attributes.phtpb_content_new )!== "undefined" ? phtpb_attributes.phtpb_content_new : "" %></div>';
			break;
		case 'image' :
			$return_string .= "<input id='$key' class='phtpb_upload-field phtpb_shortcode-attribute' type='hidden' value='$value' $data_default/>";
			$return_string .= '<input type="button" class="button button-upload phtpb_upload-button" value="' . esc_html__( 'Upload an image', 'phtpb' ) . '" data-choose="Choose an Image" data-update="Set As Image" data-type="image" />';
			break;
		case 'image_id' :
			$return_string .= "<input id='$key' class='phtpb_upload-field-id phtpb_shortcode-attribute' type='hidden' value='$value' $data_default/>";
			break;
		case 'gallery' :
			$return_string .= "<input id='$key' type='hidden' class='regular-text phtpb_gallery-ids-field phtpb_shortcode-attribute' value='$value' $data_default/>";
			$return_string .= "<input type='button' class='button button-upload phtpb_upload-button phtpb_gallery-button' value='" . esc_html__( 'Create/Update Gallery', 'phtpb' ) . "' />";
			$return_string .= '<div class="phtpb_visual-feedback">' . esc_html__( 'Number of images in this gallery: ', 'phtpb' ) . '<span class="phtpb_count">0</span></div>';
			break;
		case 'video' :
			$return_string .= "<input id='$key' class='phtpb_upload-field phtpb_shortcode-attribute' type='hidden' value='$value' $data_default/>";
			$return_string .= '<input type="button" class="button button-upload phtpb_upload-button" value="' . esc_html__( 'Upload a video', 'phtpb' ) . '" data-choose="Choose a Video" data-update="Set As Video" data-type="video" />';
			break;
		case 'checkbox' :
			$default_checkbox = isset( $default ) ? $default : '';
			$checked_class = "<% if ( typeof( phtpb_attributes['$key'] ) !== 'undefined' ) { %>";
			$checked_class .= "<%= 'yes' === phtpb_attributes['$key'] ?  'checked' : 'unchecked' %>";
			$checked_class .= "<% } else { %>";
			$checked_class .= 'yes' === $default_checkbox ?  "<%='checked' %>" : "<%='unchecked' %>";
			$checked_class .= "<% } %>";
			$checked = "<% if ( typeof( phtpb_attributes['$key'] ) !== 'undefined' ) { %>";
			$checked .= "<%= 'yes' === phtpb_attributes['$key'] ?  'checked=checked' : '' %>";
			$checked .= "<% } else { %>";
			$checked .= 'yes' === $default_checkbox ?  "<%='checked=checked' %>" : "<%= '' %>";
			$checked .= "<% } %>";
			$start_string = "<div class='phtpb_option phtpb_$key-option $custom_class $with_label $checked_class' data-check=$checked_class>";
			$return_string .= "<input id='$key' type='checkbox' name='$key' class='phtpb_shortcode-attribute' value='$key' $checked $data_default/>";
			break;
		case 'select' :
			$default_select = isset( $default ) ? $default : '';		
			$select_class = "<% if ( typeof( phtpb_attributes['$key'] ) !== 'undefined' ) { %>";
			$select_class .= "<%= phtpb_attributes['$key'] %>";
			$select_class .= "<% } else { %>";
			$select_class .= $default_select;
			$select_class .= "<% } %>";
			$start_string = "<div class='phtpb_option phtpb_$key-option $custom_class $with_label select-$select_class'>";
			$return_string .= sprintf( '<select name="%1$s" id="%1$s" class="phtpb_shortcode-attribute" %2$s>', $key, $data_default );
			if ( $element_label === 'phtpb_showcase' && $key === 'phtpb_type' && "true" == "<%= phtpb_attributes['$key'] === parseInt(phtpb_attributes['$key'], 10) %>" ) {
				$i = 0;
				foreach ( $field['options'] as $option_key => $option_value ) {
					$selected = "<%= '$i' == phtpb_attributes['$key'] ?  'selected=selected' : '' %>";
					$return_string .= "<option <%= phtpb_attributes['$key'] === parseInt(phtpb_attributes['$key'], 10) %> value='$option_key' $selected>$option_value</option>";
					$i++;
				}
			} elseif ( in_array( $element_label, array( 'phtpb_row', 'phtpb_row_inner' ) ) && $key === 'gutter' ) {
				foreach ( $field['options'] as $option_key => $option_value ) {
					$selected = "<% if ( typeof( phtpb_attributes['$key'] ) !== 'undefined' ) { %>";
					$selected .= "<%= '$option_key' === phtpb_attributes['$key'] || '$option_key' === 'flush' && phtpb_attributes['$key'] === '' ?  'selected=selected' : '' %>";
					$selected .= "<% } else { %>";
					$selected .= "<%= '$option_key' === '$default_select' ?  'selected=selected' : '' %>";
					$selected .= "<% } %>";
					$return_string .= "<option value='$option_key' $selected>$option_value</option>";
				}
			} else {
				foreach ( $field['options'] as $option_key => $option_value ) {
					$selected = "<% if ( typeof( phtpb_attributes['$key'] ) !== 'undefined' ) { %>";
					$selected .= "<%= '$option_key' === phtpb_attributes['$key'] ?  'selected=selected' : '' %>";
					$selected .= "<% } else { %>";
					$selected .= "<%= '$option_key' === '$default_select' ?  'selected=selected' : '' %>";
					$selected .= "<% } %>";
					$return_string .= "<option value='$option_key' $selected>$option_value</option>";
				}
			}			
			$return_string .= '</select>';
			break;
		case 'modules' :
			$return_string .= '<ul class="phtpb_all-modules phtpb_list phtpb_list--5">';
			$unactivated_modules = 0;
			$disabled_modules = false;
			foreach ( $this->config_data as $key => $values ) {
				$class = '';
				if ( 'phtpb_google_map' === $key ) {
					$class = "<%= phtpb_modules_data.gmapsAuthFailed ? ' phtpb_gmaps_disabled' : ' phtpb_gmaps_enabled' %>";
				}
				if ( 'module' === $values['phtpb_admin_type'] && 'advanced_twin' !== $values['phtpb_admin_mode'] && 'advanced_child' !== $values['phtpb_admin_mode'] ) {
					$disabled_string = '<li class="phtpb_border--none phtpb_disabled ' . $key . '" data-module_type="' . $key . '">';
					$disabled_string .= '<span class="phtpbcss_icon--large ' . $values['icon'] . '"></span>';
					$disabled_string .= '<span class="phtpb_module_title phtpb_truncate">' . $values['title'] . ' </span>';
					$disabled_string .= '</li>';
					if ( isset( $values['pht_themes_only'] ) && $values['pht_themes_only'] ) {
						++$unactivated_modules;
						$return_string .= $disabled_string;
					} elseif ( isset( $values['is_disabled'] ) && $values['is_disabled'] ) {
						$return_string .= $disabled_string;
						$disabled_modules = true;
					} else {
						$return_string .= '<li class="phtpb_do-modal-action phtpb_border--none phtpb_pointer phtpb_colorblock ' . $key . $class . '" data-module_type="' . $key . '">';
						$return_string .= '<span class="phtpbcss_icon--large ' . $values['icon'] . '"></span>';
						$return_string .= '<span class="phtpb_module_title phtpb_truncate">' . $values['title'] . '</span>';
						$return_string .= '</li>';
					}
				}
			}
			$return_string .= '</ul>';
			if ( $unactivated_modules ) {
				$unactivated_string = '<div class="phtpb-promo phtpb_border--small">';
				$unactivated_string .= '<p class="phtpb-promo__text">';
				$unactivated_string .= sprintf( __(  'Disabled modules: Posts Grid and Filtered Portfolio are available exlusively with <a href="%s">Yaga - our Multipurpose Premium Theme</a>', 'phtpb' ), 'http://wptemplates.pehaa.com/yaga' );
				$unactivated_string .= '</p>';
				$unactivated_string .= '<a href="http://wptemplates.pehaa.com/buy-yaga" class="phtpb-promo__button phtpb_button--insert"><span><i class="fa fa-shopping-cart"></i> ' . esc_html__( 'Buy Yaga', 'phtpb' ) . '</span></a>';
				$unactivated_string .= '</div>';
				$return_string .= $unactivated_string;
			}
			if ( $disabled_modules ) {
				$unactivated_string = '<div class="phtpb-promo phtpb_border--small">';
				$unactivated_string .= '<p class="phtpb-promo__text">';
				$unactivated_string .= sprintf( esc_html__(  'To activate the disabled module(s) you have to install and/or activate the corresponding plugin', 'phtpb' ) );
				$unactivated_string .= '</p>';
				$unactivated_string .= '</div>';
				$return_string =  $return_string . $unactivated_string;
			}
			
			break;
		case 'columns' :
			$layouts = array(
				'4_4', '1_2,1_2', '1_3,2_3', '1_3,1_3,1_3', '2_3,1_3', '1_4,3_4', '3_4,1_4', '1_4,1_2,1_4', '1_4,1_4,1_2', '1_2,1_4,1_4', '1_4,1_4,1_4,1_4',
			);
			$nice_columns = array(
				'4_4' => esc_html__( 'One column', 'phtpb' ),
				'1_2' => '1/2',
				'1_3' => '1/3',
				'1_4' => '1/4',
				'2_3' => '2/3',
				'3_4' => '3/4',
			);
			$return_string .= '<ul class="phtpb_list phtpb_list--3">';

			foreach ( $layouts as $layout ) {
				$return_string .= sprintf( '<li class="phtpb_do-modal-action phtpb_block phtpb_border--small phtpb_colorblock phtpb_pointer phtpb_list" data-layout="%s">', $layout );
				$columns = explode( ',', $layout );
				foreach ( $columns as $column ) {
					$return_string .= sprintf( '<div class="phtpbcss_column_layout %1$s"><span>%2$s</span></div>', 'phtpbcss_column_layout_' . $column, $nice_columns[ $column ] );
				}
				$return_string .= '</li>';
			}
			$return_string .= '</ul>';
			break;
		case 'icons' :

			if ( $this->fonts_data ) {
				$return_string .= '<ul class="phtpb_icons-list">';
				$active_class = "<%= typeof( phtpb_attributes['$key'] ) !== 'undefined' && phtpb_attributes['$key'] === '' ?  ' phtpb_icons-list__icon--active' : '' %>";

				$return_string .= "<li data-icon-class='' class='phtpb_icons-list__icon $active_class' title='" . esc_html__( 'None', 'phtpb' ) . "'></li>";
				foreach (  $this->fonts_data as $font_class => $font_name ) {
					$active_class = "<%= typeof( phtpb_attributes['$key'] ) !== 'undefined' && phtpb_attributes['$key'] === '$font_class' ?  ' phtpb_icons-list__icon--active' : '' %>";
					$return_string .= "<li data-icon-class='$font_class' class='phtpb_icons-list__icon phtpb_pointer phtpb_transition $font_class$active_class' title='$font_name'></li>";

				}
				$return_string .= '</ul>';
				$return_string .= "<input id='$key' class='$key phtpb_shortcode-attribute' type='hidden' value='$value' />";

			} else {
				$return_string .= "<input id='$key' class='$key regular-text phtpb_shortcode-attribute' type='text' value='$value' />";
			}

			break;
		default:
			break;

		}
		if ( isset( $field['description'] ) ) {
			$return_string .= sprintf( '<div class="phtpb_description">%s</div>', $field['description'] );
		}
		$return_string .= '</div><!-- .phtpb_option -->';
		return $start_string.$label_string.$return_string;
	}

	private function generate_modal_innertemplate( $element ) {

		$return_string = sprintf( '<script type="text/template" id="%s">', 'phtpb_builder-' . $element['label'] . '-modal-template' );
		$return_string .= sprintf( '<h3 class="phtpb_settings-heading phtpb_heading phtpb_truncate"><span>%s - Settings</span></h3>', $element['title'] );
		$return_string .= "\n";
		$label = $element['label'];
		$return_string .= "<div class='phtpb_main-settings phtpb_main-settings--$label' >";
		if ( isset( $element['phtpb_admin_mode'] ) && 'parent' === $element['phtpb_admin_mode'] ) {
			$return_string .= '<div class="phtpb_option-advanced-module-settings" data-module_type="<%= phtpb_attributes.child %>">';
			$return_string .= '<% if ( typeof phtpb_attributes.child !== "undefined" ) { %>';
			$add_submodule =  isset( $element['add_submodule'] ) ? $element['add_submodule'] : esc_html__( 'Add Submodule', 'phtpb' );
			$return_string .= '<a class="phtpb_do-inner-modal-action phtpb_button--insert phtpb_block" data-module_type="<%= phtpb_attributes.child %>"><span><i class= "fa fa-plus-circle"></i> ' . $add_submodule . '</span></a>';
			$return_string .= '<% } %>';
			$return_string .= '</div> <!-- .phtpb_option -->';
		}

		$return_string .= "\n";
		//start with 'admin_label'
		if ( isset( $element['fields'] ) && isset( $element['fields']['admin_label'] ) ) {
			$return_string .= $this->form_element( 'admin_label', $element['fields']['admin_label'], 'phtpb_option--special' );
		}
		if ( in_array( $element['label'], array( 'phtpb_google_map' ) ) ) {
			$return_string .= '<div class="phtpb_js-map phtpb_google-map"></div>';
		}
		foreach ( $element['fields'] as $key => $field ) {
			if ( 'admin_label' === $key ) { 
				continue;
			}
			$return_string .= $this->form_element( $key, $field, '', $element['label'] );			
		}
		$return_string .= "\n";

		$return_string .= '</div><!-- .phtpb_main-settings -->';
		$return_string .= "\n";
		$return_string .= '</script>';
		return $return_string;
	}

	private function phtpb_generate_app_template() {
		return '<script type="text/template" id="phtpb_builder-app-template"></script>';
	}

	private function phtpb_generate_element_template( $slug, $title = false, $controls = array(), $container = false, $insert_module = false, $insert_columns = false ) {

		$return_string = "<script type='text/template' id='phtpb_builder-$slug-template'>";
		if ( $title ) {
			if ( 'section' === $slug ) {
				$return_string .= '<div class="phtpb_js_handlediv phtpbcss_handlediv phtpb_pointer phtpb_drag_disabled" title="Click to toggle"><i class="fa fa-caret-up"></i></div>';
				$return_string .= '<div class="phtpb_js-module-title phtpb_heading phtpb_module__title phtpb_module__title--' . $slug . ' phtpb_title phtpb_title--block"><span class="phtpb_js-module-title__string"><%= admin_label %></span></div>';
			} else {
				$return_string .= '<span class="phtpb_js-module-title phtpb_module__title phtpb_module__title--' . $slug . ' phtpb_title phtpb_title--block"><span class="phtpb_js-module-title__string"><%= admin_label %></span></span>';
			}
			
		}
		if ( 'row' === $slug ) {
			$return_string .= '<div class="phtpb_js_handlediv phtpbcss_handlediv phtpb_pointer phtpb_drag_disabled" title="Click to toggle"><i class="fa fa-caret-up"></i></div>';			
		}
		if ( ! empty( $controls ) ) {
			$return_string .= "<div class='phtpb_controls phtpb_css-controls phtpb_css-controls--$slug'>";
			foreach ( $controls as $control_key ) {
				switch ( $control_key ) {
				case 'settings' :
					if ( 'column' === $slug ) {
						$return_string .= '<a href="#" class="phtpb_settings phtpb_drag_disabled phtpb_settings-' . $slug . ' phtpb_open-modal phtpb_button--insert" data-modal-window="settings" title="' . esc_html__( 'Column settings', 'phtpb' ) . '"><span><i class="fa fa-cogs"></i></span></a> ';
					} else {
						$return_string .= '<a href="#" class="phtpb_settings phtpb_drag_disabled phtpb_settings-' . $slug . ' phtpb_open-modal phtpbcss_icon" data-modal-window="settings" title="' . esc_html__( 'Settings', 'phtpb' ) . '"><i class="fa fa-cogs"></i></a> ';
					}
					break;
				case 'edit' :
					$return_string .= '<a href="#" class="phtpb_settings phtpb_drag_disabled phtpb_settings-' . $slug . ' phtpb_open-modal phtpbcss_icon" data-modal-window="settings" title="' . esc_html__( 'Edit', 'phtpb' ) . '"><i class="fa fa-edit"></i></a> ';
					break;
				case 'clone' :
					$return_string .= "<% if ( 'advanced_child' !== phtpb_admin_mode ) { %>";
					$return_string .= '<a href="#" class="phtpb_clone phtpb_drag_disabled phtpbcss_icon" data-phtpb_cid="<%= phtpb_cid %>" title="' . esc_html__( 'Clone', 'phtpb' ) . '"><i class="fa fa-files-o"></i></a> ';
					$return_string .= '<% } %>';
					break;
				case 'delete' :
					$return_string .= "<% if ( 'advanced_child' !== phtpb_admin_mode ) { %>";
					$return_string .= '<a href="#" class="phtpb_remove phtpb_drag_disabled phtpbcss_icon" data-phtpb_cid="<%= phtpb_cid %>" title="' . esc_html__( 'Delete', 'phtpb' ) .'"><i class="fa fa-trash-o"></i></a> ';
					$return_string .= '<% } %>';
					break;
				}
			}
			$return_string .= '</div><!-- .phtpb_controls -->';
		}
		if ( $container ) {
			$return_string .= 'module' === $slug ? '<% if ( "parent" === phtpb_admin_mode ) { %>' : '';
			$with_children = "<%= 'parent' === phtpb_admin_mode && typeof child !== 'undefined' ? 'phtpb_container-with-children' : '' %>";
			$return_string .= '<div class="phtpb_' . $slug . '-content phtpb_container phtpb_js-container ' . $with_children . '" data-phtpb_cid="<%= phtpb_cid %>">';
			if ( $insert_module ) {
				$return_string .= '<a href="#" class="phtpb_insert-module phtpb_drag_disabled phtpb_open-modal phtpb_button--insert" title="' . esc_html__( 'Open modal window to insert Module(s)', 'phtpb' ) . '" data-modal-window="all_modules"><span><i class="fa fa-plus-circle"></i> ' . esc_html__( 'Add Module', 'phtpb' ) . '</span></a>';
			}
			$return_string .= '</div><!-- .phtpb_container -->';
			$return_string .= 'module' === $slug ? '<%  } %>' : '';
			$return_string .= "<% if ( 'parent' === phtpb_admin_mode && typeof child !== 'undefined' ) { %>";
			$add_submodule = "<%=  typeof add_submodule !== 'undefined' ? add_submodule : 'Add' %>";
			$return_string .= '<div class="phtpb_container"><a href="#" class="phtpb_add-child phtpb_block" data-module_type="<%= child %>"><i class="fa fa-plus-circle"></i>  ' . $add_submodule . '</a></div>';
			$return_string .= '<% } %>';
		}

		if ( $insert_columns ) {
			$return_string .= '<a class="phtpb_open-modal phtpb_insert-columns phtpb_drag_disabled phtpb_css-add-row phtpb_button--insert phtpb_drag_disabled" data-modal-window="columns_layout"><span>' . $insert_columns . '</span></a>';
		}
		if ( 'section' === $slug ) {
			$return_string .= '<a href="#" class="phtpb_add-section phtpb_drag_disabled phtpb_css-add-section phtpb_button--insert"><span><i class="fa fa-plus-circle"></i> ' . esc_html__( 'Add Section', 'phtpb' ) . '</span></a>';
		}
		$return_string .= '</script>';
		return $return_string;

	}

	private function all_modal_windows() {

		$all_modal_windows = array();

		foreach ( $this->config_data as $key => $value ) {
			if ( isset( $value['fields'] ) ) {
				$all_modal_windows[] = $key;
			}
		}
		return $all_modal_windows;
	}

	public function phtpb_app_template() {
		echo $this->phtpb_generate_element_template( 'app' );
	}
	public function phtpb_section_template() {
		echo $this->phtpb_generate_element_template( 'section', true, array( 'settings', 'clone', 'delete' ), true, false, '<i class="fa fa-plus-circle"></i> ' . esc_html__( 'Add Row', 'phtpb' )  );
	}
	public function phtpb_row_template() {
		echo $this->phtpb_generate_element_template( 'row', false, array( 'settings', 'clone', 'delete' ), true  );
	}
	public function phtpb_column_template() {
		echo $this->phtpb_generate_element_template( 'column', false, array( 'settings' ), true, true, '<i class="fa fa-columns"></i> ' . esc_html__( 'Subdivision', 'phtpb' )  );
	}

	public function phtpb_module_template() {
		echo $this->phtpb_generate_element_template( 'module', true, array( 'edit', 'clone', 'delete' ), true  );
	}

	public function phtpb_modal_template() {
		echo $this->generate_modal_template();
	}
	public function phtpb_all_modules_modal_template() {
		echo $this->generate_modal_innertemplate( array( 'label' => 'all-modules', 'title' => esc_html__( 'All Modules', 'phtpb' ), 'fields' => array( 'modules' => array( 'type' => 'modules' ) ) ) );
	}
	public function phtpb_columns_modal_template() {
		echo $this->generate_modal_innertemplate( array( 'label' => 'columns', 'title' => esc_html__( 'Columns Layout', 'phtpb' ), 'fields' => array( 'columns' => array( 'type' => 'columns' ) ) ) );
	}

	public function phtpb_modal_templates() {
		foreach ( $this->all_modal_windows() as $element ) {
			if ( isset( $this->config_data[ $element ] ) ) {
				echo $this->generate_modal_innertemplate( $this->config_data[ $element ] );
			}
		}
	}

	public function phtpb_gmaps_send_to_settings_modal_template() {
		echo $this->generate_gmaps_send_to_settings_modal_template();
	}

}