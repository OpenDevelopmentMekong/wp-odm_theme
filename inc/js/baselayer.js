(function($) {

	jeo.baselayer = L.Control.extend({

  options: {
    position: 'topright'
  },
  onAdd: function (map){
			var self = this;
			var filtering_baselayers = [];
			this._container = L.DomUtil.create('div', 'jeo-filter-baselayers');
			//this._layers = map.conf.filteringLayers;
			this._layers = map.conf.layers;
			if (map.conf.base_layer != undefined)
				this._default_baselayer = map.conf.base_layer.url;
			else
				this._default_baselayer = "";
			this._map = map;
			this._$ = $(this._container);

			_.each(this._map.conf.layers, function(layer) {
		    // var detect_lang_site = document.documentElement.lang;
		    // if(detect_lang_site == "en-US")
					if (layer.map_category == "base-layer"){
				        filtering_baselayers.push({
				          ID: layer.ID,
				 	       title: layer.title,
				 	       content: layer.post_content,
				 	       map_category: layer.map_category,
				 	       tile_url: layer.tile_url,
				 	       mapbox_id: layer.mapbox_id,
				 	       legend: layer.legend,
				          on: true
								});
						}
		    });

			this._filtering_baselayers = filtering_baselayers;
	    this._build();
			return this._container;
	 },
	 _build: function() {
		var self = this;
		////////////////////////////////////////////
		var baselayer_attr= [];
	 	var	defaultMapUrl = this._default_baselayer;
		var mapDefaultBaselayer   = L.tileLayer(this._default_baselayer);
				baselayer_attr[0] = mapDefaultBaselayer ;

		    /*var g_roadmap   = new L.Google('ROADMAP');
		    var g_satellite = new L.Google('SATELLITE');
		    var g_terrain   = new L.Google('TERRAIN');*/
				////////////////////////////////////////////
				/*baselayer_attr = {
												 "mapBaselayer": mapDefaultBaselayer
											 }; */
				////////////////////////////////////////////
	    if(this._filtering_baselayers.length) {
	       var list_baselayer = '';
	       var legend = '';
				 // Add Default Baselayer set in "Base map settings" of the opendev option, url: https://[country.]opendevelopmentmekong.net/wp-admin/themes.php?page=odm_options
				 list_baselayer += '<li class="baselayer-item">';
				 list_baselayer += '<span class="baselayer-item-name" data-layer="0">Default Map</span>';
				 list_baselayer += '</li>';
				 list_baselayer += '<li class="baselayer-item">';
				 list_baselayer += '<span class="baselayer-item-name" data-layer="10">Street Map</span>';
				 list_baselayer += '</li>';
	       _.each(this._filtering_baselayers, function(layer) {
			      var attrs = 'class="active"';
			        //if layer in baselayer category
		        if (layer.map_category == "base-layer"){
		            list_baselayer += '<li class="baselayer-item">';
								var  layer_name = layer.title.replace(" ", "_");
										 layer_name = layer_name.trim();
		            list_baselayer += '<span class="baselayer-item-name" data-layer="' + layer.ID + '">'+layer.title+'</span>';
		 					 if (layer.mapbox_id)
							 		var baselayer_url = layer.mapbox_id;
		 					 else if (layer.tile_url)
							 		var baselayer_url = layer.tile_url;

		            if (layer.legend)
		             	list_baselayer += '<div class="baselayer-legend">'+layer.legend+'</div>';
		            //if (layer.content)
		             //	list_baselayer += '<div class="baselayer-content">'+layer.content+'</div>';

		            list_baselayer += '</li>';
								var	mapBaselayer  = L.tileLayer(baselayer_url);
										baselayer_attr[layer.ID] = mapBaselayer;
		         }//end else if layer is base-layer category
	       });

						 //////////////////////////////
						 var mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw';
						 var streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: ""});
						 /*baselayer_attr= {
													"10": streets
												};*/
						 baselayer_attr[10] = streets;
						///////////////////////////

	       this._switchWidget = '<ul class="switch-baselayers">' + list_baselayer + '</ul>';
	       this._$.append(this._switchWidget);
	    }

			L.control.layers(baselayer_attr,{}).addTo(this._map);
			globalmap.on('baselayerchange', function(e){
				console.log("===========baselayerchange" );
					globalmap.eachLayer(function (layer) {
							console.log(layer );
						if (globalmap.hasLayer(layer)){
						}
							/*globalmap.removeLayer(layer);
							globalmap.addLayer(layer);
							console.log(layer);*/
					});
				console.log("========baselayerchange");
			});
			//On click
			this._$.on('click', '.switch-baselayers li .baselayer-item-name', function() {
					var interactive_map = this._map;
					var base_layer_id = $(this).data('layer');
					var current_view_baselayer_id = $(".baselayer_active").data('layer');
					/*$.each( baselayer_attr, function( key, value ) {
							if (baselayer_attr[base_layer_id] != undefined)
								map.removeLayer(baselayer_attr[base_layer_id]);
					});*/

					if (current_view_baselayer_id != undefined)
							globalmap.removeLayer(baselayer_attr[current_view_baselayer_id]);

					$(".baselayer-item-name").removeClass('baselayer_active');
					$(this).addClass("baselayer_active");
					baselayer_attr[base_layer_id].addTo(globalmap);
					//baselayer_attr[base_layer_id].setZIndex(0);


		});

		return this._container;
	}

	});

})(jQuery);
