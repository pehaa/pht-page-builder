<?php

/**
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/includes
 */

/**
 * The core plugin class.
 *
 *
 * @since      1.0.0
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/includes
 * @author     PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PeHaa_Themes_Page_Builder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $option_name = 'phtpb_options';
	protected static $config_data = null;

	static $phtpb_available_post_types = array();
	static $phtpb_config_data = array();
	static $phtpb_config_data_js = array();
	static $meta_field_name_content = '_phtpb_meta_content';
	static $meta_field_name_state = '_phtpb_state_meta_value_key';
	static $post_types_field_slug = 'post_types';
	static $phtpb_post_types = array( 'page' );
	static $phtpb_forbidden_ids = array();
	static $settings;
	static $defaults = array(
		'bg_color' => '#fff',
		'color' => '#303030'
	);
	static $showcases_array = array();
	
	/**
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( ! defined( 'PHTPB_PLUGIN_INCLUDES_DIR' ) ) {
			define( 'PHTPB_PLUGIN_INCLUDES_DIR', plugin_dir_path( __FILE__ ) );
		}

		$this->plugin_name = 'phtpb';
		$this->version = '2.7.0';

		$this->load_dependencies();
		$this->set_locale();	

		add_action( 'after_setup_theme', array( $this, 'get_available_phtpb_post_types' ) );

		self::$settings = get_option( $this->option_name );
		$this->get_phtpb_post_types();

		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		// priority = 1 to be hooked before config_data 
		add_action( 'init', array( $this, 'defaults' ), 1 );
		// priority = 2 to be hooked after widgets_init that has priority = 1
		add_action( 'init', array( $this, 'pehaathemes_theme_compability' ), 2 );
		add_action( 'init', array( $this, 'config_data' ), 2 );
		add_action( 'init', array( $this, 'get_forbidden_ids' ), 2 );
		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - PeHaa_Themes_Page_Builder. Orchestrates the hooks of the plugin.
	 * - PeHaa_Themes_Page_Builder_i18n. Defines internationalization functionality.
	 * - PeHaa_Themes_Page_Builder_Admin. Defines all hooks for the dashboard.
	 * - PeHaa_Themes_Page_Builder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-phtpb-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-phtpb-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-phtpb-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-phtpb-mb-templates.php';

		/**
		 * The class responsible for the plugin options page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-phtpb-options.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-phtpb-public.php';

		$this->loader = new PeHaa_Themes_Page_Builder_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the PeHaa_Themes_Page_Builder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PeHaa_Themes_Page_Builder_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new PeHaa_Themes_Page_Builder_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_option_name() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'get_custom_data' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'check_post_for_pagebuilder', 10, 2 );

		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'add_toggle_button' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'open_editor_wrap' );
		$this->loader->add_action( 'edit_form_after_editor', $plugin_admin, 'my_second_editor' );
		$this->loader->add_action( 'edit_form_after_editor', $plugin_admin, 'close_editor_wrap' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_fields' );

		$this->loader->add_filter( 'content_save_pre', $plugin_admin, 'phtpb_content_save_pre', 9, 1 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'store_meta_fields_revision', 11 );

		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_box' );


		$this->loader->add_action( 'wp_restore_post_revision', $plugin_admin, 'restore_post_revision', 10, 2 );
		$this->loader->add_filter( '_wp_post_revision_fields', $plugin_admin, 'phtpb_revision_fields' );
		$this->loader->add_filter( '_wp_post_revision_field_' . self::$meta_field_name_content, $plugin_admin, 'phtpb_revision_field', 10, 3 );
		$this->loader->add_filter( '_wp_post_revision_field_' . self::$meta_field_name_state, $plugin_admin, 'phtpb_revision_field_state', 10, 3 );

		$plugin_options = new PeHaa_Themes_Page_Builder_Options_Page( $this->get_plugin_name(), $this->get_version(), $this->get_option_name() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_options, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_options, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_options, 'add_plugin_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_options, 'page_init' );

		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new PeHaa_Themes_Page_Builder_Public( $this->get_plugin_name(), $this->get_version(), $this->get_option_name() );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'check_post_for_pagebuilder', 1 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 11 );
		$this->loader->add_action( 'init', $plugin_public, 'get_content_width' );
		$this->loader->add_action( 'init', $plugin_public, 'add_shortcodes' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'get_phtpb_content', 1 );
		$this->loader->add_filter( 'get_the_excerpt', $plugin_public, 'get_phtpb_excerpt' );
		$this->loader->add_filter( 'pht-simple-widget-areas_widget_tag', $plugin_public, 'widget_tag' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The options field slug.
	 */
	public function get_option_name() {
		return $this->option_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function defaults() {
		self::$defaults = apply_filters( $this->plugin_name . '_defaults', self::$defaults );
	}

	public function config_data() {

		require_once PHTPB_PLUGIN_INCLUDES_DIR . 'phtpb-config.php';
		self::$phtpb_config_data = $phtpb_config_data;
		self::$phtpb_config_data_js = $phtpb_config_data_for_js;

	}

	public function get_available_phtpb_post_types() {

		self::$phtpb_available_post_types = array(
			'post' => esc_html__( 'Posts', $this->plugin_name ),
			'page' => esc_html__( 'Pages', $this->plugin_name ),
		);

		if ( class_exists( 'PeHaaThemes_Simple_Post_Types' ) && isset( PeHaaThemes_Simple_Post_Types::$options['data']['post_type'] ) ) {
			foreach ( PeHaaThemes_Simple_Post_Types::$options['data']['post_type'] as $key => $post_type_array ) {
				self::$phtpb_available_post_types[ $key ] = $post_type_array['name'];
			}
			
		}
		self::$phtpb_available_post_types = apply_filters( 'phtpb_available_post_types', self::$phtpb_available_post_types );

	}

	private function get_phtpb_post_types() {

		if ( false === self::$settings ) {
			return;
		}

		if ( isset( self::$settings[ self::$post_types_field_slug ] ) && is_array( self::$settings[ self::$post_types_field_slug ] ) ) {
			self::$phtpb_post_types = self::$settings[ self::$post_types_field_slug ];
		} elseif ( isset( self::$settings ) ) {
			self::$phtpb_post_types = array();
		}
		
	}

	public function get_forbidden_ids() {

		$phtpb_forbidden_ids = array();

		$page_for_posts = get_option( 'page_for_posts' );
		
		if ( $page_for_posts ) {
			$phtpb_forbidden_ids[] = $page_for_posts;
		}

		if ( class_exists( 'woocommerce' ) ) {

			$woocommerce_pages = array(
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_pay_page_id',
				'woocommerce_thanks_page_id',
				'woocommerce_myaccount_page_id',
				'woocommerce_edit_address_page_id',
				'woocommerce_view_order_page_id',
				'woocommerce_terms_page_id'
			);

			foreach ( $woocommerce_pages as $woocommerce_page ) {
				$wp_id = get_option( $woocommerce_page );
				if ( $wp_id ) {
					$phtpb_forbidden_ids[] = $wp_id;
				}
			}

		}

		self::$phtpb_forbidden_ids = apply_filters( $this->plugin_name . '_forbidden_ids', $phtpb_forbidden_ids );

	}


	public function pehaathemes_theme_compability() {

		if ( !class_exists( 'PeHaaThemes_Theme_Start' ) ) {
			return;
		}
		if ( !isset( PeHaaThemes_Theme_Start::$theme_name ) || !isset( PeHaaThemes_Theme_Start::$theme_version ) ) {
			return;
		}
		add_filter( 'phtpb_config_data', array( $this, 'pehaathemes_compat_phtpb_config_data' ) );
	}

	public function pehaathemes_compat_phtpb_config_data( $config_data ) {

		if ( !class_exists( 'PeHaaThemes_Theme_Start' ) ) {
			return;
		}
		if ( !isset( PeHaaThemes_Theme_Start::$theme_name ) || !isset( PeHaaThemes_Theme_Start::$theme_version ) ) {
			return;
		}

		if ( 'Yaga' === PeHaaThemes_Theme_Start::$theme_name && version_compare( PeHaaThemes_Theme_Start::$theme_version, '2.0', '<' ) ) {
			unset( $config_data['phtpb_countdown'] );
			unset( $config_data['phtpb_img_carousel'] );
		}

		if ( 'Yaga' === PeHaaThemes_Theme_Start::$theme_name && version_compare( PeHaaThemes_Theme_Start::$theme_version, '2.0.4', '<' ) ) {
			unset( $config_data['phtpb_inline_images'] );
			unset( $config_data['phtpb_timetable'] );
		}

		return $config_data;

	}

}