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
				$(".priting_footer").show();
				$(".print-preview-map").prepend("<h1 class='map-title'><span class='adding-map-title'><span></h1>")
				$(".print-setting").show(function(){
					$(".baselayer-ul").hide();
					$(".category-map-layers").hide();
					$(".leaflet-control-container").hide();

					if($("#print-basemap").is(':checked')){
						$(".baselayer-ul").show();
					}

					if($("#print-layer").is(':checked')){
						$(".category-map-layers").show();
					}

					if($("#print-tools").is(':checked')){
						$(".leaflet-control-container").show();
					}

					if($("#print-north-direction").is(':checked')){
						$(".print-preview-basemap .north-direction").fadeIn();
					}

					$("#print-all-legend").parent(".form-group").hide();
					if ($(".map-legend-ul li").length){
						$("#print-all-legend").parent().fadeIn();
						if($("#print-all-legend").is(':checked')){
							$(".map-legend-container").css("max-height", "100%");
							$(".map-legend-container .map-legend").css("max-height", "100%");
						}else {
							$(".map-legend-container").css("max-height", "350px");
							$(".map-legend-container .map-legend").css("max-height", "25vh");
						}
					}

					if($("#print-legend").val()== "right"){
						$(".map-legend-container").css("left", "");
						$(".map-legend-container").css("right", 0);
						if ($(".map-legend-ul li").length){
							$(".map-legend-container").fadeIn();
						}
					}else if($("#print-legend").val()== "left"){
						$(".map-legend-container").css("right", "");
						$(".map-legend-container").css("left", 0);
						if ($(".map-legend-ul li").length){
							$(".map-legend-container").fadeIn();
						}
					}else{
						$(".map-legend-container").fadeOut();
					}

					$(".map-legend-container").css("bottom", $(".priting_footer").height());

					if($("#print-title").val() !=""){
						$('.map-title .adding-map-title').text($("#print-title").val());
					}

					if($("#print-description").val() !=""){
						$(".printing-description").show();
						$('.printing-description').text($("#print-description").val());
						$(".map-legend-container").css("bottom", $(".priting_footer").height());
					}

				});
			}

			this._map.invalidateSize(true);
		}
	});

 	$("document").ready(function(){
			$('#print-button').click(function(event) {
			//$(document).on('click', "#print-button", function (event) {
    		event.preventDefault();
				$(".print-loading").show();
				html2canvas($(".print-preview-map"), {
							flashcanvas: "/wp-content/themes/wp-odm_theme/inc/html2canvas/flashcanvas.min.js",
              logging: true,
              profile: false,
							proxy: '/wp-content/themes/wp-odm_theme/inc/html2canvas/proxy.php',
              useCORS: true,
							svgRendering:true,
							useOverflow:true,
							onrendered: function(canvas) {
	               manipulateCanvasFunction(canvas);
	            }
          });
		});

		$("#print-basemap").change(function(){
			$(".baselayer-ul").fadeToggle();
		});

		$("#print-layer").change(function(){
			$(".category-map-layers").fadeToggle();
		});

		$("#print-layout").change(function(){
			$(".print-preview-map").toggleClass('portrait');
		});

		$("#print-legend").change(function(){
			if($("#print-legend").val()== "left"){
				$(".map-legend-container").css("right", "");
				$(".map-legend-container").css("left", 0);
				if ($(".map-legend-ul li").length){
					$(".map-legend-container").fadeIn();
				}
			}else if($("#print-legend").val()== "right"){
				$(".map-legend-container").css("left", "");
				$(".map-legend-container").css("right", 0);
				if ($(".map-legend-ul li").length){
					$(".map-legend-container").fadeIn();
				}
			}else{
				$(".map-legend-container").fadeOut();
			}

			$(".map-legend-container").css("bottom", $(".priting_footer").height());
		});
		$("#print-all-legend").change(function(){
			if($("#print-all-legend").is(':checked')){
				$(".map-legend-container").css("max-height", "100%");
					$(".map-legend-container .map-legend").css("max-height", "100%");
			}else {
				$(".map-legend-container").css("max-height", "350px");
				$(".map-legend-container .map-legend").css("max-height", "25vh");
			}
		});

		$("#print-tools").change(function(){
			$(".leaflet-control-container").fadeToggle();
		});

		$("#print-north-direction").change(function(){
			$(".print-preview-basemap .north-direction").fadeToggle();
		});

		$("#print-title").keyup(function(event){
			var addingtitle = event.target.value;
			$('.map-title .adding-map-title').text(addingtitle);
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
				$(".north-direction").hide();
				$(".interactive-map").removeClass('print-preview-map');
				$('body').removeClass('print-preview');
				$("section#map > .map-wraper").contents().unwrap();
				$(".baselayer-container").removeClass('print-preview-basemap');
				$(".print-preview .interactive-map-layers").css("max-height", original_layers_height);
				$('.map-title').remove();
				$('.map-title .adding-map-title').text("");
				$(".print-loading").hide();
				$(".printing-description").hide();
				$(".printing-description").text("");
				$(".baselayer-container").show();
				$(".category-map-layers").show();
				if ($(".map-legend-ul li").length){
					$(".map-legend-container").fadeIn();
				}
				$(".map-legend-container").css("left", 0);
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
	dataURL = savedMap.toDataURL("image/jpg", 1.0);

	var a = document.createElement('a');
	a.href = dataURL;
	a.download = "downloaded_map.jpg";
	document.getElementsByClassName("print-setting")[0].appendChild(a);
	//window.open(dataURL);
	a.click();
	$(".print-loading").hide();
}
