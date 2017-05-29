var jeo = {};
var globalmap;
var default_baselayer;
var overlayers = [];
var overbaselayers_object = {};
var overbaselayers_cartodb = {};
var overlayers_cartodb = [];
var layer_name, geoserver_URL, layer_name_localization;
var marker_layer;
var detect_lang_site = document.documentElement.lang; // or  $('html').attr('lang');
(function($) {

 jeo = function(conf, callback) {
  var _init = function() {
   if(conf.mainMap)
    $('body').addClass('loading-map');

   if(conf.admin) { // is admin panel
    return jeo.build(conf, callback);
   }

   if(conf.dataReady || !conf.postID) { // data ready
    return jeo.build(conf, callback);
   }

   return $.getJSON(jeo_localization.ajaxurl,
    {
     action: 'map_data',
     map_id: conf.postID
    },
    function(map_data) {
     mapConf = jeo.parseConf(map_data);
     mapConf = _.extend(mapConf, conf);
     return jeo.build(mapConf, callback);
    });
  };

  if($.isReady) {
   return _init();
  } else {
   return $(document).ready(_init);
  }

 };

 jeo.maps = {};

 jeo.build = function(conf, callback) {

  /*
   * Map settings
   */

  var options = {
   maxZoom: 17,
   minZoom: 0,
   zoom: 2,
   center: [0,0],
   attributionControl: false
  };
  if(conf.center && !isNaN(conf.center[0]))
   options.center = conf.center;

  if(conf.zoom && !isNaN(conf.zoom))
   options.zoom = conf.zoom;

  if(conf.bounds)
   options.maxBounds = conf.bounds;

  if(conf.maxZoom && !isNaN(conf.maxZoom) && !conf.preview)
   options.maxZoom = conf.maxZoom;

  if(conf.minZoom && !isNaN(conf.minZoom) && !conf.preview)
   options.minZoom = conf.minZoom;

  var map;

  if(!conf.containerID)
   conf.containerID = 'map_' + conf.postID + '_' + conf.count;
  var map_id = conf.containerID;
  if(conf.news_markers){
    conf.news_markers = conf.news_markers;
  }
  // use mapbox map for more map resources
  map = L.mapbox.map(map_id, null, options);
  globalmap = map;

  if(conf.mainMap)
   jeo.map = map;
  /*
   * DOM settings
   */
  // store jquery node
  map.$ = $('#' + map_id);

  if(conf.mainMap) {
   $('body').removeClass('loading-map');
   if(!$('body').hasClass('displaying-map'))
    $('body').addClass('displaying-map');
  }
  // store conf
  map.conf = conf;

  // store map id
  map.map_id = map_id;
  if(conf.postID)
   map.postID = conf.postID;

  // Defaul Baselayers
  default_baselayer = conf.layers[0];
  jeo.loadLayers(map, jeo.parse_layer(map, default_baselayer));

  // set bounds
  if(conf.fitBounds instanceof L.LatLngBounds)
   map.fitBounds(conf.fitBounds);

   conf.disable_mousewheel = false;
   if(conf.disable_mousewheel === false){
     conf.disableHandlers = false;
   }
  // Handlers
  if(conf.disableHandlers) {
   // mousewheel
   if(conf.disableHandlers.mousewheel)
    map.scrollWheelZoom.disable();
  }

  /*
   * Legends
   */
  if(conf.legend) {
   map.legendControl.addLegend(conf.legend);
  }
  if(conf.legend_full)
   jeo.enableDetails(map, conf.legend, conf.legend_full);

  /*
   * Fullscreen
   */
  map.addControl(new jeo.fullscreen());

  /*
   * Clearscreen
   */
  if(typeof(jeo.clearscreen) != 'undefined')
  map.addControl(new jeo.clearscreen());

  /*
   * Print map
   */
  if(typeof(jeo.printmap) != 'undefined')
  map.addControl(new jeo.printmap());

  /*
   * Geocode
   */
  if(map.conf.geocode)
   map.addControl(new jeo.geocode());
  /*
   * CALLBACKS
   */

  // conf passed callbacks
  if(typeof conf.callbacks === 'function')
   conf.callbacks(map);

  // map is ready, do callbacks
  jeo.runCallbacks('mapReady', [map]);

  if(typeof callback === 'function')
   callback(map);

  return map;
};

jeo.create_layer_by_maptype = function (map, layer){
    layer = (typeof layer !== 'undefined') ?  layer : null;
    var pLayer = null;
    var options = {};
    if(layer.type == 'cartodb' && layer.cartodb_type == 'viz') {
      pLayer = cartodb.createLayer(map, layer.cartodb_viz_url_localization, {legends: false, https: true});

      if(detect_lang_site == "en-US"){
        pLayer = cartodb.createLayer(map, layer.cartodb_viz_url, {legends: false, https: true});
      }

      if(layer.legend) {
        pLayer._legend = layer.legend;
      }

    } else if(layer.type == 'mapbox') {
       pLayer = L.mapbox.tileLayer(layer.mapbox_id);

       if(layer.legend) {
        pLayer._legend = layer.legend;
       }

    } else if(layer.type == 'tilelayer') {

      if(layer.tms){
        options.tms = true;
      }


      pLayer = L.tileLayer(layer.tile_url, options);
      if(layer.legend) {
        pLayer._legend = layer.legend;
      }

      if(typeof(layer.ID) == 'undefined'){
        layer.ID = 0;
      }

      if(layer.utfgrid_url && layer.utfgrid_template) {
        parsedLayers.push(L.mapbox.gridLayer({
         "name": layer.title,
         "tilejson": "2.0.0",
         "scheme": "xyz",
         "template": layer.utfgrid_template,
         "grids": [layer.utfgrid_url.replace('{s}', 'a')]
        }));

      }

    }else if(layer.type == 'wmslayer') {

      var wms_format = 'image/png';
      var transparent = false;
      if(layer.wms_transparent){
        transparent = true;
      }

      if(layer.wms_format){
        wms_format = layer.wms_format;
      }
      var info_title = null, info_attributes = null, info_detail = null;
      var spited_wms_tile_url=  layer.wms_tile_url.split("/geoserver/");
      geoserver_URL = spited_wms_tile_url[0]+"/geoserver/wms";
      if(detect_lang_site == "en-US"){
        layer_name = layer.wms_layer_name;
        if(layer.infowindow_title){
          info_title = layer.infowindow_title;
        }
        if(layer.infowindow_attributes){
          info_attributes = layer.infowindow_attributes;
        }
        if(layer.infowindow_detail){
          info_detail = layer.infowindow_detail;
        }
      }else{
        layer_name = layer.wms_layer_name_localization;
        if(layer.infowindow_title_localization){
          info_title = layer.infowindow_title_localization;
        }
        if(layer.infowindow_attributes_localization){
          info_attributes = layer.infowindow_attributes_localization;
        }
        if(layer.infowindow_detail_localization){
          info_detail = layer.infowindow_detail_localization;
        }
      }

      options = {
        layers: layer_name,
        info_title: info_title,
        info_attributes: info_attributes,
        info_detail: info_detail,
        version: '1.1.0',
        transparent: transparent,
        format: wms_format,
        crs: L.CRS.EPSG4326,
        tiled:true
      };

      pLayer = L.tileLayer.betterWms(geoserver_URL, options);

      if(layer.legend) {
        pLayer._legend = layer.legend;
      } else {
        pLayer._legend = '<img src="'+geoserver_URL+'?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=30&HEIGHT=25&LAYER='+layer_name+'&legend_options=fontName:Times%20New%20Roman;fontAntiAliasing:true" alt = "Legend"></img>';
      }
    }

   return pLayer;
 };

  jeo.bringLayerToFront = function(layer_ID, zIndex){
      zIndex = zIndex || 0;
      if( layer_ID in overlayers_cartodb ) {
          overlayers_cartodb[layer_ID].setZIndex(zIndex);
      }else if( layer_ID in overlayers ) {
          overlayers[layer_ID].setZIndex(zIndex);
          //overlayers[layer_ID].bringToFront();
      }
  };

  var layer_index = 0;
  jeo.toggle_layers = function(map, layer ) {
     if(map.hasLayer(overlayers[layer.ID]) ) {  //RemoveLayer title and wms layers
        map.removeLayer(overlayers[layer.ID]);
        $("#post-"+ layer.ID).removeClass('loading');
        $("#post-"+ layer.ID).toggleClass('active');
     }else if(map.hasLayer(overlayers_cartodb[layer.ID])) { //RemoveLayer cartodb layer
           if(overlayers_cartodb[layer.ID].type == "torque"){
                map.removeLayer(overlayers_cartodb[layer.ID]);
           }else{
              overlayers_cartodb[layer.ID].toggle();
           }
           $("#post-"+ layer.ID).removeClass('loading');
           $("#post-"+ layer.ID).toggleClass('active');
           var visible = $("#post-"+layer.ID).hasClass("active");
           if(visible){
               layer_index = layer_index + 1; //increate when the layer is actived
               jeo.bringLayerToFront(layer.ID, layer_index);
           }
     }else {
         layer_index = layer_index + 1;
         overlayers[layer.ID] = jeo.parse_layer(map, layer);

        if (layer.type == "cartodb" ){
          overlayers[layer.ID].addTo(map).on('done', function(lay) {
              overlayers_cartodb[layer.ID]= lay;
              var cartodb_layer_url = layer.cartodb_viz_url_localization;
              if(detect_lang_site == "en-US"){
                cartodb_layer_url = layer.cartodb_viz_url;
              }

              var cartodb_table = lay.options.layer_definition.layers[0].options.layer_name;
              var cartodb_user = cartodb_layer_url.split('.')[0].split('//')[1];

              if($("#searchFeature_by_mapID").length && $("#searchFeature_by_mapID").val() !== ""){
                jeo.search_map_feature(map, lay, cartodb_user, cartodb_table);
              }

              $('#searchFeature_by_mapID').keyup(function(){
                jeo.search_map_feature(map, lay, cartodb_user, cartodb_table);
              });

               if(overlayers_cartodb[layer.ID].type == "torque"){
                 cartodb_timeslider_init(lay, layer.ID);
               }
               lay.setZIndex(layer_index);
               setTimeout(function() {
                   $("#post-"+ layer.ID).removeClass('loading');
                   $("#post-"+ layer.ID).addClass('active');
               }, 1000);
           });
        }else{
          overlayers[layer.ID].addTo(map);
          jeo.bringLayerToFront(layer.ID, layer_index);
          $("#post-"+ layer.ID).removeClass('loading');
          $("#post-"+ layer.ID).addClass('active');
        }

     }
  };

  jeo.toggle_baselayers = function(map, layer ) {
        var current_layer = "baselayer_"+ layer.ID;
        overbaselayers_object[current_layer] = jeo.parse_layer(map, layer);
        if (layer.type == "cartodb" ){
          overbaselayers_object[current_layer].addTo(map).on('done', function(lay) {
             overbaselayers_cartodb[current_layer]= lay;
          });
        }else{
          map.addLayer( overbaselayers_object[current_layer]);
          overbaselayers_object[current_layer].bringToBack();
        }
        setTimeout(function() {
          $(".baselayer-container").find('.baselayer-loading').hide();
        }, 1000);
        //Remove all Cartodb Baselayer
        $.each(overbaselayers_cartodb, function(i, layer) {
            if(map.hasLayer(layer)) {
                if(i != current_layer){
                   layer.hide();
                }
            }
        });

        //Remove all Tile and WMS Baselayer
        $.each(overbaselayers_object, function(i, layer) {
            if(map.hasLayer(layer)) {
                if(i != current_layer){
                  map.removeLayer(layer);
                }
            }
        });

  };

  jeo.search_map_feature = function (map, layer, cartodb_user, cartodb_table) {
		var query;
		input = $("#searchFeature_by_mapID").val();
    if (input){
  		var sql = new cartodb.SQL({ user: cartodb_user });
  		query = "SELECT * FROM " + cartodb_table + " WHERE map_id in " + input;
  		var sublayerOptions = {
				sql: query
      };
  		layer.getSubLayer(0).set(sublayerOptions); // set layer options
  		sql.getBounds(query).done(function(bounds) {
  			map.fitBounds(bounds);
  			map.maxZoom = 10;
  		}).error(function(errors) {
  				console.log("errors:" + errors);
  		});
    }
  };

 /*
  * Utils
  */
  //Single layer
 jeo.parse_layer = function(map, layer ) {
    var parse_layer = jeo.create_layer_by_maptype(map, layer);
   return parse_layer;
 };//end function

//this function was used for group.js
/* jeo.parseLayers = function(map, layers) {
  var parsedLayers = [];
  $.each(layers, function(i, layer) {
    parsedLayers.push(jeo.create_layer_by_maptype(map, layer));
  });
  return parsedLayers;
};*/

 jeo.loadLayers = function(map, parsedLayers) {
    if(map.hasLayer(parsedLayers)) {
      map.removeLayer(parsedLayers);
    } else {
      overbaselayers_object["baselayer_0"] = parsedLayers;
      map.addLayer(parsedLayers);
    }
 };

 //default_baselayer = conf.layers[0];
 //jeo.loadLayers_filterlayer(map, jeo.parse_layer(map, default_baselayer));

 jeo.parseConf = function(conf) {
  var newConf = $.extend({}, conf);
  newConf.server = conf.server;

  if(conf.conf)
   newConf = _.extend(newConf, conf.conf);

  newConf.layers = [];
  newConf.filteringLayers = {};
  newConf.filteringLayers.switchLayers = [];
  newConf.filteringLayers.swapLayers = [];

  newConf.baselayers = [];

  $.each(conf.layers, function(i, layer) {
    if (i === 0){
      newConf.layers.push(_.clone(layer));
    }
  });

  newConf.center = [parseFloat(conf.center.lat), parseFloat(conf.center.lon)];

  if(conf.pan_limits.south && conf.pan_limits.north) {
   newConf.bounds = [
    [conf.pan_limits.south, conf.pan_limits.west],
    [conf.pan_limits.north, conf.pan_limits.east]
   ];
  }

  newConf.zoom = parseInt(conf.zoom);
  newConf.minZoom = parseInt(conf.min_zoom);
  newConf.maxZoom = parseInt(conf.max_zoom);

  if(conf.geocode)
   newConf.geocode = true;
  newConf.disableHandlers = {};
  if(conf.disable_mousewheel)
   newConf.disableHandlers.mousewheel = true;

  if(conf.legend)
   newConf.legend = conf.legend;

  if(conf.legend_full)
   newConf.legend_full = conf.legend_full;

  return newConf;
};

 /*
  * Legend page (map details)
  */
 jeo.enableDetails = function(map, legend, full) {
  if(typeof legend === 'undefined')
   legend = '';

  map.legendControl.removeLegend(legend);
  map.conf.legend_full_content = legend + '<span class="map-details-link">' + jeo_localization.more_label + '</span>';
  map.legendControl.addLegend(map.conf.legend_full_content);

  var isContentMap = map.$.parents('.content-map').length;
  var $detailsContainer = map.$.parents('.map-container');
  if(isContentMap)
   $detailsContainer = map.$.parents('.content-map');

  if(!$detailsContainer.hasClass('clearfix'))
   $detailsContainer.addClass('clearfix');

  map.$.on('click', '.map-details-link', function() {
   $detailsContainer.append($('<div class="map-details-page"><div class="inner"><a href="#" class="close">×</a>' + full + '</div></div>'));
   $detailsContainer.find('.map-details-page .close, .map-nav a').click(function() {
    $detailsContainer.find('.map-details-page').remove();
    return false;
   });

  });
};

 /*
  * Callback manager
  */

 jeo.callbacks = {};

 jeo.createCallback = function(name) {
  jeo.callbacks[name] = [];
  jeo[name] = function(callback) {
   jeo.callbacks[name].push(callback);
 };
};

 jeo.runCallbacks = function(name, args) {
  if(!jeo.callbacks[name]) {
   return false;
  }
  if(!jeo.callbacks[name].length)
   return false;

  var _run = function(callbacks) {
   if(callbacks) {
    _.each(callbacks, function(c, i) {
     if(c instanceof Function)
      c.apply(this, args);
    });
   }
 };
  _run(jeo.callbacks[name]);
};


 jeo.createCallback('mapReady');
 jeo.createCallback('layersReady');

})(jQuery);
