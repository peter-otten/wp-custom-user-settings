<?php
namespace WPCUS\WPCustomUserSettings\Classes;

/**
 * Class WPCUSUserPermission
 * @package WPCUS\WPCustomUserSettings\Classes
 */
class WPCUSUserPermission
{

    private $pageName;
    private $settingsNames;
    private $callBack;

    /**
     * WPCUS_menu_order constructor.
     */
    public function __construct()
    {
        add_action('admin_init', [$this, 'wpcuUserRoles']);

        $this->pageName = 'WPCustomUserSettings_user_permission_page';
        $this->settingsNames = [
            'WPCustomUserSettings_user_permission'
        ];
        $this->callBack = [
            [$this, 'WPCUSUserPermissionPermissionCallBack']
        ];
    }

    /**
     * Add settings for menu order
     */
    public function wpcuUserRoles()
    {
        /* Header Options Section */
        add_settings_section(
            'wpcus_user_permissions', // ID
            'User permission', // Title
            [$this, 'wpcusMenuOrderHeaderCallback'], // Callback
            $this->pageName // Page
        );

        add_settings_field(
            $this->settingsNames[0], // ID
            'Permissions', // Title
            [$this, 'wpcusUserPermissionCallback'], // Callback
            $this->pageName, // Page
            'wpcus_user_permissions' // Section
        );
    }

    /**
     * Callback to display the settings section header
     */
    public function wpcusMenuOrderHeaderCallback()
    {
        ?>
        <p>Edit User roles</p>
        <?php
    }

    /**
     * Callback to display the settings field
     */
    public function wpcusUserPermissionCallback()
    {
        $allRoles = $this->getAllUserRoles();
        $capabilities = $this->getUserCapabilities($allRoles);
        include_once(WPMENUCUSTOMIZER_PLUGIN_PATH . '/templates/WPCUS_user_permission.php');
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
     * @param array $allRoles
     * @return array
     */
    private function getUserCapabilities(array $allRoles): array
    {
        $allCapabilities = [];
        foreach ($allRoles as $userRole) {
            if (is_array($userRole->capabilities)) {
                foreach ($userRole->capabilities as $capability => $granted) {
                    $allCapabilities[] = $capability;
                }
            }
        }
        $allCapabilities = array_unique($allCapabilities);
        return $allCapabilities;
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
     * Save user role permissions
     * @param $input
     */
    public function WPCUSUserPermissionPermissionCallBack($input)
    {
        /** @var array $input */
        foreach ($input as $currentRole => $permissions) {
            $currentRole = get_role($currentRole);
            foreach ($currentRole->capabilities as $capability => $isset) {
                $currentRole->remove_cap($capability);
            }
            foreach ($permissions as $permission) {
                $currentRole->add_cap($permission);
            }
        }
    }
}