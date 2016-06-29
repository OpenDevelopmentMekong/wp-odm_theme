<?php 

    function megamenu_add_theme_odm_menu($themes) {
        $themes["odm_menu"] = array(
            'title' => 'ODM Menu',
            'container_background_from' => '#D8D8D8',
            'container_background_to' => '#D8D8D8',
            'menu_item_background_hover_from' => '#C9C9C9',
            'menu_item_background_hover_to' => '#C9C9C9',
            'menu_item_link_color' => '#505050',
            'menu_item_link_color_hover' => '#6393D2',
            'panel_background_from' => '#C9C9C9',
            'panel_background_to' => '#C9C9C9',
            'panel_header_border_color' => '#555',
            'panel_font_size' => '14px',
            'panel_font_color' => '#505050',
            'panel_font_family' => 'inherit',
            'panel_second_level_font_color' => '#555',
            'panel_second_level_font_color_hover' => '#6393D2',
            'panel_second_level_font' => 'inherit',
            'panel_second_level_font_size' => '16px',
            'panel_second_level_font_weight' => 'bold',
            'panel_second_level_font_weight_hover' => 'bold',
            'panel_second_level_text_decoration' => 'none',
            'panel_second_level_text_decoration_hover' => 'none',
            'panel_second_level_border_color' => '#555',
            'panel_third_level_font_color' => '#505050',
            'panel_third_level_font_color_hover' => '#6393D2',
            'panel_third_level_font' => 'inherit',
            'panel_third_level_font_size' => '14px',
            'flyout_menu_background_from' => '#C9C9C9',
            'flyout_menu_background_to' => '#C9C9C9',
            'flyout_background_from' => '#C9C9C9',
            'flyout_background_to' => '#C9C9C9',
            'flyout_link_size' => '14px',
            'flyout_link_color' => '#505050',
            'flyout_link_color_hover' => '#6393D2',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#D8D8D8',
            'toggle_background_to' => '#D8D8D8',
            'toggle_font_color' => '#505050',
            'mobile_background_from' => '#D8D8D8',
            'mobile_background_to' => '#D8D8D8',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        return $themes;
    }
    add_filter("megamenu_themes", "megamenu_add_theme_odm_menu");

?>