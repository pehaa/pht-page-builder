<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 */

include('class-phtpb-add-shortcode.php');

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 * @author PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder_Public {

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
	 * Should the page builder scripts and styles be enqueued.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $render_page_builder = false;

	/**
	 * Mobile device detection.
	 *
	 * @since    1.0.0
	 * @var      boolean    $version    The current version of this plugin.
	 */
	public static $is_mobile = false;

	/**
	 * The content width value.
	 *
	 * @since    1.0.0
	 * @var      int    $content_width   The content width value.
	 */
	public static $content_width = 1140;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {
		
		$this->name = $name;
		$this->version = $version;
		$this->settings = PeHaa_Themes_Page_Builder::$settings;
		self::$is_mobile = apply_filters( $this->name . '_is_mobile', wp_is_mobile() );
		
	}

	/**
	 * Set the content width.
	 *
	 * @since    1.0.0
	 */
	public static function get_content_width() {

		$theme_content_width = (int) apply_filters( 'phtpb_content_width', self::$content_width );
		if ( $theme_content_width ) {
			self::$content_width = $theme_content_width;
		}

	}

	public function check_post_for_pagebuilder() {

		$id = get_queried_object_id();
		if ( !isset( $id ) ) {
			return;
		}
		$post_type = get_post_type( $id );
		if ( !$post_type ) {
			return;
		}
		$this->render_page_builder = $this->check_for_pagebuilder_by_id( $id, $post_type );

	}

	private function check_for_pagebuilder_by_id( $id = NULL, $post_type = NULL ) {

		if ( !$id ) {
			return;
		}
		if ( !$post_type ) {
			$post_type = get_post_type( $id );
		}
		if ( !array_key_exists( $post_type, PeHaa_Themes_Page_Builder::$phtpb_available_post_types ) ) {
			return false;
		}
		if ( !in_array( $post_type, PeHaa_Themes_Page_Builder::$phtpb_post_types ) ) {
			return false;
		}
		if ( in_array( $id, PeHaa_Themes_Page_Builder::$phtpb_forbidden_ids ) ) {
			return false;
		}
		return true;
	
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( ! $this->render_page_builder ) {
			return;
		}

		if ( apply_filters( $this->name . '_load_public_styles', true, $this->version ) ) {
			wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( ! $this->render_page_builder ) {
			return;
		}

		if ( apply_filters( $this->name . '_load_public_modernizr', true, $this->version ) ) {
			wp_enqueue_script( $this->name . '-modernizr' , plugin_dir_url( __FILE__ ) . 'js/modernizr.js', array(), $this->version, false );
		}

		if ( apply_filters( $this->name . '_load_public_scripts', true, $this->version ) ) {

			wp_enqueue_script( $this->name . '-all', plugin_dir_url( __FILE__ ) . 'js/all.min.js', array( 'jquery', 'jquery-ui-accordion', 'jquery-ui-tabs' ), $this->version, true );
		}

		$api_key_query = isset( $this->settings['gmaps_api_key'] ) && ''!== $this->settings['gmaps_api_key'] ? '&key=' . $this->settings['gmaps_api_key'] : '';
		$protocol = is_ssl() ? 'https' : 'http';
		$gmaps_url = $protocol . '://maps.googleapis.com/maps/api/js?v=3.exp' . $api_key_query . '&callback=phtpb_initialize';
		
		wp_localize_script( 
			apply_filters( $this->name . '_load_public_scripts', true, $this->version ) ? $this->name . '-all' : apply_filters( $this->name . '_theme_script_handler', 'all' ), 
			'phtpb_data', 
			array( 
				'gmaps_url' => $gmaps_url,
			)
		);
	
	}

	/**
	 * List and call shortcodes class for all plugin shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function add_shortcodes() {
 
		$shortcode = new PeHaa_Themes_Page_Builder_Shortcode( 'phtpb_section' );
		$shortcode = new PeHaa_Themes_Page_Builder_Shortcode( 'phtpb_column' );
		$shortcode = new PeHaa_Themes_Page_Builder_Shortcode( 'phtpb_column_inner' );
		$shortcode = new PeHaa_Themes_Page_Builder_Shortcode( 'phtpb_row' );
		$shortcode = new PeHaa_Themes_Page_Builder_Shortcode( 'phtpb_row_inner' );
		
		foreach ( PeHaa_Themes_Page_Builder::$phtpb_config_data as $key => $values ) {
			if ( 'module' !== $values['phtpb_admin_type'] ) {
				continue;
			}
			if ( isset( $values['widget'] ) ) {
				$shortcode = new PeHaa_Themes_Page_Builder_WP_Widget_Shortcode( $key, $values['widget'] );
			} else {
				$shortcode = new PeHaa_Themes_Page_Builder_Shortcode( $key );
			}
		}
	}

	/**
	 * Remove unnecessary empty paragraphs.
	 *
	 * @since    1.0.0
	 * @var      string    $content
	 * @return      string    $content       The post content.
	 */
	public function get_meta_content( $content ) {
		
		global $post;
		
		if ( ! isset( $post->post_type ) || ! isset( $post->ID ) ) {
			return;
		}
		if ( ! in_array( $post->post_type, PeHaa_Themes_Page_Builder::$phtpb_post_types ) ) {
			return $content;
		}
		if ( in_array( $post->ID, PeHaa_Themes_Page_Builder::$phtpb_forbidden_ids ) ) {
			return $content;
		}
		if ( 'yes' !== get_post_meta( $post->ID, PeHaa_Themes_Page_Builder::$meta_field_name_state, true ) ) {
			return $content;
		}
		remove_filter( 'the_content', 'wpautop' );
		
		if ( isset( $_GET['preview'] ) &&  isset( $_GET['preview_id'] ) && isset( $_GET['preview_nonce'] ) ) {
			
			if ( $post->ID === (int) $_GET['preview_id'] && wp_verify_nonce( $_GET['preview_nonce'], 'post_preview_' . $post->ID ) ) {
				return $content;
			}
			
		}

		if ( post_password_required() ) {
			return '<div class="pht-wrapper">' . get_the_password_form() . '</div>';
		}

		return get_post_meta( $post->ID, PeHaa_Themes_Page_Builder::$meta_field_name_content, true );

	}

	/**
	 * Better manage excerpts.
	 *
	 * @since    1.0.0
	 * @var      string    $excerpt
	 * @return      string          The post excerpt.
	 */
	public function get_phtpb_excerpt( $excerpt ) {
		
		global $post;
		if ( !isset( $post->post_type ) || !isset( $post->ID ) ) {
			return;
		}
		if ( !in_array( $post->post_type, PeHaa_Themes_Page_Builder::$phtpb_post_types ) ) {
			return $excerpt;
		}
		if ( in_array( $post->ID, PeHaa_Themes_Page_Builder::$phtpb_forbidden_ids ) ) {
			return $excerpt;
		}
		if ( 'yes' !== get_post_meta( $post->ID, PeHaa_Themes_Page_Builder::$meta_field_name_state, true ) ) {
			return $excerpt;
		}
		return $post->post_excerpt;

	}

	/**
	 * Filter the widget markup.
	 *
	 * @since    1.0.0
	 * @return      string   
	 */
	public function widget_tag() {
		return 'aside';
	}

}