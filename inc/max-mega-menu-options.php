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
        $themes["odv_menu"] = array(
            'title' => 'ODV Menu',
            'container_background_from' => '#3D9C00',
            'container_background_to' => '#3D9C00',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#3D9C00',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#C6C366',
            'flyout_link_color_hover' => '#3D9C00',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#3D9C00',
            'mobile_background_to' => '#3D9C00',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        $themes["odmm_menu"] = array(
            'title' => 'ODMm Menu',
            'container_background_from' => '#704C98',
            'container_background_to' => '#704C98',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#704C98',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#F8BF42',
            'flyout_link_color_hover' => '#704C98',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#704C98',
            'mobile_background_to' => '#704C98',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        $themes["odl_menu"] = array(
            'title' => 'ODL Menu',
            'container_background_from' => '#3b2226',
            'container_background_to' => '#3b2226',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#3b2226',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#D61E2A',
            'flyout_link_color_hover' => '#3b2226',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#3b2226',
            'mobile_background_to' => '#3b2226',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        $themes["odt_menu"] = array(
            'title' => 'ODT Menu',
            'container_background_from' => '#64161d',
            'container_background_to' => '#64161d',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#64161d',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#F7904D',
            'flyout_link_color_hover' => '#64161d',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#64161d',
            'mobile_background_to' => '#64161d',
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
