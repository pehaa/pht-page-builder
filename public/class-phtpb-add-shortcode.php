<?php

/**
 * The add_shortcode functionality of the plugin.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 */

include( 'class-phtpb-shortcode-templates.php' );

/**
 * The add_shortcode functionality of the plugin.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 * @author PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder_Shortcode {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	protected $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $wp_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $function_name    The shortcode name.
	 */
	public function __construct( $name, $function_name = NULL ) {

		$this->name = $name;

		if ( $function_name ) {
			$this->function_name = $function_name;
		} else {
			$this->function_name = $name;
		}

		$this->data = PeHaa_Themes_Page_Builder::$phtpb_config_data;
		add_shortcode( $this->name, array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode.
	 *
	 * @since    1.0.0
	 */
	public function render( $atts, $content = NULL ) {

		$this->templates = apply_filters( 'phtpb_shortcode_template', new PeHaa_Themes_Page_Builder_Shortcode_Template( $this->name ), $this->name );
		return $this->templates->getTemplate( $this->function_name, $this->phtpb_shortcode_atts( $atts ), $content ); 
	}
	
	/**
	 * Shortcode atts.
	 *
	 * @since    1.0.0
	 */
	protected function phtpb_shortcode_atts( $atts ) {

		if ( ! isset( $this->data[ $this->name ] ) || ! isset( $this->data[ $this->name ]['fields'] ) ) {
			return $atts;
		}
		
		foreach ( $this->data[ $this->name ]['fields'] as $key => $values ) {
			$sh_attrs[ $key ] = isset( $values['default'] ) ? $values['default'] : '' ;
		}
		if ( isset( $atts['phtpb_width'] ) && 'NaN' === $atts['phtpb_width'] )
			$atts['phtpb_width'] = 1;
		
		$sh_attrs['phtpb_width'] = 1;

		$sh_attrs['herited_color'] = '';
		
		return shortcode_atts( $sh_attrs , $atts, $this->name );
	}

}

/**
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 * @author     PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder_WP_Widget_Shortcode extends PeHaa_Themes_Page_Builder_Shortcode {

	public function __construct( $name, $wp_type ) {

		$this->name = $name;
		$this->wp_type = $wp_type;

		$this->data = PeHaa_Themes_Page_Builder::$phtpb_config_data;
		add_shortcode( $name, array( $this, 'render' ) );

	}
	
	public function render( $atts, $content = NULL ) {
		$args = apply_filters( 'phtpb_wp_widget_args', array(), $this->name );
		return $this->widget_shortcode( $atts, $args ); 
	}
	
	private function widget_shortcode( $atts, $args = array() ) {
		
		$shortcode_atts = $this->phtpb_shortcode_atts( $atts );
		$phtpb_module_id = isset( $shortcode_atts['module_id'] ) && trim( $shortcode_atts['module_id'] ) ? 'id="' . sanitize_title( $shortcode_atts['module_id'] ) . '"' : '';
		$phtpb_module_class = isset( $shortcode_atts['module_class'] ) && trim( $shortcode_atts['module_class'] ) ? esc_attr( $shortcode_atts['module_class'] ) : '';
		if ( isset( $shortcode_atts['margin_b'] ) && 'yes' === $shortcode_atts['margin_b'] ) {
			$phtpb_module_class .= ' pht-mb';
		}
		$output = "<div $phtpb_module_id class='$this->name phtpb_widget phtpb_item $phtpb_module_class'>";
		ob_start();
		the_widget( $this->wp_type, $atts, $args );
		$output .= ob_get_clean();
		$output .= '</div><!-- .phtpb_widget -->';

		return $output;
	}

}