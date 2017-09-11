<?php
namespace WPCUS\WPCustomUserSettings\Classes;

/**
 * Class WPCUSMenuOrder
 * @package WPCUS\Classes
 */
class WPCUSMenuOrder
{

    private $pageName;
    private $settingsNames;

    /**
     * WPCUS_menu_order constructor.
     */
    public function __construct()
    {
        add_filter('custom_menu_order', create_function('', 'return true;'));
        add_filter('menu_order', [$this, 'customMenuOrder']);
        add_action('admin_init', [$this, 'wpcusMenuOrderSettings']);

        $this->pageName = 'WPCustomUserSettings_menu_order_page';
        $this->settingsNames = [
            'WPCustomUserSettings_menu_order'
        ];
    }

    /**
     * Add settings for menu order
     */
    public function wpcusMenuOrderSettings()
    {
        /* Header Options Section */
        add_settings_section(
            'wpcus_menu_order', // ID
            'Custom menu order', // Title
            [$this, 'wpcusMenuOrderHeaderCallback'], // Callback
            $this->pageName // Page
        );

        add_settings_field(
            $this->settingsNames[0], // ID
            'Menu order', // Title
            [$this, 'wpcusMenuOrderCallback'], // Callback
            $this->pageName, // Page
            'wpcus_menu_order' // Section
        );
    }

    /**
     * Callback to display the settings section header
     */
    public function wpcusMenuOrderHeaderCallback()
    {
        ?>
        <p>Drag and drop the menu in your custom order.</p>
        <?php
    }

    /**
     * Callback to display the settings field
     */
    public function wpcusMenuOrderCallback()
    {
        ?>
        <ul id="wp_menu_customizer_menu_list">
        <?php foreach ($this->getMenuItems() as $menuItem) { ?>
            <li class="ui-state-default">
                <h2><?= (empty($menuItem[0])) ? $menuItem[2] : $menuItem[0]; ?></h2>
                <span class="dashicons dashicons-sort"></span>
                <input type="hidden" name="WPCustomUserSettings_menu_order[]" value="<?= $menuItem[2]; ?>">
            </li>
        <?php } ?>
        </ul>
        <?php
    }

    /**
     * @return array|bool|mixed
     */
    public function customMenuOrder()
    {
        $savedMenuItems = get_option('WPCustomUserSettings_menu_order');
        if ($savedMenuItems) {
            return $savedMenuItems;
        }
        return [];
    }

    /**
     * @return array|mixed
     */
    public function getMenuItems()
    {
        global $menu;

        $savedMenuItems = get_option('WPCustomUserSettings_menu_order');
        if (!$savedMenuItems) {
            $menuItems = $menu;
        } else {
            $newMenuItems = [];

            foreach ($savedMenuItems as $savedMenuItem) {
                foreach ($menu as $currentMenuItem) {
                    if ($currentMenuItem[2] == $savedMenuItem) {
                        array_push($newMenuItems, $currentMenuItem);
                    }
                }
            }
            $menuItems = $newMenuItems;
        }

        return $menuItems;
    }

    /**
     * @return string
     */
    public function getPageName(): string
    {
        return $this->pageName;
    }

    /**
     * @return mixed
     */
    public function getSettingsNames()
    {
        return $this->settingsNames;
    }
}