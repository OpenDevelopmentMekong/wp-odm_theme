<?php
/*
 * Open Development
 * Interactive Map
 */

require_once get_stylesheet_directory().'/inc/utils/mapping.php';
class OpenDev_InteractiveMap {
    function __construct() {
        add_shortcode('odmap', array($this, 'shortcode'));
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
        $base_layers = array();
        $layers_legend = array();
        $map = odm_get_interactive_map_data();
        $map['postID'] = 'interactive_map';
        $map['count'] = 0;
        $map['title'] = __('Interactive Map', 'opendev');
        $cat_baselayers = 'base-layers';
        $term_baselayers = get_term_by('slug', $cat_baselayers, 'layer-category');
        $cat_baselayers_id =  $term_baselayers->term_id;
        $cat_map_catalogue = 'map-catalogue';
        $term_map_catalogue = get_term_by('slug', $cat_map_catalogue, 'layer-category');
        $cat_map_catalogue_id =  $term_map_catalogue->term_id;

        $categories = get_terms('layer-category');
        ob_start();
        ?>
        <div class="interactive-map">
        	<div class="map-container">
        		<div id="map_interactive_map_0" class="map"></div>
        	</div>
        	<?php
        	//Get all posts in Layer of map-category to assing to layers array for loading layer on map
        	$all_post_layers_arg =  array(
                                      'post_type' => 'map-layer',
                                      'posts_per_page' => -1,
                                      'post_status' => 'publish',
                                      'orderby'   => 'title',
                                      'order'   => 'ASC',
                                      'tax_query' => array(array(
                                                            'taxonomy' => 'layer-category',
                                                            'terms' => $exclude_posts_in_cats,
                                                            'field' => 'id',
                                                            'operator' => 'NOT IN'
                                                      ))
                                      );
        	$all_post_layers = new WP_Query( $all_post_layers_arg );

        	if($all_post_layers->have_posts() ){
                while ( $all_post_layers->have_posts() ) : $all_post_layers->the_post();
                    $post_ID = get_the_ID();
                    $layers[$post_ID] = get_post_meta_of_layer($post_ID );

                    if(get_legend_of_map_by($post_ID)!=""){
                        $layers_legend[$post_ID ] = get_legend_of_map_by($post_ID);
                    }
                endwhile;
                wp_reset_postdata();
        	}//end if

        	//Show Baselayers Navigations
        	display_baselayer_navigation(5, 'base-layers', false);

        	//Get all baselayers' post meta for loading on map
        	$base_layers = get_post_meta_of_all_baselayer();

        	//List cetegory and layer by cat for menu items
            $layer_taxonomy = 'layer-category';
            $exclude_posts_in_cats = array($cat_baselayers_id, $cat_map_catalogue_id);
            $layer_term_args=array(
              'parent' => 0,
              'orderby'   => 'name',
              'order'   => 'ASC',
              'exclude' => $exclude_posts_in_cats
            );
            $terms_layer = get_terms($layer_taxonomy,$layer_term_args);
            if ($terms_layer) {
                echo '<div class="category-map-layers box-shadow hide_show_container">';
                    echo '<h2 class="sidebar_header map_headline widget_headline">'.__("Map Layers", "odm");
                        echo "<i class='fa fa-caret-down hide_show_icon'></i>";
                    echo '</h2>';
                    echo '<div class="interactive-map-layers dropdown">';
                        echo '<ul class="categories">';
                          foreach( $terms_layer as $term ) {
                            $args_layer = array(
                               'posts_per_page' => -1,
                               'post_type' => 'map-layer',
                               'orderby'   => 'name',
                               'order'   => 'asc',
                               'tax_query' => array(
                                                  'relation' => 'AND',
                                                   array(
                                                     'taxonomy' => 'layer-category',
                                                     'field' => 'id',
                                                     'terms' => $term->term_id,
                                                     'include_children' => false,
			                                               'operator' => 'IN'
                                                   ),
                                                   array(
                                                     'taxonomy' => 'layer-category',
                                                     'field' => 'id',
                                                     'terms' => $exclude_posts_in_cats,
                                                     'operator' => 'NOT IN'
                                                    )
                                                 )
                            );
                            $query_layer = new WP_Query( $args_layer );
                            $count_items_of_main_cat = 0;
                            $main_category_li = '<li class="cat-item cat-item-'.get_the_ID().'" id="post-'.get_the_ID().'"><a href="#">'.$term->name.'</a>';
                            $layer_items = "";
                            if($query_layer->have_posts() ){
                                $cat_layer_ul= "<ul class='cat-layers switch-layers'>";
                                    while ( $query_layer->have_posts() ) : $query_layer->the_post();
                                            $count_items_of_main_cat++;
                                            $layer_items .= display_layer_as_menu_item_on_mapNavigation(get_the_ID(), 0);
                                    endwhile;
                                    // use reset postdata to restore orginal query
                                    wp_reset_postdata();

                                $cat_layer_close_ul =  "</ul>";
                            } //$query_layer->have_posts
                            $children_term = get_terms($layer_taxonomy, array('parent' => $term->term_id, 'hide_empty' => 0, 'orderby' => 'name') );
                            $sub_cats = "";
                            if ( !empty($children_term) ) {
                                $sub_cats = walk_child_category_by_post_type( $children_term, "map-layer", "", $exclude_posts_in_cats );
                                if ($sub_cats !=""){
                                    $count_items_of_main_cat++;
                                }
                            }
                            $main_category_close_li = '</li>';
                            if($count_items_of_main_cat > 0){ //if layers and sub-cats exist
                             echo $main_category_li;
                                 echo $cat_layer_ul;
                                    echo $layer_items;
                                 echo $cat_layer_close_ul ;
                                 echo $sub_cats;
                             echo $main_category_close_li;
                            }
                          }//foreach
                        echo '</ul>'; //ul: class="categories"
                    echo '</div>'; //interactive-map-layers dropdown
                echo '</div>'; //category-map-layers  box-shadow

                $map['dataReady'] = true;
                $map['baselayers'] = $base_layers;
                $map['layers'] = $layers;
                if($map['base_layer']) {
                    array_unshift($map['layers'], array(
                    	'type' => 'tilelayer',
                    	'tile_url' => $map['base_layer']['url']
                    ));
                }
            }//if terms_layer
            ?>
        </div><!-- interactive-map" -->

        <?php
        display_legend_container();
        display_layer_information($layers);
        ?>

        <script type="text/javascript">
            var all_baselayer_value = <?php echo json_encode($base_layers) ?>;
            var all_layers_value = <?php echo json_encode($layers) ?>;
            var all_layers_legends = <?php echo json_encode($layers_legend) ?>;

            jeo(jeo.parseConf(<?php echo json_encode($map); ?>));

            (function($) {
                var adminbar = 0;
                if($('body').hasClass("admin-bar")){
                  adminbar = 35;
                }
                var resize_height_map_container = window.innerHeight - adminbar -140 + "px"; //map, layer cat, and legend
                var resize_height_map_category = window.innerHeight  - adminbar - 178 + "px";
                var resize_height_map_layer = window.innerHeight  - adminbar - 215+ "px";
                var resize_layer_toggle_info = $(".layer-toggle-info-container").height()  - adminbar -38 + "px";

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
            })(jQuery);

        </script>
        <?php
        $html = ob_get_clean();
        return $html;
    }//end shortcode
}//end class
new OpenDev_InteractiveMap();
?>
