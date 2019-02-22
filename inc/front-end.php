<?php

// Exit if loaded from outside of WP
if ( !defined( 'ABSPATH' ) ) exit;

// FUNCTIONALITY SETUP FUNCTION BEGINS HERE //////////////////////////////////////////////
// Add filters to get_pages and wp_get_nav_menu_items. Filters applied to front end only.
// Also filters anything using wp_list_pages, which uses get_pages.
function swt_epr_add_exclude_filters()
{
	add_filter( 'get_pages', 'swt_epr_exclude_from_get_pages', 10, 2 );
	add_filter( 'wp_get_nav_menu_items', 'swt_epr_exclude_from_nav_menu_items', 10, 3 );	
}
add_action( 'init', 'swt_epr_add_exclude_filters' );

// EXCLUSION FUNCTIONS BEGIN HERE ////////////////////////////////////////////////////////
// Remove excluded pages from get_pages.
function swt_epr_exclude_from_get_pages( $pages, $r )
{
	$excluded_ids = swt_epr_get_excluded_ids();
	$excluded_ids = apply_filters( 'swt_epr_get_pages_excluded_ids', $excluded_ids, $pages, $r );
			
	foreach ( $pages as $key => $page) {
		if ( in_array( $page->ID, $excluded_ids ) ) {
			unset( $pages[$key] );
		}
	}	
	return $pages;
}

// Remove excluded pages from nav_menu_items.
function swt_epr_exclude_from_nav_menu_items( $items, $menu, $args ) 
{
    $excluded_ids = swt_epr_get_excluded_ids();
	$excluded_ids = apply_filters( 'swt_epr_nav_menu_items_excluded_ids', $excluded_ids, $items, $menu, $args );
    
    foreach ( $items as $key => $item ) {
        if ( in_array( $item->object_id, $excluded_ids ) ) {
        	unset( $items[$key] );
        }
    }
    return $items;
}