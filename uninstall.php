<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_name = 'phtpb_options';

delete_option( $option_name );

// For site options in multisite
delete_site_option( $option_name ); 

delete_transient( 'phtpb_gm_auth_failed' );

delete_metadata( 'post', null, '_phtpb_state_meta_value_key', null, true );
delete_metadata( 'post', null, '_phtpb_meta_content', null, true );