var original_layers_height;

(function($) {
	jeo.layers = L.Control.extend({
		options: {
			position: 'topleft'
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
			this._$.click(function() {
				var visibleSiblings = [];
				$(".hide-on-mobile-dialog").each(function(index){
					$(this).hide();	
				});
				$(".mobile-dialog").css("display","contents");
				return false;
			});
		}
	});

})(jQuery);
