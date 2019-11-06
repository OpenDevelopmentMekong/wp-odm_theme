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
        add_action('add_meta_boxes', array($this, 'od_mapbox_add_meta_box'));
        add_action( 'init', array($this, 'od_add_category_to_map_layer_posttype'));
        add_post_type_support( 'map-layer', 'thumbnail' );
    }

    function od_add_category_to_map_layer_posttype() {
      register_taxonomy_for_object_type( 'category', 'map-layer' );
    }

    function add_layers_box_to_post_types(){
      $supported_post_types = array("map", "profiles");
      return $supported_post_types;
    }

    function add_meta_box() {
        // Layer settings
        add_meta_box(
            'layer-settings',
            __('Layer settings', 'odm'),
            array($this, 'settings_box'),
            'map-layer',
            'advanced',
            'high'
        );
        // Layer legend
        add_meta_box(
            'layer-legend',
            __('Layer legend', 'odm'),
            array($this, 'legend_box'),
            'map-layer',
            'side',
            'default'
        );
        // Post layers
        add_meta_box(
            'post-layers',
            __('Layers', 'odm'),
            array($this, 'post_layers_box'),
            $this->add_layers_box_to_post_types(),
            'advanced',
            'high'
        );
    }

    function od_mapbox_add_meta_box() {
    	// register the metabox
    	add_meta_box(
    		'mapbox', // metabox id
    		__('Map setup', 'odm'), // metabox title
    		'mapbox_inner_custom_box', // metabox inner code
    		'profiles', // post type
    		'advanced', // metabox position (advanced to show on main area)
    		'high' // metabox priority (kind of an ordering)
    	);
    }

    function legend_box($post = false) {
        $legend = $post ? get_post_meta($post->ID, '_layer_legend', true) : '';
        $legend_localization = $post ? get_post_meta($post->ID, '_layer_legend_localization', true) : '';

        ?>
        <h4><?php _e('Enter your HTML code to use as legend on the layer (English)', 'odm'); ?></h4>
        <textarea name="_layer_legend" style="width:100%;height: 200px;"><?php echo $legend; ?></textarea>
        <?php if(odm_language_manager()->get_the_language_by_site()){ ?>
              <h4><?php _e('Enter your HTML code to use as legend on the layer ('.odm_language_manager()->get_the_language_by_site().')', 'odm'); ?></h4>
              <textarea name="_layer_legend_localization" style="width:100%;height: 200px;"><?php echo $legend_localization; ?></textarea>
        <?php
            }
    }

    function post_layers_box($post = false) {
     $layer_query = new WP_Query(array('post_type' => 'map-layer', 'posts_per_page' => -1));
     $layers = array();
     $post_layers = $post ? $this->get_map_layers($post->ID) : false;
     $show_cat = get_post_meta($post->ID, '_jeo_map_show_cat', true);
     $show_hierarchy = get_post_meta($post->ID, '_jeo_map_show_hierarchy', true);
     ?>

     <p>
      <?php
      printf(__('Add and manage <a href="%s" target="_blank">layers</a> on your map.', 'odm'), admin_url('edit.php?post_type=map-layer'));
      if(!$layer_query->have_posts())
       printf(__(' You haven\'t created any layers yet, <a href="%s" target="_blank">click here</a> to create your first!'), admin_url('post-new.php?post_type=map-layer'));
      ?>
     </p>

     <?php
     if($layer_query->have_posts()) {
      while($layer_query->have_posts()) {
       $layer_query->the_post();
       $layer = $this->get_layer(get_the_ID());
       wp_reset_postdata();
       if ($layer) {
	 $layers[] = $layer;
       }

      }
      ?>
      <input type="text" data-bind="textInput: search" placeholder="<?php _e('Search for layers', 'odm'); ?>" size="50">

      <!-- ko if: !search() -->
       <h4 class="results-title"><?php _e('Latest layers', 'odm'); ?></h4>
      <!-- /ko -->

      <!-- ko if: search() -->
       <h4 class="results-title"><?php _e('Search results', 'odm'); ?></h4>
      <!-- /ko -->

      <!-- ko if: !filteredLayers().length && !search() -->
       <p style="font-style:italic;color: #999;"><?php _e('You are using all of your layers.', 'odm'); ?></p>
      <!-- /ko -->

      <!-- ko if: !filteredLayers().length && search() -->
       <p style="font-style:italic;color: #999;"><?php _e('No layers were found.', 'odm'); ?></p>
      <!-- /ko -->

      <table class="layers-list available-layers">
       <tbody data-bind="foreach: filteredLayers">
        <tr>
         <td><strong><a data-bind="text: title, attr: {href: url}" target="_blank"></a></strong></td>
         <td data-bind="text: type"></td>
         <td><a target="_blank" data-bind="attr: {href:'<?php echo get_bloginfo("url")."/wp-admin/post.php?action=edit&post="; ?>'+ID}" title="<?php _e('edit', 'odm'); ?>"><?php _e('Edit'); ?></a></td>
         <td style="width:1%;"><a class="button" data-bind="click: $parent.addLayer" href="javascript:void(0);" title="<?php _e('Add layer', 'odm'); ?>">+ <?php _e('Add'); ?></a></td>
        </tr>
       </tbody>
      </table>

      <h4 class="selected-title"><?php _e('Selected layers', 'odm'); ?></h4>
      <p class='jeo_map_show_cat'>
        <input type="checkbox" name="_jeo_map_show_cat" id="jeo_map_show_cat" value="1" <?php checked(1, $show_cat);?>>
        <label for="jeo_map_show_cat"><?php _e('Group layers by showing category', 'odm'); ?></label> &nbsp;&nbsp;

        <input type="checkbox" name="_jeo_map_show_hierarchy" id="jeo_map_show_hierarchy" disabled value="1" <?php checked(1, $show_hierarchy);?>>
        <label for="jeo_map_show_hierarchy"><?php _e('Show category layers in hierarchy', 'odm'); ?></label>
      </p>

      <table class="layers-list selected-layers">
       <tbody class="selected-layers-list">
        <!-- ko foreach: {data: selectedLayers} -->
         <tr class="layer-item">
          <td style="width: 60%;">
           <p><strong><a data-bind="text: title, attr: {href: url}" target="_blank"></a></strong></p>
           <p data-bind="text: type"></p>
          </td>
          <td>
           <p><?php _e('Layer options', 'odm'); ?></p>
           <div class="filter-opts">
            <input type="radio" value="fixed" data-bind="attr: {name: ID + '_filtering_opt', id: ID + '_filtering_opt_fixed'}, checked: $data.filtering" />
            <label data-bind="attr: {for: ID + '_filtering_opt_fixed'}"><?php _e('Enable', 'odm'); ?></label> &nbsp; &nbsp; &nbsp;
            <input  type="radio" value="switch" data-bind="attr: {name: ID + '_filtering_opt', id: ID + '_filtering_opt_switch'}, checked: $data.filtering" />
            <label data-bind="attr: {for: ID + '_filtering_opt_switch'}"><?php _e('Disable', 'odm'); ?></label>
            <!--<input type="radio" value="swap"  data-bind="attr: {name: ID + '_filtering_opt', id: ID + '_filtering_opt_swap'}, checked: $data.filtering" />
            <label data-bind="attr: {for: ID + '_filtering_opt_swap'}"><?php _e('Swapable', 'odm'); ?></label>-->

            <div class="filtering-opts" style="display: none;">
             <!-- ko if: $data.filtering() == 'switch' -->
              <input type="checkbox" data-bind="attr: {id: ID + '_switch_hidden'}, checked: $data.hidden" />
              <label data-bind="attr: {for: ID + '_switch_hidden'}"><?php _e('Hidden', 'odm'); ?></label>
             <!-- /ko -->
             <!-- ko if: $data.filtering() == 'swap' -->
              <input type="radio" data-bind="attr: {id: ID + '_first_swap'}, checked: $data.first_swap" name="_jeo_map_layer_first_swap" />
              <label data-bind="attr: {for: ID + '_first_swap'}"><?php _e('Default swap option', 'odm'); ?></label>
             <!-- /ko -->
            </div>
           </div>
          </td>
          <td><a target="_blank" data-bind="attr: {href:'<?php echo get_bloginfo("url")."/wp-admin/post.php?action=edit&post="; ?>'+ID}" title="<?php _e('edit', 'odm'); ?>"><?php _e('Edit'); ?></a></td>
          <td style="width:1%;"><a class="button" data-bind="click: $parent.removeLayer" href="javascript:void(0);" title="<?php _e('Remove layer', 'odm'); ?>"><?php _e('Remove'); ?></a></td>
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
        max-height: 100px;
        font-size: 14px;
       }
       #post-layers .layers-list tr td {
        margin: 0;
        border: 1px solid #f0f0f0;
        padding: 5px 8px;
        font-size: 14px;
       }
       #post-layers .layers-list tr:hover td {
        background: #fff;
       }

       #post-layers p{
         margin: 5px  0
       }
       .jeo_map_show_cat{
         display: none;
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
         $('.jeo_map_show_cat').show();
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

         if(layers.length){
           $('.jeo_map_show_cat').show();
         }else{
           $('.jeo_map_show_cat').hide();
         }

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

        enable_hierarchy_checkbox();
        $("#jeo_map_show_cat").click(enable_hierarchy_checkbox);
       });

       function enable_hierarchy_checkbox() {
          if ($('#jeo_map_show_cat').is(":checked")) {
            $("input#jeo_map_show_hierarchy").removeAttr("disabled");
          } else {
            $("input#jeo_map_show_hierarchy").attr("disabled", true);
          }
        }
      </script>
      <?php
      wp_reset_query();
     }
    }

    function settings_box($post = false) {
        $layer_type = $post ? $this->get_layer_type($post->ID) : false;
        $layer_download_link = get_post_meta($post->ID, '_layer_download_link', true);
        $layer_download_link_localization = get_post_meta($post->ID, '_layer_download_link_localization', true);
        $layer_profilepage_link = get_post_meta($post->ID, '_layer_profilepage_link', true);
        $layer_profilepage_link_localization = get_post_meta($post->ID, '_layer_profilepage_link_localization', true);
        ?>
        <div class="layer_settings_box">
            <div class="layer-download-link">
                <h4><?php _e('Download Page URL', 'odm'); ?></h4>
                <tbody>
                    <tr>
                        <th><label for="_layer_download_link"><?php _e('Download URL (English)', 'odm'); ?></label></th>
                        <td>
                            <input id="_layer_download_link" type="text" placeholder="https://" size="65" name="_layer_download_link" value="<?php echo $layer_download_link; ?>" />
                            <p class="description"><?php _e('A link to a dataset\'s page on CKAN', 'odm'); ?></p>
                        </td>
                    </tr>
                    <?php if (odm_language_manager()->get_the_language_by_site() != "English") { ?>
                    <tr>
                        <th><label for="_layer_download_link_localization"><?php _e('Download URL ('.odm_language_manager()->get_the_language_by_site().')', 'odm'); ?></label></th>
                        <td>
                            <input id="_layer_download_link_localization" type="text" placeholder="https://" size="65" name="_layer_download_link_localization" value="<?php echo $layer_download_link_localization; ?>" />
                            <p class="description"><?php _e('A link to a dataset\'s page on CKAN', 'odm'); ?></p>
                        </td>
                    </tr>
                 <?php } ?>
                </tbody>
            </div>

            <div class="layer-profilepage-link">
                <h4><?php _e('Profile Page URL', 'odm'); ?></h4>
                <tbody>
                    <tr>
                        <th><label for="_layer_profilepage_link"><?php _e('Profile Page URL (English)', 'odm'); ?></label></th>
                        <td>
                            <input id="_layer_profilepage_link" type="text" placeholder="https://" size="65" name="_layer_profilepage_link" value="<?php echo $layer_profilepage_link; ?>" />
                            <p class="description"><?php _e('A link to profile page on Wordpress', 'odm'); ?></p>
                        </td>
                    </tr>
                    <?php if(odm_country_manager()->get_current_country()!="mekong"){ ?>
                    <tr>
                        <th><label for="_layer_profilepage_link_localization"><?php _e('Profile Page URL ('.odm_language_manager()->get_the_language_by_site().')', 'odm'); ?></label></th>
                        <td>
                            <input id="_layer_profilepage_link_localization" type="text" placeholder="https://" size="65" name="_layer_profilepage_link_localization" value="<?php echo $layer_profilepage_link_localization; ?>" />
                            <p class="description"><?php _e('A link to profile page on Wordpress', 'odm'); ?></p>
                        </td>
                    </tr>
                  <?php } ?>
                </tbody>
            </div>

            <div class="layer-type">
                <h4><?php _e('Layer type', 'odm'); ?></h4>
                <p>
                     <input type="radio" id="layer_type_tilelayer" name="layer_type" value="tilelayer" class="tilelayer" <?php if($layer_type == 'tilelayer' || !$layer_type) echo 'checked'; ?> />
                     <label for="layer_type_tilelayer"><?php _e('Tile layer', 'odm'); ?></label>
                     <input type="radio" id="layer_type_wmslayer" name="layer_type" value="wmslayer"  class="wmslayer" <?php if($layer_type == 'wmslayer') echo 'checked'; ?> />
                     <label for="layer_type_wmslayer"><?php _e('WMS layer', 'odm'); ?></label>

                     <input type="radio" id="layer_type_mapbox" name="layer_type" value="mapbox"  class="mapbox" <?php if($layer_type == 'mapbox') echo 'checked'; ?> />
                     <label for="layer_type_mapbox"><?php _e('MapBox', 'odm'); ?></label>

                     <input type="radio" id="layer_type_cartodb" name="layer_type" value="cartodb"  class="cartodb" <?php if($layer_type == 'cartodb') echo 'checked'; ?> />
                     <label for="layer_type_cartodb"><?php _e('CartoDB', 'odm'); ?></label>
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
                        <th><label for="tilelayer_tile_url"><?php _e('URL', 'odm'); ?></label></th>
                        <td>
                            <input id="tilelayer_tile_url" type="text" placeholder="<?php _e('http://{s}.example.com/{z}/{x}/{y}.png', 'odm'); ?>" size="65" name="_tilelayer_tile_url" value="<?php echo $tileurl; ?>" />
                            <p class="description"><?php _e('Tilelayer URL. E.g.: http://{s}.example.com/{z}/{x}/{y}.png', 'odm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tilelayer_utfgrid_url"><?php _e('UTFGrid URL (optional)', 'odm'); ?></label></th>
                        <td>
                            <input id="tilelayer_utfgrid_url" type="text" placeholder="<?php _e('http://{s}.example.com/{z}/{x}/{y}.grid.json', 'odm'); ?>" size="65" name="_tilelayer_utfgrid_url" value="<?php echo $utfgridurl; ?>" />
                            <p class="description"><?php _e('Optional UTFGrid URL. E.g.: http://{s}.example.com/{z}/{x}/{y}.grid.json', 'odm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tilelayer_utfgrid_template"><?php _e('UTFGrid Template (optional)', 'odm'); ?></label></th>
                        <td>
                            <textarea id="tilelayer_utfgrid_template" rows="10" cols="40" name="_tilelayer_utfgrid_template"><?php echo $utfgrid_template; ?></textarea>
                            <p class="description"><?php _e('UTFGrid template using mustache.<br/>E.g.: City: {{city}}'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tilelayer_tms"><?php _e('TMS', 'odm'); ?></label></th>
                        <td>
                            <input id="tilelayer_tms" type="checkbox" name="_tilelayer_tms" <?php if($tms) echo 'checked'; ?> /> <label for="tilelayer_tms"><?php _e('Enable TMS', 'odm'); ?></label>
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
                $infowindow_attributes =  $post ? get_post_meta($post->ID, '_infowindow_attributes', true) : '';
                $infowindow_attributes_localization =  $post ? get_post_meta($post->ID, '_infowindow_attributes_localization', true) : '';
                $infowindow_title = $post ? get_post_meta($post->ID, '_infowindow_title', true) : '';
                $infowindow_title_localization = $post ? get_post_meta($post->ID, '_infowindow_title_localization', true) : '';
                $infowindow_detail = $post ? get_post_meta($post->ID, '_infowindow_detail', true) : '';
                $infowindow_detail_localization = $post ? get_post_meta($post->ID, '_infowindow_detail_localization', true) : '';
                ?>
                <tbody>
                    <tr>
                        <th><label for="wmslayer_tile_url"><?php _e('WMS Service URL', 'odm'); ?></label></th>
                        <td>
                            <input id="wmslayer_tile_url" type="text" placeholder="<?php _e('http://{geoserver adress & port}/geoserver/wms', 'odm'); ?>" size="65" name="_wmslayer_tile_url" value="<?php echo $wmstileurl; ?>" />
                            <p class="description"><?php _e('Eg. WMS URL: http://geoserver.example.com:8080/geoserver/wms', 'odm'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="wmslayer_layer_name"><?php _e('Workspaces:Layer Name (English)', 'odm'); ?></label></th>
                        <td>
                            <input id="wmslayer_layer_name" type="text" placeholder="<?php _e('Workspaces and Layer name"', 'odm'); ?>" size="65" name="_wmslayer_layer_name" value="<?php echo $layername; ?>" />
                            <p class="description"><?php _e('Eg. in Geoserver, Energy:Transmission_lines, <strong>Engergy</strong> is workspace name and <strong>Transmission_lines</strong> is layer name.', 'odm'); ?></p>
                        </td>
                    </tr>
                    <?php if(odm_country_manager()->get_current_country()!="mekong"){ ?>
                    <tr>
                        <th><label for="wmslayer_layer_name_localization"><?php _e('Workspaces:Layer Name ('.odm_language_manager()->get_the_language_by_site().')', 'odm'); ?></label></th>
                        <td>
                            <input id="wmslayer_layer_name_localization" type="text" placeholder="<?php _e('Workspaces and Layer name"', 'odm'); ?>" size="65" name="_wmslayer_layer_name_localization" value="<?php echo $layername_localization; ?>" />
                            <p class="description"><?php _e('Eg. in Geoserver, Energy:Transmission_lines_kh, <strong>Engergy</strong> is workspace name and <strong>Transmission_lines</strong> is layer name.', 'odm'); ?></p>
                        </td>
                     </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th><label for="wmslayer_wms_format"><?php _e('WMS format (optional)', 'odm'); ?></label></th>
                        <td>
                            <input id="wmslayer_wms_format" type="text" placeholder="<?php _e('image/png', 'odm'); ?>" size="65" name="_wmslayer_wms_format" value="<?php echo $wms_format; ?>" />
                            <p class="description"><?php _e('E.g.: image/png or image/jpeg'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="wmslayer_transparent"><?php _e('Transparent', 'odm'); ?></label></th>
                        <td>
                            <?php $is_new_page = get_current_screen(); // Transparent is checked by default. ?>
                            <input id="wmslayer_transparent" type="checkbox" name="_wmslayer_transparent" <?php if($transparent) echo 'checked'; else if ( $is_new_page->action =="add") echo 'checked'; ?> /> <label for="wmslayer_transparent"><?php _e('Transparent', 'odm'); ?></label>
                            <p class="description"><?php _e('Enable it to make transparent the layer of WMS.'); ?></p>
                        </td>
                    </tr>
                    <tr>
                      <th><label for="wmslayer_transparent"><?php _e('Inforwindow (popup)', 'odm'); ?></label></th>
                      <td>
                        <div id="infowindow_multiple_box box-shadow">
                          <div id="multiple-site" class="<?php if(odm_country_manager()->get_current_country()=="mekong") echo 'hide'; ?>">
                            <input type="radio" id="infowindow_en" class="en" name="language_switch" value="en" checked />
                            <label for="infowindow_en"><?php _e('English', 'wp-odm_profile_pages'); ?></label> &nbsp;
                            <?php if (odm_language_manager()->get_the_language_by_site() != "English"):   ?>
                              <input type="radio" id="infowindow_localization" class="localization" name="language_switch" value="localization" />
                              <label for="infowindow_localization"><?php _e(odm_language_manager()->get_the_language_by_site(), 'wp-odm_profile_pages'); ?></label>
                            <?php endif; ?>
                          </div>

                          <div id="infowindow_box">
                            <div class="language_settings en">
                              <p class="description"><?php _e('Please add the attribute_name or strings to display as the title in inforwindow. ', 'odm'); ?></p>
                              <input id="infowindow_title" type="text" placeholder="<?php _e('Strings or attribute_name', 'odm'); ?>" size="65" name="_infowindow_title" value="<?php echo $infowindow_title; ?>" />

                              <textarea name="_infowindow_attributes" style="width:100%;height: 100px;" placeholder="attribute_name => Label"><?php echo $infowindow_attributes;  ?></textarea>

                              <p class="description"><?php _e('View detail', 'odm'); ?></p>
                              <input id="infowindow_detail" type="text" placeholder="<?php _e('View detail URL', 'odm'); ?>" size="65" name="_infowindow_detail" value="<?php echo $infowindow_detail; ?>" />
                              <p class="description"><?php _e('To show the view detail link on infowindow, please add the URL with {{attribute_name}} or only the attribute_name that contains the link.', 'odm'); ?> eg. https://opendevelopmentcambodia.net/profiles/economic-land-concessions/?feature_id={{map_id}} OR view_detail_column_name</p>
                            </div>
                            <?php if (odm_language_manager()->get_the_language_by_site() != "English"):   ?>
                              <div class="language_settings localization">
                                  <p class="description"><?php _e('Please add the attribute_name or strings to display as the title in inforwindow. ', 'odm'); ?></p>
                                  <input id="infowindow_title_localization" type="text" placeholder="<?php _e('Strings or attribute_name', 'odm'); ?>" size="65" name="_infowindow_title_localization" value="<?php echo $infowindow_title_localization; ?>" />
                                  <textarea name="_infowindow_attributes_localization" style="width:100%;height: 100px;" placeholder="attribute_name => Label"><?php echo $infowindow_attributes_localization;  ?></textarea>

                                  <p class="description"><?php _e('View detail', 'odm'); ?></p>
                                  <input id="infowindow_detail_localization" type="text" placeholder="<?php _e('View detail URL', 'odm'); ?>" size="65" name="_infowindow_detail_localization" value="<?php echo $infowindow_detail_localization; ?>" />
                                  <p class="description"><?php _e('To show the view detail link on infowindow, please add the URL with {{attribute_name}} or only the attribute_name that contains the link.', 'odm'); ?> eg. https://opendevelopmentcambodia.net/profiles/economic-land-concessions/?feature_id={{map_id}} OR view_detail_column_name</p>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                </tbody>
            </table>
            <table class="form-table type-setting mapbox">
                <?php $mapbox_id = $post ? get_post_meta($post->ID, '_mapbox_id', true) : ''; ?>
                <tbody>
                    <tr>
                        <th><label for="mapbox_id"><?php _e('MapBox ID', 'odm'); ?></label></th>
                        <td>
                            <input id="mapbox_id" type="text" placeholder="examples.map-20v6611k" size="65" name="_mapbox_id" value="<?php echo $mapbox_id; ?>" />
                            <p class="description"><?php _e('MapBox map ID. E.g.: examples.map-20v6611k', 'odm'); ?></p>
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
                  <th><?php _e('Visualization type', 'odm'); ?></th>
                  <td>
                    <input name="_cartodb_type" id="cartodb_viz_type_viz" type="radio" value="viz" <?php if($cartodb_type == 'viz' || !$cartodb_type) echo 'checked'; ?> />
                    <label for="cartodb_viz_type_viz"><?php _e('Visualization', 'odm'); ?></label>
                    <input name="_cartodb_type" id="cartodb_viz_type_custom" type="radio" value="custom" disabled <?php if($cartodb_type == 'custom') echo 'checked'; ?> />
                    <label for="cartodb_viz_type_custom"><?php _e('Advanced (build from your tables)', 'odm'); ?> - <?php _e('coming soon', 'odm'); ?></label>
                  </td>
                </tr>
                <tr class="subopt viz_type_viz">
                <th><label for="cartodb_viz_url"><?php _e('CartoDB URL (English)', 'odm'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_url" type="text" placeholder="http://user.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json" size="65" name="_cartodb_viz_url" value="<?php echo $vizurl; ?>" />
                    <p class="description"><?php _e('CartoDB visualization URL.<br/>E.g.: http://infoamazonia.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json', 'odm'); ?></p>
                  </td>
                </tr>
                <?php if(odm_country_manager()->get_current_country()!="mekong"){ ?>
                <tr class="subopt viz_type_viz">
                  <th><label for="cartodb_viz_url_localization"><?php _e('CartoDB URL ('.odm_language_manager()->get_the_language_by_site().')', 'odm'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_url_localization" type="text" placeholder="http://user.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json" size="65" name="_cartodb_viz_url_localization" value="<?php echo $vizurl_localization; ?>" />
                    <p class="description"><?php _e('CartoDB visualization URL.<br/>E.g.: http://infoamazonia.cartodb.com/api/v2/viz/621d23a0-5eaa-11e4-ab03-0e853d047bba/viz.json', 'odm'); ?></p>
                  </td>
                </tr>
                <?php } ?>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_username"><?php _e('Username', 'odm'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_username" type="text" placeholder="johndoe" name="_cartodb_username" value="<?php echo $username; ?>" />
                    <p class="description"><?php _e('Your CartoDB username.'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_table"><?php _e('Table', 'odm'); ?></label></th>
                  <td>
                    <input id="cartodb_viz_table" type="text" placeholder="deforestation_2012" name="_cartodb_table" value="<?php echo $table; ?>" />
                    <p class="description"><?php _e('The CartoDB table you\'d like to visualize.'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_where"><?php _e('Where (optional)', 'odm'); ?></label></th>
                  <td>
                    <textarea id="cartodb_viz_where" rows="3" cols="40" name="_cartodb_where"><?php echo $where; ?></textarea>
                    <p class="description"><?php _e('Query data from your table.<br/>E.g.: region = "north"'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_cartocss"><?php _e('CartoCSS', 'odm'); ?></label></th>
                  <td>
                    <textarea id="cartodb_viz_cartocss" rows="10" cols="40" name="_cartodb_cartocss"><?php echo $cartocss; ?></textarea>
                    <p class="description"><?php printf(__('Styles for your table. <a href="%s" target="_blank">Learn more</a>.'), 'https://www.mapbox.com/tilemill/docs/manual/carto/'); ?></p>
                  </td>
                </tr>
                <tr class="subopt viz_type_custom">
                  <th><label for="cartodb_viz_template"><?php _e('Template', 'odm'); ?></label></th>
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
            function show_infowindow(inputname, settings_box){
              var $forms = $("."+settings_box);
              $forms.hide();
              var selected = $('input[type="radio"][name='+ inputname+ ']').filter(':checked').val();
              $('.'+settings_box+"." + selected).show();
            }
            jQuery(document).ready(function($) {
                show_infowindow("language_switch", "language_settings");
                show_infowindow("layer_type", "form-table");

                var $language_Selection = $('input[type="radio"][name="language_switch"]');
                $language_Selection.on('change', function() {
                  $('.' + this.className).prop('checked', this.checked);
                  show_infowindow("language_switch", "language_settings");
                });

                var $layerSelection = $('input[type="radio"][name="layer_type"]');
                $layerSelection.on('change', function() {
                  $('.' + this.className).prop('checked', this.checked);
                  show_infowindow("layer_type", "form-table");
                });
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
            if(isset($_REQUEST['_infowindow_attributes']))
                update_post_meta($post_id, '_infowindow_attributes', $_REQUEST['_infowindow_attributes']);

            if(isset($_REQUEST['_infowindow_attributes_localization']))
                update_post_meta($post_id, '_infowindow_attributes_localization', $_REQUEST['_infowindow_attributes_localization']);

            if(isset($_REQUEST['_infowindow_title']))
                update_post_meta($post_id, '_infowindow_title', $_REQUEST['_infowindow_title']);

            if(isset($_REQUEST['_infowindow_title_localization']))
                update_post_meta($post_id, '_infowindow_title_localization', $_REQUEST['_infowindow_title_localization']);

            if(isset($_REQUEST['_infowindow_detail']))
                update_post_meta($post_id, '_infowindow_detail', $_REQUEST['_infowindow_detail']);

            if(isset($_REQUEST['_infowindow_detail_localization']))
                update_post_meta($post_id, '_infowindow_detail_localization', $_REQUEST['_infowindow_detail_localization']);
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

      if(isset($_REQUEST['_jeo_map_show_cat'])) {
       update_post_meta($post_id, '_jeo_map_show_cat', TRUE);
      }else{
       update_post_meta($post_id, '_jeo_map_show_cat', FALSE);
      }

      if(isset($_REQUEST['_jeo_map_show_hierarchy'])) {
       update_post_meta($post_id, '_jeo_map_show_hierarchy', TRUE);
      }else{
       update_post_meta($post_id, '_jeo_map_show_hierarchy', FALSE);
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

           if($filter == "baselayer") {
	     if(isset($layer['map_category']) && $layer['map_category']['name'] == "base-layers"){
                  $layer['post_title'] = $l['title'];
                  $layers[] = $layer;
	     }
           }elseif($filter == "layer" && array_key_exists('map_category',$layers)) {
              if($layer['map_category']['name'] != "base-layers"){
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
	if (!$post) {
	  return;
	}
        $type = $this->get_layer_type();

        $content = apply_filters('translate_text', $post->post_content, odm_language_manager()->get_current_language());
        $excerpt = apply_filters('translate_text', $post->excerpt, odm_language_manager()->get_current_language());

        $in_category = get_the_terms( $post->ID, 'layer-category' );
        if($in_category):
          if (($key = array_search('map-catalogue', $in_category)) !== false) {
              unset($array[$key]);
          }
          foreach ($in_category as $key => $in_cat) {
              if($in_cat->slug == "map-catalogue"){
                unset($in_category[$key]);
              }
          }
        $in_category = array_values($in_category);
        endif;

        if ( (odm_language_manager()->get_current_language() !== "en") ){
            $layer_legend = get_post_meta( $post->ID , '_layer_legend_localization', true);
        }else {
            $layer_legend = get_post_meta( $post->ID , '_layer_legend', true);
        }
        $layer = array(
            'ID' => $post->ID,
            'title' => get_the_title(),
            'url' => get_permalink(),
            'type' => $type
        );

        if (!empty($in_category)):
          $layer['map_category']['id'] = $in_category[0]->term_id;
          $layer['map_category']['name'] = $in_category[0]->slug;
          $layer['map_category']['parent'] = $in_category[0]->parent;
        endif;

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
            $layer['infowindow_title'] = get_post_meta($post->ID, '_infowindow_title', true);
            $layer['infowindow_title_localization'] = get_post_meta($post->ID, '_infowindow_title_localization', true);
            $infowindow_attributes = get_post_meta($post->ID, '_infowindow_attributes', true);
            if ($infowindow_attributes) {
                $array_attribute = parse_mapping_pairs(trim($infowindow_attributes));
                $layer['infowindow_attributes'] = $array_attribute;
            }
            $infowindow_attributes_localization = get_post_meta($post->ID, '_infowindow_attributes_localization', true);
            if ($infowindow_attributes_localization) {
                $array_attribute_localization = parse_mapping_pairs(trim($infowindow_attributes_localization));
                $layer['infowindow_attributes_localization'] = $array_attribute_localization;
            }

            $layer['infowindow_detail'] = get_post_meta($post->ID, '_infowindow_detail', true);
            $layer['infowindow_detail_localization'] = get_post_meta($post->ID, '_infowindow_detail_localization', true);
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
