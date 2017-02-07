(function($) {

	jeo.printmap = L.Control.extend({

		options: {
			position: 'topleft'
		},

		onAdd: function(map) {

			this._container = L.DomUtil.create('div', 'jeo-printmap leaflet-bar leaflet-control');
			this._$ = $(this._container);

			this._map = map;

			this._map.printmap = this;

			this._$.append('<a class="map-printmap print" href="#printmap"></a>');

			this._bindEvents();

			return this._container;
		},

		_bindEvents: function() {
			var self = this;

			this._$.click(function() {

				jQuery.print("#map" /*, options*/);
				return false;

			});
		}
	});

})(jQuery);
