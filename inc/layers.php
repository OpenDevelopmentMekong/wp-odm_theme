<?php
// Extend parent theme class
 class Extended_JEO_Layers extends JEO_Layers
 {
     public function __construct()
     {
         // Call parent class constructor
        // parent::__construct();
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
         add_action('save_post', array($this, 'layer_save'));
     }
    /* function unregister_parent_post_type_again() {
        remove_action( 'init', 'register_post_type', 0 );
        }
       add_action ('init', array($this, 'unregister_parent_post_type_again')); */

    public function settings_box($post = false)
    {
        $layer_type = $post ? $this->get_layer_type($post->ID) : false;
        $layer_download_link = get_post_meta($post->ID, '_layer_download_link', true);

        ?>
      <div id="layer_settings_box">
       <div class="layer-download-link">
        <tbody>
         <tr>
          <th><label for="_layer_download_link"><?php _e('Download URL', 'jeo');
        ?></label></th>
          <td>
           <input id="_layer_download_link" type="text" placeholder="https://" size="40" name="_layer_download_link" value="<?php echo $layer_download_link;
        ?>" />
           <p class="description"><?php _e('A link to a dataset\'s page on CKAN', 'jeo');
        ?></p>
          </td>
         </tr>
        </tbody>
       </div>

       <div class="layer-type">
        <h4><?php _e('Layer type', 'jeo');
        ?></h4>
        <p>
         <input type="radio" id="layer_type_tilelayer" name="layer_type" value="tilelayer" <?php if ($layer_type == 'tilelayer' || !$layer_type) {
    echo 'checked';
}
        ?> />
         <label for="layer_type_tilelayer"><?php _e('Tile layer', 'jeo');
        ?></label>
         <input type="radio" id="layer_type_wmslayer" name="layer_type" value="wmslayer" <?php if ($layer_type == 'wmslayer') {
    echo 'checked';
}
        ?> />
         <label for="layer_type_wmslayer"><?php _e('WMS layer', 'jeo');
        ?></label>

         <input type="radio" id="layer_type_mapbox" name="layer_type" value="mapbox" <?php if ($layer_type == 'mapbox') {
    echo 'checked';
}
        ?> />
         <label for="layer_type_mapbox"><?php _e('MapBox', 'jeo');
        ?></label>

         <input type="radio" id="layer_type_cartodb" name="layer_type" value="cartodb" <?php if ($layer_type == 'cartodb') {
    echo 'checked';
}
        ?> />
         <label for="layer_type_cartodb"><?php _e('CartoDB', 'jeo');
        ?></label>
        </p>
       </div>
       <table class="form-table type-setting tilelayer">
        <?php

        $tileurl = $post ? get_post_meta($post->ID, '_tilelayer_tile_url', true) : '';
        $utfgridurl = $post ? get_post_meta($post->ID, '_tilelayer_utfgrid_url', true) : '';
        $utfgrid_template = $post ? get_post_meta($post->ID, '_tilelayer_utfgrid_template', true) : '';
        $tms = $post ? get_post_meta($post->ID, '_tilelayer_tms', true) : '';

        ?>
        <tbody>
         <tr>
          <th><label for="tilelayer_tile_url"><?php _e('URL', 'jeo');
        ?></label></th>
          <td>
           <input id="tilelayer_tile_url" type="text" placeholder="<?php _e('http://{s}.example.com/{z}/{x}/{y}.png', 'jeo');
        ?>" size="40" name="_tilelayer_tile_url" value="<?php echo $tileurl;
        ?>" />
           <p class="description"><?php _e('Tilelayer URL. E.g.: http://{s}.example.com/{z}/{x}/{y}.png', 'jeo');
        ?></p>
          </td>
         </tr>
         <tr>
          <th><label for="tilelayer_utfgrid_url"><?php _e('UTFGrid URL (optional)', 'jeo');
        ?></label></th>
          <td>
           <input id="tilelayer_utfgrid_url" type="text" placeholder="<?php _e('http://{s}.example.com/{z}/{x}/{y}.grid.json', 'jeo');
        ?>" size="40" name="_tilelayer_utfgrid_url" value="<?php echo $utfgridurl;
        ?>" />
           <p class="description"><?php _e('Optional UTFGrid URL. E.g.: http://{s}.example.com/{z}/{x}/{y}.grid.json', 'jeo');
        ?></p>
          </td>
         </tr>
         <tr>
          <th><label for="tilelayer_utfgrid_template"><?php _e('UTFGrid Template (optional)', 'jeo');
        ?></label></th>
          <td>
           <textarea id="tilelayer_utfgrid_template" rows="10" cols="40" name="_tilelayer_utfgrid_template"><?php echo $utfgrid_template;
        ?></textarea>
           <p class="description"><?php _e('UTFGrid template using mustache.<br/>E.g.: City: {{city}}');
        ?></p>
          </td>
         </tr>
         <tr>
          <th><label for="tilelayer_tms"><?php _e('TMS', 'jeo');
        ?></label></th>
          <td>
           <input id="tilelayer_tms" type="checkbox" name="_tilelayer_tms" <?php if ($tms) {
    echo 'checked';
}
        ?> /> <label for="tilelayer_tms"><?php _e('Enable TMS', 'jeo');
        ?></label>
           <p class="description"><?php _e('Inverses Y axis numbering for tiles (turn this on for TMS services).');
        ?></p>
          </td>
         </tr>
        </tbody>
       </table>

       <table class="form-table type-setting wmslayer">
        <?php
        $wmstileurl = $post ? get_post_meta($post->ID, '_wmslayer_tile_url', true) : '';
        $layername = $post ? get_post_meta($post->ID, '_wmslayer_layer_name', true) : '';
        $wms_format = $post ? get_post_meta($post->ID, '_wmslayer_wms_format', true) : '';
        $transparent = $post ? get_post_meta($post->ID, '_wmslayer_transparent', true) : '';
        ?>
        <tbody>
         <tr>
          <th><label for="wmslayer_tile_url"><?php _e('WMS Service URL', 'opendev');
        ?></label></th>
          <td>
           <input id="wmslayer_tile_url" type="text" placeholder="<?php _e('http://{geoserver adress & port}/geoserver/wms', 'jeo');
        ?>" size="65" name="_wmslayer_tile_url" value="<?php echo $wmstileurl;
        ?>" />
           <p class="description"><?php _e('Eg. WMS URL: http://geoserver.example.com:8080/geoserver/wms', 'opendev');
        ?></p>
          </td>
         </tr>
         <tr>
          <th><label for="wmslayer_layer_name"><?php _e('Workspaces:Layer Name', 'opendev');
        ?></label></th>
          <td>
           <input id="wmslayer_layer_name" type="text" placeholder="<?php _e('Workspaces and Layer name"', 'jeo');
        ?>" size="40" name="_wmslayer_layer_name" value="<?php echo $layername;
        ?>" />
           <p class="description"><?php _e('Eg. in Geoserver, Energy:Transmission_lines, <strong>Engergy</strong> is workspace name and <strong>Transmission_lines</strong> is layer name.', 'opendev');
        ?></p>
          </td>
         </tr>
         <tr>
          <th><label for="wmslayer_wms_format"><?php _e('WMS format (optional)', 'opendev');
        ?></label></th>
          <td>
            <input id="wmslayer_wms_format" type="text" placeholder="<?php _e('image/png', 'opendev');
        ?>" size="40" name="_wmslayer_wms_format" value="<?php echo $wms_format;
        ?>" />
           <p class="description"><?php _e('E.g.: image/png or image/jpeg');
        ?></p>
          </td>
         </tr>
         <tr>
          <th><label for="wmslayer_transparent"><?php _e('Transparent', 'jeo');
        ?></label></th>
          <td>
          <?php $is_new_page = get_current_screen(); // Transparent is checked by default. ?>
           <input id="wmslayer_transparent" type="checkbox" name="_wmslayer_transparent" <?php if ($transparent) {
    echo 'checked';
} elseif ($is_new_page->action == 'add') {
    echo 'checked';
}
        ?> /> <label for="wmslayer_transparent"><?php _e('Transparent', 'jeo');
        ?></label>
           <p class="description"><?php _e('Enable it to make transparent the layer of WMS.');
        ?></p>
          </td>
         </tr>
        </tbody>
       </table>
       <table class="form-table type-setting mapbox">
        <?php

        $mapbox_id = $post ? get_post_meta($post->ID, '_mapbox_id', true) : '';

        ?>
        <tbody>
         <tr>
          <th><label for="mapbox_id"><?php _e('MapBox ID', 'jeo');
        ?></label></th>
          <td>
           <input id="mapbox_id" type="text" placeholder="examples.map-20v6611k" size="40" name="_mapbox_id" value="<?php echo $mapbox_id;
        ?>" />
           <p class="description"><?php _e('MapBox map ID. E.g.: examples.map-20v6611k', 'jeo');
        ?></p>
          </td>
         </tr>
        </tbody>
       </table>
       <table class="form-table type-setting cartodb">
        <?php

        // opt
        $cartodb_type = $post ? get_post_meta($post->ID, '_cartodb_type', true) : 'viz';

        // viz
        $vizurl = $post ? get_post_meta($post->ID, '_cartodb_viz_url', true) : '';

        // custom
        $username = $post ? get_post_meta($post->ID, '_cartodb_username', true) : '';
        $table = $post ? get_post_meta($post->ID, '_cartodb_table', true) : '';
        $where = $post ? get_post_meta($post->ID, '_cartodb_where', true) : '';
        $cartocss = $post ? get_post_meta($post->ID, '_cartodb_cartocss', true) : '';
        $template = $post ? get_post_meta($post->ID, '_cartodb_template', true) : '';

        ?>
        <tbody>
         <tr>
          <th><?php _e('Visualization type', 'jeo');
        ?></th>
          <td>
           <input name="_cartodb_type" id="cartodb_viz_type_viz" type="radio" value="viz" <?php if ($cartodb_type == 'viz' || !$cartodb_type) {
    echo 'checked';
}
        ?> />
           <label for="cartodb_viz_type_viz"><?php _e('Visualization', 'jeo');
        ?></label>
           <input name="_cartodb_type" id="cartodb_viz_type_custom" type="radio" value="custom" disabled <?php if ($cartodb_type == 'custom') {
    echo 'checked';
}
        ?> />
           <label for="cartodb_viz_type_custom"><?php _e('Advanced (build from your tables)', 'jeo');
        ?> - <?php _e('coming soon', 'jeo');
        ?></label>
          </td>
         </tr>
         <tr class="subopt viz_type_viz">
          <th><label for="cartodb_viz_url"><?php _e('CartoDB URL', 'jeo');
        ?></label></th>
          <td>
           <input id="cartodb_viz_url" type="text" placeholder="http://user.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json" size="40" name="_cartodb_viz_url" value="<?php echo $vizurl;
        ?>" />
           <p class="description"><?php _e('CartoDB visualization URL.<br/>E.g.: http://infoamazonia.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json', 'jeo');
        ?></p>
          </td>
         </tr>
         <tr class="subopt viz_type_custom">
          <th><label for="cartodb_viz_username"><?php _e('Username', 'jeo');
        ?></label></th>
          <td>
           <input id="cartodb_viz_username" type="text" placeholder="johndoe" name="_cartodb_username" value="<?php echo $username;
        ?>" />
           <p class="description"><?php _e('Your CartoDB username.');
        ?></p>
          </td>
         </tr>
         <tr class="subopt viz_type_custom">
          <th><label for="cartodb_viz_table"><?php _e('Table', 'jeo');
        ?></label></th>
          <td>
           <input id="cartodb_viz_table" type="text" placeholder="deforestation_2012" name="_cartodb_table" value="<?php echo $table;
        ?>" />
           <p class="description"><?php _e('The CartoDB table you\'d like to visualize.');
        ?></p>
          </td>
         </tr>
         <tr class="subopt viz_type_custom">
          <th><label for="cartodb_viz_where"><?php _e('Where (optional)', 'jeo');
        ?></label></th>
          <td>
           <textarea id="cartodb_viz_where" rows="3" cols="40" name="_cartodb_where"><?php echo $where;
        ?></textarea>
           <p class="description"><?php _e('Query data from your table.<br/>E.g.: region = "north"');
        ?></p>
          </td>
         </tr>
         <tr class="subopt viz_type_custom">
          <th><label for="cartodb_viz_cartocss"><?php _e('CartoCSS', 'jeo');
        ?></label></th>
          <td>
           <textarea id="cartodb_viz_cartocss" rows="10" cols="40" name="_cartodb_cartocss"><?php echo $cartocss;
        ?></textarea>
           <p class="description"><?php printf(__('Styles for your table. <a href="%s" target="_blank">Learn more</a>.'), 'https://www.mapbox.com/tilemill/docs/manual/carto/');
        ?></p>
          </td>
         </tr>
         <tr class="subopt viz_type_custom">
          <th><label for="cartodb_viz_template"><?php _e('Template', 'jeo');
        ?></label></th>
          <td>
           <textarea id="cartodb_viz_template" rows="10" cols="40" name="_cartodb_template"><?php echo $template;
        ?></textarea>
           <p class="description"><?php _e('UTFGrid template using mustache.<br/>E.g.: City: {{city}}');
        ?></p>
          </td>
         </tr>
        </tbody>
       </table>
      </div>
      <style type="text/css">
       .layer-type label,
       .form-table label {
        margin-right: 10px;
       }
      </style>
      <script type="text/javascript">
       jQuery(document).ready(function($) {

        var $container = $('#layer_settings_box');
        var $layerSelection = $container.find('input[name="layer_type"]');
        var $forms = $container.find('.form-table');

        $forms.hide();

        var showForms = function() {

         var selected = $layerSelection.filter(':checked').val();

         $forms.hide().filter('.' + selected).show();

        }

        $layerSelection.on('change', function() {
         showForms();
        });
        showForms();

        /*
         * CartoDB sub options
         */

        var $form = $forms.filter('.cartodb');

        var $subOpts = $form.find('tr.subopt');

        $subOpts.hide();

        var showSubOpts = function() {
         var selected = $form.find('input[name="_cartodb_type"]:checked').val();
         $subOpts.hide().filter('.viz_type_' + selected).show();
        };

        $form.find('input[name="_cartodb_type"]').on('change', function() {
         showSubOpts();
        });
        showSubOpts();

       });
      </script>
      <?php

    }

     public function layer_save($post_id)
     {
         if (get_post_type($post_id) == 'map-layer') {
             if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                 return;
             }

             if (false !== wp_is_post_revision($post_id)) {
                 return;
             }

               /*
                * Download URL
                */
                if (isset($_REQUEST['_layer_download_link'])) {
                    update_post_meta($post_id, '_layer_download_link', $_REQUEST['_layer_download_link']);
                }

               /*
                * Layer legend
                */
               if (isset($_REQUEST['_layer_legend'])) {
                   update_post_meta($post_id, '_layer_legend', $_REQUEST['_layer_legend']);
               }

               /*
                * Layer type
                */
               if (isset($_REQUEST['layer_type'])) {
                   wp_set_object_terms($post_id, $_REQUEST['layer_type'], 'layer-type', false);
               }

               /*
                * Tilelayer
                */

               if (isset($_REQUEST['_tilelayer_tile_url'])) {
                   update_post_meta($post_id, '_tilelayer_tile_url', $_REQUEST['_tilelayer_tile_url']);
               }

             if (isset($_REQUEST['_tilelayer_utfgrid_url'])) {
                 update_post_meta($post_id, '_tilelayer_utfgrid_url', $_REQUEST['_tilelayer_utfgrid_url']);
             }

             if (isset($_REQUEST['_tilelayer_utfgrid_template'])) {
                 update_post_meta($post_id, '_tilelayer_utfgrid_template', $_REQUEST['_tilelayer_utfgrid_template']);
             }

             if (isset($_REQUEST['_tilelayer_tms'])) {
                 if ($_REQUEST['_tilelayer_tms']) {
                     update_post_meta($post_id, '_tilelayer_tms', true);
                 }
             } else {
                 delete_post_meta($post_id, '_tilelayer_tms');
             }

               /*
                * WMS Tilelayer
                */

               if (isset($_REQUEST['_wmslayer_tile_url'])) {
                   update_post_meta($post_id, '_wmslayer_tile_url', $_REQUEST['_wmslayer_tile_url']);
               }

             if (isset($_REQUEST['_wmslayer_layer_name'])) {
                 update_post_meta($post_id, '_wmslayer_layer_name', $_REQUEST['_wmslayer_layer_name']);
             }

             if (isset($_REQUEST['_wmslayer_wms_format'])) {
                 update_post_meta($post_id, '_wmslayer_wms_format', $_REQUEST['_wmslayer_wms_format']);
             }

             if (isset($_REQUEST['_wmslayer_transparent'])) {
                 if ($_REQUEST['_wmslayer_transparent']) {
                     update_post_meta($post_id, '_wmslayer_transparent', true);
                 }
             } else {
                 delete_post_meta($post_id, '_wmslayer_transparent');
             }

               /*
                * MapBox
                */

               if (isset($_REQUEST['_mapbox_id'])) {
                   update_post_meta($post_id, '_mapbox_id', $_REQUEST['_mapbox_id']);
               }

               /*
                * CartoDB
                */

               if (isset($_REQUEST['_cartodb_type'])) {
                   update_post_meta($post_id, '_cartodb_type', $_REQUEST['_cartodb_type']);
               }

             if (isset($_REQUEST['_cartodb_viz_url'])) {
                 update_post_meta($post_id, '_cartodb_viz_url', $_REQUEST['_cartodb_viz_url']);
             }

             if (isset($_REQUEST['_cartodb_username'])) {
                 update_post_meta($post_id, '_cartodb_username', $_REQUEST['_cartodb_username']);
             }

             if (isset($_REQUEST['_cartodb_table'])) {
                 update_post_meta($post_id, '_cartodb_table', $_REQUEST['_cartodb_table']);
             }

             if (isset($_REQUEST['_cartodb_where'])) {
                 update_post_meta($post_id, '_cartodb_where', $_REQUEST['_cartodb_where']);
             }

             if (isset($_REQUEST['_cartodb_cartocss'])) {
                 update_post_meta($post_id, '_cartodb_cartocss', $_REQUEST['_cartodb_cartocss']);
             }

             if (isset($_REQUEST['_cartodb_template'])) {
                 update_post_meta($post_id, '_cartodb_template', $_REQUEST['_cartodb_template']);
             }

             do_action('jeo_layer_save', $post_id);
         }// if post type: 'map-layer'
     }//end function

     public function get_layer($post_id = false)
     {
         global $post;
         $post_id = $post_id ? $post_id : $post->ID;

         $post = get_post($post_id);
         setup_postdata($post);

         $type = $this->get_layer_type();

         $layer = array(
           'ID' => $post->ID,
           'title' => get_the_title(),
           'post_content' => content(999),
           'excerpt' => content(40),
           'download_url' => get_post_meta($post->ID, '_layer_download_link', true),
           'type' => $type,
           'legend' => get_post_meta($post->ID, '_layer_legend', true),
          );

         if ($type == 'tilelayer') {
             $layer['tile_url'] = htmlspecialchars(urldecode(get_post_meta($post->ID, '_tilelayer_tile_url', true)));
             $layer['utfgrid_url'] = get_post_meta($post->ID, '_tilelayer_utfgrid_url', true);
             $layer['utfgrid_template'] = get_post_meta($post->ID, '_tilelayer_utfgrid_template', true);
             $layer['tms'] = get_post_meta($post->ID, '_tilelayer_tms', true);
         } elseif ($type == 'wmslayer') {
             $layer['wms_tile_url'] = htmlspecialchars(urldecode(get_post_meta($post->ID, '_wmslayer_tile_url', true)));
             $layer['wms_layer_name'] = get_post_meta($post->ID, '_wmslayer_layer_name', true);
             $layer['wms_format'] = get_post_meta($post->ID, '_wmslayer_wms_format', true);
             $layer['wms_transparent'] = get_post_meta($post->ID, '_wmslayer_transparent', true);
         } elseif ($type == 'mapbox') {
             $layer['mapbox_id'] = get_post_meta($post->ID, '_mapbox_id', true);
         } elseif ($type == 'cartodb') {
             $layer['cartodb_type'] = get_post_meta($post->ID, '_cartodb_type', true);
             if ($layer['cartodb_type'] == 'viz') {
                 $layer['cartodb_viz_url'] = get_post_meta($post->ID, '_cartodb_viz_url', true);
             } else {
                 $layer['cartodb_username'] = get_post_meta($post->ID, '_cartodb_username', true);
                 $layer['cartodb_table'] = get_post_meta($post->ID, '_cartodb_table', true);
                 $layer['cartodb_where'] = get_post_meta($post->ID, '_cartodb_where', true);
                 $layer['cartodb_cartocss'] = get_post_meta($post->ID, '_cartodb_cartocss', true);
                 $layer['cartodb_template'] = get_post_meta($post->ID, '_cartodb_template', true);
             }
         }

         wp_reset_postdata();

         return $layer;
     }
 } //class
// Init Child class

$GLOBALS['extended_jeo_layers'] = new Extended_JEO_Layers();

function extended_jeo_get_layer($post_id = false)
{
    return $GLOBALS['extended_jeo_layers']->get_layer($post_id);
}

?>
