<?php
namespace WPCUS\WPCustomUserSettings;

/*
Plugin Name: WP Custom user settings
Version: 1.0.0
Description: Plugin voor het verbergen en sorteren van menu items
Author: Peter Otten
Author URI: https://github.com/rexxnar/
*/

define('WPMENUCUSTOMIZER_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Class WPCustomUserSettings
 * @package WPCUS\wp_custom_user_settings
 */
class WPCustomUserSettings
{

    private $WPCUSMenuOrder;

    /**
     * WPCustomUserSettings constructor.
     */
    public function __construct()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');

        include_once(WPMENUCUSTOMIZER_PLUGIN_PATH . '/Classes/WPCUS_menu_order.php');
        $this->WPCUSMenuOrder = new Classes\WPCUSMenuOrder();

        $this->includeCustomAssets();
        $this->registerPluginSettings();

        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        add_action('admin_menu', [$this, 'optionsPage']);
        add_action('admin_menu', [$this, 'optionsPage']);
    }

    /**
     * Deactivation hook
     */
    public function deactivate()
    {
        $optionName = $this->getOptionNames();
        foreach ($optionName as $value) {
            delete_option($value);
        }
    }

    /**
     * Register settings
     */
    public function registerPluginSettings()
    {
        foreach ($this->getSettingNames() as $pageName => $settingName) {
            register_setting(
                $pageName, //group - page
                $settingName //name
            );
        }
    }

    /**
     * @return array
     */
    private function getSettingNames()
    {
        $optionNames = [];
        if ($this->WPCUSMenuOrder !== null) {
            $pageName = $this->WPCUSMenuOrder->getPageName();
            $settingsNames = $this->WPCUSMenuOrder->getSettingsNames();
            foreach ($settingsNames as $settingsName) {
                $optionNames[$pageName] = $settingsName;
            }
        }
        return $optionNames;
    }

    /**
     * Adds the menu page
     */
    public function optionsPage()
    {
        // Create new top-level menu
        add_menu_page(
            'WP Custom user settings', //page title
            'WP Custom user settings', //menu title
            'manage_options', //capability
            'wpcu-settings-page', //menu slug
            [$this, 'wpcuSettingsPage'], //callable function
            'dashicons-editor-ul', // icon
            100 // position
        );
    }

    /**
     *
     */
    public function wpcuSettingsPage()
    {
        include_once(plugin_dir_path(__FILE__) . '/templates/WPCUS_admin_page.php');
    }

    /**
     * Embeds custom assets for the plugin
     */
    private function includeCustomAssets()
    {
        $scripts = [
            'wp_custom_user_settings.js'
        ];
        foreach ($scripts as $script) {
            if (wp_script_is($script, 'enqueued')) {
                return;
            } else {
                wp_enqueue_script($script, plugin_dir_url(__FILE__) . 'assets/js/' . $script, ['jquery'], null, 'all');
            }
        }

        $stylesArray = [
            'wp_custom_user_settings.css'
        ];

        foreach ($stylesArray as $styles) {
            if (wp_style_is($styles, 'enqueued')) {
                return;
            } else {
                wp_enqueue_style($styles, plugin_dir_url(__FILE__) . 'assets/css/' . $styles, false, null, 'all');
            }
        }
    }

    /**
     * @return Classes\WPCUSMenuOrder
     */
    public function getWPCUSMenuOrder(): Classes\WPCUSMenuOrder
    {
        return $this->WPCUSMenuOrder;
    }
}

$WPCustomUserSettings = new WPCustomUserSettings();