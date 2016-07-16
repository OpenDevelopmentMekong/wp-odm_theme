(function($) {
	$(document).ready(function(){
		//close toggle-information box
		$(".toggle-close-icon").click(function(){
			$(this).parent().fadeOut();
			$(this).siblings(".layer-toggle-info").fadeOut();
			$(this).siblings(".layer-toggle-info").removeClass('show_it');
		});
		//$( ".news-marker input[type=checkbox]" ).on( "click", function(){
		//$('.news-marker .news-marker-toggle').click(function(){
		$('.news-marker label').on( "click", '.news-marker-toggle', function(e){
			$('.leaflet-marker-icon').toggle();
		});
		//Hide and show on click the collapse and expend icon
		$(document).on('click',".hide_show_container h2 > .hide_show_icon, .hide_show_container h5 > .hide_show_icon", function (e) {
			e.stopPropagation();
			var target =  $( e.target );
			var parent_of_target =  $( e.target ).parent();
			var drop = parent_of_target.siblings('.dropdown');
			//console.log(drop);
					target.toggleClass('fa-caret-down');
					target.toggleClass('fa-caret-up');

			if (drop.is(":hidden")) {
				parent_of_target.removeClass("title_active")
					.siblings('.dropdown').hide();
				drop.show();
				parent_of_target.addClass("title_active");
				//parent_of_target.parent().addClass("ms_active");
			} else {
				drop.hide();
				parent_of_target.removeClass("title_active");
			}
		}); //end onclick

		//Drag Drop to change zIndex of layers
		$( ".map-legend-ul" ).sortable({
			stop: function (event, ui) {
				//var layer_Id = $(ui.item).attr('id');
			   $($(".map-legend-ul > li").get().reverse()).each(function (index) {
					var layer_Id = $(this).attr('id');
					jeo.bringLayerToFront(layer_Id, index);
				});
			},
		}).disableSelection();
	});
	//Loading layers
	jeo.mapReady(function(map) {
		var $layers = $('.interactive-map .interactive-map-layers');
		$layers.find('.categories ul').hide();
		$layers.find('li.cat-item > a').on('click', function() {
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).parent().find('ul').hide();
			} else {
				$(this).addClass('active');
				$(this).parent().find('> ul').show();
			}
			return false;
		});

		//Display the information of baselayer on mouseover
		$(".baselayer-container").find('.baselayer-ul .baselayer').on( "mouseover", function(e) {
			$(this).children(".baselayer_description").show();
		}).on( "mouseout", function(e) {
			$(this).children(".baselayer_description").hide();
		});
		//Baselayer is switched
		$(".baselayer-container").find('.baselayer-ul .baselayer').bind('click', function(e) {
			var base_layer_id = $(this).data('layer');
			var target =  $( e.target );
			if (target.is( "li" ) || target.is(".baselayer_thumbnail img") || target.is(".baselayer_name") ) {
				if($(this).hasClass('active')){
					$(this).removeClass("active");
					jeo.toggle_baselayers(map, all_baselayer_value[0]);
				}else {
					$(this).find('.baselayer-loading').show();
					$(".baselayer-container").find('.baselayer-ul .baselayer').removeClass("active");
					$(this).addClass("active");
					jeo.toggle_baselayers(map, all_baselayer_value[base_layer_id]);
				}
			}
		});

		//Layer enable/disable
		$layers.find('.cat-layers li').on('click', function(e) {
		  var target =  $( e.target );
		  if (target.is( "span" ) ) {
			var get_layer_id = $(this).data('layer');

			if($(this).hasClass('active')){
				jeo.toggle_layers(map, all_layers_value[get_layer_id]);
				$('.layer-toggle-info-container').hide();
				$(this).find('i.fa-info-circle').removeClass("active");
				$('.map-legend-ul .'+get_layer_id).remove().fadeOut('slow');
				if ( !$(".map-legend-ul li").length){
				   $('.map-legend-container').hide('slow');
				}
			}else if($(this).hasClass('loading')){
				console.log("still loading");
				return false;
			}else {
			  $(this).addClass('loading');
			  jeo.toggle_layers(map, all_layers_value[get_layer_id]);
			  var get_legend = all_layers_legends[get_layer_id]; //$(this).find(".legend").html();
			  if( typeof get_legend != "undefined"){
				  var legend_li = '<li class="legend-list hide_show_container '+$(this).data('layer')+'" id ='+$(this).data('layer')+'>'+ get_legend +'</li>';

				  $('.map-legend-ul').prepend(legend_li);

				  // Add class title to the legend title
				  var legend_h5 = $( ".map-legend-ul ."+$(this).data('layer')+" h5" );
				  if (legend_h5.length == 0){
					var h5_title = '<h5>'+ $(this).children('.layer-item-name').text()+ '</h5>';
					$( ".map-legend-ul ."+$(this).data('layer')+" .legend").first().prepend(h5_title);
				  }
				  var legend_h5_title = $( ".map-legend-ul ."+$(this).data('layer')+" h5" );
				  legend_h5_title.addClass("title");

				  // Add class dropdown to the individual legend box
				  legend_h5_title.siblings().addClass( "dropdown" );

				  //dropdown legen auto show
				  $( ".map-legend-ul ."+$(this).data('layer')+" .dropdown").show();

				  // Add hide_show_icon into h5 element
				  var hide_show_icon = "<i class='fa fa-times-circle' id='"+$(this).data('layer')+"' aria-hidden='true'></i>";
					  hide_show_icon += "<i class='fa fa-caret-down hide_show_icon'></i>";
				  legend_h5_title.prepend(hide_show_icon);

				  if ($(".map-legend-ul li").length){
					 $('.map-legend-container').slideDown('slow');
				  }
			  }//typeof get_legend != "undefined"

			} //if has class active
		  }//if (target.is( "span" ) )
		}); //$layers.find('.cat-layers li')

		//Click on info icon
		$layers.find('.cat-layers li i.fa-info-circle').on('click', function(e) {
			var target =  $( e.target );
			//Get the tool tip container width adn height
			var toolTipWidth = $(".layer-toggle-info-container").width();
			var toolTipHeight = $(".layer-toggle-info-container").height();
			$('.layer-toggle-info-container').hide();
			$('.toggle-info-'+$(this).attr('id')).siblings(".layer-toggle-info").hide();
			$('.toggle-info-'+$(this).attr('id')).siblings(".layer-toggle-info").removeClass('show_it');
			if ( target.is( "i.fa-info-circle" )) {
				if ($(this).hasClass("active")){
					$(this).removeClass("active");
				}else{
					$layers.find('.cat-layers li i.fa-info-circle').removeClass('active');
					$(this).addClass("active");
					if ($('.toggle-info-'+$(this).attr('id')).length){
					//get the height position of the current object
						  var elementHeight = $(this).height();
						  var offsetWidth = 40;
						  var offsetHeight = 30;
						  var marginright = 10;
						  var marginbttom = 10;

						  //Get the HTML document width and height
						  var documentWidth = $(document).width();
						  var documentHeight = $(document).height();

						  //Set top and bottom position of the tool tip
						  var top = $(this).offset().top;
						  if (top + toolTipHeight > documentHeight) {
							  // flip the tool tip position to the top of the object
							  // so it won't go out of the current Html document height
							  // and show up in the correct place
							  top = documentHeight - toolTipHeight - offsetHeight - (2 * elementHeight) - marginbttom;
						  }

						  //set  the left and right position of the tool tip
						  var left = $(this).offset().left + (2*offsetWidth);

						  if (left + toolTipWidth > documentWidth) {
							  // shift the tool tip position to the left of the object
							  // so it won't go out of width of current HTML document width
							  // and show up in the correct place
							  //left = documentWidth - toolTipWidth - (2 * offsetWidth);
							  left = $(this).offset().left - toolTipWidth - (offsetWidth) + marginright;
						  }

						  //set the position of the tool tip
						  $('.toggle-info-'+$(this).attr('id')).css("max-height", toolTipHeight-offsetHeight);
						  $('.toggle-info-'+$(this).attr('id')).addClass("show_it");
						  $('.toggle-info-'+$(this).attr('id')).show();
						  $('.layer-toggle-info-container').show();

						  //set info-container possition folow the mouseclik/mouseover
						  //$('.layer-toggle-info-container').css({'max-height':'100%' ,'top': top, 'left': left });
						  //show tool tips
						 // $('.layer-toggle-info-container').fadeIn();
					}
				}

			}//end if

		});

		$('.hide_show_container').on( "click", '.fa-times-circle', function(e){
		  var get_layer_id = $(this).attr("ID");
		  var target = $( e.target );
		  if ( target.is( "i" ) ) {
			  jeo.toggle_layers(map, all_layers_value[get_layer_id]);
			  $('.layer-toggle-info-container').hide();
			  $("#"+get_layer_id).find('i.fa-info-circle').removeClass("active");
			  $('.map-legend-ul .'+get_layer_id).remove().fadeOut('slow');
			  if ( !$(".map-legend-ul li").length){
				 $('.map-legend-container').hide('slow');
			 }
		  }
		});

	}); //	jeo.mapReady

})(jQuery);
