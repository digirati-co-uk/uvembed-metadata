<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly.

/**
 * UVembed Metadata Shortcode
 *
 * @package WordPress
 * @subpackage Uvembed_Metadata
 * @category Plugin
 * @author Hannah
 * @since 1.0.0
 */

add_shortcode('uvembed', 'uvembed_shortcode');

/**
 * @param $atts
 * @return string
 */
function uvembed_shortcode($atts)
{
    // Settings
    $settings = get_option('uvembed_metadata_group_name');
    $endpoint = $settings['uvembed_metadata_endpoint'];

    // Attributes
    $a = shortcode_atts(
        array(
            'work' => '',
        ),
        $atts,
        'uvembed'
    );

	$uvEmbedUrl = (!empty($settings['uvembed_metadata_embedurl'])) ? $settings['uvembed_metadata_embedurl'] : 'https://universalviewer.io/vendor/uv/lib/embed.js';
	$uvConfigUrl = (!empty($settings['uvembed_metadata_configurl'])) ? $settings['uvembed_metadata_configurl'] : 'https://universalviewer.io/config.json';
    $uvDataUri = preg_replace('/{{work}}/', $a['work'], $endpoint);
    $style = 'width: 100%; height: 600px; background-color: #000;';
	
    $shortcode = '<div class="uv" data-uri="' . $uvDataUri . '" data-config="' . $uvConfigUrl . '" style="' . $style . '"></div>';
    $shortcode .= '<script type="text/javascript" id="embedUV" src="' . $uvEmbedUrl . '"></script>';

    return $shortcode;

}

