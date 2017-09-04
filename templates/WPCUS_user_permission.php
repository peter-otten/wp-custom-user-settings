<?php
foreach ($allRoles as $key => $currentRole) {
    ?>
    <div id="wpcus_user_permissions">
        <div class="user-roles-container">
            <h3 class="user-roles-title" data-key="<?= $key ?>">
                <span><?= $currentRole->name; ?></span>
            </h3>
            <div class="user-roles-body" data-key="<?= $key ?>">
                <ul>
                    <?php
                    foreach ($capabilities as $capability) {
                        if (is_array($currentRole->capabilities)) {
                            $foundCapability = key_exists($capability, $currentRole->capabilities);
                        }
                        $checked = ($foundCapability)? 'checked="checked"' : '';
                        ?>
                        <li>
                            <p>
                                <input name="WPCustomUserSettings_user_permission[<?= $currentRole->name; ?>][]" id="<?= $currentRole->name; ?>" type="checkbox" <?= $checked; ?> value="<?= $capability; ?>">
                                <label for="<?= $currentRole->name; ?>"><?= $capability; ?></label>
                            </p>
                        </li>
                        <?
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
}