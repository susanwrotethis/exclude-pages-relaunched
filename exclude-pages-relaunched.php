<?php
/*
Plugin Name: Exclude Pages Relaunched
Plugin URI: https://github.com/susanwrotethis/exclude-pages-relaunched
GitHub Plugin URI: https://github.com/susanwrotethis/exclude-pages-relaunched
Description: Adds a checkbox to the page editor which you can check to exclude pages from page lists and menus. Inspired by the original Exclude Pages plugin by Simon Wheatley but rewritten for extensibility.
Version: 1.0
Author: Susan Walker
Author URI: https://susanwrotethis.com
License: GPL v2 or later
Text Domain: swt-epr
Domain Path: /lang/
*/

// Exit if loaded from outside of WP
if ( !defined( 'ABSPATH' ) ) exit;

// SCRIPT LOADING AND LANGUAGE SUPPORT SETUP BEGINS HERE /////////////////////////////////
if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ).'inc/admin.php' );
} else {
	require_once( plugin_dir_path( __FILE__ ).'inc/front-end.php' );
}

// Load plugin textdomain
function swt_epr_load_textdomain()
{
  load_plugin_textdomain( 'swt-epr', false, dirname( plugin_basename( __FILE__ ) ).'/lang/' );
}
add_action( 'plugins_loaded', 'swt_epr_load_textdomain' );

// TRANSIENT MANAGEMENT FUNCTIONS BEGIN HERE /////////////////////////////////////////////
// Retrieve excluded pages ids and store query results in transient.
function swt_epr_set_transient()
{
	global $wpdb;
	$excluded_ids = $wpdb->get_col( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'swt_epr_exclude_this' and meta_value = '1'" );
	
	// Action hook lets us apply exclusions to other plugins (e.g., Relevanssi, sitemaps)
	set_transient( 'swt_epr_excluded_ids', $excluded_ids );
	do_action( 'swt_epr_set_transient', $excluded_ids );
	return $excluded_ids;
}

// Retrieve array of excluded page ids. 
// Look for transient before executing query. Set transient if not found.
function swt_epr_get_excluded_ids()
{	
	if ( false === $excluded_ids = get_transient( 'swt_epr_excluded_ids' ) ) {
		$excluded_ids = swt_epr_set_transient();
	}
	
	$excluded_ids = maybe_unserialize( $excluded_ids );
	$excluded_ids = apply_filters( 'swt_epr_excluded_ids', $excluded_ids );

	return $excluded_ids;
}