<?php
global $menu;
global $submenu;
foreach ($allRoles as $key => $currentRole) {
    ?>
    <div id="wpcus_menu_items">
        <div class="menu-items-container">
            <h3 class="menu-items-title" data-key="<?= $key ?>">
                <span><?= $currentRole->name; ?></span>
            </h3>
            <div class="menu-items-body" data-key="<?= $key ?>">
                <ul>
                    <?php foreach ($menu as $number => $menuItem) :
                        if (empty($menuItem[0])) {
                            $menuName = $menuItem[2];
                        } else {
                            $menuName = $menuItem[0];
                        }
                        $checked = false;
                        if (array_key_exists($currentRole->name, $hiddenMenuItems) == true &&
                            $hiddenMenuItems[$currentRole->name][$number] == $menuItem[2]) {
                            $checked = 'checked="checked"';
                        };
                        $subNumber = 0;
                        ?>
                        <li>
                            <p>
                                <input name="WPCUS_menu_items[<?= $currentRole->name; ?>][]" id="<?= $currentRole->name; ?>" type="checkbox" <?= $checked; ?> value="<?= $menuItem[2] ?>">
                                <label for="<?= $currentRole->name; ?>"><?= $menuName ?></label>
                            </p>
                        </li>
                        <ul class="menu-items-sub-list">
                        <?php foreach ($submenu[$menuItem[2]] as $submenuItem) :
                            $checked = false;
                            if (array_key_exists($currentRole->name, $hiddenMenuItems) == true &&
                                array_key_exists($menuItem[2], $hiddenMenuItems[$currentRole->name]) == true &&
                                $hiddenMenuItems[$currentRole->name][$menuItem[2]][$subNumber] == $submenuItem[2]
                            ) {
                                $checked = 'checked="checked"';
                            }
                            $subNumber++;
                            ?>
                            <li>
                                <p>
                                    <input name="WPCUS_menu_items[<?= $currentRole->name; ?>][<?= $menuItem[2] ?>][]" id="<?= $currentRole->name . $submenuItem[2]; ?>" type="checkbox" <?= $checked; ?> value="<?= $submenuItem[2] ?>">
                                    <label for="<?= $currentRole->name. $submenuItem[2]; ?>"><?= $submenuItem[0] ?></label>
                                </p>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                        <hr />
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
}