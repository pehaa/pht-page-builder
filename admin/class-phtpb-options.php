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
	 * Post types available for the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $available_post_types    Post types available for the plugin.
	 */
	private $available_post_types;

  	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $option_name       The options field slug.
	 */
 	public function __construct( $name, $option_name ) {

 		$this->name = $name;
		$this->option_name = $option_name;
		$this->menu_slug = 'phtpb-admin'; 
		$this ->option_group = 'phtpb_option_group';
		$this->page_title = esc_html__( 'PeHaa Themes Page Builder', $this->name ); 
		$this->menu_title = esc_html__( 'PHT Page Builder', $this->name );

		$this->available_post_types = array(
			'post' => esc_html__( 'Posts',  $this->name ),
			'page' => esc_html__( 'Pages',  $this->name )
		);

		$this->sections = array(
			'main' => array(
				'id' => 'phtpb_main',
				'title' => esc_html__( 'Main Settings', $this->name ),
				'callback' => 'main_section_display'
			),
			'gmaps' => array(
				'id' => 'phtpb_gmaps',
				'title' => esc_html__( 'Google Maps Settings', $this->name ),
				'callback' => 'gmaps_section_display'
			)
		);

		add_filter( 'plugin_action_links_' . PHTPB_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
		
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
	 * Adds options page fot the plugin.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_options_page() {
    
    	add_menu_page(
        	$this->page_title,
        	$this->menu_title,
        	'manage_options', 
        	$this->menu_slug,
        	array( $this, 'create_admin_page' )
    	);

    }

	/**
	 * Callback for the add_plugin_options_page(). Displays the options page. Uses Settings API.
	 *
	 * @since    1.0.0
	 */
	public function create_admin_page() {

		$this->options = get_option( $this->option_name ); ?>

		<div class="wrap">

			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<form method="post" action="options.php">

				<?php settings_fields( $this->option_group );

				do_settings_sections( $this->menu_slug );

				submit_button(); ?>

			</form>

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

		$this->available_post_types = apply_filters( $this->name . '_available_post_types', array(
			'post' => esc_html__( 'Posts',  $this->name ),
			'page' => esc_html__( 'Pages',  $this->name )
			) );

		$this->fields[ PeHaa_Themes_Page_Builder::$post_types_field_slug ] = array(
			'title' => esc_html__( 'Post Types', $this->name ),
			'callback' => 'multicheck_callback',
			'sanitize' => 'sanitize_multicheck_post_types',
			'description' => esc_html__( 'Check the postypes that will be edited with PeHaa Themes Page Builder.', $this->name ),
			'section' => $this->sections['main']['id'],
			'options' => $this->available_post_types,
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
				'id' => $key,
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
		if ( !is_array( $this->available_post_types ) ) return $return;
		$available_post_types_keys = array_keys( $this->available_post_types );
		foreach ( $values as $v ) {
			if ( in_array( $v, $available_post_types_keys ) ) {
				$return[] = esc_attr( $v );
			}	
		}
		return $return;
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

		<p><?php esc_html_e( 'This settings is optional. You can still display google maps without an API key.', $this->name ); ?></p>
		<p>
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

		printf( '<input type="text" size="64" maxlength="64" class="regular-text" id="%1$s" name="%2$s" value="%3$s" />',
			esc_attr( $args['id'] ),
			esc_attr( $this->option_name . '[' .$args['id'] . ']' ),
			isset( $this->options[ $args['id'] ] ) ? esc_attr( $this->options[ $args['id'] ]) : '' 
			);
		$this->field_description( $args );
	}

	/**
	 * Prints multicheck fields
	 *
	 * @since    1.0.0
	 * @param array args
	 */
	public function multicheck_callback( $args ) {
	
		if ( ! isset( $this->options[ $args['id'] ] ) ) {
			$this->options[ $args['id'] ] = array();
		}
		foreach ( $args['options'] as $key => $title ) { ?>
			<div>
				<input type="checkbox" id="phtpb_field_<?php echo esc_attr( $key );?>" name="<?php echo esc_attr( $this->option_name . '[' .$args['id'] . ']' ); ?>[]" value="<?php echo esc_attr( $key );?>" <?php checked( in_array( $key, $this->options[ $args['id'] ] ) ) ?> />
				<label for="phtpb_field_<?php echo esc_attr( $key );?>"><?php echo esc_html( $title ); ?></label>
			</div>							
		<?php }
		
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