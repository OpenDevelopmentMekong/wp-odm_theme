(function($) {

	jeo.createCallback('markerCentered');

	var markers = function(map) {

		if(map.conf.disableMarkers || map.conf.admin)
			return false;

		map.markers = markers;

		var	layer,
			features = [],
			geojson,
			fragment = false,
			listPost,
			icon = L.Icon.extend({}),
			activeIcon = new icon(opendev_markers.marker_active),
			activeMarker;

		if(typeof jeo.fragment === 'function' && !map.conf.disableHash)
			fragment = jeo.fragment();


		$.getJSON(opendev_markers.ajaxurl,
		{
			action: 'markers_geojson',
			query: opendev_markers.query
		},
		function(data) {
			geojson = data;
			if(geojson === 0)
				return;
			_build(geojson);
		});

		var _build = function(geojson) {

			var icons = {};

			var parentLayer;
			if(opendev_markers.enable_clustering) {

				parentLayer = new L.MarkerClusterGroup({
					maxClusterRadius: 20,
					iconCreateFunction: function(cluster) {
        				return new L.DivIcon({ html: '<b class="story-points">' + cluster.getChildCount() + '</b>' });
   					}
				});

			} else {
				parentLayer = new L.layerGroup();
			}

			map.addLayer(parentLayer);

			layer = L.geoJson(geojson, {
				pointToLayer: function(f, latLng) {

					var marker = new L.marker(latLng);
					features.push(marker);
					marker.addTo(parentLayer);
					return marker;

				},
				onEachFeature: function(f, l) {

					if(f.properties.marker.markerId) {

						if(icons[f.properties.marker.markerId]) {
							var fIcon = icons[f.properties.marker.markerId];
						} else {
							var fIcon = new icon(f.properties.marker);
							icons[f.properties.marker.markerId] = fIcon;
						}

						l.markerIcon = fIcon;

						l.setIcon(fIcon);

					}

					l.bindPopup(f.properties.bubble);

					l.on('mouseover', function(e) {
						e.target.previousOffset = e.target.options.zIndexOffset;
						e.target.setZIndexOffset(1500);
						e.target.openPopup();
					});
					l.on('mouseout', function(e) {
						e.target.setZIndexOffset(e.target.previousOffset);
						e.target.closePopup();
					});
					l.on('click', function(e) {
						jeo.runCallbacks('markerClicked', [e]);
						console.log(e.target);
						//markers.openMarker(e.target, false);
						window.location = e.target.feature.properties.permalink;
						return false;
					});

				}

			});

			map._markers = features;
			map._markerLayer = parentLayer;

			layer = parentLayer;

			jeo.runCallbacks('markersReady', [map]);

			if(map.conf.sidebar === false)
				return;

			/*
			 * SIDEBAR STUFF (opendev)
			 */

			// FIRST STORY
			var story = features[0];
			var silent = false;

			// if not home, navigate to post
			if(!opendev_markers.home)
				silent = false;

			if(fragment) {
				var fStoryID = fragment.get('story');
				if(fStoryID) {
					var found = _.any(geojson.features, function(f) {
						if(f.properties.id == fStoryID) {
							story = fStoryID;
							if(fragment.get('loc'))
								silent = true;
							return true;
						}
					});
					if(!found) {
						fragment.rm('story');
					}
				}
			}

			// bind list post events
			listPosts = $('.list-posts');
			if(listPosts.length) {
				if(!fStoryID)
					story = listPosts.find('li:first-child').attr('id');
			}

			if(map.conf.forceCenter)
				silent = true;

			if(fStoryID) {

				markers.openMarker(story, silent);

			} else if(!opendev_markers.home || $('html#embedded').length) {

				markers.openMarker(story, silent);

			}

		};

		markers.getMarker = function(markerID) {

			if(typeof markerID == 'undefined')
				return false;

			if(markerID instanceof L.Marker)
				return markerID;

			// if marker is string, get object
			if(typeof markerID === 'string') {
				marker = _.find(features, function(m) { return m.toGeoJSON().properties.id === markerID; });
			}

			if(markerID && !marker)
				marker = _.find(geojson.features, function(f) { return f.properties.id === markerID; });

			return marker;

		};

		markers.activateMarker = function(marker) {

			if(activeMarker instanceof L.Marker) {
				activeMarker.setIcon(activeMarker.markerIcon);
				activeMarker.setZIndexOffset(0);
			}

			if(marker instanceof L.Marker) {
				activeMarker = marker;
				marker.setIcon(activeIcon);
				marker.setZIndexOffset(1000);
				marker.previousOffset = 1000;
				marker = marker.toGeoJSON();
			}

			return marker;

		};

		markers.focusMarker = function(marker) {

			marker = markers.activateMarker(markers.getMarker(marker));

			if(!marker || typeof marker == 'undefined')
				return false;

			var center,
				zoom;

			if(marker.geometry) {
				center = [
					marker.geometry.coordinates[1],
					marker.geometry.coordinates[0]
				];
				if(map.getZoom() < 7) {
					zoom = 7;
					if(map.conf.maxZoom < 7)
						zoom = map.conf.maxZoom;
				} else {
					zoom = map.getZoom();
				}
			} else {
				center = map.conf.center;
				zoom = map.conf.zoom;
			}

			if(typeof marker.properties.zoom !== 'undefined')
				zoom = marker.properties.zoom;

			if(!center || isNaN(center[0]))
				center = [0,0];

			if(!zoom)
				zoom = 1;

			var viewOptions = {
				animate: true,
				duration: 1,
				pan: {
					animate: true,
					duration: 1
				},
				zoom: { animate: true }
			};

			if(window.location.hash == '#print') {
				viewOptions = {
					animate: false,
					duration: 0,
					pan: {
						naimate: false,
						duration: 0
					},
					zoom: { animate: false }
				};
			}

			map.setView(center, zoom, viewOptions);
			if(fragment) {
				fragment.rm('loc');
			}

			return marker;

		};

		markers.openMarker = function(marker, silent) {

			marker = markers.getMarker(marker);

			if(!marker) {
				return false;
			}

			if(!silent) {

				marker = markers.focusMarker(marker);

			} else {

				marker = markers.activateMarker(marker);

			}

			if(map.conf.sidebar === false) {
				window.location = marker.properties.url;
				return false;
			}

			if(fragment) {
				if(!silent)
					fragment.set({story: marker.properties.id});
			}

			if(typeof _gaq !== 'undefined') {
				_gaq.push(['_trackPageView', location.pathname + location.search + '#!/story=' + marker.properties.id]);
			}

			jeo.runCallbacks('markerCentered', [map]);

			// populate sidebar
			if(map.$.sidebar && map.$.sidebar.length) {

				var permalink_slug = marker.properties.permalink.replace(opendev_markers.site_url, '');
				marker.properties.permalink = opendev_markers.site_url + '/' + permalink_slug;

				if(!map.$.sidebar.story) {
					map.$.sidebar.append('<div class="story" />');
					map.$.sidebar.story = map.$.sidebar.find('.story');
				}

				map.$.find('.story-points').removeClass('active');
				var $point = map.$.find('.story-points.' + marker.properties.id);
				$point.addClass('active');

				var storyData = marker.properties;

				var story = '';
				story += '<small>' + storyData.date + '</small>';
				story += '<h2>' + storyData.title + '</h2>';
				if(storyData.thumbnail)
					story += '<div class="media-limit"><img class="thumbnail" src="' + storyData.thumbnail + '" /></div>';
				story += '<div class="story-content"><p>' + storyData.content + '</p></div>';

				var $story = $(story);

				map.$.sidebar.story.empty().append($story);

				// adjust thumbnail image
				map.$.sidebar.imagesLoaded(function() {

					var $sidebar = map.$.sidebar;

					if(!$sidebar.find('.media-limit'))
						return;

					var containerHeight = $sidebar.find('.media-limit').height();
					var imageHeight = $sidebar.find('.media-limit img').height();

					var topOffset = (containerHeight - imageHeight) / 2;

					if(topOffset < 0) {
						$sidebar.find('.media-limit img').css({
							'margin-top': topOffset
						});
					}

				});

				// add share button
				if(!map.$.sidebar.share) {

					map.$.sidebar.append('<div class="buttons" />');
					map.$.sidebar.share = map.$.sidebar.find('.buttons');

					var shareContent = '';
					shareContent += '<a class="button read-button" href="' + storyData.url + '">' + opendev_markers.read_more_label + '</a>';
					shareContent += '<a class="button share-button" href="#">' + opendev_markers.share_label + '</a>';
					//shareContent += '<a class="button print-button" href="#" target="_blank">' + opendev_markers.print_label + '</a>';

					map.$.sidebar.share.append(shareContent);

					// New window if iframe is detected
					if(window !== window.top) {
						map.$.sidebar.share.find('a').attr('target', '_blank');
					}

				}

				map.$.sidebar.share.find('.share-options').hide().addClass('hidden');

				var share_vars = '?p=' + marker.properties.postID;
				var map_id = map.postID;
				if(map.currentMapID)
					map_id = map.currentMapID;

				if(typeof map_id === 'undefined') {
					share_vars += '&layers=' + map.conf.layers;
				} else {
					share_vars += '&map_id=' + map_id;
				}

				var embed_url = opendev_markers.share_base_url + share_vars;
				var print_url = opendev_markers.embed_base_url + share_vars + '&print=1' + '#print';

				map.$.sidebar.share.find('.share-button').attr('href', embed_url);
				map.$.sidebar.share.find('.print-button').attr('href', print_url);

				if(map.currentMapID) {

					jeo.groupChanged(function(group, prevMap) {

						share_vars = '?p=' + marker.properties.postID;
						map_id = map.postID;
						if(map.currentMapID)
							map_id = map.currentMapID;

						if(typeof map_id === 'undefined') {
							share_vars += '&layers=' + map.conf.layers;
						} else {
							share_vars += '&map_id=' + map_id;
						}

						embed_url = opendev_markers.share_base_url + share_vars;
						print_url = opendev_markers.embed_base_url + share_vars + '&print=1' + '#print';

						map.$.sidebar.share.find('.share-button').attr('href', embed_url);
						map.$.sidebar.share.find('.print-button').attr('href', print_url);

					});

				}

				// add close button
				if(!map.$.sidebar.find('.close-story').length && !$('html#embedded').length && opendev_markers.home) {

					map.$.sidebar.append('<a class="close-story" href="#">x</a>');

					map.$.sidebar.find('.close-story').click(function() {
						markers.closeMarker();
						return false;
					});

				}

			}
			var postList = $('.list-posts');
			if(postList.length) {
				postList.find('li').removeClass('active');
				var item = postList.find('#' + marker.properties.id);
				if(item.length) {
					item.addClass('active');
				}
			}

			if(map.$.sidebar && map.$.sidebar.length)
				map.$.sidebar.addClass('active');

			jeo.runCallbacks('markerOpened', [map]);

			return marker;

		};

		markers.closeMarker = function() {

			if(activeMarker instanceof L.Marker) {
				activeMarker.setIcon(activeMarker.markerIcon);
				activeMarker.setZIndexOffset(0);
			}

			if(fragment)
				fragment.rm('story');

			$('.list-posts li').removeClass('active');

			map.$.find('.story-points').removeClass('active');

			map.$.sidebar.removeClass('active').find('.story').empty();
			map.setView(map.conf.center, map.conf.zoom);

		};

		return markers;

	}
	jeo.mapReady(markers);
	jeo.createCallback('markersReady');
	jeo.createCallback('markerClicked');
	jeo.createCallback('markerOpened');

})(jQuery);