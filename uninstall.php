<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

function swt_epr_switch_and_delete( $blog, $meta_key, $transient )
{
	global $wpdb;
	
	switch_to_blog( $blog );
	$wpdb->delete( $wpdb->prefix.'postmeta', array( 'meta_key' => $meta_key ), array( '%s' ) );
	delete_transient( $transient );
	restore_current_blog();
}

function swt_epr_uninstall_plugin()
{
	global $wpdb;
	
	$meta_key = 'swt_exclude_this';
	$transient = 'swt_excluded_ids';

	if ( !is_multisite() ) {

		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => $meta_key ) );
		delete_transient( $transient );
		return;

	} else {

		$offset = 0;
		while ( $offset > -1 ) {

			$blogs = get_sites( array( 'fields' => 'ids', 'offset' => $offset ) );

			if ( !$blogs ) {

				return;

			} else {

				foreach ( $blogs as $blog ) {

					swt_epr_switch_and_delete( $blog, $meta_key, $transient );

				}
				$offset = $offset + count( $blogs );
			}
		} // Do loop
	} // End check for multisite
}
swt_epr_uninstall_plugin();