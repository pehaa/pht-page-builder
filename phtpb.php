<?php
/**
 *
 * @link              http://github.com/pehaa/pht-page-builder
 * @since             1.0.0
 * @package           PeHaa_Themes_Page_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       PeHaa Themes Page Builder
 * Plugin URI:        http://github.com/pehaa/pht-page-builder
 * Description:       Back-end drag and drop page builder.
 * Version:           3.3.3
 * Author:            PeHaa THEMES
 * Author URI:        http://wptemplates.pehaa.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phtpb
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PHTPB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PHTPB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PHTPB_PLUGIN_ABSOLUTE_PATH', __FILE__ );

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-phtpb-activator.php';
/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-phtpb-deactivator.php';

/** This action is documented in includes/class-phtpb-activator.php */
register_activation_hook( __FILE__, array( 'PeHaa_Themes_Page_Builder_Activator', 'activate' ) );

/** This action is documented in includes/class-phtpb-deactivator.php */
register_deactivation_hook( __FILE__, array( 'PeHaa_Themes_Page_Builder_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-phtpb.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_phtpb() {

	$plugin = new PeHaa_Themes_Page_Builder();
	$plugin->run();


}
run_phtpb();

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'http://wp-plugins.pehaa.com/pht-page-builder/metadata.json',
    __FILE__,
    'phtpb'
);