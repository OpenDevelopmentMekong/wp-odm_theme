var groups = {};
var interactiveMap;

(function($) {

	interactiveMap = function(conf) {

		var group = {};

		var _init = function() {

			if(conf.mainMap)
				$('body').addClass('loading-map');

			return group.build(conf);

		}

		if($.isReady) {
			_init();
		} else {
			$(document).ready(_init);
		}

		group.build = function(data) {

			data.postID = data.postID || data.groupID;

			group.$ = $('#interactive-map_' + data.postID);

			// store maps data
			group.mapsData = data.maps;

			// nodes
			group.$.nav = group.$.find('.map-nav');
			group.$.map = group.$.find('.map');

			group.containerID = group.$.map.attr('id');

			// prepare first map and group conf
			var firstMapID = group.$.nav.find('li:first-child a').data('map');

			group.conf = _.extend(data, jeo.parseConf(group.mapsData[firstMapID]));
			delete group.conf.postID;

			// set jeo conf containerID to group id
			group.conf.containerID = group.containerID;

			// force main map
			group.conf.mainMap = true;

			// store current map id
			group.currentMapID = firstMapID;

			// build group
			group.map = jeo(group.conf);
			group.map.isGroup = true;
			group.map.currentMapID = firstMapID;

			group.updateUI();

			group.$.nav.find('li a').click(function() {

				// disable "more" tab click
				if($(this).hasClass('toggle-more'))
					return false;

				if($(this).hasClass('active'))
					return false;

				var mapID = $(this).data('map');

				// update layers
				group.update(mapID);

				// update ui
				group.updateUI();

				return false;
			});

			jeo.runCallbacks('interactiveMapReady', [group]);

		}

		group.updateUI = function() {

			var mapID = group.currentMapID;
			var $navEl = group.$.nav.find('[data-map="' + mapID + '"]');

			group.$.nav.find('li a').removeClass('active');
			$navEl.addClass('active');

		}

		group.update = function(mapID) {

			// store prev conf
			var prevMap = group.map;
			var prevConf = prevMap.conf;

			// prepare new conf and layers
			var conf = jeo.parseConf(group.mapsData[mapID]);
			var layers = jeo.loadLayers(group.map, jeo.parseLayers(group.map, conf.layers));

			// store new conf
			group.map.conf = conf;

			// update current map id
			group.currentMapID = mapID;
			group.map.currentMapID = mapID;

			/*
			 * reset geocode
			 */
			if(prevConf.geocode)
				group.map.geocode.removeFrom(group.map);

			if(group.map.conf.geocode)
				group.map.addControl(new jeo.geocode());


			/*
			 * reset filtering layers
			 */
			if(prevConf.filteringLayers)
				group.map.filterLayers.removeFrom(group.map);

			if(group.map.conf.filteringLayers)
				group.map.addControl(new jeo.filterLayers());

			/*
			 * clear tooltips
			 */
			 group.$.find('.map-tooltip').hide();


			/*
			 * reset legend
			 */
			 if(typeof group.map.legendControl !== 'undefined') {
				if(prevConf.legend_full_content)
					group.map.legendControl.removeLegend(prevConf.legend_full_content);
				else
					group.map.legendControl.removeLegend(prevConf.legend);
			}

			if(conf.legend)
				group.map.legendControl.addLegend(conf.legend);

			if(conf.legend_full)
				jeo.enableDetails(group.map, conf.legend, conf.legend_full);


			// callbacks
			jeo.runCallbacks('interactiveMapChanged', [group, prevMap]);

		}

		groups[group.id] = group;
		return group;
	}

	jeo.createCallback('interactiveMapReady');
	jeo.createCallback('interactiveMapChanged');

})(jQuery);