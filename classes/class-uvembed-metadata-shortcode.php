<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly.

// Add Shortcode
function uv_shortcode($atts)
{

    // Attributes
    $atts = shortcode_atts(
        array(
            'work' => '',
        ),
        $atts,
        'uvembed'
    );

}

add_shortcode('uvembed', 'uv_shortcode');


/**
 * UVembed Metadata Shortcode
 *
 * UVEmbed shortcode
 *
 * @package WordPress
 * @subpackage Uvembed_Metadata
 * @category Plugin
 * @author Matty
 * @since 1.0.0
 */
class Uvembed_Metadata_Shortcode
{

    /**
     * Uvembed_Metadata_Shortcode The single instance of Uvembed_Metadata_Shortcode.
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;


    /**
     * Main Uvembed_Metadata_Shortcode Instance
     *
     * Ensures only one instance of Uvembed_Metadata_Shortcode is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Main Uvembed_Metadata_Shortcode instance
     */
    public static function instance()
    {
        if (is_null(self::$_instance))
            self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * Uvembed_Metadata_Shortcode constructor.
     */
    public function __construct()
    {
        add_shortcode('uvembed', array($this, 'uv_shortcode'));
    }

    /**
     * @param $atts
     * @return string
     */
    public function uv_shortcode($atts)
    {

        // Attributes
        $a = shortcode_atts(
            array(
                'work' => '',
            ),
            $atts,
            'uvembed'
        );

        $shortcode = '<div class="uv" ';
        $shortcode .= 'data-uri="http://rcvs.digtest.co.uk:8000/work/' . $a['work'] . '.manifest" ';
        $shortcode .= 'data-config="http://universalviewer.io/config.json" ';
        $shortcode .= 'style="width: 100%; height: 600px; background-color: #000;" ';
        $shortcode .= '></div>';
        $shortcode .= '<script type="text/javascript" id="embedUV" src="http://universalviewer.io/uv/lib/embed.js"></script>';

        return $shortcode;

    }
}

