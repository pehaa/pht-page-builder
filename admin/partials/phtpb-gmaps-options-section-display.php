<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/pehaa/pht-page-builder
 * @since      2.9.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?>

<p id="phtpb-gmaps-auth">
	<?php esc_html_e( 'From June 22, 2016 the Google Maps Javascript API no longer supports keyless access (any request that doesn\'t include an API key). Don’t be afraid, getting an API key is fast and simple.', 'phtpb' ); ?>
	<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true&pli=1"><?php esc_html_e( 'Get your Key', 'phtpb' ); ?></a>
</p>
<p><?php printf( __( 'For more details check <a href="%s" target="_blank">this link.</a>', 'phtpb' ), esc_url( 'http://wptemplates.pehaa.com/yaga/documentation/#google-maps-api' ) ); ?></p>