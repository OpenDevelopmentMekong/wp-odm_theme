var original_layers_height;

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
				self.toggle();
				return false;
			});
		},
		toggle: function() {
			var container;
			var frame = window.frameElement;
			original_layers_height = $(".interactive-map-layers").height();

			if(this._map.$.parents('.content-map').length){
				container = this._map.$.parents('.content-map');
			}else{
				container = this._map.$.parents('.map-container');
			}

			if(!container.parent().hasClass('print-preview-map')) {
				container.parent().addClass('print-preview-map');
				$('body').addClass('print-preview');
				$("section#map").wrapInner( "<div class='map-wraper print-preview-overlay'></div>" );
				$(".print-preview .interactive-map-layers").css("max-height", $(".print-preview-map").height() -150);
				$(".baselayer-container").addClass('print-preview-basemap');
				$(".print-setting").show();
				$(".priting_footer").show();
				$(".print-preview-map").prepend("<h1 class='map-title'></h1>")
			}

			this._map.invalidateSize(true);
		}
	});

 	$("document").ready(function(){
			$('#print-button').click(function() {
				$(".print-loading").show();
				$(".print-preview-map").html2canvas({
						flashcanvas: "/wp-content/themes/wp-odm_theme/inc/html2canvas/flashcanvas.min.js",
						proxy: '/wp-content/themes/wp-odm_theme/inc/html2canvas/proxy.php',
						logging: false,
						profile: false,
						useCORS: true
					});
		});

		$("#print-basemap").change(function(){
			$(".baselayer-container").fadeToggle();
		});

		$("#print-layer").change(function(){
			$(".category-map-layers").fadeToggle();
		});

		$("#print-layout").change(function(){
			$(".print-preview-map").toggleClass('portrait');
		});

		$("#print-legend").change(function(){
			if($("#print-legend").val()== "left"){
				$(".map-legend-container").css("left", 0);
			}

			if($("#print-legend").val()== "right"){
				$(".map-legend-container").css("right", 0);
				$(".map-legend-container").css("bottom", $(".priting_footer").height());
			}
		});

		$("#print-tools").change(function(){
			$(".leaflet-control-container").fadeToggle();
		});

		$("#print-north-direction").change(function(){
			$(".print-preview-basemap").toggleClass("no-north-direction");
		});

		$("#print-title").keyup(function(event){
			var addingtitle = event.target.value;
			$('.map-title').text(addingtitle);
		});

		$("#print-description").keyup(function(event){
			var addingdescriptiom = event.target.value;
			if(addingdescriptiom.length > 0){
				$(".printing-description").show();
				$('.printing-description').text(addingdescriptiom);
				$(".map-legend-container").css("bottom", $(".priting_footer").height());
			}else{
					$(".printing-description").hide();
			}
		});

		$('.fa-times-circle').click(function(){
			if($(".interactive-map").hasClass('print-preview-map')) {
				$(".print-setting").hide();
				$(".priting_footer").hide();
				$(".interactive-map").removeClass('print-preview-map');
				$('body').removeClass('print-preview');
				$("section#map > .map-wraper").contents().unwrap();
				$(".baselayer-container").removeClass('print-preview-basemap');
				$(".print-preview .interactive-map-layers").css("max-height", original_layers_height);
				$('.map-title').remove();
				$(".print-loading").hide();
				$(".printing-description").hide();
				$(".baselayer-container").show();
				$(".category-map-layers").show();
				$(".leaflet-control-container").show();
			}
		});

	});
	/*
	$(document).keyup(function(e) {
	  if (e.keyCode == 27){
			exit_print_preview();
		}
	});
*/

})(jQuery);

function manipulateCanvasFunction(savedMap) {
	dataURL = savedMap.toDataURL("image/jpg");
	dataURL = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	$.post("/wp-content/themes/wp-odm_theme/inc/html2canvas/ajax/saveMap.php", { savedMap: dataURL }, function(data) {
			var a = document.createElement('a');
			a.href = "/wp-content/themes/wp-odm_theme/inc/html2canvas/"+data;
			a.download = "download_map.png";
			document.getElementsByClassName("print-setting")[0].appendChild(a);
			a.click();
			document.getElementsByClassName("print-setting")[0].removeChild(a);
	 		//window.open("/wp-content/themes/wp-odm_theme/inc/html2canvas/"+data, "Download");
	 	}).fail(function(xhr, status, error) {
			//console.log(xhr);
    });
	$(".print-loading").hide();
}
