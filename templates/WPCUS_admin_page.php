<div id="wp_menu_customizer" class="wrap">
    <h1>Wordpress menu customizer</h1>

    <?php settings_errors(); ?>
    <?php
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wpcus_menu_order';
    ?>

    <h2 class="nav-tab-wrapper">
        <?php if ($this->getWPCUSMenuOrder() !== null) : ?><a href="?page=wpcu-settings-page&tab=wpcus_menu_order" class="nav-tab <?php echo $active_tab == 'wpcus_menu_order' ? 'nav-tab-active' : ''; ?>">Front Page Options</a><?php endif; ?>
        <?php if ($this->getWPCUSUserPermission() !== null) : ?><a href="?page=wpcu-settings-page&tab=wpcus_user_permission" class="nav-tab <?php echo $active_tab == 'wpcus_user_permission' ? 'nav-tab-active' : ''; ?>">User permission</a><?php endif; ?>
    </h2>

    <form method="post" action="options.php">
        <?php
        if ($active_tab == 'wpcus_menu_order' && $this->getWPCUSMenuOrder() !== null) {
            settings_fields('WPCustomUserSettings_menu_order_page');
            do_settings_sections('WPCustomUserSettings_menu_order_page');
        }

        if ($active_tab == 'wpcus_user_permission' && $this->getWPCUSUserPermission() !== null) {
            settings_fields('WPCustomUserSettings_user_permission_page');
            do_settings_sections('WPCustomUserSettings_user_permission_page');
        }
        ?>
        <?php submit_button(); ?>
    </form>
</div>