<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly


/**
 * UVembed Metadata Admin
 *
 * @package WordPress
 * @subpackage Uvembed_Metadata
 * @category Plugin
 * @author Hannah
 * @since 1.0.0
 */
class Uvembed_Metadata_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     *
     * @var $options
     */
    private $options;

    /**
     * Uvembed_Metadata_Settings constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page to Settings
     */
    public function add_plugin_page()
    {

        add_submenu_page(
            'options-general.php',
            'UV Embed Metadata Settings',
            'UV Embed Metadata',
            'manage_options',
            'uvembed-metadata',
            array($this, 'create_admin_page')
        );

    }

    /**
     * Settings page markup
     *
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('uvembed_metadata_group_name');
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('uvembed_metadata_group');
                do_settings_sections('uvembed-metadata');
                submit_button(__('Save Changes', 'uvembed-metadata'));
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'uvembed_metadata_group', // Option group
            'uvembed_metadata_group_name', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'uvembed_metadata_section', // ID
            'UV Embed Metadata Settings', // Title
            array($this, 'print_uvembed_metadata_endpoint_section_info'), // Callback
            'uvembed-metadata' // Page
        );

        add_settings_field(
            'uvembed_metadata_endpoint', // ID
            'UV Embed Endpoint', // Title
            array($this, 'uvembed_metadata_endpoint_callback'), // Callback
            'uvembed-metadata', // Page
            'uvembed_metadata_section' // Section
        );


    }
    /**
     * Sanitize each setting field as needed
     *
     * @param $input Contains all settings fields as array keys
     *
     * @return array
     */
    public function sanitize($input)
    {
        $new_input = array();


        if (isset($input['uvembed_metadata_endpoint'])) {
            $new_input['uvembed_metadata_endpoint'] = sanitize_text_field($input['uvembed_metadata_endpoint']);
        }


        return $new_input;
    }

    /**
     * Print the Section's instruction text
     */
    public function print_uvembed_metadata_endpoint_section_info()
    {
        $instructions = 'Enter the UV embed end point here, replace the work ID with {{work}} eg/. <strong>http://www.rcvs.org.uk/{{work}}.manifest</strong>';
        print $instructions;
    }

    /**
     * Markup for the field.
     */
    public function uvembed_metadata_endpoint_callback()
    {
        printf(
            '<input type="text" id="uvembed_metadata_endpoint" name="uvembed_metadata_group_name[uvembed_metadata_endpoint]" style="width: 90%%" value="%s" />',
            isset($this->options['uvembed_metadata_endpoint']) ? esc_attr($this->options['uvembed_metadata_endpoint']) : ''
        );
    }


}
