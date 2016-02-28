<?php
			
/**
 * The options page for the plugin. Uses Settings API.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/admin
 */

/**
 * The options page for the plugin. Uses Settings API.
 *
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themesy_Page_Builder/admin
 * @author     PeHaa THEMES <info@pehaa.com>
 */

class PeHaa_Themes_Page_Builder_Options_Page {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The slug for the database options field.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $option_name    The database slug.
	 */
	private $option_name;


	/**
	 * The options array.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    The options array.
	 */
	private $options;

	/**
	 * The options page menu slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $menu_slug    The options page menu slug.
	 */
	private $menu_slug;

	/**
	 * The settings for user input sections for options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $sections    The user input sections for options page.
	 */
	private $sections;

	/**
	 * The settings for user input fields for options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $fields    The user input fields for options page.
	 */
	private $fields = array();

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    2.5.0
	 *
	 * @var      string
	 */
	private $plugin_screen_hook_suffix = null;

  	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $option_name       The options field slug.
	 */
 	public function __construct( $name, $version, $option_name ) {


 		$this->name = $name;
 		$this->version = $version;
		$this->option_name = $option_name;
		$this->menu_slug = 'phtpb-admin'; 
		$this ->option_group = 'phtpb_option_group';
		$this->page_title = esc_html__( 'PeHaa Themes Page Builder', $this->name ); 
		$this->menu_title = esc_html__( 'PHT Page Builder', $this->name );

		$this->sections = array(
			'main' => array(
				'id' => 'phtpb_main',
				'title' => esc_html__( 'General Settings', $this->name ),
				'callback' => 'main_section_display'
			),
			'gmaps' => array(
				'id' => 'phtpb_gmaps',
				'title' => esc_html__( 'Google Maps Settings', $this->name ),
				'callback' => 'gmaps_section_display'
			),
		);

		add_filter( 'plugin_action_links_' . PHTPB_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
		
	}

	/**
	 * Check if viewing one of this plugin's admin pages.
	 *
	 * @since   2.5.0.
	 *
	 * @return  bool
	 */
	private function viewing_this_plugin() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) )
			return false;

		$screen = get_current_screen();

		if ( !isset( $screen->id ) ) return false;

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffix ) )
			return true;
		else
			return false;
	}
	

	/**
	 * Adds Settings link below the plugin in the plugins.php list.
	 *
	 * @since    1.0.0
	 * @var      array    $links       An array of links.
	 */
	public function add_action_links( $links ) {

		$admin_url = 'admin.php?page=' . $this->menu_slug;
		$title_attr = esc_html__( 'View PeHaa Themes Page Builder Settings', $this->name );

		$mylinks = array(
			'<a href="' . esc_url( admin_url( $admin_url ) ) .'" title="' . esc_attr( $title_attr ) . '">' . esc_html__( 'Settings', $this->name ) . '</a>'
		);
		return array_merge( $links, $mylinks );
	}

	/**
	 * Adds options page for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_options_page() {
    
    	$this->plugin_screen_hook_suffix[] = add_menu_page(
        	$this->page_title,
        	$this->menu_title,
        	'manage_options', 
        	$this->menu_slug,
        	array( $this, 'create_admin_page' )
    	);

    }

    /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_styles() {

		if ( $this->viewing_this_plugin() ) {
			wp_enqueue_style( $this->name . '-options-style', plugin_dir_url( __FILE__ ) . 'css/options.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_scripts() {}

	/**
	 * Callback for the add_plugin_options_page(). Displays the options page. Uses Settings API.
	 *
	 * @since    1.0.0
	 */
	public function create_admin_page() {

		$this->options = get_option( $this->option_name ); ?>

		<div class="wrap">

			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<div class="phtpb-options">

				<form method="post" action="options.php">

					<?php settings_fields( $this->option_group );

					do_settings_sections( $this->menu_slug );

					submit_button(); ?>

				</form>

			</div>

		</div>

	<?php }

	/**
	 * Register settings and add settings sections. Uses Settings API.
	 *
	 * @since    1.0.0
	 */
	public function page_init() {
		
		register_setting(
			$this ->option_group, 
			$this->option_name, 
			array( $this, 'sanitize' ) 
		);

		foreach ( $this->sections as $key => $fields ) {
			add_settings_section(
				$fields['id'],
				$fields['title'],
				array( $this, $fields['callback'], ),
				$this->menu_slug
			);
		}

		$this->add_settings_fields();    
	
	}

	/**
	 * Sets the options fields.
	 *
	 * @since    1.0.0
	 * @access private
	 */
	private function set_fields() {


		$this->fields[ PeHaa_Themes_Page_Builder::$post_types_field_slug ] = array(
			'title' => esc_html__( 'Activated Post Types', $this->name ),
			'callback' => 'multicheck_callback',
			'sanitize' => 'sanitize_multicheck_post_types',
			'description' => esc_html__( 'Make PeHaa Themes Page Builder available for the checked post types.', $this->name ),
			'section' => $this->sections['main']['id'],
			'options' => PeHaa_Themes_Page_Builder::$phtpb_available_post_types,
		);

		$this->fields['deactivation'] = array(
			'title' => esc_html__( 'On Plugin Deactivation ', $this->name ),
			'label' => esc_html__( 'Deactivate on all pages', $this->name ),
			'callback' => 'checkbox_callback',
			'sanitize' => 'sanitize_checkbox',
			'description' => esc_html__( 'This setting affects the re-activation behavior. If "Deactivate on all pages" is checked when you deactivate this plugin, the page builder content will be deactivated on all pages. If you decide to reactivate the plugin, your pages will not display the page builder content. If "Deactivate on all pages" is left unchecked, your pages will display the page builder content after reactivation.', $this->name ),
			'section' => $this->sections['main']['id'],
		);

		$this->fields['save_to_content'] = array(
			'title' => esc_html__( 'Storing', $this->name ),
			'label' => esc_html__( 'Save to Content', $this->name ),
			'callback' => 'checkbox_callback',
			'sanitize' => 'sanitize_checkbox',
			'description' => esc_html__( 'Define how to store the page builder content in the database. If "Save to Content" is not checked, the page builder content is stored as post meta data. Only the content of the Text Modules is retrieved and saved to as the post content. That means that when you uninstall the plugin, ther will be no unrendered shortcodes in your pages. You will not have the page builder layout, but you will keep all the text that you entered into the Text Modules. If you check "Save to Content" the page builder content is saved to the database as the post content.', $this->name ),
			'section' => $this->sections['main']['id'],
		);

		$this->fields['gmaps_api_key'] = array(
			'title' => esc_html__( 'Google Maps Api Key', $this->name ),
			'callback' => 'gmaps_input_callback',
			'sanitize' => 'sanitize_gmaps_api_key',
			'description' => esc_html__( 'Paste your Google Maps Api Key here.', $this->name ),
			'section' => $this->sections['gmaps']['id'],
		);
	
	}

	/**
	 * Adds the options fields.
	 *
	 * @since    1.0.0
	 * @access private
	 */
	private function add_settings_fields() {

		$this->set_fields();
		
		foreach ( $this->fields as $key => $field ) {
			$args = array(
				'title' => isset( $field['title'] ) ? $field['title'] : '',
				'id' => $key,
				'label' => isset( $field['label'] ) ? $field['label'] : '',
				'description' => isset( $field['description'] ) ? $field['description'] : '',
			);
			if ( isset( $field['options'] ) ) {
				$args['options'] = $field['options'];
			}
			add_settings_field(
				$key,
				$field['title'],
				array( $this, $field['callback'] ),
				$this->menu_slug, // Page
				$field['section'], // Section
				$args
			);
		}		

	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @since    1.0.0
	 * @param array $input Contains all settings fields as array keys
	 * @return array sanitized input
	 */
	public function sanitize( $input ) {
		
		$new_input = array();

		foreach ( $input as $key=> $value ) {
		
			if ( ! isset( $this->fields[ $key ] ) ) continue;
			
			if ( isset( $this->fields[ $key ]['sanitize'] ) ) {
				$new_input[ $key ] = call_user_func( array( $this, $this->fields[ $key ]['sanitize'] ), $value );
			} else { 
				$new_input[ $key ] = esc_attr( $value );
			} 					
		}

    	return $new_input;
  
	}

	/**
	 * Sanitize gmaps api key
	 *
	 * @since    1.0.0
	 * @access private
	 * @param string $value
	 * @return string sanitized input
	 */
	private function sanitize_gmaps_api_key( $value ) {

		$value = preg_replace( '/[^0-9a-zA-Z\-_]/', '', $value );
		return substr( $value, 0, 64 );

	}
	
	/**
	 * Sanitize multicheck data (post types)
	 *
	 * @since    1.0.0
	 * @access private
	 * @param array $values
	 * @return array sanitized input
	 */
	private function sanitize_multicheck_post_types( $values ) {

		$return = array();
		if ( !is_array( PeHaa_Themes_Page_Builder::$phtpb_available_post_types ) ) {
			return $return;
		}
		$available_post_types_keys = array_keys( PeHaa_Themes_Page_Builder::$phtpb_available_post_types );
		foreach ( $values as $v ) {
			if ( in_array( $v, $available_post_types_keys ) ) {
				$return[] = esc_attr( $v );
			}	
		}
		return $return;
	}

	/**
	 * Sanitize checkbox
	 *
	 * @since    2.4.0
	 * @access private
	 * @param array $values
	 * @return array sanitized input
	 */
	private function sanitize_checkbox( $values ) {

		if ( 'yes' !== $values ) {
			return;
		}

		return $values;
	}

	/**
	 * Displays section content
	 *
	 * @since    1.0.0
	 */
	public function main_section_display() {}

	/**
	 * Displays section content
	 *
	 * @since    1.0.0
	 */
	public function gmaps_section_display() { ?>

		<p><?php esc_html_e( 'This settings is optional. You can still display google maps without an API key.', $this->name ); ?>
			<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key"><?php esc_html_e( 'Learn more about Google Maps JavaScript API', $this->name ); ?></a>
		</p>

	<?php }


	/**
	 * Prints input fields
	 *
	 * @since    1.0.0
	 * @param array args
	 */
	public function text_input_callback( $args ) {

		printf( '<input id="%1$s" type="text" class="regular-text" name="%2$s" value="%3$s" />',
			esc_attr( $args['id'] ),
			esc_attr( $this->option_name . '[' .$args['id'] . ']' ),
			isset( $this->options[ $args['id'] ] ) ? esc_attr( $this->options[ $args['id'] ]) : '' 
			);
		$this->field_description( $args );
	}


	/**
	 * Prints input fields
	 *
	 * @since    1.0.0
	 * @param array args
	 */
	public function gmaps_input_callback( $args ) {
		echo '<div class="phtpb-options__fields">';
		printf( '<input type="text" size="64" maxlength="64" class="regular-text" id="%1$s" name="%2$s" value="%3$s" />',
			esc_attr( $args['id'] ),
			esc_attr( $this->option_name . '[' .$args['id'] . ']' ),
			isset( $this->options[ $args['id'] ] ) ? esc_attr( $this->options[ $args['id'] ]) : '' 
			);
		echo '</div>';
		$this->field_description( $args );
	}

	/**
	 * Prints multicheck fields
	 *
	 * @since    1.0.0
	 * @param array args
	 */
	public function multicheck_callback( $args ) {

		if ( false === $this->options ) {
			$this->options = array();
			$this->options[ $args['id'] ] = array( 'page' );
		} elseif ( ! isset( $this->options[ $args['id'] ] ) ) {
			$this->options[ $args['id'] ] = array();
		}
		echo '<div class="phtpb-options__fields">';
		foreach ( $args['options'] as $key => $title ) { ?>
			<div class="phtpb-options__multicheck">
				<input type="checkbox" id="phtpb_field_<?php echo esc_attr( $key );?>" name="<?php echo esc_attr( $this->option_name . '[' .$args['id'] . ']' ); ?>[]" value="<?php echo esc_attr( $key );?>" <?php checked( in_array( $key, $this->options[ $args['id'] ] ) ) ?> />
				<label for="phtpb_field_<?php echo esc_attr( $key );?>"><?php echo esc_html( $title ); ?></label>
			</div>							
		<?php }
		echo '</div>';
		$this->field_description( $args );
		
	}

	/**
	 * Prints checkbox field
	 *
	 * @since    2.4.0
	 * @param array args
	 */
	public function checkbox_callback( $args ) {

		?>
		<div class="phtpb-options__fields">
			<input type="checkbox" id="phtpb_field_<?php echo esc_attr( $args['id'] );?>" name="<?php echo esc_attr( $this->option_name . '[' .$args['id'] . ']' ); ?>" value="yes" <?php checked( isset( $this->options[ $args['id'] ] ) && 'yes' === $this->options[ $args['id'] ] ); ?> />
			<label for="phtpb_field_<?php echo esc_attr( $args['id'] );?>"><?php echo esc_html( $args['label'] ); ?></label>
		</div>							
		<?php 
		
		$this->field_description( $args );
		
	}

	

	/**
	 * Prints field description
	 *
	 * @since    1.0.0
	 * @param array args
	 */
	private function field_description( $args ) {
		if ( isset( $args['description'] ) ) {
			printf( '<div class="phtpb_description"><small>%s</small></div>', esc_html( $args['description'] ) );
		}
	}

}