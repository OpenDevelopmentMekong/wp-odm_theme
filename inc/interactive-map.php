<?php
/*
 * Open Development
 * Interactive Map
 */
 class OpenDev_InteractiveMap {
 	function __construct() {
 		add_shortcode('odmap', array($this, 'shortcode'));
 	}
 	function shortcode() {
    $query_arg = array(
 			'post_type' => 'map-layer',
 			'posts_per_page' -1
 		);
 		$layer_query = new WP_Query($query_arg);
    $layer_query_args = array(
      'post_type' => 'map-layer',
      'posts_per_page'=>-1
    );
    $layer_query = new WP_Query($layer_query_args);
    $layers = array();
 		$categories = get_terms('layer-category');
 		$parsed_cats = array();
 		if($layer_query->have_posts()) {
 			while($layer_query->have_posts()) {
        $layer_query->the_post();
 				$layer = array();
 				$layer['filtering'] = 'switch';
 				$layer['hidden'] = 1;

        foreach($categories as $key=>$val) {
           	$cat = $categories[$key];
            if(is_object_in_term(get_the_ID(), 'layer-category', $cat->term_id)) {
             		if(!isset($parsed_cats[$cat->term_id]))
             			$parsed_cats[$cat->term_id] = array();
             		$parsed_cats[$cat->term_id][] = get_the_ID();
             		$parsed_cats[$cat->term_id]['order'] = $key;
             	}
        }//foreach

           if (function_exists(extended_jeo_get_layer)){
                $layer = array_merge($layer, extended_jeo_get_layer(get_the_ID())); //added by H.E
            }else {
                $layer = array_merge($layer, jeo_get_layer(get_the_ID()));
            }
				$layers[] = $layer;
				wp_reset_postdata();
			}
    }//$layer_query
 		$map = opendev_get_interactive_map_data();
 		$map['dataReady'] = true;
 		$map['postID'] = 'interactive_map';
 		$map['layers'] = $layers;
 		$map['count'] = 0;
 		$map['title'] = __('Interactive Map', 'opendev');
 		if($map['base_layer']) {
 			array_unshift($map['layers'], array(
 				'type' => 'tilelayer',
 				'tile_url' => $map['base_layer']['url']
 			));
 		}
 		// print_r($map);
		ob_start();
		?>
		<div class="interactive-map">
			<div class="map-container">
				    <div id="map_interactive_map_0" class="map"></div>
			</div>

      <?php
            $cat_baselayers = 'base-layer';
            $term_baselayers = get_term_by('slug', $cat_baselayers, 'layer-category');
            $cat_baselayers_id =  $term_baselayers->term_id;
            $args_base_layer = array( 'posts_per_page' => 5,
                                       'post_type' => 'map-layer',
                                    	 'post_status' => 'publish',
                                       'tax_query' => array(
                                                           array(
                                                             'taxonomy' => 'layer-category',
                                                             'field' => 'slug',
                                                             'terms' => $cat_baselayers
                                                           )
                                                         )
                                       ); //'offset'=> 1,
            $base_layer_posts = get_posts( $args_base_layer );
          /*  if($base_layer_posts){
                $base_layers_array = array();
                echo '<div class="baselayers">';
                foreach ( $base_layer_posts as $baselayer ) :
                    setup_postdata( $baselayer ); ?>
                    <div class="b_layer" data-layer="<?php echo $baselayer->ID; ?>"><?php echo $baselayer->post_title; ?></div>
                    <?php
                        if (get_post_meta($baselayer->ID, '_mapbox_id', true))
                            $base_layers_array[$baselayer->ID] =  array("layer_url" => get_post_meta($baselayer->ID, '_mapbox_id', true));
                        else if(get_post_meta($baselayer->ID, '_tilelayer_tile_url', true))
                            $base_layers_array[$baselayer->ID] = array("layer_url" => get_post_meta($baselayer->ID, '_tilelayer_tile_url', true));
                endforeach;
                echo '</div>'; //baselayers
                wp_reset_postdata();
            }*/
            //print_r(json_encode($base_layers_array));
          ?>
      <div class="baselayer"><ul class="base-layers" /></div>
      <div class="category-map-layers box-shadow hide_show_container">
            <h2 class="sidebar_header widget_headline"><?php _e("Map Layers", "opendev"); ?>
             <i class='fa fa-caret-down hide_show_icon'></i>
            </h2>
      			<div class="interactive-map-layers dropdown">
      				<ul class="categories">
      					<?php // get all layers form different categories, but not base-layer category
                  wp_list_categories(array('taxonomy' => 'layer-category', 'title_li' => '', 'depth'=> 2, 'exclude'=> $cat_baselayers_id)); //43002 ?>
      				</ul>
      			</div>
     </div><!--category-map-layers-->
   </div><!-- interactive-map" -->

     <div class="box-shadow map-legend-container hide_show_container">
       <h2 class="widget_headline"><?php _e("LEGEND", "opendev"); ?> <i class='fa fa-caret-down hide_show_icon'></i></h2>
       <div class="map-legend dropdown">
          <hr class="color-line" />
         <ul class="map-legend-ul">
         </ul>
       </div>
     </div><!--map-legend-container-->

     <?php //  print_r($map['layers']);   ?>
     <div class="box-shadow layer-toggle-info-container layer-right-screen">
       <div class="toggle-close-icon"><i class="fa fa-times"></i></div>
        <?php $lang = 'en';
        $i = 0;
        //if (function_exists("qtranxf_getLanguage")) $lang = qtranxf_getLanguage();
        foreach($map['layers'] as $individual_layer){ $i++;
            $get_post_by_id = get_post($individual_layer['ID']);
            //$get_post_content_by_id = apply_filters('the_content', $get_post_by_id->post_content);
            if (function_exists( qtrans_use))
              $get_post_content_by_id = qtrans_use($lang, $get_post_by_id->post_content,false);
            else
              $get_post_content_by_id = $get_post_by_id->post_conten;

            //echo "<pre>".$individual_layer['ID']."=> ".$get_post_content_by_id ."</pre>";
              if($individual_layer['download_url']!="" ){
                    $split_download_url = explode("?type=", $individual_layer['download_url']);
                    $split_url_bw_ckanlink_dataset_id = explode("/dataset/", $split_download_url[0]);
                    $ckan_domain = $split_url_bw_ckanlink_dataset_id[0];
                    $ckan_dataset_id =   $split_url_bw_ckanlink_dataset_id[1];

                    // get ckan record by id
                    $get_info_from_ckan = get_dataset_by_id($ckan_domain,$ckan_dataset_id);
                    //print_r(  $get_info_from_ckan );
                    $showing_fields = array(
                                          "title_translated" => "Title",
                                          "notes_translated" => "Description",
                                          "odm_source" => "Source(s)",
                                          "odm_date_created" => "Date of data",
                                          "odm_completeness" => "Completeness",
                                          "license_id" => "License"
                                      );

                    ?>
                    <?php if($get_info_from_ckan) { //print_r($get_info_from_ckan); ?>
                    <?php //echo "I: ".$i ." CKAN OF DATASET ". $individual_layer['ID'] ."<br/>"; ?>
                      <div class="layer-toggle-info toggle-info-<?php echo $individual_layer['ID']; ?>">
                            <table border="0" class="toggle-talbe">
                                <tr><td colspan="2"><h5><?php echo $get_info_from_ckan['title_translated'][$lang] ?></h5></td></tr>
                                <tr>
                                    <td><?php echo $showing_fields['notes_translated']; ?></td><td><?php echo $get_info_from_ckan['notes_translated'][$lang]; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $showing_fields['odm_source']; ?></td><td><?php echo $get_info_from_ckan['odm_source']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $showing_fields['odm_date_created']; ?></td><td><?php echo $get_info_from_ckan['odm_date_created']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $showing_fields['odm_completeness']; ?></td><td><?php echo $get_info_from_ckan['odm_completeness'][$lang]; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $showing_fields['license_id']; ?></td>
                                    <td><?php echo $get_info_from_ckan['license_id'] == "unspecified"? ucwords($get_info_from_ckan['license_id'] ) : $get_info_from_ckan['license_id']; ?></td>
                                </tr>
                            </table>
                            <div class="atlernative_links">
                            <?php if ($lang != 'en'){ ?>
                                    <div class="div-button"><a href="<?php echo $individual_layer['download_url_localization']; ?>" target="_blank"><i class="fa fa-arrow-down"></i> <?php _e("Download data", "opendev"); ?></a></div>

                                    <?php if ($individual_layer['profilepage_url_localization']){ ?>
                                      <div class="div-button"><a href="<?php echo $individual_layer['profilepage_url_localization']; ?>" target="_blank"><i class="fa fa-table"></i> <?php _e("View dataset table", "opendev"); ?></a></div>
                                    <?php } ?>
                            <?php }else {  ?>
                                    <div class="div-button"><a href="<?php echo $individual_layer['download_url']; ?>" target="_blank"><i class="fa fa-arrow-down"></i> <?php _e("Download data", "opendev"); ?></a></div>

                                    <?php if ($individual_layer['profilepage_url']){ ?>
                                      <div class="div-button"><a href="<?php echo $individual_layer['profilepage_url']; ?>" target="_blank"><i class="fa fa-table"></i> <?php _e("View dataset table", "opendev"); ?></a></div>
                                    <?php } ?>
                            <?php } ?>
                            </div><!-- atlernative_links -->
                          </div><!--layer-toggle-info-->
                    <?php }//if download_url available
              } else if($get_post_content_by_id){
                //echo "I: ".$i." ID: ".$individual_layer['ID']." HAS CONTENT " . $get_post_content_by_id ."<br/>"; ?>
                        <div class="layer-toggle-info toggle-info-<?php echo $individual_layer['ID']; ?>">
                            <div class="layer-toggle-info-content">
                                <h4><?php echo get_the_title($individual_layer['ID']); ?></h4>
                                <?php echo $get_post_content_by_id ?>
                                <?php //echo $individual_layer['excerpt']; ?>
                            </div>
                        </div>
              <?php } ?>
          <?php
      }// foreach
        ?>
     </div><!--llayer-toggle-info-containero-->

		<script type="text/javascript">
			(function($) {
        // Resize the map container and category box based on the browsers
        /*   //Page is not schollable
        var resize_height_map_container = window.innerHeight - $("#od-head").height() -10 + "px";
        var resize_height_map_category = window.innerHeight - $("#od-head").height() -33 + "px";
        var resize_height_map_layer = window.innerHeight - $("#od-head").height()  - 73 + "px";*/

        // Page is scrollable
        var resize_height_map_container = window.innerHeight - $("#od-head").height()+75 + "px"; //map, layer cat, and legend
        var resize_height_map_category = window.innerHeight - $("#od-head").height() + "px";
        var resize_height_map_layer = window.innerHeight - $("#od-head").height() - 41+ "px";
        var resize_layer_toggle_info = $(".layer-toggle-info-container").height() -30 + "px";

        $(".page-template-page-map-explorer .interactive-map .map-container").css("height", resize_height_map_container);
        $(".page-template-page-map-explorer .category-map-layers").css("max-height", resize_height_map_category);
        $(".page-template-page-map-explorer .interactive-map-layers").css("max-height", resize_height_map_layer);
        $(".page-template-page-map-explorer .layer-toggle-info").css("max-height", resize_layer_toggle_info);
        $(".page-template-page-map-explorer .layer-toggle-info").css("display", "none");
        $(window).resize(function() {
          $(".page-template-page-map-explorer .interactive-map .map-container").css("height", resize_height_map_container);
          $(".page-template-page-map-explorer .category-map-layers").css("max-height", resize_height_map_category);
          $(".page-template-page-map-explorer .interactive-map-layers").css("max-height", resize_height_map_layer);
          $(".page-template-page-map-explorer .layer-toggle-info").css("max-height", resize_layer_toggle_info);
        });
        // End Resize

        //close toggle-information box
        $(".toggle-close-icon").click(function(){
            $(this).parent().fadeOut();
            $(this).siblings(".layer-toggle-info").fadeOut();
            $(this).siblings(".layer-toggle-info").removeClass('show_it');
        });


				var term_rel = <?php echo json_encode($parsed_cats); ?>;
				jeo(jeo.parseConf(<?php echo json_encode($map); ?>));
				jeo.mapReady(function(map) {
					var $layers = $('.interactive-map .interactive-map-layers');
					if(map.postID == 'interactive_map') {
						//map.$.find('.jeo-filter-layers').appendTo($layers);
						for(var key in term_rel) {
							var $item = $layers.find('.cat-item-' + key);
							$item.find(' > a').after($('<ul class="cat-layers switch-layers" />'));
							$.each(term_rel[key], function(i, layerId) {
								var $layer = map.$.find('[data-layer="' + layerId + '"]');
								$layer.appendTo($item.find('.cat-layers'));
							});
						}
						$('.jeo-filter-layers').hide();
					}
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

          var cancel = false;
					$layers.find('.cat-layers li').on('click', function(e) {
            var target =  $( e.target );
                //if ( target.is( "li" ) || target.is( "span" ) ) {
                  if (target.is( "span" ) ) {
    						      map.filterLayers._switchLayer($(this).data('layer'));

    						      if(map.filterLayers._getStatus($(this).data('layer')).on) {
          							$(this).addClass('active');
                        var legend_li = '<li class="hide_show_container '+$(this).data('layer')+'">'+ $(this).find(".legend").html()+'</li>';
                        $('.map-legend-ul').prepend(legend_li);

                        // Add class title to the legend title
                        var legend_h5 =$( ".map-legend-ul ."+$(this).data('layer')+" h5" );
                        legend_h5.addClass("title");

                        // Add class dropdown to the individual legend box
                        legend_h5.next().addClass( "dropdown" );

                        //dropdown legen auto show
                        $( ".map-legend-ul ."+$(this).data('layer')+" .dropdown").show();

                        // Add hide_show_icon into h5 element
                        var hide_show_icon = "<i class='fa fa-caret-down hide_show_icon'></i>";
                        legend_h5.prepend(hide_show_icon);

                        if ($(".map-legend-ul li").length){
                           $('.map-legend-container').slideDown('slow');
                        }
                        //console.log("MMMMM "+ legen_li + $(this).data('layer') );
          						} else {
            							$(this).removeClass('active');
                          var legend_li_disactive_class = "."+$(this).data('layer');
              						$('.map-legend-ul '+legend_li_disactive_class).remove().fadeOut('slow');
                          if ( !$(".map-legend-ul li").length){
                             $('.map-legend-container').hide('slow');
                          }
          						}
                  }
    					}); //$layers.find('.cat-layers li')

              // if mouseover on info icon on cick/mouseover

          //$layers.find('.cat-layers li i.fa-info-circle').mouseover( "cick", function(e) {
          $layers.find('.cat-layers li i.fa-info-circle').on('click', function(e) {
                var target =  $( e.target );
                //Get the tool tip container width adn height
                var toolTipWidth = $(".layer-toggle-info-container").width();
                var toolTipHeight = $(".layer-toggle-info-container").height();
                $('.toggle-info-'+$(this).attr('id')).siblings(".layer-toggle-info").hide();
                $('.layer-toggle-info-container').toggle();
                $('.toggle-info-'+$(this).attr('id')).siblings(".layer-toggle-info").removeClass('show_it');
                if ( target.is( "i.fa-info-circle" )) {
                  if ($('.toggle-info-'+$(this).attr('id')).length){
                        //$('.layer-toggle-info').hide();
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

                              //set info-container possition folow the mouseclik/mouseover
                              //$('.layer-toggle-info-container').css({'max-height':'100%' ,'top': top, 'left': left });
                              //show tool tips
                              $('.layer-toggle-info-container').fadeIn();
                    }

                    //console.log("documentHeight: "+documentHeight + " documentWidth: "+documentWidth + " toolTipWidth:" + toolTipWidth +" left:"+ $(this).offset().left+" top:"+$(this).offset().top +" toolTipHeight: "+toolTipHeight +" offsetHeight:"+ offsetHeight +" elementHeight:" +elementHeight);
                }//end if

            });
                /*$(".layer-toggle-info-container").on( "mouseout", function(e) {
                      $(".layer-toggle-info-container").hide();
                });*/
				}); //	jeo.mapReady
        var baselayer_attr = [];
        $(".baselayers").find('.b_layer').on('click', function() {
            	var base_layer_id = $(this).data('layer');
              var baselayer_value = <?php echo json_encode($base_layers_array) ?>;
              var baselayer_url = baselayer_value[base_layer_id].layer_url;
              var	mapBaselayer  = L.tileLayer(baselayer_url);
        });

        //Hide and show on click the collapse and expend icon
        $(document).on('click',".hide_show_container h2 > .hide_show_icon, .hide_show_container h5 > .hide_show_icon", function (e) {
            e.stopPropagation();
            var target =  $( e.target );
            var parent_of_target =  $( e.target ).parent();
            var drop = parent_of_target.siblings('.dropdown');

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


			})(jQuery);
		</script>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}
new OpenDev_InteractiveMap();
