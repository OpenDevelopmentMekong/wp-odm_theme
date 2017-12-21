<?php

function megamenu_add_theme_odm_menu($themes) {
    $themes["default_odm_menu"] = array(
        'title' => 'ODM Menu',
        'container_background_from' => '#373c6a',
        'container_background_to' => '#373c6a',
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
        'flyout_link_color' => '#292d56',
        'flyout_link_color_hover' => '#0066CC',
        'flyout_link_family' => 'inherit',
        'mobile_columns' => '1',
        'toggle_background_from' => '#373c6a',
        'toggle_background_to' => '#373c6a',
        'mobile_background_from' => '#373c6a',
        'mobile_background_to' => '#373c6a',
        'panel_second_level_font_color_hover' => '#0066CC',
        'panel_third_level_font_color_hover' => '#0066CC',
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
          'menu_item_link_color_hover' => '#06323D',
          'panel_background_from' => '#EEEEEE',
          'panel_background_to' => '#EEEEEE',
          'flyout_menu_background_from' => '#EEEEEE',
          'flyout_menu_background_to' => '#EEEEEE',
          'flyout_background_from' => '#EEEEEE',
          'flyout_background_to' => '#EEEEEE',
          'flyout_link_color' => '#06323D',
          'flyout_link_color_hover' => '#00A600',
          'flyout_link_family' => 'inherit',
          'mobile_columns' => '1',
          'toggle_background_from' => '#12414e',
          'toggle_background_to' => '#12414e',
          'mobile_background_from' => '#12414e',
          'mobile_background_to' => '#12414e',
          'panel_second_level_font_color_hover' => '#00A600',
          'panel_third_level_font_color_hover' => '#00A600',
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
            'menu_item_link_color_hover' => '#50813b',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#50813b',
            'flyout_link_color_hover' => '#beab74',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#3D9C00',
            'toggle_background_to' => '#3D9C00',
            'mobile_background_from' => '#3D9C00',
            'mobile_background_to' => '#3D9C00',
            'panel_second_level_font_color_hover' => '#beab74',
            'panel_third_level_font_color_hover' => '#beab74',
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
            'menu_item_link_color_hover' => '#59396C',
            'panel_background_from' => '#EEEEEE',
            'panel_background_to' => '#EEEEEE',
            'flyout_menu_background_from' => '#EEEEEE',
            'flyout_menu_background_to' => '#EEEEEE',
            'flyout_background_from' => '#EEEEEE',
            'flyout_background_to' => '#EEEEEE',
            'flyout_link_color' => '#59396C',
            'flyout_link_color_hover' => '#F8BF42',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#704C98',
            'toggle_background_to' => '#704C98',
            'mobile_background_from' => '#704C98',
            'mobile_background_to' => '#704C98',
            'panel_second_level_font_color_hover' => '#F8BF42',
            'panel_third_level_font_color_hover' => '#F8BF42',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        $themes["odl_menu"] = array(
            'title' => 'ODL Menu',
            'container_background_from' => '#583A43',
            'container_background_to' => '#583A43',
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
            'flyout_link_color' => '#3b2226',
            'flyout_link_color_hover' => '#D61E2A',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#583A43',
            'toggle_background_to' => '#583A43',
            'mobile_background_from' => '#583A43',
            'mobile_background_to' => '#583A43',
            'panel_second_level_font_color_hover' => '#D61E2A',
            'panel_third_level_font_color_hover' => '#D61E2A',
            'custom_css' => '/** Push menu onto new line **/
                #{$wrap} {
                    clear: both;
                }
                ',
            );
        $themes["odt_menu"] = array(
            'title' => 'ODT Menu',
            'container_background_from' => '#772028',
            'container_background_to' => '#772028',
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
            'flyout_link_color' => '#64161d',
            'flyout_link_color_hover' => '#F7904D',
            'flyout_link_family' => 'inherit',
            'mobile_columns' => '1',
            'toggle_background_from' => '#772028',
            'toggle_background_to' => '#772028',
            'mobile_background_from' => '#772028',
            'mobile_background_to' => '#772028',
            'panel_second_level_font_color_hover' => '#F7904D',
            'panel_third_level_font_color_hover' => '#F7904D',
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
