<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/pehaa/pht-page-builder
 * @since      2.9.0
 *
 * @package    PeHaaThemes_Simple_Post_Types
 * @subpackage PeHaaThemes_Simple_Post_Types/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?>

<p id="phtpb-gmaps-auth">
	<?php esc_html_e( 'From June 22, 2016 the Google Maps Javascript API no longer supports keyless access (any request that doesn\'t include an API key). Don’t be afraid, getting an API key is really fast and simple.', $this->name ); ?>
	<a href="https://developers.google.com/maps/documentation/javascript/get-api-key"><?php esc_html_e( 'Get a Key/Authentication', $this->name ); ?></a>
</p>