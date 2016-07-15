<?php

    function megamenu_add_theme_odm_menu($themes) {
        $themes["odm_menu"] = array(
            'title' => 'ODM Menu',
            );
        return $themes;
    }
    add_filter("megamenu_themes", "megamenu_add_theme_odm_menu");

?>
