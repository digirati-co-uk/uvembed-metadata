<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * UVembed Metadata Apply
 *
 * Applies the metadata to the post or page (or any custom post type) on update and on creation
 *
 * @package WordPress
 * @subpackage Uvembed_Metadata
 * @category Plugin
 * @author Hannah
 * @since 1.0.0
 */
add_action( 'save_post', 'uvembed_metadata_apply' );


function uvembed_metadata_apply( $post_id ) {

	// If this is just a revision, don't do anything
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	$content = get_post_field( 'post_content', $post_id );
	if ( shortcode_exists( 'uvembed' ) && has_shortcode( $content, 'uvembed' ) ) {

		// find all instances of uvembed
		$uvembed = uvembed_metadata_findUVembedShortcode( $content );

		// find all work attributes for those embeds
		$works = uvembed_metadata_findAttr( 'work', $uvembed );

		// Get all the metadata
		$metaData = uvembed_metadata_getWorkMetadata( $works );

		// Save the metadata to custom field/fields on this post
		if(!empty($metaData)) {
			// as json - mostly for future reference
			$metaDataJson = json_encode( $metaData );

			// as string - for searching
			$metaDataString = uvembed_metadata_metaToString( $metaData );

			// add/update post meta
			update_post_meta( $post_id, 'uvembed_metadata_json', $metaDataJson );
			update_post_meta( $post_id, 'uvembed_metadata_string', $metaDataString );
		} else {
			// add/update post meta
			delete_post_meta( $post_id, 'uvembed_metadata_json' );
			delete_post_meta( $post_id, 'uvembed_metadata_string' );
		}

	}

}

/**
 * Find the shortcode with UVembed and Work attribute
 *
 * @param $content
 *
 * @return array
 */
function uvembed_metadata_findUVembedShortcode( $content ) {
	$shortcodes        = array();
	$uvembedShortcodes = array();
	preg_match_all( '/(?<=\\[)(.*?)(?=\\])/', $content, $shortcodes );
	if ( ! empty( $shortcodes ) ) {
		foreach ( $shortcodes[1] as $shortcode ) {
			// if uvembed and has work attribute
			if ( preg_match( '/uvembed/', $shortcode ) ) {
				array_push( $uvembedShortcodes, $shortcode );
			}
		}
	}

	return $uvembedShortcodes;
}

/**
 *
 * Find the value of a specific attribute
 *
 * @param $attr
 * @param $shortcodes
 *
 * @return array
 */
function uvembed_metadata_findAttr( $attr, $shortcodes ) {
	$works = array();
	foreach ( $shortcodes as $shortcode ) {
		$pattern = '/' . $attr . '=\"([^"]*)\"/';
		if ( preg_match( $pattern, $shortcode, $work ) ) {
			$works[] = $work[1];
		}
	}

	return $works;
}

/**
 *
 * Get an array of the metadata field 'values'
 *
 * @param $works
 *
 * @return array
 */
function uvembed_metadata_getWorkMetadata( $works ) {

	$uvEmbedOptions = get_option( 'uvembed_metadata_group_name' );
	$metadata       = array();

	foreach ( $works as $work ) {
		$url = $uvEmbedOptions['uvembed_metadata_endpoint'];
		$data = array();
		$url  = preg_replace( '/{{work}}/', $work, $url );

		// Get Manifest

		$ch = curl_init(); //  Initiate curl
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); // Enable SSL verification
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Will return the response, if false it print the response
		curl_setopt( $ch, CURLOPT_URL, $url ); // Set the url
		$result = curl_exec( $ch ); // Execute
		curl_close( $ch ); // Closing
		$manifest = json_decode( $result, true );

		if(!empty($manifest)) {
			// look in metadata field
			$data[] = $manifest['metadata'];
			// look in each sequences section
			foreach ( $manifest['sequences'] as $sequence ) {
				foreach ( $sequence['canvases'] as $canvas ) {
					array_push( $data, $canvas['metadata'] );
				}
			}

			// flatten the data
			$dataFlat = array();
			array_walk_recursive( $data, function ( $v, $k ) use ( &$dataFlat ) {
				if ( $k == 'value' ) {
					$dataFlat[] = sanitize_text_field( $v );
				}
			} );

			// return unique array
			$metadata[ $work ] = array_unique( $dataFlat );

		}

	}

	return $metadata;

}


/**
 * Return metadata as string
 *
 * @param $metaData
 *
 * @return string
 */
function uvembed_metadata_metaToString($metaData) {
	$metaDataString = '';
	foreach ( $metaData as $meta ) {
		$cnt = 0;
		foreach ( $meta as $data ) {
			if ( $cnt != 0 ) {
				$metaDataString .= '|';
			}
			$metaDataString .= $data;
			$cnt ++;
		}
	}
	return $metaDataString;
}