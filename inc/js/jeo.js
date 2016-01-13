var jeo = {};
var layer_name, geoserver_URL;
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
  }

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

  // use mapbox map for more map resources
  map = L.mapbox.map(map_id, null, options);

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

  // layers
  //jeo.loadLayers(map, jeo.parseLayers(map, conf.layers));

  // set bounds
  if(conf.fitBounds instanceof L.LatLngBounds)
   map.fitBounds(conf.fitBounds);

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
   * Geocode
   */
  if(map.conf.geocode)
   map.addControl(new jeo.geocode());

  /*
   * Filter layers
   */
  if(map.conf.filteringLayers)
   map.addControl(new jeo.filterLayers());

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
 }

 /*
  * Utils
  */

 jeo.parseLayers = function(map, layers) {

  var parsedLayers = [];

  $.each(layers, function(i, layer) {

   if(layer.type == 'cartodb' && layer.cartodb_type == 'viz') {

       var pLayer = cartodb.createLayer(map, layer.cartodb_viz_url, {legends: false, https: true });

    if(layer.legend) {
     pLayer._legend = layer.legend;
    }

    parsedLayers.push(pLayer);

   } else if(layer.type == 'mapbox') {

    var pLayer = L.mapbox.tileLayer(layer.mapbox_id);

    if(layer.legend) {
     pLayer._legend = layer.legend;
    }

    parsedLayers.push(pLayer);
    parsedLayers.push(L.mapbox.gridLayer(layer.mapbox_id));

   } else if(layer.type == 'tilelayer') {

        var options = {};

        if(layer.tms)
         options.tms = true;

        var pLayer = L.tileLayer(layer.tile_url, options);
        if(layer.legend) {
         pLayer._legend = layer.legend;
        }

        parsedLayers.push(pLayer);

       if(layer.utfgrid_url && layer.utfgrid_template) {

             parsedLayers.push(L.mapbox.gridLayer({
              "name": layer.title,
              "tilejson": "2.0.0",
              "scheme": "xyz",
              "template": layer.utfgrid_template,
              "grids": [layer.utfgrid_url.replace('{s}', 'a')]
             }));

        }

   } //end else if(layer.type == 'tilelayer')
   //H.E
   else if(layer.type == 'wmslayer') { 
        if(layer.wms_transparent)
         var transparent = true;

        if(layer.wms_format)
            var wms_format = layer.wms_format;
        else
            var wms_format = 'image/png';

       var spited_wms_tile_url=  layer.wms_tile_url.split("/geoserver/");
            //geoserver_URL = spited_wms_tile_url[0]+"/"; for sample test 2
            geoserver_URL = spited_wms_tile_url[0]+"/geoserver/wms";
            layer_name = layer.wms_layer_name;

        var options = {
            layers: layer_name,
            version: '1.1.0',
            transparent: transparent,
            //pointerCursor: true,
            format: wms_format,
            crs: L.CRS.EPSG4326,
            tiled:true
            };
        //geoserver_URL = "https://geoserver.opendevelopmentmekong.net/geoserver/wms";
        //var pLayer = L.tileLayer.betterWms(geoserver_URL, options);
        var pLayer = L.tileLayer.wms(geoserver_URL, options);

            var defaultParameters = {
                service: 'WFS',
                version: '1.0.0',
                request: 'GetFeature',
               // bbox: this._map.getBounds().toBBoxString(),
                typeName : 'Testing:Test_mining',
                outputFormat : 'text/javascript',
                typeName : layer_name,
                format_options : 'callback:getJson',
                SrsName : 'EPSG:4326'
            };
                var parameters = L.Util.extend(defaultParameters);
                var URL = geoserver_URL + L.Util.getParamString(parameters);
                //console.log(URL);

                var WFSLayer = null;
                var ajax = $.ajax({
                    url : URL,
                    dataType : 'jsonp',
                    jsonpCallback : 'getJson',
                    success : function (response) {
                        WFSLayer = L.geoJson(response, {
                            style: function (feature) {
                                return {
                                    stroke: false,
                                    fillColor: 'FFFFFF',
                                    fillOpacity: 0
                                };
                            }, 
                            pointToLayer: function(feature, latlng) {
                                return new L.CircleMarker(latlng, {radius: 5, fillOpacity: 0.85});
                            },
                            onEachFeature: function (feature, layer) { 
                                popupOptions = {maxWidth: 400, maxHeight:200};
                                var content = "";
                                var count_properties = 0; 
                                for (var name in feature.properties) {
                                    var field_name = name.substr(0, 1).toUpperCase() + name.substr(1);
                                    var field_value = feature.properties[name] ; 
                                    //if (field_value == "") field_value = "Not found";  //How about Khmer?
                                    if (name != "map_id"){
                                        if (count_properties == 1)
                                             content = content + "<h5>"  + field_value + " </h5>";      
                                        else{                                                 
                                            // Set the regex string
                                             var regexp = /(https?:\/\/([-\w\.]+)+(:\d+)?(\/([-\w\/_\.]*(\?\S+)?)?)?)/ig;
                                            // Replace plain text links by hyperlinks
                                            if (regexp.test(field_value)){  
                                                var field_url_value = field_value.split(";");
                                                console.log(field_url_value.length);         
                                                var url_doc = "";
                                                for(var url =0; url < field_url_value.length; url++ ){
                                                     url_doc = url_doc + field_url_value[url].replace(regexp, "<a href='$1' target='_blank'>$1</a><br />");                                  
                                                }    
                                                field_value = url_doc;
                                            }
                                            
                                            content = content + "<strong>" + field_name.replace("_", " ") + ": </strong>" + field_value + "<br>";    
                                        }
                                            
                                    } 
                                    count_properties= count_properties + 1; 
                                };
                                layer.bindPopup(content ,popupOptions); 
                                layer.on({
                                    mouseover: function highlightFeature(e) {
                                        var layer = e.target;
                                        
                                        if (feature.geometry.type != "Point"){
                                            layer.setStyle({
                                                //fillColor: "yellow",
                                                stroke: true,
                                                color: "orange",
                                                weight: 2,
                                                opacity: 0.7,
                                                fillOpacity: 0.2
                                            });
                                        }else {
                                            layer.setStyle({
                                                stroke: true,
                                                color: "orange",
                                                radius: 5,
                                                weight: 4,
                                                opacity: 0.7,
                                                fillOpacity: 0.2
                                            }); 
                                        }
                                        
                                        if (!L.Browser.ie && !L.Browser.opera) {
                                            layer.bringToFront();
                                        }
                                    },
                                    mouseout: function resetHighlight(e) {
                                            WFSLayer.resetStyle(e.target);
                                            //info.update();
                                    }
                                });  
                            }
                        }).addTo(map);
                        //map.fitBounds(WFSLayer.getBounds());
                    }
                });

        if(layer.legend) {
             pLayer._legend = layer.legend;
        } else {
             pLayer._legend = '<img src="'+geoserver_URL+'?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=30&HEIGHT=25&LAYER='+layer_name+'&legend_options=fontName:Times%20New%20Roman;fontAntiAliasing:true" alt = "Legend"></img>';
        }
        parsedLayers.push(pLayer);

   }//else
   // End H.E
  });

  return parsedLayers;
 };

 jeo.loadLayers = function(map, parsedLayers) {

  for(var key in map.legendControl._legends) {
   map.legendControl.removeLegend(key);
  }

  if(map.coreLayers) {
  console.log(map.coreLayers._layers);
   for(var key in map.coreLayers._layers) {
    map.coreLayers.removeLayer(key);
   }
  } else {  
   map.coreLayers = new L.layerGroup(); 
   map.addLayer(map.coreLayers);
  }
  $.each(parsedLayers, function(i, layer) {

       if(layer._legend) {
        map.legendControl.addLegend(layer._legend);
       }
       layer.addTo(map.coreLayers);
       if(layer._tilejson) {
        map.addControl(L.mapbox.gridControl(layer));
       }

  });  // $.each

  return map.coreLayers;
 }

 jeo.parseConf = function(conf) {

  var newConf = $.extend({}, conf);

  newConf.server = conf.server;

  if(conf.conf)
   newConf = _.extend(newConf, conf.conf);

  newConf.layers = [];
  newConf.filteringLayers = {};
  newConf.filteringLayers.switchLayers = [];
  newConf.filteringLayers.swapLayers = [];

  $.each(conf.layers, function(i, layer) {
   newConf.layers.push(_.clone(layer));
   if(layer.filtering == 'switch') {
    var switchLayer = {
     ID: layer.ID,
     title: layer.title,
     content: layer.post_content,
     excerpt: layer.excerpt,
     download: layer.download_url,
     legend: layer.legend
    };
    if(layer.hidden){
         switchLayer.hidden = true;
    }
    newConf.filteringLayers.switchLayers.push(switchLayer);
   }
   if(layer.filtering == 'swap') {
    var swapLayer = {
     ID: layer.ID,
     title: layer.title,
     content: layer.post_content,
     excerpt: layer.excerpt,
     legend: layer.legend
    };
    if(layer.first_swap)
     swapLayer.first = true;
    newConf.filteringLayers.swapLayers.push(swapLayer);
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
 }

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
 }

 /*
  * Callback manager
  */

 jeo.callbacks = {};

 jeo.createCallback = function(name) {
  jeo.callbacks[name] = [];
  jeo[name] = function(callback) {
   jeo.callbacks[name].push(callback);
  }
 }

 jeo.runCallbacks = function(name, args) {
  if(!jeo.callbacks[name]) {
   console.log('A JEO callback tried to run, but wasn\'t initialized');
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
  }
  _run(jeo.callbacks[name]);
 }

  jeo.createCallback('mapReady');
 jeo.createCallback('layersReady');

})(jQuery);