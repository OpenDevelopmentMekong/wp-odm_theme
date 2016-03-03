(function($) {

	jeo.clearscreen = L.Control.extend({

		options: {
			position: 'topleft'
		},

		onAdd: function(map) {

			this._container = L.DomUtil.create('div', 'jeo-clearscreen leaflet-bar leaflet-control');
			this._$ = $(this._container);

			this._map = map;

			this._map.clearscreen = this;

			this._$.append('<a class="map-clearscreen hide-all-boxes-on-map" href="#clearscreen"></a>');

			this._bindEvents();

			return this._container;
		},

		_bindEvents: function() {
			var self = this;

			this._$.click(function() {

				self.toggle();
				return false;

			});
		},
		toggle: function() {
			if($('.map-clearscreen').hasClass('hide-all-boxes-on-map')){
					$('.category-map-layers').hide("fade");
					$('.map-legend-container').hide("fade");
					$('.layer-toggle-info-container').hide("fade");
			}else if ($('.map-clearscreen').hasClass('show-all-boxes-on-map')){
					$('.category-map-layers').show("fade");
					if ($(".map-legend-container .map-legend-ul li").length){
						  $('.map-legend-container').show("fade");
					}
					if ($(".layer-toggle-info-container .show_it").length){
						  $('.layer-toggle-info-container').show("fade");
					}
			  

			}
	 		$('.map-clearscreen').toggleClass('show-all-boxes-on-map');
			$('.map-clearscreen').toggleClass('hide-all-boxes-on-map');
		}
	});

})(jQuery);
