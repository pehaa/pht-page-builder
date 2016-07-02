<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/admin
 * @author     PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * The options field slug for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $option_name
	 */
	private $option_name;

	private $post_type = NULL;

	private $post_id = NULL;

	private $render_page_builder = false;

	private $add_page_builder_scripts_and_styles = false;

	private $check_content_inconsistency = false;

	private $state = false;

	private $meta_content = false;

	/**
	 * The options field slug for this plugin.
	 *
	 * @since    2.9.0
	 * @access   private
	 * @var      string    $gmaps_auth_transient
	 */

	private $gmaps_auth_transient = 'phtpb_gm_auth_failed';

	private $gmaps_auth_action = 'save_gmaps_auth_failure_to_transient';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version, $option_name ) {

		$this->name = $name;
		$this->version = $version;
		$this->option_name = $option_name;
		$this->post_id = NULL;
		$this->settings = get_option( $this->option_name );
		$this->save_to_content = isset( PeHaa_Themes_Page_Builder::$settings['save_to_content'] ) && 'yes' === PeHaa_Themes_Page_Builder::$settings['save_to_content'];
		$this->phtpb_post_types = PeHaa_Themes_Page_Builder::$phtpb_post_types;

		$this->gmaps_api_key = isset( $this->settings['gmaps_api_key'] ) && trim( $this->settings['gmaps_api_key'] ) ? $this->settings['gmaps_api_key'] :false;

		add_filter( 'pht_meta_content_into_editor', array( $this, 'replace_preview_img_srcs' ) );

		add_action( 'wp_ajax_' . $this->gmaps_auth_action, array( $this, 'save_gmaps_auth_failure_to_transient' ) );

		add_filter( 'phtpb_google_map_inner', array( $this, 'gmaps_auth_failed_warning' ) );

	}

	public function check_post_for_pagebuilder( $post_type, $post ) {

		if ( !isset( $post->ID ) ) {
			return;
		}
		if ( !isset( $post->post_type ) ) {
			return;
		}
		$this->post_id = $post->ID;
		$this->render_page_builder = $this->check_for_pagebuilder_by_id( $post->ID, $post_type );
		$this->add_page_builder_scripts_and_styles = $this->render_page_builder;
		$this->state = 'yes' === get_post_meta( $post->ID, PeHaa_Themes_Page_Builder::$meta_field_name_state, true );
		$this->meta_content = get_post_meta( $post->ID, PeHaa_Themes_Page_Builder::$meta_field_name_content, true );
		$this->check_content_inconsistency = $this->check_content_inconsistency( $post );
		$this->is_builder_used = !$this->check_content_inconsistency && $this->state;

	}

	private function check_for_pagebuilder_by_id( $id = NULL, $post_type = NULL ) {

		if ( !$id ) {
			return;
		}
		if ( !$post_type ) {
			$post_type = get_post_type( $id );
			if ( 'revision' == $post_type ) {
				$parent_id = wp_is_post_revision( $id );
				if ( !$parent_id ) {
					return;
				}
				$post_type = get_post_type( $parent_id );
			}
		}

		if ( !array_key_exists( $post_type, PeHaa_Themes_Page_Builder::$phtpb_available_post_types ) ) {
			return;
		}
		if ( !in_array( $post_type, $this->phtpb_post_types ) ) {
			return;
		}
		if ( in_array( $id, PeHaa_Themes_Page_Builder::$phtpb_forbidden_ids ) ) {
			return;
		}
		return true;
	}


	function restore_post_revision( $post_id, $revision_id ) {

		if ( $this->save_to_content ) {
			return;
		}

		$meta_content = get_metadata( 'post', $revision_id, PeHaa_Themes_Page_Builder::$meta_field_name_content, true );
		if ( false !== $meta_content ) {
			update_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_content, $meta_content );
		} else {
			delete_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_content );
		}

		$meta_content_state = get_metadata( 'post', $revision_id, PeHaa_Themes_Page_Builder::$meta_field_name_state, true );
		if ( false !== $meta_content_state ) {
			update_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_state, $meta_content_state );
		} else {
			delete_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_state );
		}

	}


	function phtpb_revision_fields( $fields ) {

		if ( $this->save_to_content ) {
			return $fields;
		}

		$fields[PeHaa_Themes_Page_Builder::$meta_field_name_content] = esc_html__( 'Page Builder Content', $this->name );
		$fields[PeHaa_Themes_Page_Builder::$meta_field_name_state] = esc_html__( 'Page Builder Activated', $this->name );
		return $fields;

	}

	function phtpb_revision_field( $value, $field, $revision ) {

		if ( $this->save_to_content ) {
			return $value;
		}

		$page_builder_data = get_metadata( 'post', $revision->ID, PeHaa_Themes_Page_Builder::$meta_field_name_content, true );
		return $page_builder_data;

	}

	function phtpb_revision_field_state( $value, $field, $revision ) {

		if ( $this->save_to_content ) {
			return $value;
		}

		$page_builder_data = 'yes' === get_metadata( 'post', $revision->ID, PeHaa_Themes_Page_Builder::$meta_field_name_state, true ) ? esc_html__( 'Page Builder is activated' , $this->name ) :  esc_html__( 'Page Builder is not activated' , $this->name );

		return $page_builder_data;

	}


	public function get_custom_data() {
		$this->phtpb_config_data_js = PeHaa_Themes_Page_Builder::$phtpb_config_data_js;
	}

	/**
	 * Register the stylesheets for the admin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$this->add_page_builder_scripts_and_styles = $this->render_page_builder || apply_filters( 'phtpb_add_page_builder_scripts_and_styles', $this->render_page_builder );

		if ( !$this->add_page_builder_scripts_and_styles ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/screen.dev.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		$this->add_page_builder_scripts_and_styles = $this->render_page_builder || apply_filters( 'phtpb_add_page_builder_scripts_and_styles', $this->render_page_builder );

		if ( !$this->add_page_builder_scripts_and_styles ) {
			return;
		}

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
		wp_enqueue_script( 'backbone' );
		wp_enqueue_script( 'jquery-ui-datepicker' );

		$api_key_query = $this->gmaps_api_key ? 'key=' . $this->gmaps_api_key .'&' : '';

		$protocol = is_ssl() ? 'https' : 'http';
		$gmaps_url = $protocol . '://maps.googleapis.com/maps/api/js?' . $api_key_query . 'callback=initialize';

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/phtpb-admin.min.js', array( 'jquery', 'jquery-ui-core', 'underscore', 'jquery-ui-sortable', 'jquery-ui-droppable', 'backbone', 'wp-color-picker', 'jquery-ui-datepicker' ), $this->version, true );

		$save_to = ( $this->save_to_content && !$this->meta_content ) ? 'content' : 'phtpb_secondeditor';
		$save_to = apply_filters( 'phtpb_save_to', $save_to );

		wp_localize_script(
			'jquery',
			'phtpb_data',
			array(
				'save_to' => esc_attr( $save_to ),
				'elements' => $this->phtpb_config_data_js,
				'gmaps_url' => $gmaps_url,
				'is_always_active' => ( boolean ) apply_filters( 'phtpb_is_always_active', false ),
				'confirmation' => esc_html__( 'Your content will be modified. You have probably altered the shortcodes syntax.  We will reestablish it properly.', $this->name ),
				'rmv_img' => esc_html__( 'Remove image', $this->name ),
				'gmaps_auth_nonce' => wp_create_nonce( $this->gmaps_auth_action ),
				'gmaps_auth_action' => $this->gmaps_auth_action,
				'gmaps_key_missing' => !$this->gmaps_api_key,
				'gmaps_auth_failed' => !$this->gmaps_api_key && get_transient( $this->gmaps_auth_transient )

			)
		);

	}


	/**
	 *  Add Page Builder Meta Box
	 *
	 * @since    1.0.0
	 */
	public function add_meta_box( $post_type ) {

		if ( !$this->render_page_builder ) {
			return;
		}

		add_meta_box(
			'phtpb',
			__( 'Page Builder', $this->name ),
			array( $this, 'render_page_builder_meta_box' ),
			$post_type,
			'normal',
			'high'
		);

		
		add_meta_box(
			'phtpb_state_mb',
			__( 'PeHaa Themes Page Builder state', $this->name ),
			array( $this, 'render_meta_box_content' ),
			$post_type,
			'normal',
			'high'
		);

	}

	public function render_page_builder_meta_box() {

		self::render_page_builder();
		echo $this->meta_box_footer();
	}

	public static function render_page_builder() {
		
		do_action( 'phtpb_before_page_builder' );

		echo '<div class="phtpb_preloader phtpb_h-align--center pht-transition"><i class="fa fa-cog fa-spin"></i></div>';

		echo '<div id="phtpb_hidden_editor" class="phtpb_hidden_editor">';

		wp_editor( '', 'phtpb_content_new', array( 'media_buttons' => true ) );

		echo '</div><!-- #phtpb_hidden_editor -->';

		echo '<div id="phtpb_main_container" class="phtpb_main_container pht-transition"></div>';

		self::add_templates();

		do_action( 'phtpb_after_page_builder' );
	}

	private static function add_templates() {

		$templates = new PeHaa_Themes_Page_Builder_MB_Templates();
		$templates->phtpb_app_template();
		$templates->phtpb_section_template();
		$templates->phtpb_row_template();
		$templates->phtpb_column_template();
		$templates->phtpb_module_template();
		$templates->phtpb_modal_template();
		$templates->phtpb_columns_modal_template();
		$templates->phtpb_modal_templates();
		$templates->phtpb_all_modules_modal_template();
		$templates->phtpb_gmaps_send_to_settings_modal_template(); 
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		wp_nonce_field( 'phtpb_inner_custom_box', 'phtpb_inner_custom_box_nonce' );
		$value = $this->state ? 'yes' : 'no';?>
    	<p>
        <label for="phtpb_state-no">
            <input type="radio" name="phtpb_state" id="phtpb_state-no" value="no" <?php checked( $value, 'no' ); ?>>
            <?php _e( 'Not active', $this->name )?>
        </label>
        <label for="phtpb_state-yes">
            <input type="radio" name="phtpb_state" id="phtpb_state-yes" value="yes" <?php checked( $value, 'yes' ); ?>>
            <?php _e( 'Active', $this->name )?>
        </label>
   
</p>

	<?php }

	private function meta_box_footer() {

		$return_string = '<footer class="phtpb_mb_footer">';
		$return_string .= '<p>' . esc_html__( 'Developed by ', $this->name ) . sprintf( '<a href="%s" title="PeHaa THEMES">PeHaa THEMES</a>', esc_url( 'http://wptemplates.pehaa.com' ) ) .'</p>';
		$return_string .= '<div>' . esc_html__( 'Version: ', $this->name ) . $this->version . '</div>';
		$return_string .= '</footer>';
		return $return_string;
	}

	private function check_content_inconsistency( $post ) {

		if ( $this->save_to_content && !$this->state ) {
			return false;
		}

		if ( $this->save_to_content && $this->meta_content ) {
			return false;
		}

		if ( $this->meta_content && !$this->state ) {
			return true;
		}
		
		$cleaned_meta_content = trim( strip_tags( $this->cleaned_content( $this->meta_content ) ) );
		$content = trim( strip_tags( $post->post_content ) );

		if ( $this->meta_content && ( $content !==  $cleaned_meta_content ) && ( trim( strip_tags( $this->meta_content ) ) !== $content ) ) {
			return true;
		}
			
		return false;
		
	}

	/**
	 *  Add Page Builder Toggle Button
	 *
	 * @since    1.0.0
	 */
	public function add_toggle_button( $post ) {

		if ( !$this->render_page_builder ) {
			return;
		}

		if ( $this->check_content_inconsistency ) { ?>
			
			<div class="phtpb_table">
		
		<?php }
				printf( '<a href="#" id="phtpb_toggle_builder-meta" data-content-source="%7$s" class="js-phtpb_toggle_builder button phtpb_button phtpb_h-align--center %4$s"><span class="phtpb_use_default %6$s">%2$s</span> <span class="phtpb_activate_pb %5$s">%1$s</span> <span class="phtpb_use_pb js-hidden">%3$s</span></a>',
					$this->check_content_inconsistency ? esc_html__( 'Hi! Let\'s restore the PHT Page Builder content.', $this->name ) : esc_html__( 'Hi! Let\'s use PeHaa Themes Page Builder', $this->name ),
					esc_html__( 'Show me the default editor', $this->name ),
					esc_html__( 'Take me back to the Page Builder', $this->name ),
					( $this->is_builder_used ? 'phtpb_builder_is_used' : 'button-primary' ) . ( $this->check_content_inconsistency ? ' phtpb_table--cell' : ''),
					( $this->is_builder_used ? 'js-hidden' : '' ),
					( $this->is_builder_used ? '' : 'js-hidden' ),
					( $this->save_to_content && !$this->meta_content ) ? 'content' : 'phtpb_secondeditor'
				);

			if ( $this->check_content_inconsistency ) { ?>

				<span class="phtpb_table--cell"><?php _e( 'or', $this->name ); ?></span>

				<?php
				printf( '<a href="#" id="phtpb_toggle_builder-content" data-content-source="content" class="js-phtpb_toggle_builder button phtpb_button phtpb_h-align--center phtpb_table--cell %4$s"><span class="phtpb_use_default %6$s">%2$s</span> <span class="phtpb_activate_pb %5$s">%1$s</span> <span class="phtpb_use_pb js-hidden">%3$s</span></a>',
					esc_html__( 'Hi! Let\'s start with your current content.', $this->name ),
					esc_html__( 'Show me the default editor', $this->name ),
					esc_html__( 'Take me back to the PHT Page Builder', $this->name ),
					( $this->is_builder_used ? 'phtpb_builder_is_used' : 'button-primary' ),
					( $this->is_builder_used ? 'js-hidden' : '' ),
					( $this->is_builder_used ? '' : 'js-hidden' )
				); ?>

			</div>

		<?php }

	}

	/**
	 *  Editor wrap
	 *
	 * @since    1.0.0
	 */
	public function open_editor_wrap( $post ) {

		if ( !$this->render_page_builder ) {
			return;
		}

		if ( ! in_array( $post->post_type, $this->phtpb_post_types ) ) {
			return;
		}
		printf( '<div id="phtpb_main_editor_wrap" class="%s">',
			$this->is_builder_used ? 'phtpb_hidden phtpb_activated' : 'phtpb_not_activated'
		);
	}

	public function my_second_editor( $post ) {

		if ( $this->save_to_content && !$this->meta_content ) {
			return;
		}
 
		if ( !$this->render_page_builder ) {
			return;
		}
		
		$content = apply_filters( 'pht_meta_content_into_editor', $this->meta_content );
		
		wp_editor( $content, 'phtpb_secondeditor' );
	}

	public function replace_preview_img_srcs( $content ) {
		$new_meta_content = preg_replace_callback('/background_image=\"([^\"]*)\"([^\[]*)(phtpb_id=\"(\d+)\")/', array( $this, 'preview_thumbnails_fix' ), $content );
		return $new_meta_content;
	}

	private function preview_thumbnails_fix( $matches ) { 
		$new_img = wp_get_attachment_image_src( (int) $matches[4], 'thumbnail' );
		if ( $new_img ) {
			return 'background_image="'.$new_img[0].'"'.$matches[2].$matches[3];
		} else {
			return $matches[2];
		}
	}

	public function close_editor_wrap( $post ) {

		if ( !$this->render_page_builder ) return;
		echo '</div> <!-- #phtpb_main_editor_wrap -->';

	}

	public function store_meta_fields_revision( $post_id ) {

		if ( $this->save_to_content ) {
			return;
		}

		$parent_id = wp_is_post_revision( $post_id );

		if ( $parent_id ) {

			if ( !$this->check_for_pagebuilder_by_id( $parent_id ) ) {
				return;
			}

			$my_meta = get_post_meta( $parent_id, PeHaa_Themes_Page_Builder::$meta_field_name_content, true );
			if ( false !== $my_meta ) {
				add_metadata( 'post', $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_content, $my_meta );
			}
				
			$my_meta_state = get_post_meta( $parent_id, PeHaa_Themes_Page_Builder::$meta_field_name_state, true );
			if ( false !== $my_meta_state ) {
				add_metadata( 'post', $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_state, $my_meta_state );
			}
				
		}

	}


	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int     $post_id The ID of the post being saved.
	 */
	public function save_meta_fields( $post_id ) {

		if ( ! isset( $_POST['phtpb_inner_custom_box_nonce'] ) ) {
			return $post_id;
		}
			
		$nonce = $_POST['phtpb_inner_custom_box_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'phtpb_inner_custom_box' ) ) {
			return $post_id;
		}
			
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
			
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$chk = isset( $_POST['phtpb_state'] ) && 'yes' === $_POST['phtpb_state'] ? 'yes' : 'no';		
		update_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_state, $chk );

		if ( wp_is_post_revision( $post_id ) &&  isset( $_POST['wp-preview'] ) && 'dopreview' === $_POST['wp-preview'] )  {
			return;
		}

		if ( $this->save_to_content ) {
			update_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_content, '' );
			return;
		}

		if ( isset( $_POST['phtpb_secondeditor'] ) ) {
			$meta_content = $_POST['phtpb_secondeditor'];
			update_post_meta( $post_id, PeHaa_Themes_Page_Builder::$meta_field_name_content, $meta_content );
		}

	}

	/**
	 * Remove all shortcodes, leave what is embraced by [phtpb_text].
	 *
	 * @since    1.0.0
	 */

	private function cleaned_content( $content ) {

		if ( $this->save_to_content ) {
			return $content;
		}

		$tagregexp = 'phtpb_text';
		$pattern =
		  '\\['                              // Opening bracket
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "($tagregexp)"                     // 2: Shortcode name
		. '(?![\\w-])'                       // Not followed by word character or hyphen
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		. ')'
		. '(?:'
		.     '(\\/)'                        // 4: Self closing tag ...
		.     '\\]'                          // ... and closing bracket
		. '|'
		.     '\\]'                          // Closing bracket
		.     '(?:'
		.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.         ')'
		.         '\\[\\/\\2\\]'             // Closing shortcode tag
		.     ')?'
		. ')'
		. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]


		preg_match_all( "/$pattern/s", $content, $output_array );

		if ( isset( $output_array[5] ) && count( $output_array[5] ) ) {
			$content = implode( '' , $output_array[5] );
		} else {
			$content = '';
		}

		return $content;

	}

	public function phtpb_content_save_pre( $content ) {

		global $post;
		
		if ( ! $post ) {
			return $content;
		}

		if ( ! $this->check_for_pagebuilder_by_id( $post->ID ) ) {
			return $content;
		}

		if ( isset( $_POST['wp-preview'] ) && 'dopreview' === $_POST['wp-preview'] ) {
			return $content;
		} 

		if ( false === strpos( $content, '[phtpb_section' ) ) {
			return $content;
		}

		if ( isset( $_GET['action'] ) && 'restore' === $_GET['action'] ) {
			return $content;
		}
		
		return $this->cleaned_content( $content );

	}

	public function save_gmaps_auth_failure_to_transient() {

		check_ajax_referer( $this->gmaps_auth_action, 'nonce' );
		set_transient( $this->gmaps_auth_transient, true, 60*60*24 );
	
	}

	public function gmaps_auth_failed_warning( $output ) {

		if ( current_user_can( 'manage_options' ) ) {
			
			if ( !$this->gmaps_api_key && get_transient( $this->gmaps_auth_transient ) ) {
				$output .= '<div class="pht-box pht-underline-links pht-milli pht-white phtpb_admin-warning" style="position:absolute; top:12px; left:12px; max-width:480px; background:rgba(255,0,0,.85);">';
				$output .= esc_html__( 'From June 22, 2016 the Google Maps Javascript API no longer supports keyless access (any request that doesn\'t include an API key).', 'phtpb' );
				$output .= '</br>';
				$output .= sprintf( __( 'To get started using the Google Maps JavaScript API follow <a class="" href="%s" target="_blank">this link.</a>', 'phtpb' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' );
				$output .= '</br>';
				$output .= sprintf( __( 'Once you have got your Google Maps JavaScript API key generated - all you have to do is to <a class="" href="%s"><strong>paste it into the "Google Maps Api Key" field in the page builder settings.</strong></a>', 'phtpb' ), esc_url( PeHaa_Themes_Page_Builder_Options_Page::settings_url() . '#phtpb-gmaps-auth' ) );
				$output .= '</div>';
			}
		}
		
		return $output;
	}

}