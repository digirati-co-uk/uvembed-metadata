<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * UVembed Metadata Search
 *
 * Extend WordPress search to include custom fields
 *
 * @package WordPress
 * @subpackage Uvembed_Metadata
 * @category Plugin
 * @author Hannah
 * @since 1.0.0
 */

add_filter( 'posts_where_request', 'uvembed_metadata_add_to_search' );
add_filter( 'posts_join_request', 'uvembed_metadata_join_postmeta' );
add_filter( 'posts_distinct_request', 'uvembed_metadata_distinct' );

/**
 *
 * Add meta_keys into search
 *
 * @param $where
 *
 * @return string
 */
function uvembed_metadata_add_to_search( $where ) {
	global $wpdb, $wp;

	if ( ! is_admin() && is_search() ) {
		$keys = implode( "','", array( 'uvembed_metadata_string' ) );
		$where .= " OR ({$wpdb->postmeta}.meta_key IN('{$keys}') AND {$wpdb->postmeta}.meta_value LIKE '%{$wp->query_vars['s']}%')";
	}

	return $where;
}

/**
 * Add post meta table to query
 *
 * @param $join
 *
 * @return string
 */
function uvembed_metadata_join_postmeta( $join ) {
	global $wpdb;

	return $join .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ";

}

/**
 * No duplicate results
 *
 * @return string
 */
function uvembed_metadata_distinct() {
	return 'DISTINCT';
}

