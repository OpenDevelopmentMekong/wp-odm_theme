<?php
// Extend parent theme class
 class Extended_JEO_Layers extends JEO_Layers {
    function __construct() {
        $this->language = array('ODM' => "", 'Cambodia' => "Khmer", 'Laos' => "Lao", 'Myanmar' => "Burmese",'Thailand' => "Thai", 'Vietnam' => "Vietnamese");
         // Call parent class constructor
        // parent::__construct();
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'layer_save'));
        add_action('save_post', array($this, 'map_save'));

        add_post_type_support( 'map-layer', 'thumbnail' );
    }

    function add_layers_box_to_post_types(){
      $supported_post_types = array("map", "profiles");
      return $supported_post_types;
    }

    function get_localization_language($site=""){
        $site_name = str_replace('Open Development ', '', get_bloginfo('name'));
        $language['ODM'] = "";
        $language['Cambodia'] = "Khmer";
        $language['Laos'] = "Lao";
        $language['Myanmar'] = "Burmese";
        $language['Thailand'] = "Thai";
        $language['Vietnam'] = "Vietnamese";
        return $language[$site_name];
    }
    function add_meta_box() {
        // Layer settings
        add_meta_box(
            'layer-settings',
            __('Layer settings', 'jeo'),
            array($this, 'settings_box'),
            'map-layer',
            'advanced',
            'high'
        );
        // Layer legend
        add_meta_box(
            'layer-legend',
            __('Layer legend', 'jeo'),
            array($this, 'legend_box'),
            'map-layer',
            'side',
            'default'
        );
        // Post layers
        add_meta_box(
            'post-layers',
            __('Layers', 'jeo'),
            array($this, 'post_layers_box'),
            $this->add_layers_box_to_post_types(),
            'advanced',
            'high'
        );
    }

    function legend_box($post = false) {
        $legend = $post ? get_post_meta($post->ID, '_layer_legend', true) : '';
        $legend_localization = $post ? get_post_meta($post->ID, '_layer_legend_localization', true) : '';

        ?>
        <h4><?php _e('Enter your HTML code to use as legend on the layer (English)', 'jeo'); ?></h4>
        <textarea name="_layer_legend" style="width:100%;height: 200px;"><?php echo $legend; ?></textarea>
        <?php if($this->get_localization_language()){ ?>
              <h4><?php _e('Enter your HTML code to use as legend on the layer ('.$this->get_localization_language().')', 'jeo'); ?></h4>
              <textarea name="_layer_legend_localization" style="width:100%;height: 200px;"><?php echo $legend_localization; ?></textarea>
        <?php
            }
    }

    function post_layers_box($post = false) {
     $layer_query = new WP_Query(array('post_type' => 'map-layer', 'posts_per_page' => -1));
     $layers = array();
     $post_layers = $post ? $this->get_map_layers($post->ID) : false;
     ?>

     <p>
      <?php
      printf(__('Add and manage <a href="%s" target="_blank">layers</a> on your map.', 'jeo'), admin_url('edit.php?post_type=map-layer'));
      if(!$layer_query->have_posts())
       printf(__(' You haven\'t created any layers yet, <a href="%s" target="_blank">click here</a> to create your first!'), admin_url('post-new.php?post_type=map-layer'));
      ?>
     </p>

     <?php
     if($layer_query->have_posts()) {
      while($layer_query->have_posts()) {
       $layer_query->the_post();
       $layers[] = $this->get_layer(get_the_ID());
       wp_reset_postdata();
      }
      ?>
      <input type="text" data-bind="textInput: search" placeholder="<?php _e('Search for layers', 'jeo'); ?>" size="50">

      <!-- ko if: !search() -->
       <h4 class="results-title"><?php _e('Latest layers', 'jeo'); ?></h4>
      <!-- /ko -->

      <!-- ko if: search() -->
       <h4 class="results-title"><?php _e('Search results', 'jeo'); ?></h4>
      <!-- /ko -->

      <!-- ko if: !filteredLayers().length && !search() -->
       <p style="font-style:italic;color: #999;"><?php _e('You are using all of your layers.', 'jeo'); ?></p>
      <!-- /ko -->

      <!-- ko if: !filteredLayers().length && search() -->
       <p style="font-style:italic;color: #999;"><?php _e('No layers were found.', 'jeo'); ?></p>
      <!-- /ko -->

      <table class="layers-list available-layers">
       <tbody data-bind="foreach: filteredLayers">
        <tr>
         <td><strong data-bind="text: title"></strong></td>
         <td data-bind="text: type"></td>
         <td style="width:1%;"><a class="button" data-bind="click: $parent.addLayer" href="javascript:void(0);" title="<?php _e('Add layer', 'jeo'); ?>">+ <?php _e('Add'); ?></a></td>
        </tr>
       </tbody>
      </table>

      <h4 class="selected-title"><?php _e('Selected layers', 'jeo'); ?></h4>

      <table class="layers-list selected-layers">
       <tbody class="selected-layers-list">
        <!-- ko foreach: {data: selectedLayers} -->
         <tr class="layer-item">
          <td style="width: 30%;">
           <p><strong data-bind="text: title"></strong></p>
           <p data-bind="text: type"></p>
          </td>
          <td>
           <p><?php _e('Layer options', 'jeo'); ?></p>
           <div class="filter-opts">
            <input type="radio" value="fixed" data-bind="attr: {name: ID + '_filtering_opt', id: ID + '_filtering_opt_fixed'}, checked: $data.filtering" />
            <label data-bind="attr: {for: ID + '_filtering_opt_fixed'}"><?php _e('Enable', 'jeo'); ?></label> &nbsp; &nbsp; &nbsp;
            <input  type="radio" value="switch" data-bind="attr: {name: ID + '_filtering_opt', id: ID + '_filtering_opt_switch'}, checked: $data.filtering" />
            <label data-bind="attr: {for: ID + '_filtering_opt_switch'}"><?php _e('Disable', 'jeo'); ?></label>
            <!--<input type="radio" value="swap"  data-bind="attr: {name: ID + '_filtering_opt', id: ID + '_filtering_opt_swap'}, checked: $data.filtering" />
            <label data-bind="attr: {for: ID + '_filtering_opt_swap'}"><?php _e('Swapable', 'jeo'); ?></label>-->

            <div class="filtering-opts" style="display: none;">
             <!-- ko if: $data.filtering() == 'switch' -->
              <input type="checkbox" data-bind="attr: {id: ID + '_switch_hidden'}, checked: $data.hidden" />
              <label data-bind="attr: {for: ID + '_switch_hidden'}"><?php _e('Hidden', 'jeo'); ?></label>
             <!-- /ko -->
             <!-- ko if: $data.filtering() == 'swap' -->
              <input type="radio" data-bind="attr: {id: ID + '_first_swap'}, checked: $data.first_swap" name="_jeo_map_layer_first_swap" />
              <label data-bind="attr: {for: ID + '_first_swap'}"><?php _e('Default swap option', 'jeo'); ?></label>
             <!-- /ko -->
            </div>
           </div>
          </td>
          <td style="width:1%;"><a class="button" data-bind="click: $parent.removeLayer" href="javascript:void(0);" title="<?php _e('Remove layer', 'jeo'); ?>"><?php _e('Remove'); ?></a></td>
         </tr>
        <!-- /ko -->
       </tbody>
      </table>

      <input type="hidden" name="_jeo_map_layers" data-bind="textInput: selection" />

      <style type="text/css">
       #post-layers input[type='text'] {
        width: 100%;
       }
       #post-layers .layers-list {
        background: #fcfcfc;
        border-collapse: collapse;
        width: 100%;
       }
       #post-layers .selected-layers .layer-item {
        width: 100%;
        height: 100px;
       }
       #post-layers .layers-list tr td {
        margin: 0;
        border: 1px solid #f0f0f0;
        padding: 5px 8px;
       }
       #post-layers .layers-list tr:hover td {
        background: #fff;
       }
      </style>

      <script type="text/javascript">
       function LayersModel() {
        var self = this;

        var origLayers = <?php echo json_encode($layers); ?>;
        self.search = ko.observable('');

        self.addLayer = function(layer) {
         var layer = layer || this;
         if(typeof layer.filtering !== 'function')
          layer.filtering = ko.observable(layer.filtering || 'fixed');
         if(typeof layer.hidden !== 'function')
          layer.hidden = ko.observable(layer.hidden || false);
         if(typeof layer.first_swap !== 'function')
          layer.first_swap = ko.observable(layer.first_swap || false);
         self.selectedLayers.push(layer);
         self.layers.remove(layer);
        };

        self.removeLayer = function(layer) {
         var layer = layer || this;
         self.layers.push(layer);
         self.selectedLayers.remove(layer);
        };

        /*
         * Layer list
         */

        self.layers = ko.observableArray(origLayers.slice(0));

        self.filteredLayers = ko.computed(function() {
         if(!self.search()) {
          return self.layers().slice(0, 4);
         } else {
          return ko.utils.arrayFilter(self.layers(), function(l) {
           return l.title.toLowerCase().indexOf(self.search().toLowerCase()) !== -1;
          }).slice(0, 4);
         }
        });

        /*
         * Layer selection
         */

        self.selectedLayers = ko.observableArray([]);

        var initSelection = <?php if($post_layers) echo json_encode($post_layers); else echo '[]'; ?>;
        if(initSelection.length) {
         _.each(initSelection, function(l) {
          var layer = _.extend(_.find(self.layers(), function(layer) {
           if(layer.ID == l.ID) {
            _.extend(l, layer);
            return true;
           }
           return false;
          }), l);
          self.addLayer(layer);
         });
        }

        self.selection = ko.computed(function() {
         var layers = [];
         _.each(self.selectedLayers(), function(layer) {
          var layer = _.extend({}, layer);
          layer.filtering = layer.filtering();
          layer.hidden = layer.hidden();
          layer.first_swap = layer.first_swap();
          layers.push(layer);
         });
         window.editingLayers = layers;
         return JSON.stringify(layers);
        });

        /*
         * Sortable selected layers
         */

        // jquery sort binding method
        self.bindSort = function(listSelector, listKey) {
         var startIndex = -1;

         var sortableSetup = {

          // on sorting start
          start: function (event, ui) {
           // cache the item index when the dragging starts
           startIndex = ui.item.index();
          },

          // on sorting stop
          stop: function (event, ui) {

           // get the new location item index
           var newIndex = ui.item.index();

           if (startIndex > -1) {
            //  get the item to be moved
            var item = self[listKey]()[startIndex];

            //  move the item
            self[listKey].remove(item);
            self[listKey].splice(newIndex, 0, item);

            //  ko rebinds to the array so we need to remove duplicate ui item
            ui.item.remove();
           }

          }
         };

         // bind jquery using the .fruitList class selector
         jQuery(listSelector).sortable( sortableSetup );

        };

       }

       jQuery(document).ready(function() {
        var model = new LayersModel();
        model.bindSort('.selected-layers-list', 'selectedLayers');
        ko.applyBindings(model);
       });
      </script>
      <?php
     }
    }

    function settings_box($post = false) {
        $layer_type = $post ? $this->get_layer_type($post->ID) : false;
        $layer_download_link = get_post_meta($post->ID, '_layer_download_link', true);
        $layer_download_link_localization = get_post_meta($post->ID, '_layer_download_link_localization', true);
        $layer_profilepage_link = get_post_meta($post->ID, '_layer_profilepage_link', true);
        $layer_profilepage_link_localization = get_post_meta($post->ID, '_layer_profilepage_link_localization', true);
        ?>
        <div id="layer_settings_box">
            <div class="layer-download-link">
                <h4><?php _e('Download Page URL', 'jeo'); ?></h4>
                <tbody>
                    <tr>
                        <th><label for="_layer_download_link"><?php _e('Download URL (English)', 'jeo'); ?></label></th>
                        <td>
                            <input id="_layer_download_link" type="text" placeholder="https://" size="40" name="_layer_download_link" value="<?php echo $layer_download_link; ?>" />
                            <p class="description"><?php _e('A link to a dataset\'s page on CKAN', 'jeo'); ?></p>
                        </td>
                    </tr>
                    <?php if($this->get_localization_language()){ ?>
                    <tr>
                        <th><label for="_layer_download_link_localization"><?php _e('Download URL ('.$this->get_localization_language().')', 'jeo'); ?></label></th>
                        <td>
                            <input id="_layer_download_link_localization" type="text" placeholder="https://" size="40" name="_layer_download_link_localization" value="<?php echo $layer_download_link_localization; ?>" />
                            <p class="description"><?php _e('A link to a dataset\'s page on CKAN', 'jeo'); ?></p>
                        </td>
                    </tr>
                 <?php } ?>
                </tbody>
            </div>

            <div class="layer-profilepage-link">
                <h4><?php _e('Profile Page URL', 'jeo'); ?></h4>
                <tbody>
                    <tr>
                        <th><label for="_layer_profilepage_link"><?php _e('Profile Page URL (English)', 'jeo'); ?></label></th>
                        <td>
                            <input id="_layer_profilepage_link" type="text" placeholder="https://" size="40" name="_layer_profilepage_link" value="<?php echo $layer_profilepage_link; ?>" />
                            <p class="description"><?php _e('A link to profile page on Wordpress', 'jeo'); ?></p>
                        </td>
                    </tr>
                    <?php if($this->get_localization_language()){ ?>
                    <tr>
                        <th><label for="_layer_profilepage_link_localization"><?php _e('Profile Page URL ('.$this->get_localization_language().')', 'jeo'); ?></label></th>
                        <td>
                            <input id="_layer_profilepage_link_localization" type="text" placeholder="https://" size="40" name="_layer_profilepage_link_localization" value="<?php echo $layer_profilepage_link_localization; ?>" />
                            <p class="description"><?php _e('A link to profile page on Wordpress', 'jeo'); ?></p>
                        </td>
                    </tr>
                  <?php } ?>
                </tbody>
            </div>

            <div class="layer-type">
                <h4><?php _e('Layer type', 'jeo'); ?></h4>
                <p>
                     <input type="radio" id="layer_type_tilelayer" name="layer_type" value="tilelayer" <?php if($layer_type == 'tilelayer' || !$layer_type) echo 'checked'; ?> />
                     <label for="layer_type_tilelayer"><?php _e('Tile layer', 'jeo'); ?></label>
                     <input type="radio" id="layer_type_wmslayer" name="layer_type" value="wmslayer" <?php if($layer_type == 'wmslayer') echo 'checked'; ?> />
                     <label for="layer_type_wmslayer"><?php _e('WMS layer', 'jeo'); ?></label>

                     <input type="radio" id="layer_type_mapbox" name="layer_type" value="mapbox" <?php if($layer_type == 'mapbox') echo 'checked'; ?> />
                     <label for="layer_type_mapbox"><?php _e('MapBox', 'jeo'); ?></label>

                     <input type="radio" id="layer_type_cartodb" name="layer_type" value="cartodb" <?php if($layer_type == 'cartodb') echo 'checked'; ?> />
                     <label for="layer_type_cartodb"><?php _e('CartoDB', 'jeo'); ?></label>
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
                        <th><label for="tilelayer_tile_url"><?php _e('URL', 'jeo'); ?></label></th>
                        <td>
                            <input id="tilelayer_tile_url" type="text" placeholder="<?php _e('http://{s}.example.com/{z}/{x}/{y}.png', 'jeo'); ?>" size="40" name="_tilelayer_tile_url" value="<?php echo $tileurl; ?>" />
                            <p class="description"><?php _e('Tilelayer URL. E.g.: http://{s}.example.com/{z}/{x}/{y}.png', 'jeo'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tilelayer_utfgrid_url"><?php _e('UTFGrid URL (optional)', 'jeo'); ?></label></th>
                        <td>
                            <input id="tilelayer_utfgrid_url" type="text" placeholder="<?php _e('http://{s}.example.com/{z}/{x}/{y}.grid.json', 'jeo'); ?>" size="40" name="_tilelayer_utfgrid_url" value="<?php echo $utfgridurl; ?>" />
                            <p class="description"><?php _e('Optional UTFGrid URL. E.g.: http://{s}.example.com/{z}/{x}/{y}.grid.json', 'jeo'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tilelayer_utfgrid_template"><?php _e('UTFGrid Template (optional)', 'jeo'); ?></label></th>
                        <td>
                            <textarea id="tilelayer_utfgrid_template" rows="10" cols="40" name="_tilelayer_utfgrid_template"><?php echo $utfgrid_template; ?></textarea>
                            <p class="description"><?php _e('UTFGrid template using mustache.<br/>E.g.: City: {{city}}'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tilelayer_tms"><?php _e('TMS', 'jeo'); ?></label></th>
                        <td>
                            <input id="tilelayer_tms" type="checkbox" name="_tilelayer_tms" <?php if($tms) echo 'checked'; ?> /> <label for="tilelayer_tms"><?php _e('Enable TMS', 'jeo'); ?></label>
                            <p class="description"><?php _e('Inverses Y axis numbering for tiles (turn this on for TMS services).'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="form-table type-setting wmslayer">
                <?php
                $wmstileurl = $post ? get_post_meta($post->ID, '_wmslayer_tile_url', true) : '';
                $layername = $post ? get_post_meta($post->ID, '_wmslayer_layer_name', true) : '';
                $layername_localization = $post ? get_post_meta($post->ID, '_wmslayer_layer_name_localization', true) : '';
                $wms_format = $post ? get_post_meta($post->ID, '_wmslayer_wms_format', true) : '';
                $transparent = $post ? get_post_meta($post->ID, '_wmslayer_transparent', true) : '';
                ?>
                <tbody>
                    <tr>
                        <th><label for="wmslayer_tile_url"><?php _e('WMS Service URL', 'opendev'); ?></label></th>
                        <td>
                            <input id="wmslayer_tile_url" type="text" placeholder="<?php _e('http://{geoserver adress & port}/geoserver/wms', 'jeo'); ?>" size="65" name="_wmslayer_tile_url" value="<?php echo $wmstileurl; ?>" />
                            <p class="description"><?php _e('Eg. WMS URL: http://geoserver.example.com:8080/geoserver/wms', 'opendev'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="wmslayer_layer_name"><?php _e('Workspaces:Layer Name (English)', 'opendev'); ?></label></th>
                        <td>
                            <input id="wmslayer_layer_name" type="text" placeholder="<?php _e('Workspaces and Layer name"', 'jeo'); ?>" size="40" name="_wmslayer_layer_name" value="<?php echo $layername; ?>" />
                            <p class="description"><?php _e('Eg. in Geoserver, Energy:Transmission_lines, <strong>Engergy</strong> is workspace name and <strong>Transmission_lines</strong> is layer name.', 'opendev'); ?></p>
                        </td>
                    </tr>
                    <?php if($this->get_localization_language()){ ?>
                    <tr>
                        <th><label for="wmslayer_layer_name_localization"><?php _e('Workspaces:Layer Name ('.$this->get_localization_language().')', 'opendev'); ?></label></th>
                        <td>
                            <input id="wmslayer_layer_name_localization" type="text" placeholder="<?php _e('Workspaces and Layer name"', 'jeo'); ?>" size="40" name="_wmslayer_layer_name_localization" value="<?php echo $layername_localization; ?>" />
                            <p class="description"><?php _e('Eg. in Geoserver, Energy:Transmission_lines_kh, <strong>Engergy</strong> is workspace name and <strong>Transmission_lines</strong> is layer name.', 'opendev'); ?></p>
                        </td>
                     </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th><label for="wmslayer_wms_format"><?php _e('WMS format (optional)', 'opendev'); ?></label></th>
                        <td>
                            <input id="wmslayer_wms_format" type="text" placeholder="<?php _e('image/png', 'opendev'); ?>" size="40" name="_wmslayer_wms_format" value="<?php echo $wms_format; ?>" />
                            <p class="description"><?php _e('E.g.: image/png or image/jpeg'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="wmslayer_transparent"><?php _e('Transparent', 'jeo'); ?></label></th>
                        <td>
                            <?php $is_new_page = get_current_screen(); // Transparent is checked by default. ?>
                            <input id="wmslayer_transparent" type="checkbox" name="_wmslayer_transparent" <?php if($transparent) echo 'checked'; else if ( $is_new_page->action =="add") echo 'checked'; ?> /> <label for="wmslayer_transparent"><?php _e('Transparent', 'jeo'); ?></label>
                            <p class="description"><?php _e('Enable it to make transparent the layer of WMS.'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="form-table type-setting mapbox">
                <?php $mapbox_id = $post ? get_post_meta($post->ID, '_mapbox_id', true) : ''; ?>
                <tbody>
                    <tr>
                        <th><label for="mapbox_id"><?php _e('MapBox ID', 'jeo'); ?></label></th>
                        <td>
                            <input id="mapbox_id" type="text" placeholder="examples.map-20v6611k" size="40" name="_mapbox_id" value="<?php echo $mapbox_id; ?>" />
                            <p class="description"><?php _e('MapBox map ID. E.g.: examples.map-20v6611k', 'jeo'); ?></p>
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
              $vizurl_localization = $post ? get_post_meta($post->ID, '_cartodb_viz_url_localization', true) : '';

              // custom
              $username = $post ? get_post_meta($post->ID, '_cartodb_username', true) : '';
              $table = $post ? get_post_meta($post->ID, '_cartodb_table', true) : '';
              $where = $post ? get_post_meta($post->ID, '_cartodb_where', true) : '';
              $cartocss = $post ? get_post_meta($post->ID, '_cartodb_cartocss', true) : '';
              $template = $post ? get_post_meta($post->ID, '_cartodb_template', true) : '';

              ?>
              <tbody>
                <tr>
                  <th><?php _e('Visualization type', 'jeo'); ?></th>
                  <td>
                    <input name="_cartodb_type" id="cartodb_viz_type_viz" type="radio" value="viz" <?php if($cartodb_type == 'viz' || !$cartodb_type) echo 'checked'; ?> />
                    <label for="cartodb_viz_type_viz"><?php _e('Visualization', 'jeo'); ?></label>
                    <input name="_cartodb_type" id="cartodb_viz_type_custom" type="radio" value="custom" disabled <?php if($cartodb_type == 'custom') echo 'checked'; ?> />
                    <label for="cartodb_viz_type_custom"><?php _e('Advanced (build from your tables)', 'jeo'); ?> - <?php _e('coming soon', 'jeo'); ?></label>
                  </td>
                </tr>
                <tr class="subopt viz_type_viz">
                <th><label for="cartodb_viz_url"><?php _e('CartoDB URL (English)', 'jeo'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_url" type="text" placeholder="http://user.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json" size="40" name="_cartodb_viz_url" value="<?php echo $vizurl; ?>" />
                    <p class="description"><?php _e('CartoDB visualization URL.<br/>E.g.: http://infoamazonia.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json', 'jeo'); ?></p>
                  </td>
                </tr>
                <?php if($this->get_localization_language()){ ?>
                <tr class="subopt viz_type_viz">
                  <th><label for="cartodb_viz_url_localization"><?php _e('CartoDB URL ('.$this->get_localization_language().')', 'jeo'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_url_localization" type="text" placeholder="http://user.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json" size="40" name="_cartodb_viz_url_localization" value="<?php echo $vizurl_localization; ?>" />
                    <p class="description"><?php _e('CartoDB visualization URL.<br/>E.g.: http://infoamazonia.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json', 'jeo'); ?></p>
                  </td>
                </tr>
                <?php } ?>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_username"><?php _e('Username', 'jeo'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_username" type="text" placeholder="johndoe" name="_cartodb_username" value="<?php echo $username; ?>" />
                    <p class="description"><?php _e('Your CartoDB username.'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_table"><?php _e('Table', 'jeo'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_table" type="text" placeholder="deforestation_2012" name="_cartodb_table" value="<?php echo $table; ?>" />
                    <p class="description"><?php _e('The CartoDB table you\'d like to visualize.'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_where"><?php _e('Where (optional)', 'jeo'); ?></label></th>
                  <td>
                    <textarea id="cartodb_viz_where" rows="3" cols="40" name="_cartodb_where"><?php echo $where; ?></textarea>
                    <p class="description"><?php _e('Query data from your table.<br/>E.g.: region = "north"'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_cartocss"><?php _e('CartoCSS', 'jeo'); ?></label></th>
                  <td>
                    <textarea id="cartodb_viz_cartocss" rows="10" cols="40" name="_cartodb_cartocss"><?php echo $cartocss; ?></textarea>
                    <p class="description"><?php printf(__('Styles for your table. <a href="%s" target="_blank">Learn more</a>.'), 'https://www.mapbox.com/tilemill/docs/manual/carto/'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_template"><?php _e('Template', 'jeo'); ?></label></th>
                  <td>
                    <textarea id="cartodb_viz_template" rows="10" cols="40" name="_cartodb_template"><?php echo $template; ?></textarea>
                    <p class="description"><?php _e('UTFGrid template using mustache.<br/>E.g.: City: {{city}}'); ?></p>
                  </td>
                </tr>
              </tbody>
              </table>

        </div>
        <style type="text/css">
            .layer-type label, .form-table label {
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

    function layer_save($post_id) {
        if(get_post_type($post_id) == 'map-layer') {
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

            if (false !== wp_is_post_revision($post_id))
            return;
            /*
            * Download URL
            */
            if(isset($_REQUEST['_layer_download_link']))
                update_post_meta($post_id, '_layer_download_link', $_REQUEST['_layer_download_link']);
           /*
            * Download URL in other language
            */
            if(isset($_REQUEST['_layer_download_link_localization']))
                update_post_meta($post_id, '_layer_download_link_localization', $_REQUEST['_layer_download_link_localization']);

            /*
            * Profile Page URL
            */
            if(isset($_REQUEST['_layer_profilepage_link']))
                update_post_meta($post_id, '_layer_profilepage_link', $_REQUEST['_layer_profilepage_link']);
            /*
            * Profile Page URL in other language
            */
            if(isset($_REQUEST['_layer_profilepage_link_localization']))
                update_post_meta($post_id, '_layer_profilepage_link_localization', $_REQUEST['_layer_profilepage_link_localization']);

            /*
            * Layer legend
            */
            if(isset($_REQUEST['_layer_legend']))
                update_post_meta($post_id, '_layer_legend', $_REQUEST['_layer_legend']);

            if(isset($_REQUEST['_layer_legend_localization']))
                update_post_meta($post_id, '_layer_legend_localization', $_REQUEST['_layer_legend_localization']);

            /*
            * Layer type
            */
            if(isset($_REQUEST['layer_type']))
                wp_set_object_terms($post_id, $_REQUEST['layer_type'], 'layer-type', false);

            /*
            * Tilelayer
            */

            if(isset($_REQUEST['_tilelayer_tile_url']))
                update_post_meta($post_id, '_tilelayer_tile_url', $_REQUEST['_tilelayer_tile_url']);

            if(isset($_REQUEST['_tilelayer_utfgrid_url']))
                update_post_meta($post_id, '_tilelayer_utfgrid_url', $_REQUEST['_tilelayer_utfgrid_url']);

            if(isset($_REQUEST['_tilelayer_utfgrid_template']))
                update_post_meta($post_id, '_tilelayer_utfgrid_template', $_REQUEST['_tilelayer_utfgrid_template']);

            if(isset($_REQUEST['_tilelayer_tms'])) {
                if($_REQUEST['_tilelayer_tms'])
                    update_post_meta($post_id, '_tilelayer_tms', true);
            } else {
                delete_post_meta($post_id, '_tilelayer_tms');
            }

            /*
            * WMS Tilelayer
            */
            if(isset($_REQUEST['_wmslayer_tile_url']))
                update_post_meta($post_id, '_wmslayer_tile_url', $_REQUEST['_wmslayer_tile_url']);

            if(isset($_REQUEST['_wmslayer_layer_name']))
                update_post_meta($post_id, '_wmslayer_layer_name', $_REQUEST['_wmslayer_layer_name']);

            if(isset($_REQUEST['_wmslayer_layer_name_localization']))
                update_post_meta($post_id, '_wmslayer_layer_name_localization', $_REQUEST['_wmslayer_layer_name_localization']);

            if(isset($_REQUEST['_wmslayer_wms_format']))
                update_post_meta($post_id, '_wmslayer_wms_format', $_REQUEST['_wmslayer_wms_format']);

            if(isset($_REQUEST['_wmslayer_transparent'])) {
                if($_REQUEST['_wmslayer_transparent'])
                    update_post_meta($post_id, '_wmslayer_transparent', true);
            } else {
                delete_post_meta($post_id, '_wmslayer_transparent');
            }

            /*
            * MapBox
            */
            if(isset($_REQUEST['_mapbox_id']))
                update_post_meta($post_id, '_mapbox_id', $_REQUEST['_mapbox_id']);

            /*
            * CartoDB
            */
            if(isset($_REQUEST['_cartodb_type']))
                update_post_meta($post_id, '_cartodb_type', $_REQUEST['_cartodb_type']);

            if(isset($_REQUEST['_cartodb_viz_url']))
                update_post_meta($post_id, '_cartodb_viz_url', $_REQUEST['_cartodb_viz_url']);

            if(isset($_REQUEST['_cartodb_viz_url_localization']))
                update_post_meta($post_id, '_cartodb_viz_url_localization', $_REQUEST['_cartodb_viz_url_localization']);

            if(isset($_REQUEST['_cartodb_username']))
                update_post_meta($post_id, '_cartodb_username', $_REQUEST['_cartodb_username']);

            if(isset($_REQUEST['_cartodb_table']))
                update_post_meta($post_id, '_cartodb_table', $_REQUEST['_cartodb_table']);

            if(isset($_REQUEST['_cartodb_where']))
                update_post_meta($post_id, '_cartodb_where', $_REQUEST['_cartodb_where']);

            if(isset($_REQUEST['_cartodb_cartocss']))
                update_post_meta($post_id, '_cartodb_cartocss', $_REQUEST['_cartodb_cartocss']);

            if(isset($_REQUEST['_cartodb_template']))
                update_post_meta($post_id, '_cartodb_template', $_REQUEST['_cartodb_template']);

            do_action('jeo_layer_save', $post_id);
        }// if post type: 'map-layer'
    }//end function

    function map_save($post_id) {
     if(in_array(get_post_type($post_id), $this->add_layers_box_to_post_types() )) {
      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
       return;

      if (false !== wp_is_post_revision($post_id))
       return;

      if(isset($_REQUEST['_jeo_map_layers'])) {
       update_post_meta($post_id, '_jeo_map_layers', json_decode(stripslashes($_REQUEST['_jeo_map_layers']), true));
      }
     }
    }

    function get_map_layers($post_id = false, $filter = "") {
     global $post;
     $post_id = $post_id ? $post_id : $post->ID;

     $layers = array();

     $map_layers = get_post_meta($post_id, '_jeo_map_layers', true);

     if($map_layers) {
        foreach($map_layers as $l) {
           $layer = $this->get_layer($l['ID']);
           $layer['filtering'] = $l['filtering'];
           if($layer['filtering'] == 'swap') {
            $layer['first_swap'] = $l['first_swap'];
           } elseif($layer['filtering'] == 'switch') {
            $layer['hidden'] = $l['hidden'];
           }

           if($filter == "baselayer") {
               if($layer['map_category'] == "base-layers"){
                  $layer['post_title'] = $l['title'];
                  $layers[] = $layer;
               }
           }elseif($filter == "layer") {
              if($layer['map_category'] != "base-layers"){
                  $layers[] = $layer;
              }
          }else{
             $layers[] = $layer;
           }
        }//foreach
     }

     return $layers;

    }

    function get_layer($post_id = false) {
        global $post;
        $post_id = $post_id ? $post_id : $post->ID;

        $post = get_post($post_id);
        setup_postdata($post);

        $type = $this->get_layer_type();

        //$content = apply_filters('the_content', $post->post_content);
        $content = apply_filters('translate_text', $post->post_content, odm_language_manager()->get_current_language());
        $excerpt = apply_filters('translate_text', $post->excerpt, odm_language_manager()->get_current_language());

        $in_category = get_the_terms( $post->ID, 'layer-category' );
        if ( (odm_language_manager()->get_current_language() != "en") ){
            $layer_legend = get_post_meta( $post->ID , '_layer_legend_localization', true);
        }else {
            $layer_legend = get_post_meta( $post->ID , '_layer_legend', true);
        }
        $layer = array(
            'ID' => $post->ID,
            'title' => get_the_title(),
            //'post_content' => $content, //content(999)
            //'excerpt' => $excerpt,
            'map_category' => $in_category[0]->slug,
            //'download_url' => get_post_meta($post->ID, '_layer_download_link', true),
            //'download_url_localization' => get_post_meta($post->ID, '_layer_download_link_localization', true),
            //'profilepage_url' => get_post_meta($post->ID, '_layer_profilepage_link', true),
            //'profilepage_url_localization' => get_post_meta($post->ID, '_layer_profilepage_link_localization', true),
            'type' => $type
            //, 'legend' => $layer_legend
        );

        if($type == 'tilelayer') {
            $layer['tile_url'] = htmlspecialchars(urldecode(get_post_meta($post->ID, '_tilelayer_tile_url', true)));
            $layer['utfgrid_url'] = get_post_meta($post->ID, '_tilelayer_utfgrid_url', true);
            $layer['utfgrid_template'] = get_post_meta($post->ID, '_tilelayer_utfgrid_template', true);
            $layer['tms'] = get_post_meta($post->ID, '_tilelayer_tms', true);
        }
        elseif($type == 'wmslayer') {
            $layer['wms_tile_url'] = htmlspecialchars(urldecode(get_post_meta($post->ID, '_wmslayer_tile_url', true)));
            $layer['wms_layer_name'] = get_post_meta($post->ID, '_wmslayer_layer_name', true);
            $layer['wms_layer_name_localization'] = get_post_meta($post->ID, '_wmslayer_layer_name_localization', true);
            $layer['wms_format'] = get_post_meta($post->ID, '_wmslayer_wms_format', true);
            $layer['wms_transparent'] = get_post_meta($post->ID, '_wmslayer_transparent', true);
        }
        elseif($type == 'mapbox') {
            $layer['mapbox_id'] = get_post_meta($post->ID, '_mapbox_id', true);
        }
        elseif($type == 'cartodb') {
            $layer['cartodb_type'] = get_post_meta($post->ID, '_cartodb_type', true);
            if($layer['cartodb_type'] == 'viz') {
                $layer['cartodb_viz_url'] = get_post_meta($post->ID, '_cartodb_viz_url', true);
                $layer['cartodb_viz_url_localization'] = get_post_meta($post->ID, '_cartodb_viz_url_localization', true);
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
    }//end function
} //class
// Init Child class

new Extended_JEO_Layers();

$GLOBALS['extended_jeo_layers'] = new Extended_JEO_Layers();

function extended_jeo_get_layer($post_id = false) {
    return $GLOBALS['extended_jeo_layers']->get_layer($post_id);
}
?>
