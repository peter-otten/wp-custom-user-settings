<?php
namespace WPCUS\WPCustomUserSettings\Classes;

/**
 * Class WPCUS_menu_items
 * @package WPCUS\WPCustomUserSettings\Classes
 */
class WPCUSMenuItems
{

    private $pageName;
    private $settingsNames;
    private $callBack;

    /**
     * WPCUS_menu_order constructor.
     */
    public function __construct()
    {
        add_action('admin_init', [$this, 'wpcusMenuItems']);

        $this->pageName = 'WPCUS_menu_items';
        $this->settingsNames = [
            'WPCUS_menu_items'
        ];
        $this->callBack = [
            []
        ];
    }

    /**
     * Add settings for menu order
     */
    public function wpcusMenuItems()
    {
        /* Header Options Section */
        add_settings_section(
            'wpcus_menu_items', // ID
            'Menu items', // Title
            [$this, 'wpcusMenuItemsHeaderCallback'], // Callback
            $this->pageName // Page
        );

        add_settings_field(
            $this->settingsNames[0], // ID
            'Menu items', // Title
            [$this, 'wpcusMenuItemsCallback'], // Callback
            $this->pageName, // Page
            'wpcus_menu_items' // Section
        );
    }

    /**
     * Callback to display the settings section header
     */
    public function wpcusMenuItemsHeaderCallback()
    {
        ?>
        <p>Edit menu items</p>
        <?php
    }

    /**
     * Callback to display the settings field
     */
    public function wpcusMenuItemsCallback()
    {
        $allRoles = $this->getAllUserRoles();
        $hiddenMenuItems = $this->getHiddenMenuItems();
        include_once(WPMENUCUSTOMIZER_PLUGIN_PATH . '/templates/WPCUS_menu_items.php');
    }

    /**
     * Get all user roles
     * @return array
     */
    private function getAllUserRoles(): array
    {
        global $wp_roles;

        $allRoles = $wp_roles->role_objects;
        $editableRoles = apply_filters('editable_roles', $allRoles);

        return $editableRoles;
    }

    /**
     * @return string
     */
    public function getPageName(): string
    {
        return $this->pageName;
    }

    /**
     * @return array
     */
    public function getSettingsNames()
    {
        return $this->settingsNames;
    }

    /**
     * @return array
     */
    public function getCallBack(): array
    {
        return $this->callBack;
    }

    public function getHiddenMenuItems(): array
    {
        $hiddenMenuItems = get_option($this->settingsNames[0]);
        return $hiddenMenuItems;
    }
}