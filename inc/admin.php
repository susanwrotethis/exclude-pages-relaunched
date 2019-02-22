<?php

// Exit if loaded from outside of WP
if ( !defined( 'ABSPATH' ) ) exit;

// META BOX DISPLAY FUNCTIONS BEGIN HERE /////////////////////////////////////////////////
// Add meta box to page editor
function swt_epr_add_meta_box()
{
	add_meta_box( 'swt_epr_exclude_box', __( 'Exclude Page', 'swt-epr' ), 'swt_epr_display_meta_box', 'page', 'side' );
}
add_action( 'add_meta_boxes', 'swt_epr_add_meta_box' );

// Display meta box in page editor
function swt_epr_display_meta_box( $post )
{
	wp_nonce_field( 'swt_epr_update_page_exclude', 'swt_epr_excludes_nonce' );
	
	$metavalue = get_post_meta( $post->ID, 'swt_epr_exclude_this', true );
	$checked = '';
	if ( !empty( $metavalue ) && '1' === $metavalue ) {
		$checked = 'checked="checked" ';
	}
	else {
		$metavalue = '';
	}
	
	$text = esc_html__( 'Exclude from navigation', 'swt-epr' );
	echo '<p><label><input id="swt_epr_exclude_this" name="swt_epr_exclude_this" type="checkbox" value="1" '.$checked.'/> '.$text.'</label>';
	echo '<input id="swt_epr_exclude_meta" name="swt_epr_exclude_meta" type="hidden" value="'.$metavalue.'" /></p>';
	
	do_action( 'swt_epr_display_meta_box', $post, $metavalue );
}

// META BOX UPDATE FUNCTIONS BEGIN HERE //////////////////////////////////////////////////
// Update postmeta value and transient on save. Returns without action if unchanged.
function swt_epr_update_postmeta( $post_id )
{
	// Return if post type is not one of the accepted types
	$post_types = array( 'page' );
	$post_types = apply_filters( 'swt_epr_excludes_save_post_types', $post_types, $post_id );
	if ( !in_array( get_post_type( $post_id ), $post_types ) ) {
		return;
	}
	
	// Return if nonce is not set or not verified
	if ( !isset( $_POST['swt_epr_excludes_nonce'] ) || !wp_verify_nonce( $_POST['swt_epr_excludes_nonce'], 'swt_epr_update_page_exclude' ) ) {
		return;
	}

	// Get old and new postmeta values; return if no change
	$oldvalue = ( isset( $_POST['swt_epr_exclude_meta'] ) && '1' === $_POST['swt_epr_exclude_meta'] ? '1' : '' );
	$newvalue = ( isset( $_POST['swt_epr_exclude_this'] ) && '1' === $_POST['swt_epr_exclude_this'] ? '1' : '' );
	if ( $oldvalue === $newvalue ) {
		return;
	}
	
	// Add or delete meta, then update transient
	if ( '1' === $newvalue ) {
		add_post_meta( $post_id, 'swt_epr_exclude_this', $newvalue, true );	
	}
	else {
		delete_post_meta( $post_id, 'swt_epr_exclude_this' );
	}
	swt_epr_set_transient();
	
	// Action hook lets us apply exclusion to other plugins (e.g., Exclude from Search)
	do_action( 'swt_epr_update_postmeta', $post_id, $newvalue, $oldvalue );
}
add_action( 'save_post', 'swt_epr_update_postmeta' );