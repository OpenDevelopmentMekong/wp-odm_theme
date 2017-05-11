<?php
// get baselayer Navigation
function display_baselayer_navigation($num=5, $cat='base-layers', $include_children=false ){
	$selected_baselayer = $GLOBALS['extended_jeo_layers']->get_map_layers(get_the_ID(), "baselayer");
	$selected_baselayer_obj = json_decode(json_encode($selected_baselayer));

	$get_all_baselayers = query_get_baselayer_posts();
	$baselayer_posts = $selected_baselayer ? $selected_baselayer_obj : $get_all_baselayers;
	if($baselayer_posts){
		echo '<div class="baselayer-container">';
		echo '<ul class="baselayer-ul box-shadow">';
		foreach ( $baselayer_posts as $baselayer ) :
			setup_postdata( $baselayer ); ?>
			<li class="baselayer" data-layer="<?php echo $baselayer->ID; ?>">
				<?php if ( has_post_thumbnail($baselayer->ID) ) { ?>
						<div class="baselayer_thumbnail"><?php echo get_the_post_thumbnail( $baselayer->ID, 'thumbnail' ); ?></div>
						<img class="baselayer-loading" src="<?php echo get_stylesheet_directory_uri() ?>/img/loading-map.gif">
				<?php } ?>
				<?php echo "<div class='baselayer_name'>".$baselayer->post_title."</div>"; ?>
				<?php if($baselayer->post_content != ""){ ?>
						<div class="box-shadow baselayer_description">
						  <div class="toggle-close-icon"><i class="fa fa-times"></i></div>
						  <?php echo get_the_content(); ?></div>
				<?php } ?>
			</li>
			<?php
			if (get_post_meta($baselayer->ID, '_mapbox_id', true)){
				$base_layers[$baselayer->ID] =  array("layer_url" => get_post_meta($baselayer->ID, '_mapbox_id', true));
			}else if(get_post_meta($baselayer->ID, '_tilelayer_tile_url', true)){
				$base_layers[$baselayer->ID] = array("layer_url" => get_post_meta($baselayer->ID, '_tilelayer_tile_url', true));
			}
		endforeach;
		echo '</ul></div>'; //baselayers
		wp_reset_postdata();
	}
}//end function

// get baselayer post only
function query_get_baselayer_posts($num=5, $cat='base-layers', $include_children=false ){
	$args_base_layer = array(
		'posts_per_page' => $num,
		'post_type' => 'map-layer',
		'post_status' => 'publish',
		'tax_query' => array(
							array(
							  'taxonomy' => 'layer-category',
							  'field' => 'slug',
							  'terms' => $cat,
							  'include_children' => $include_children,
								'operator' => 'IN'
							)
						  )
						);
	$baselayer_posts = get_posts( $args_base_layer );
	return $baselayer_posts;
}

function set_default_map_baselayer($map_ID = null){
	if($map_ID):
		$map = get_post_meta($map_ID, 'map_data', true);
	else:
		$map = odm_get_interactive_map_data();
	endif;

	if(!empty($map)):
		if($map['base_layer']):
			$baselayer = $map['base_layer'];
			if($baselayer):
				$default_map = array(
					'ID' => 0,
					'filtering' => '',
					'type' => 'tilelayer',
					'tile_url' => $baselayer['url']
				);

				return $default_map;
			endif;
		endif;
	endif;

	return;
}
//Query all baselayers' post meta into an array
function get_post_meta_of_all_baselayer($num=5, $cat='base-layers', $include_children=false){
	//get map configure from the opendev-setting
	if(set_default_map_baselayer()):
			$base_layers[0] = set_default_map_baselayer();
	endif;
	$base_layer_posts = query_get_baselayer_posts();
	if($base_layer_posts){
		foreach ( $base_layer_posts as $baselayer ) :
			$base_layers[$baselayer->ID] = get_post_meta_of_layer($baselayer->ID);
		endforeach;
	}
	return $base_layers;
}
//************//
function display_map_layer_sidebar_and_legend_box($layers, $show_cat = null){
	if (!empty($layers)){
		unset($layers[0]); //basemap

		echo '<div class="category-map-layers box-shadow hide_show_container">';
			echo '<h2 class="sidebar_header map_headline widget_headline">'.__("Map Layers", "odm");
				echo "<i class='fa fa-caret-down hide_show_icon'></i>";
			echo '</h2>';
			echo '<div class="interactive-map-layers dropdown">';
				if($show_cat):
					$tmp_category = array();
					foreach ($layers as $key => $row) {
						$tmp_category[$key] = null;
						if(isset($row['map_category'])):
							$tmp_category[$key] = $row['map_category'];
							$layer_cat[] = $row['map_category'];
						endif;
					}
					$layer_category = array_unique($layer_cat);
					asort($layer_category);
					array_multisort($tmp_category, SORT_ASC, $layers);
					echo '<ul class="categories layer-category">';
						foreach ($layer_category as $cat) {
									$category = get_term_by('slug', $cat, 'layer-category');
									echo '<li class="cat-item cat-item-'.$category->term_id.'" id="post-'.$category->term_id.'">';
										echo'<a href="#">'.$category->name.'</a>';
										echo "<ul class='cat-layers switch-layers cat-layer-items'>";
											foreach ($layers as $id => $layer) {
												if($layer['map_category'] == $cat):
													display_layer_as_menu_item_on_mapNavigation($layer['ID'], 1, $layer['filtering']);
												endif;
											}
										echo "</ul>";
									echo "</li>";
						}

					echo "</ul>";
				else:
					echo "<ul class='cat-layers switch-layers cat-layer-items'>";
						foreach ($layers as $id => $layer) {
							display_layer_as_menu_item_on_mapNavigation($layer['ID'], 1, $layer['filtering']);
						}
					echo "</ul>";
				endif;
				echo '<div class="news-marker">';
				echo '<label><input class="news-marker-toggle" type="checkbox" />';
				 	echo '<span class="label">'.__("Show news on map", "odm")."</span>";
				echo '</label>';
				echo '</div>';
				echo '<div class="searchFeature">';
					echo '<input type="text" name="searchFeature_by_mapID" class="hidden" value="" id="searchFeature_by_mapID" size="10" />';
				echo '</div>';
			echo '</div>'; //interactive-map-layers dropdown
		echo '</div>'; //category-map-layers  box-shadow

		//show legend box
		display_legend_container();

		//show layer information
    display_layer_information($layers);
		?>
		<script type="text/javascript">
		(function($) {
				// Resize height of layer menu
				var resize_height_map_category = $('.map-container').height() - 120 + "px";

				$(".category-map-layers .cat-layer-items").css("max-height", resize_height_map_category);
				$(window).resize(function() {
						$(".category-map-layers .cat-layer-items").css("max-height", resize_height_map_category);
				});
		})(jQuery);
		</script>
		<?php
	}
}

//display layer menus fixed on right bar
function display_layer_as_menu_item_on_mapNavigation($post_ID, $echo =1, $option=""){
	if($post_ID !== 0){
		$get_post = get_post($post_ID);
		$title = apply_filters('translate_text', $get_post->post_title, odm_language_manager()->get_current_language());
		$content = apply_filters('translate_text', $get_post->post_content, odm_language_manager()->get_current_language());
		$layer_items = '<li class="layer-item '.$option.'" data-layer="'.$post_ID.'" id="post-'.$post_ID.'">
		  <img class="list-loading" src="'. get_stylesheet_directory_uri(). '/img/loading-map.gif">
		  <span class="list-circle-active"></span>
		  <span class="list-circle-o"></span>
		  <span class="layer-item-name">'.$title.'</span>';

		  if ( (odm_language_manager()->get_current_language() !== "en") ){
			  $get_download_link = get_post_meta($post_ID, '_layer_download_link_localization', true);
			  $layer_profilepage_link = get_post_meta($post_ID, '_layer_profilepage_link_localization', true);
		  }else {
			  $get_download_link = get_post_meta($post_ID, '_layer_download_link', true);
			  $layer_profilepage_link = get_post_meta($post_ID, '_layer_profilepage_link', true);
		  }

		  if($get_download_link!=""){
				$ckan_dataset_id = wpckan_get_dataset_id_from_dataset_url($get_download_link);
				$layer_download_link = wpckan_get_link_to_dataset($ckan_dataset_id);
		   	$layer_items .= '
		      <a class="download-url" href="'.$layer_download_link.'" target="_blank"><i class="fa fa-arrow-down"></i></a>
		      <a class="toggle-info" alt="Info" href="#"><i id="'. $post_ID.'" class="fa fa-info-circle"></i></a>';
		  }else if($content!= ""){
		   $layer_items .= '
		      <a class="toggle-info" alt="Info" href="#"><i id="'. $post_ID.'" class="fa fa-info-circle"></i></a>';
		  }
		  if($layer_profilepage_link!=""){
		   $layer_items .= '
		      <a class="profilepage_link" href="'. $layer_profilepage_link.'" target="_blank"><i class="fa fa-table"></i></a>';
		  }
		$layer_items .= '</li>';

		if($echo == 1){
			echo $layer_items;
		}else {
			return $layer_items;
		}
	}

}

//Query all baselayers' post meta into an array
function query_get_layer_posts($term_id, $num = -1, $exclude_cats = null, $filter_arr = null, $layer_taxonomy=null){
	$layer_taxonomy = $layer_taxonomy? $layer_taxonomy : "layer-category";
	if($filter_arr){
		$filter_string = isset($filter_arr['filter_s'])? $filter_arr['filter_s'] : null;
		$filter_taxonomy = isset($filter_arr['filter_taxonomy'])? $filter_arr['filter_taxonomy'] : null;
		if($filter_taxonomy):
			$taxonomy_name = array_keys($filter_taxonomy);
			$selected_terms = $filter_taxonomy[$taxonomy_name[0]];
			$term_id = $selected_terms;
		endif;
	}

	if($exclude_cats){
		$tax_query = array(
	 										'relation' => 'AND',
	 										 array(
	 											 'taxonomy' => $layer_taxonomy,
	 											 'field' => 'id',
	 											 'terms' => $term_id,
	 											 'include_children' => false,
	 											 'operator' => 'IN'
	 										 ),
	 										 array(
	 											 'taxonomy' => $layer_taxonomy,
	 											 'field' => 'id',
	 											 'terms' => $exclude_cats,
	 											 'operator' => 'NOT IN'
	 											)
	 									 );
	}else {
		$tax_query = array(
										array(
										  'taxonomy' => $layer_taxonomy,
										  'field' => 'id',
										  'terms' => $term_id, // Where term_id of Term 1 is "1".
										  'include_children' => false
											)
						  		);
	}
	$args_layer = array(
		'post_type' => 'map-layer',
    's' => $filter_string,
		'orderby'   => 'name',
		'order'   => 'asc',
		'posts_per_page' => $num,
		'tax_query' => $tax_query
	);
	$layers = new WP_Query( $args_layer );
	return $layers;
}

function get_all_layers_grouped_by_subcategory( $term_id = 0, $exclude_cats ='', $filter_arr = null, $layer_taxonomy=null) {
	$layer_taxonomy = $layer_taxonomy? $layer_taxonomy : "layer-category";
	if(isset($_GET['filter_category']) && !empty($_GET['filter_category'])):
		$layer_taxonomy = "category";
	endif;

	if(array_filter($filter_arr)){
		$filter_taxonomy = isset($filter_arr['filter_taxonomy'])? $filter_arr['filter_taxonomy'] : null;
		if($filter_taxonomy):
			$taxonomy_name = array_keys($filter_taxonomy);
			$layer_taxonomy = $taxonomy_name[0];
			$selected_terms = $filter_taxonomy[$taxonomy_name[0]];
			$term_id = $selected_terms;
		endif;
	}

	if($term_id == 0){
		$layer_term_args= array(
			'parent' => $term_id,
			'orderby'   => 'name',
			'order'   => 'ASC',
			'exclude' => $exclude_cats
		);
		$terms_layer = get_terms($layer_taxonomy, $layer_term_args);
		if ($terms_layer) {
			foreach( $terms_layer as $term ):
				$query_layers[] = query_get_layer_posts_by_terms($term->term_id, false, $exclude_cats, $filter_arr, $layer_taxonomy);
		  endforeach;

			foreach($query_layers as $layers ) {
				foreach($layers as $key => $layer) {
					$layers_catalogue[$key] = $layer;
				}
			}

			$map_catalogue = array_filter($layers_catalogue);
			$tmp_arr = array();
			//Sort Map Catalogue by name
			if($map_catalogue):
				foreach ($map_catalogue as $key => $row):
					$tmp_arr[$key] = $row->post_title;
				endforeach;
			endif;
			array_multisort($tmp_arr, SORT_ASC, $map_catalogue);

			return $map_catalogue;
		}//if terms_layer
	}else{
		if(is_array($term_id)){
			foreach ($term_id as $key => $id):
				$map_catalogue = query_get_layer_posts_by_terms($id, false, $exclude_cats, $filter_arr, $layer_taxonomy);
			endforeach;
		}else {
				$map_catalogue = query_get_layer_posts_by_terms($term_id, false, $exclude_cats, $filter_arr, $layer_taxonomy);
		}

	}//if term_id
	return $map_catalogue;
}

function query_get_layer_posts_by_terms($term_id, $num = -1, $exclude_cats = null, $filter_arr = null, $layer_taxonomy=null){
	$layer_taxonomy = $layer_taxonomy?$layer_taxonomy:"layer-category";
	$layers_catalogue = array();
	$query_layer = query_get_layer_posts($term_id, false, $exclude_cats, $filter_arr, $layer_taxonomy);
	if($query_layer->have_posts() ){
		while ( $query_layer->have_posts() ) : $query_layer->the_post();
				if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
					$layers_catalogue[get_the_ID()] = get_layer_information_in_array(get_the_ID());
			}
		endwhile;
		wp_reset_postdata();
	}
	//Get Layers/posts grouped by subcategory of term id
	$children_term = get_terms($layer_taxonomy, array('parent' => $term_id, 'hide_empty' => 0, 'orderby' => 'name') );
	if(!empty($children_term)) {
		foreach($children_term as $child){
			$layers_catalogue[$child->term_id] = get_layers_of_sub_category( $child->term_id, $filter_arr);
			//check if the sub category has children
			$has_children = get_terms($layer_taxonomy, array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'name') );
			if ( !empty($has_children) ) {
				foreach($has_children as $sub_child){
					$layers_catalogue[$sub_child->term_id] = get_layers_of_sub_category( $sub_child->term_id, $filter_arr);
				}
			}
		}//foreach
	}//if empty($children_term) )
	return $layers_catalogue;
}

function get_sort_posts_by_post_title($array_layers){
	//Sort Map Catalogue by name
	$tmp_arr = array();
	foreach ($array_layers as $key => $row) {
			$tmp_arr[$key] = $row->post_title;
	}
	array_multisort($tmp_arr, SORT_ASC, $array_layers);
	//Array index 0 is added during sorting, and it is no value
	unset($array_layers[0]);

	return $array_layers;
}

function get_pagination_of_layers_grouped_by_subcategory($list){
	//Pagination
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$total_items= count($list);  //count number of records
	$posts_per_page = get_option('posts_per_page');
	$total_pages = ceil($total_items / $posts_per_page);
	$pagination["start_post"] = ($paged-1) * $posts_per_page + 1; //start from 1
	if ($total_items <= ($posts_per_page * $paged)):
		$pagination["end_post"]  = $total_items;
	else:
		$pagination["end_post"]  = $posts_per_page * $paged;
	endif;
	$pagination["paging_arg"] = array('current'=> $paged, 'total_pages'=> $total_pages);
	return $pagination;
}

function display_legend_container(){
	echo '<div class="box-shadow map-legend-container hide_show_container">';
	echo '<h2 class="widget_headline">'.__("LEGEND", "odm");
	echo '<i class="fa fa-caret-down hide_show_icon"></i>';
	echo '</h2>';
	echo '<hr class="color-line" />';
	echo '<div class="map-legend dropdown">';
	echo '<ul class="map-legend-ul"></ul>';
	echo '</div>';
	echo '</div>'; //map-legend-container
}

//show the toggle information container
function display_layer_information($layers){
?>
	<div class="box-shadow layer-toggle-info-container layer-right-screen">
	   <div class="toggle-close-icon"><i class="fa fa-times"></i></div>
	   <?php
	   foreach($layers as $individual_layer){
			$get_post_content_by_id = null;
		  $get_post_by_id = get_post($individual_layer["ID"]);
		  if ( (odm_language_manager()->get_current_language() !== "en") ){
				$get_download_url = get_post_meta($get_post_by_id->ID, '_layer_download_link_localization', true);
		  }else {
			 	$get_download_url = get_post_meta($get_post_by_id->ID, '_layer_download_link', true);
		  }

		  // get post content if has
			$get_post_content_by_id = apply_filters('translate_text', $get_post_by_id->post_content, odm_language_manager()->get_current_language());
			$check_post_content= trim(str_replace("&nbsp;", "", strip_tags($get_post_content_by_id)));
			if(!empty($check_post_content)){ ?>
					<div class="layer-toggle-info toggle-info-<?php echo $individual_layer['ID']; ?>">
						<div class="layer-toggle-info-content">
							<h4><?php echo get_the_title($individual_layer['ID']); ?></h4>
							<?php echo $get_post_content_by_id ?>
						</div>
					</div>
			<?php
			}
			elseif($get_download_url!="" ){
				  $showing_fields = array(
									  //  "title_translated" => "Title",
										"notes_translated" => "Description",
										"odm_source" => "Source(s)",
										"odm_date_created" => "Date of data",
										"odm_completeness" => "Completeness",
										"license_id" => "License"
									);

					$ckan_domain = wpckan_get_ckan_domain();
					$ckan_dataset_id = wpckan_get_dataset_id_from_dataset_url($get_download_url);
				  if($ckan_dataset_id!= ""):
					  wpckan_get_metadata_info_of_dataset_by_id($ckan_domain, $ckan_dataset_id, $get_post_by_id, 1,  $showing_fields);
				  endif;
			}
			?>
		<?php
	   }// foreach
	   ?>
	</div><!--llayer-toggle-info-containero-->
<?php
}

//get post meta of layer by id
function get_post_meta_of_layer($post_ID, $layer_option = false){
  $layer = array();
  if($layer_option == false){
	  $layer['filtering'] = 'switch';
	  $layer['hidden'] = 1;
  }
  if (function_exists('extended_jeo_get_layer')){
	  $layer = array_merge($layer, extended_jeo_get_layer($post_ID)); //added by H.E
  }else {
	  $layer = array_merge($layer, jeo_get_layer($post_ID));
  }
  return $layer;
}

//List all layers' value into an array by post ID
function get_selected_layers_of_map_by_mapID($map_ID) {
	$map_ID = $map_ID ? $map_ID : get_the_ID();
	if(set_default_map_baselayer($map_ID)):
		$layers[0] = set_default_map_baselayer($map_ID);
	endif;

	$get_layers = $GLOBALS['extended_jeo_layers']->get_map_layers($map_ID, "layer");

	if(!empty($get_layers)){
		foreach ($get_layers as $key => $layer) {
			$layer_ID = $layer['ID'];
			$layer_postmeta = get_post_meta_of_layer($layer_ID, true);
			if($layer['filtering']) :
				$layer_postmeta['filtering'] = $layer['filtering'];
			endif;
		  $layers[$layer_ID] = $layer_postmeta;
		}//foreach
	}
	return $layers;
}

//List all layers' value into an array by post ID
function get_selected_layers_of_map_by_layerID($layer_ID, $map_ID = null) {
	$layer_ID = $layer_ID ? $layer_ID : get_the_ID();

	if(set_default_map_baselayer($map_ID)):
		$layers[0] = set_default_map_baselayer($map_ID);
	endif;

	if(is_array($layer_ID) ){
			foreach ($layer_ID as $key => $lay_ID) {
				$layers[$lay_ID] = extended_jeo_get_layer($lay_ID);
				$layers[$lay_ID]['filtering'] = 'fixed';
			}
	}else {
		$layers[$layer_ID] = extended_jeo_get_layer($layer_ID);
		$layers[$layer_ID]['filtering'] = 'fixed';
	}

	return $layers;
}

//List all legends' value into an array by post ID
function get_legend_of_map_by($post_ID = false){
	if ($post_ID != ""){
		if( get_post_type( $post_ID ) == "profiles" ){
				$is_map = true;
		}else {
			$is_map = jeo_is_map($post_ID); //if postID is map post type
		}
	}else{
		$post_ID = get_the_ID();
	}
	$legends = null;
	if ($is_map){
		$map_layers = get_post_meta($post_ID, '_jeo_map_layers', true);
		if($map_layers){
			foreach ($map_layers as $key => $lay) {
			   $lay_ID =  $lay['ID'];
			   if ( (odm_language_manager()->get_current_language() !== "en") ){
				   $layer_legend = get_post_meta($lay_ID , '_layer_legend_localization', true);
			   }else {
				   $layer_legend = get_post_meta($lay_ID , '_layer_legend', true);
			   }

			   if($layer_legend!=""){
				   $legends[$lay_ID ] = '<div class="legend">'. $layer_legend.'</div>';
			   }
			}//foreach
		}
	}if(is_array($post_ID) ){
			foreach ($post_ID as $postID) {
				if ( (odm_language_manager()->get_current_language() !== "en") ){
					$layer_legend = get_post_meta($postID , '_layer_legend_localization', true);
				}else {
					$layer_legend = get_post_meta($postID , '_layer_legend', true);
				}

				if($layer_legend!=""){
					$legends[$postID ] = '<div class="legend">'. $layer_legend.'</div>';
				}
			}
	}else {
		if ( (odm_language_manager()->get_current_language() !== "en") ){
			$layer_legend = get_post_meta($post_ID , '_layer_legend_localization', true);
		}else {
			$layer_legend = get_post_meta($post_ID , '_layer_legend', true);
		}
		if($layer_legend!=""){
			$legends = '<div class="legend">'. $layer_legend.'</div>';
		}
	}//else
	return $legends;
} //function

function get_layer_information_in_array($post_ID){
	//link to WP dataset page by dataset ID
	if ( (odm_language_manager()->get_current_language() !== "en") ){
		$get_download_url = get_post_meta($post_ID, '_layer_download_link_localization', true);
	}else {
		$get_download_url = get_post_meta($post_ID, '_layer_download_link', true);
	}
	if($get_download_url){
	 	$ckan_dataset_id = wpckan_get_dataset_id_from_dataset_url($get_download_url);
	}

	//get category of post by post_id
	$layer_cat = wp_get_post_terms($post_ID, 'layer-category',  array("fields" => "all"));
	$dataset_link = null;
	if(isset($ckan_dataset_id)):
		 $dataset_link = wpckan_get_link_to_dataset($ckan_dataset_id);
	endif;
	$layer = (object) array("ID" => get_the_ID(),
								"post_title" => get_the_title(),
								"dataset_link" => $dataset_link,
								"category" => $layer_cat[0]->name,
								"parent" => $layer_cat[0]->parent
					);
	return $layer;
}

function get_layers_of_sub_category( $child_id, $filter_arr = null, $layer_taxonomy= "layer-category", $post_type = "map-layer") {
	$filter_string = isset($filter_arr['filter_s'])? $filter_arr['filter_s'] : null;

	if($post_type == "map-layer"){
		$args_get_post = array(
		    'post_type' => $post_type,
		    's' => $filter_string,
				'orderby'   => 'name',
		    'tax_query' => array(
		                        array(
		                          'taxonomy' => $layer_taxonomy,
		                          'field' => 'id',
		                          'terms' => $child_id, // Where term_id of Term 1 is "1".
		                          'include_children' => false
		                        )
		                      )
		);
		$child_term = get_term( $child_id, $layer_taxonomy );
		$query_get_post = new WP_Query( $args_get_post );
		$layer_id = null;
		if($query_get_post->have_posts() ){
			while ( $query_get_post->have_posts() ) : $query_get_post->the_post();
				if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
					$get_layer_info = get_layer_information_in_array(get_the_ID());
					//find all map layer post to get it ID. eg. layer name: All Natural protected area
					if(substr( strtolower(get_the_title()), 0, 4 ) === "all"):
						$layer_id = get_the_ID();
						break;
					endif;
				}
			endwhile;
			wp_reset_postdata();
			if(!empty($get_layer_info)):
				$layers_list_id = $layer_id? $layer_id : $get_layer_info->ID;

				$layers_list_array = (object) array("ID" => $get_layer_info->ID,
										"post_title" => $child_term->name,
										"title_and_link" => "<a class='item-title' target='_blank' href='". $get_layer_info->dataset_link."' 	title='".$child_term->name."'>".$child_term->name."</a>",
										"dataset_link" => $get_layer_info->dataset_link,
										//"description" => "<ul>" .$layers_list."</ul>",
										"category" => $child_term->name,
										"parent" => $child_term->parent
							);
				return $layers_list_array;
			endif;
		}//if have_posts
	}
}
/** END CATEGORY */

function priting_map_setting(){ ?>
	<div class="print-setting hide">
		<h2><?php _e("Create Map", "odm");?> <i class="fa fa-times-circle" aria-hidden="true"></i></h2>
		<form name="print-map" class="print-setting-form form-inline" action="javascript:void(0);">
			<div class="form-group inline">
				<label for="print-title"><?php _e("Title", "odm"); ?>: </label>
				<input type="text" value="" class="form-control" id="print-title" />
			</div>
			<div class="form-group inline">
				<label for="print-description"><?php _e("Description", "odm"); ?>: </label>
				<textarea value="" class="form-control" id="print-description"></textarea>
			</div>
			<div class="form-group">
				<input type="checkbox" class="form-control" id="print-basemap" checked="checked" value="1" />
				<label for="print-basemap"><?php _e("Basemap", "odm"); ?></label>
			</div>
			<div class="form-group">
				<input type="checkbox" class="form-control" id="print-layer" checked="checked" value="1" />
				<label for="print-layer"><?php _e("Map layers", "odm"); ?></label>
			</div>

			<div class="form-group">
				<input type="checkbox" class="form-control" id="print-north-direction" checked="checked" value="1" />
				<label for="print-north-direction"><?php _e("North Direction", "odm"); ?></label>
			</div>

			<div class="form-group">
				<input type="checkbox" class="form-control" id="print-tools" value="1" />
				<label for="print-tools"><?php _e("Map tools", "odm"); ?></label>
			</div>



			<div class="form-group"><label for="print-legend"><?php _e("Legend", "odm"); ?>:</label>
				<select name="print-legend" id="print-legend" class="form-control">
					<option value="left"><?php _e("Left", "odm"); ?></option>
					<option value="right"><?php _e("Right", "odm"); ?></option>
				</select>
			</div>

			<div class="form-group"><label for="print-file-format"><?php _e("Format", "odm"); ?>:</label>
				<select name="print-file-format" id="print-file-format" class="form-control">
					<option value="image"><?php _e("Image (JPG)", "odm"); ?></option>
				</select>
			</div>

			<div class="form-group"><label for="print-layout"><?php _e("Layout", "odm"); ?>:</label>
				<select name="print-layout" id="print-layout"  class="form-control">
					<option value="landscape"><?php _e("Landscape", "odm"); ?></option>
					<option value="portrait"><?php _e("Portrait", "odm"); ?></option>
				</select>
			</div>

			<div class="form-group"><label for="print-paper-size"><?php _e("Size", "odm"); ?>:</label>
				<select name="print-paper-size" id="print-paper-size" class="form-control">
					<option value="A4"><?php _e("A4", "odm"); ?></option>
				</select>
			</div>
			<!--
			<div class="form-group"><label for="print-scale"><?php _e("Scale", "odm"); ?>:</label>
				<select name="print-scale" id="print-scale" class="form-control">
					<option value="1:20,000,000"><?php _e("1:20,000,000", "odm"); ?></option>
					<option value="1:10,000,000"><?php _e("1:10,000,000", "odm"); ?></option>
				</select>
			</div>--->

			<!--
			<div class="form-group"><label for="print-dpi"><?php _e("DPI", "odm"); ?>:</label>
				<select name="print-dpi" id="print-dpi" class="form-control">
					<option value="96"><?php _e("96", "odm"); ?></option>
					<option value="150"><?php _e("150", "odm"); ?></option>
					<option value="300"><?php _e("300", "odm"); ?></option>
				</select>
			</div>--->

			<div class="form-group inline">
				<input type="button" class="form-control" id="print-button" onclick="" value="<?php _e('Print', 'odm'); ?>" />
				<img class="print-loading" src="<?php echo get_stylesheet_directory_uri() ?>/img/loading-black-bg.gif">
			</div>
		</form>

    <div id="divtest"></div>
	</div>
	<?php //window.print();
}

function priting_map_footnote(){ ?>
	<div class="priting_footer">
		<p class="printing-description"></p>
    <span id="icon-od-logo">
			<svg class="svg-od-logo <?php echo odm_country_manager()->get_current_country(); ?>-logo"><use xlink:href="#icon-od-logo"></use></svg>
		</span>
		<span>
			<?php bloginfo(); ?> | <?php the_permalink(); ?>
		</span>
		<span class="printing_date">
			<?php
			_e("Created date: ", "odm");
			if (odm_language_manager()->get_current_language() == 'km') {
					echo convert_date_to_kh_date(date('j.M.Y'));
			} else {
					echo date('j M Y');
			}
			?>
		</span>
	</div>
	<?php
}
?>
