var original_layers_height;

(function($) {
	jeo.layers = L.Control.extend({
		options: {
			position: 'topright'
		},

		onAdd: function(map) {
			this._container = L.DomUtil.create('div', 'jeo-layers leaflet-bar leaflet-control hideOnDesktop');
			this._$ = $(this._container);

			this._map = map;

			this._map.layers = this;

			this._$.append('<a class="map-layers layers open-mobile-dialog" href="#layers"></a>');

			this._bindEvents();

			return this._container;
		},
		_bindEvents: function() {
			var self = this;
	    if ($(document).width() < 750) {
	        this._map.setZoom(6);
	    }

			this._$.click(function() {
				var visibleSiblings = [];
				$(".hide-on-mobile-dialog").each(function(index){
					$(this).hide();
				});
				$(".map-container").parents(".row").css("display","block");
				$(".category-map-layers.mobile-dialog").css("display","block");
				return false;
			});
		}
	});

})(jQuery);
