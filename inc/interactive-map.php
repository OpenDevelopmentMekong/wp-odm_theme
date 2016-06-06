<?php
/*
 * Open Development
 * Interactive Map
 */
 class OpenDev_InteractiveMap {
 	function __construct() {
 		add_shortcode('odmap', array($this, 'shortcode'));
 	}
  function add_post_to_map_array($post_id){
      $layer = array();
      $layer['filtering'] = 'switch';
      $layer['hidden'] = 1;
      if (function_exists(extended_jeo_get_layer)){
          $layer = array_merge($layer, extended_jeo_get_layer($post_id)); //added by H.E
      }else {
          $layer = array_merge($layer, jeo_get_layer($post_id));
      }
      return $layer;
  }
  function get_maplayers_key_by_post_id($array, $search_val) {
    foreach ($array as $key => $val){
        if (in_array($search_val, $val)){
          return $key;
        }
      return false;
    }
  }
 	function shortcode() {
    $layers = array();
    $cat_baselayers = 'base-layer';
    $term_baselayers = get_term_by('slug', $cat_baselayers, 'layer-category');
    $cat_baselayers_id =  $term_baselayers->term_id;
 		$categories = get_terms('layer-category');
		ob_start();
		?>
		<div class="interactive-map">
			<div class="map-container">
				    <div id="map_interactive_map_0" class="map"></div>
			</div>

      <?php
          $args_base_layer = array(
              'posts_per_page' => 5,
              'post_type' => 'map-layer',
              'post_status' => 'publish',
              'tax_query' => array(
                                  array(
                                    'taxonomy' => 'layer-category',
                                    'field' => 'slug',
                                    'terms' => $cat_baselayers,
                                    'include_children' => false
                                  )
                                )
          );
            $base_layer_posts = get_posts( $args_base_layer );
            if($base_layer_posts){
                $base_layers_array = array();
                echo '<div class="baselayer-container">';
                foreach ( $base_layer_posts as $baselayer ) :
                    setup_postdata( $baselayer ); ?>
                    <div class="baselayer" data-layer="<?php echo $baselayer->ID; ?>"><?php echo $baselayer->post_title; ?></div>
                    <?php
                        if (get_post_meta($baselayer->ID, '_mapbox_id', true))
                            $base_layers_array[$baselayer->ID] =  array("layer_url" => get_post_meta($baselayer->ID, '_mapbox_id', true));
                        else if(get_post_meta($baselayer->ID, '_tilelayer_tile_url', true))
                            $base_layers_array[$baselayer->ID] = array("layer_url" => get_post_meta($baselayer->ID, '_tilelayer_tile_url', true));

                            $base_layers[] = $this->add_post_to_map_array($baselayer->ID);
                endforeach;
                echo '</div>'; //baselayers
                wp_reset_postdata();
            }

      //Get cetegory and layer
          $layer_taxonomy = 'layer-category';
          $layer_term_args=array(
            'parent' => 0,
            'exclude' => $cat_baselayers_id //43002
          );
          $terms_layer = get_terms($layer_taxonomy,$layer_term_args);
          if ($terms_layer) {
            ?>
            <div class="category-map-layers box-shadow hide_show_container">
                <h2 class="sidebar_header map_headline widget_headline"><?php _e("Map Layers", "opendev"); ?>
                 <i class='fa fa-caret-down hide_show_icon'></i>
                </h2>
                <div class="interactive-map-layers dropdown">
                  <ul class="categories">
                  <?php
                  foreach( $terms_layer as $term ) {
                     $args_layer = array(
                         'post_type' => 'map-layer',
                         'tax_query' => array(
                                             array(
                                               'taxonomy' => 'layer-category',
                                               'field' => 'id',
                                               'terms' => $term->term_id, // Where term_id of Term 1 is "1".
                                               'include_children' => false
                                             )
                                           )
                     );
                     $query_layer = new WP_Query( $args_layer );
                     ?>
                     <li class="cat-item cat-item-<?php the_ID(); ?>" id="post-<?php the_ID(); ?>">
                         <a href="#<?php //the_permalink(); ?>"><?php echo $term->name ?></a>
                          <?php
                          if($query_layer->have_posts() ){
                            echo "<ul class='cat-layers switch-layers'>";
                            while ( $query_layer->have_posts() ) : $query_layer->the_post();
                            ?>
                              <li class="layer-item" data-layer="<?php the_ID(); ?>" id="post-<?php the_ID(); ?>">
                                  <img class="list-loading" src="<?php echo get_stylesheet_directory_uri() ?>/img/loading-map.gif">
                                  <span class="list-circle-active"></span>
                                  <span class="list-circle-o"></span>
                                  <span class="layer-item-name"><?php the_title(); ?></span>
                                  <?php
                                  if ( (CURRENT_LANGUAGE != "en") ){
                                    $layer_legend = get_post_meta(get_the_ID(), '_layer_legend_localization', true);
                                    $layer_download_link = get_post_meta(get_the_ID(), '_layer_download_link_localization', true);
                                    $layer_profilepage_link = get_post_meta(get_the_ID(), '_layer_profilepage_link_localization', true);
                                  }else {
                                    $layer_legend = get_post_meta(get_the_ID(), '_layer_legend', true);
                                    $layer_download_link = get_post_meta(get_the_ID(), '_layer_download_link', true);
                                    $layer_profilepage_link = get_post_meta(get_the_ID(), '_layer_profilepage_link', true);
                                  }

                                  if($layer_legend!=""){ ?>
                                    <div class="legend"><?php echo $layer_legend; ?></div>
                                  <?php }
                                  if($layer_download_link!=""){ ?>
                                    <a class="download-url" href="<?php echo $layer_download_link; ?>" target="_blank"><i class="fa fa-arrow-down"></i></a>
                                    <a class="toggle-info" alt="Info" href="#"><i id="<?php the_ID(); ?>" class="fa fa-info-circle"></i></a>
                                  <?php
                                  }else if(get_the_content()!= ""){ ?>
                                    <a class="toggle-info" alt="Info" href="#"><i id="<?php the_ID(); ?>" class="fa fa-info-circle"></i></a>
                                  <?php
                                  }
                                  if($layer_profilepage_link!=""){ ?>
                                    <a class="profilepage_link" href="<?php echo $layer_profilepage_link; ?>" target="_blank"><i class="fa fa-table"></i></a>
                                  <?php } ?>
                              </li>
                            <?php
                            $layers[get_the_ID()] = $this->add_post_to_map_array(get_the_ID());
                            endwhile;
                            echo "</ul>";
                          } //$query_layer->have_posts
                            $children_term = get_terms($layer_taxonomy, array('parent' => $term->term_id, 'hide_empty' => 0, 'orderby' => 'name', ) );
                          if ( !empty($children_term) ) {
                            echo '<ul class="children">';
                              walk_child_category_by_post_type( $children_term, "map-layer", "", 0 );
                            echo '</ul>';
                          }
                          ?>

                     </li>
                   <?php // use reset postdata to restore orginal query
                   wp_reset_postdata();
                  }//foreach ?>
                  </ul><!--<ul class="categories">-->
                </div><!--interactive-map-layers dropdown-->
            </div><!--category-map-layers  box-shadow-->
            <?php
         		$map = opendev_get_interactive_map_data();
         		$map['dataReady'] = true;
         		$map['postID'] = 'interactive_map';
         		$map['count'] = 0;
            $map['layers'] = $layers;
         		$map['title'] = __('Interactive Map', 'opendev');
            if($map['base_layer']) {
         			array_unshift($map['layers'], array(
         				'type' => 'tilelayer',
         				'tile_url' => $map['base_layer']['url']
         			));
         		}

            $map['baselayers'] = $base_layers;
          }//if terms_layer
      ?>
      <!--<div class="category-map-layers box-shadow hide_show_container">
            <h2 class="sidebar_header widget_headline"><?php _e("Map Layers", "opendev"); ?>
             <i class='fa fa-caret-down hide_show_icon'></i>
            </h2>
      			<div class="interactive-map-layers dropdown">
      				<ul class="categories">
      					<?php // get all layers form different categories, but not base-layer category
                  wp_list_categories(array('taxonomy' => 'layer-category', 'title_li' => '', 'depth'=> 2, 'exclude'=> $cat_baselayers_id)); //43002 ?>
      				</ul>
      			</div>
     </div><!- -category-map-layers-->
   </div><!-- interactive-map" -->

     <div class="box-shadow map-legend-container hide_show_container">
       <h2 class="widget_headline"><?php _e("LEGEND", "opendev"); ?> <i class='fa fa-caret-down hide_show_icon'></i></h2>
       <div class="map-legend dropdown">
          <hr class="color-line" />
         <ul class="map-legend-ul">
         </ul>
       </div>
     </div><!--map-legend-container-->

     <div class="box-shadow layer-toggle-info-container layer-right-screen">
       <div class="toggle-close-icon"><i class="fa fa-times"></i></div>
        <?php
        foreach($layers as $individual_layer){
            $get_post_by_id = get_post($individual_layer["ID"]);
            if ( (CURRENT_LANGUAGE != "en") ){
               $get_download_url = str_replace("?type=dataset", "", get_post_meta($get_post_by_id->ID, '_layer_download_link_localization', true));
            }else {
               $get_download_url = str_replace("?type=dataset", "", get_post_meta($get_post_by_id->ID, '_layer_download_link', true));
            }

            // get post content if has
            if (function_exists( qtrans_use)){
              $get_post_content_by_id = qtrans_use(CURRENT_LANGUAGE, $get_post_by_id->post_content,false);
            }else{
              $get_post_content_by_id = $get_post_by_id->post_conten;
            }
              if($get_download_url!="" ){
                    $ckan_dataset_id_exploded_by_dataset = explode("/dataset/", $get_download_url);
                    $ckan_dataset_id = $ckan_dataset_id_exploded_by_dataset[1];
                    $ckan_domain = $ckan_dataset_id_exploded_by_dataset[0];
                    // get ckan record by id
                    $get_info_from_ckan = get_dataset_by_id($ckan_domain,$ckan_dataset_id);
                    $showing_fields = array(
                                        //  "title_translated" => "Title",
                                          "notes_translated" => "Description",
                                          "odm_source" => "Source(s)",
                                          "odm_date_created" => "Date of data",
                                          "odm_completeness" => "Completeness",
                                          "license_id" => "License"
                                      );

                    get_metadata_info_of_dataset_by_id(CKAN_DOMAIN, $ckan_dataset_id, $get_post_by_id, 1,  $showing_fields);
              } else if($get_post_content_by_id){ ?>
                    <div class="layer-toggle-info toggle-info-<?php echo $individual_layer['ID']; ?>">
                        <div class="layer-toggle-info-content">
                            <h4><?php echo get_the_title($individual_layer['ID']); ?></h4>
                            <?php echo $get_post_content_by_id ?>
                            <?php //echo $individual_layer['excerpt']; ?>
                        </div>
                    </div>
              <?php
              }
              ?>
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

          var all_layers_value = <?php echo json_encode($layers) ?>;
					$layers.find('.cat-layers li').on('click', function(e) {
              var target =  $( e.target );
              if (target.is( "span" ) ) {
                var get_layer_id = $(this).data('layer');

                if($(this).hasClass('active')){
                    jeo.toggle_layers(map, all_layers_value[get_layer_id]);
                    var legend_li_disactive_class = "."+$(this).data('layer');
                    $('.map-legend-ul '+legend_li_disactive_class).remove().fadeOut('slow');
                    if ( !$(".map-legend-ul li").length){
                       $('.map-legend-container').hide('slow');
                    }
                }else {
                  $(this).addClass('loading');
                  jeo.toggle_layers(map, all_layers_value[get_layer_id]);
                  var get_legend = $(this).find(".legend").html();
                  if( typeof get_legend != "undefined"){
                      var legend_li = '<li class="hide_show_container '+$(this).data('layer')+'">'+ $(this).find(".legend").html()+'</li>';
                      $('.map-legend-ul').prepend(legend_li);

                      // Add class title to the legend title
                      var legend_h5 = $( ".map-legend-ul ."+$(this).data('layer')+" h5" );
                      if (legend_h5.length == 0){
                        var h5_title = '<h5>'+ $(this).children('.layer-item-name').text()+'</h5>';
                        $( ".map-legend-ul ."+$(this).data('layer')+" .cartodb-legend" ).prepend(h5_title);

                      }
                      var legend_h5_title = $( ".map-legend-ul ."+$(this).data('layer')+" h5" );
                      legend_h5_title.addClass("title");

                      // Add class dropdown to the individual legend box
                      legend_h5_title.siblings().addClass( "dropdown" );

                      //dropdown legen auto show
                      $( ".map-legend-ul ."+$(this).data('layer')+" .dropdown").show();

                      // Add hide_show_icon into h5 element
                      var hide_show_icon = "<i class='fa fa-caret-down hide_show_icon'></i>";
                      legend_h5_title.prepend(hide_show_icon);

                      if ($(".map-legend-ul li").length){
                         $('.map-legend-container').slideDown('slow');
                      }
                          //console.log("MMMMM "+ legen_li + $(this).data('layer') );
                  }//typeof get_legend != "undefined"

                } //if has class active
              }//if (target.is( "span" ) )
    			}); //$layers.find('.cat-layers li')

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
        $(".baselayer-container").find('.baselayer').on('click', function() {
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


			})(jQuery);
		</script>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}
new OpenDev_InteractiveMap();
