<?php

function megamenu_add_theme_odm_menu($themes) {
    $themes["default_odm_menu"] = array(
        'title' => 'ODM Menu',
        'container_background_from' => '#292d56',
        'container_background_to' => '#292d56',
        'menu_item_background_hover_from' => '#EEEEEE',
        'menu_item_background_hover_to' => '#EEEEEE',
        'menu_item_link_color' => '#FFFFFF',
        'menu_item_link_color_hover' => '#292d56',
        'panel_background_from' => '#EEEEEE',
        'panel_background_to' => '#EEEEEE',
        'flyout_menu_background_from' => '#EEEEEE',
        'flyout_menu_background_to' => '#EEEEEE',
        'flyout_background_from' => '#EEEEEE',
        'flyout_background_to' => '#EEEEEE',
        'flyout_link_color' => '#6393D2',
        'flyout_link_color_hover' => '#292d56',
        'flyout_link_family' => 'inherit',
        'mobile_columns' => '1',
        'toggle_background_from' => '#EEEEEE',
        'toggle_background_to' => '#EEEEEE',
        'mobile_background_from' => '#292d56',
        'mobile_background_to' => '#292d56',
        'custom_css' => '/** Push menu onto new line **/
            #{$wrap} {
                clear: both;
            }
            ',
        );
      $themes["odc_menu"] = array(
          'title' => 'ODC Menu',
          'container_background_from' => '#12414e',
          'container_background_to' => '#12414e',
          'menu_item_background_hover_from' => '#EEEEEE',
          'menu_item_background_hover_to' => '#EEEEEE',
          'menu_item_link_color' => '#FFFFFF',
          'menu_item_link_color_hover' => '#12414e',
          'panel_background_from' => '#EEEEEE',
          'panel_background_to' => '#EEEEEE',
          'flyout_menu_background_from' => '#EEEEEE',
          'flyout_menu_background_to' => '#EEEEEE',
          'flyout_background_from' => '#EEEEEE',
          'flyout_background_to' => '#EEEEEE',
          'flyout_link_color' => '#97D320',
          'flyout_link_color_hover' => '#12414e',
          'flyout_link_family' => 'inherit',
          'mobile_columns' => '1',
          'toggle_background_from' => '#EEEEEE',
          'toggle_background_to' => '#EEEEEE',
          'mobile_background_from' => '#12414e',
          'mobile_background_to' => '#12414e',
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
