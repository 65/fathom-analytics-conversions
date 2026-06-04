<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Only deletes plugin data if the user has opted in via the
 * "Delete data on uninstall" setting in the plugin's Advanced section.
 *
 * @link              https://www.fathomconversions.com
 * @since      1.0.9
 *
 * @package    Fathom_Analytics_Conversions
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$fac_options = get_option( 'fac4wp-options', [] );

// Only delete data if the user explicitly opted in.
if ( empty( $fac_options['fac_delete_data_on_uninstall'] ) ) {
	return;
}

global $wpdb;

// Delete plugin options.
delete_option( 'fac4wp-options' );
delete_option( 'fac_options' );

// Delete CF7 event mappings.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM $wpdb->options WHERE option_name LIKE %s;",
		$wpdb->esc_like( 'fac_cf7_' ) . '%'
	)
);

// Delete post meta created by the URL tracking feature.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM $wpdb->postmeta WHERE meta_key IN (%s, %s);",
		'_fac_url_page',
		'_fac_url_event_name'
	)
);
