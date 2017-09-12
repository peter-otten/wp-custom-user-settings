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
        add_action('admin_init', [$this, 'wpcusHideMenuItems']);

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

    /**
     * @return array
     */
    public function getHiddenMenuItems(): array
    {
        $hiddenMenuItems = get_option($this->settingsNames[0]);
        return $hiddenMenuItems;
    }

    public function wpcusHideMenuItems()
    {
        $hiddenMenuItems = $this->getHiddenMenuItems();
        $currentUser = wp_get_current_user();

        global $menu;
        global $submenu;


        foreach ($currentUser->roles as $userRole) {
            if (array_key_exists($userRole, $hiddenMenuItems)) {
                foreach ($hiddenMenuItems[$userRole] as $menuParentSlug) {
                    if (!is_array($menuParentSlug)) {
                        $menuItem = $this->in_array_r($menuParentSlug, $menu);
                        if ($menuItem) {
                            $arrayKey = array_search($menuItem, $menu);
                            if (array_key_exists($arrayKey, $menu)) {
                                unset($menu[$arrayKey]);
                            }
                        }
                    }

                    if (is_array($menuParentSlug)) {
                        foreach ($submenu as $subMenuParent => $submenuItems) {
                            foreach ($submenuItems as $submenuItemKey => $submenuItem) {
                                foreach ($menuParentSlug as $hiddenSubMenuItem) {
                                    if ($hiddenSubMenuItem == $submenuItem[2]) {
                                        unset($submenu[$subMenuParent][$submenuItemKey]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //exit();
    }

    /**
     * @param $needle
     * @param $haystack
     * @param bool $strict
     * @return mixed
     */
    private function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) ||
                (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return $item;
            }
        }
        return false;
    }

}