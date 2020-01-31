<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Plugin Name: UVembed metadata
 * Plugin URI: https://github.com/digirati-co-uk/uvembed-metadata
 * Description: Universal Viewer Meta data
 * Version: 1.0.3
 * Author: Hannah Nicholas
 * Author URI: http://www.digirati.com
 * Requires at least: 4.0.0
 * Tested up to: 5.3.0
 *
 * Text Domain: uvembed-metadata
 * Domain Path: /languages/
 *
 * @package Uvembed_Metadata
 * @category Core
 * @author Hannah
 */
require_once( 'inc/uvembed-metadata-admin.php' );
require_once( 'inc/uvembed-metadata-shortcode.php' );
require_once( 'inc/uvembed-metadata-search.php' );
require_once( 'inc/uvembed-metadata-apply.php' );


if ( is_admin() ) {
	$uvembedMetadataSettings = new Uvembed_Metadata_Settings();
}
