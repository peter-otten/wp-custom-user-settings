<?php
namespace WPCUS\WPCustomUserSettings\Classes;

/**
 * Class WPCUSMenuOrder
 * @package WPCUS\Classes
 */
class WPCUSMenuOrder
{
    /**
     * WPCUS_menu_order constructor.
     */
    public function __construct()
    {
        add_filter('custom_menu_order', create_function('', 'return true;'));
        add_filter('menu_order', [$this, 'customMenuOrder']);
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
}