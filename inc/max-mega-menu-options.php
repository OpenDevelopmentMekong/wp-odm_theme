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
            'container_background_from' => '#4AB208',
            'container_background_to' => '#4AB208',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#4AB208',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#C6C366',
            'flyout_link_color_hover' => '#4AB208',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#4AB208',
            'mobile_background_to' => '#4AB208',
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
            'container_background_from' => '#583a43',
            'container_background_to' => '#583a43',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#583a43',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#D61E2A',
            'flyout_link_color_hover' => '#583a43',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#583a43',
            'mobile_background_to' => '#583a43',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        $themes["odt_menu"] = array(
            'title' => 'ODT Menu',
            'container_background_from' => '#F7904D',
            'container_background_to' => '#F7904D',
            'menu_item_background_hover_from' => '#EEEEEE',
            'menu_item_background_hover_to' => '#EEEEEE',
            'menu_item_link_color' => '#FFFFFF',
            'menu_item_link_color_hover' => '#F7904D',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#F7904D',
            'flyout_link_color_hover' => '#F7904D',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#EEEEEE',
            'toggle_background_to' => '#EEEEEE',
            'mobile_background_from' => '#F7904D',
            'mobile_background_to' => '#F7904D',
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
