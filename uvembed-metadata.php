<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly.

/**
 * Plugin Name: UVembed metadata
 * Plugin URI: https://github.com/digirati-co-uk/uvembed-metadata
 * Description: Universal Viewer Meta data
 * Version: 1.0.0
 * Author: Hannah Nicholas
 * Author URI: http://www.digirati.com
 * Requires at least: 4.0.0
 * Tested up to: 4.0.0
 *
 * Text Domain: uvembed-metadata
 * Domain Path: /languages/
 *
 * @package Uvembed_Metadata
 * @category Core
 * @author Hannah
 */

/**
 * Returns the main instance of Uvembed_Metadata to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Uvembed_Metadata
 */
function Uvembed_Metadata()
{
    return Uvembed_Metadata::instance();
} // End Uvembed_Metadata()

add_action('plugins_loaded', 'Uvembed_Metadata');

/**
 * Main Uvembed_Metadata Class
 *
 * @class Uvembed_Metadata
 * @version    1.0.0
 * @since 1.0.0
 * @package    Uvembed_Metadata
 * @author Matty
 */
final class Uvembed_Metadata
{
    /**
     * Uvembed_Metadata The single instance of Uvembed_Metadata.
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $token;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $version;

    /**
     * The plugin directory URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $plugin_url;

    /**
     * The plugin directory path.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $plugin_path;

    // Admin - Start
    /**
     * The admin object.
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $admin;

    /**
     * The settings object.
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $settings;
    // Admin - End

    // Post Types - Start
    /**
     * The post types we're registering.
     * @var     array
     * @access  public
     * @since   1.0.0
     */
    public $post_types = array();
    // Post Types - End
    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     */
    public function __construct()
    {
        $this->token = 'uvembed-metadata';
        $this->plugin_url = plugin_dir_url(__FILE__);
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->version = '1.0.0';

        // Admin - Start
//        require_once( 'classes/class-uvembed-metadata-settings.php' );
//        $this->settings = Uvembed_Metadata_Settings::instance();

        if (is_admin()) {
//            require_once( 'classes/class-uvembed-metadata-admin.php' );
//            $this->admin = Uvembed_Metadata_Admin::instance();
        }
        // Admin - End

        // UVembed shortcode
        require_once('classes/class-uvembed-metadata-shortcode.php');
        Uvembed_Metadata_Shortcode::instance();

        register_activation_hook(__FILE__, array($this, 'install'));

        add_action('init', array($this, 'load_plugin_textdomain'));
    } // End __construct()

    /**
     * Main Uvembed_Metadata Instance
     *
     * Ensures only one instance of Uvembed_Metadata is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see Uvembed_Metadata()
     * @return Main Uvembed_Metadata instance
     */
    public static function instance()
    {
        if (is_null(self::$_instance))
            self::$_instance = new self();
        return self::$_instance;
    } // End instance()

    /**
     * Load the localisation file.
     * @access  public
     * @since   1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain('uvembed-metadata', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    } // End load_plugin_textdomain()

    /**
     * Cloning is forbidden.
     * @access public
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), '1.0.0');
    } // End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     * @access public
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), '1.0.0');
    } // End __wakeup()

    /**
     * Installation. Runs on activation.
     * @access  public
     * @since   1.0.0
     */
    public function install()
    {
        $this->_log_version_number();
    } // End install()

    /**
     * Log the plugin version number.
     * @access  private
     * @since   1.0.0
     */
    private function _log_version_number()
    {
        // Log the version number.
        update_option($this->token . '-version', $this->version);
    } // End _log_version_number()
} // End Class