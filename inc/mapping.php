<?php
// get baselayer Navigation
function display_baselayer_navigation($num=5, $cat='base-layers', $include_children=false ){
	$base_layer_posts = query_get_baselayer_posts();
	if($base_layer_posts){
		echo '<div class="baselayer-container box-shadow">';
		echo '<ul class="baselayer-ul">';
		foreach ( $base_layer_posts as $baselayer ) :
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
							  'include_children' => $include_children
							)
						  )
						);
	$baselayer_posts = get_posts( $args_base_layer );
	return $baselayer_posts;
}

//Query all baselayers' post meta into an array
function get_post_meta_of_all_baselayer($num=5, $cat='base-layers', $include_children=false){
	$base_layer_posts = query_get_baselayer_posts();
	if($base_layer_posts){
		foreach ( $base_layer_posts as $baselayer ) :
			$base_layers[$baselayer->ID] = get_post_meta_of_layer($baselayer->ID);
		endforeach;
	}
	return $base_layers;
}
//************//
//display layer menus fixed on right bar
function display_layer_as_menu_item_on_mapNavigation($post_ID, $echo =1){
	$get_post = get_post($post_ID);
	if (function_exists( qtrans_use)){
		$title = qtrans_use(odm_language_manager()->get_current_language(), $get_post->post_title,false);//get TITLE by langauge
		$content = qtrans_use(odm_language_manager()->get_current_language(), $get_post->post_content,false);//get CONTENT by langauge
	}else {
		$title = $get_post->post_title;
		$content = $get_post->post_content;
	}
	$layer_items = '<li class="layer-item" data-layer="'.$post_ID.'" id="post-'.$post_ID.'">
	  <img class="list-loading" src="'. get_stylesheet_directory_uri(). '/img/loading-map.gif">
	  <span class="list-circle-active"></span>
	  <span class="list-circle-o"></span>
	  <span class="layer-item-name">'.$title.'</span>';

	  if ( (odm_language_manager()->get_current_language() != "en") ){
	  $layer_download_link = get_post_meta($post_ID, '_layer_download_link_localization', true);
	  $layer_profilepage_link = get_post_meta($post_ID, '_layer_profilepage_link_localization', true);
	  }else {
	  $layer_download_link = get_post_meta($post_ID, '_layer_download_link', true);
	  $layer_profilepage_link = get_post_meta($post_ID, '_layer_profilepage_link', true);
	  }

	  if($layer_download_link!=""){
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

//Query all baselayers' post meta into an array
function query_get_layer_posts($term_id, $num=-1, $include_children =false,
$exclude_cats =""){
	$layer_taxonomy = "layer-category";
	if($exclude_cats != ""){
		$tax_query = array(
	 										'relation' => 'AND',
	 										 array(
	 											 'taxonomy' => $layer_taxonomy,
	 											 'field' => 'id',
	 											 'terms' => $term->term_id,
	 											 'include_children' => false,
	 											 'operator' => 'IN'
	 										 ),
	 										 array(
	 											 'taxonomy' => $layer_taxonomy,
	 											 'field' => 'id',
	 											 'terms' => $exclude_posts_in_cats,
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
		'orderby'   => 'name',
		'order'   => 'asc',
		'posts_per_page' => $num,
		'tax_query' => $tax_query
	);
	$layers = new WP_Query( $args_layer );
	return $layers;
}

function get_all_layers_grouped_by_subcategory( $term_id = 0, $exclude_cats ='', 			$layer_taxonomy='layer-category'){
	//List cetegory and layer by cat for menu items
		$layer_term_args= array(
			'parent' => 0,
			'orderby'   => 'name',
			'order'   => 'ASC',
			'exclude' => $exclude_cats
		);
		//$query_layer = query_get_layer_posts($term_id, false, $exclude_cats);
		$terms_layer = get_terms($layer_taxonomy, $layer_term_args);
		if ($terms_layer) {
			foreach( $terms_layer as $term ) {
				$query_layer = query_get_layer_posts($term->term_id, false, $exclude_cats);
				if($query_layer->have_posts() ){
					while ( $query_layer->have_posts() ) : $query_layer->the_post();
							if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
									$layers_catalogue[get_the_ID()] = get_layer_information_in_array(get_the_ID());
						}
					endwhile;
					wp_reset_postdata();
				} //$query_layer->have_posts

				//Get Layers of subcategory
				$children_term = get_terms($layer_taxonomy, array('parent' => $term->term_id, 'hide_empty' => 0, 'orderby' => 'name') );
				if ( !empty($children_term) ) {
					foreach($children_term as $child){
						$layers_catalogue[$child->term_id] = get_layers_of_sub_category( $child->term_id);

						//check if the sub category has children
						$has_children = get_terms($layer_taxonomy, array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'name') );
						if ( !empty($has_children) ) {
							foreach($has_children as $sub_child){
								$layers_catalogue[$sub_child->term_id] = get_layers_of_sub_category( $sub_child->term_id);
							}
						}
					}//foreach
				}//if empty($children_term) )
			}//foreach $terms_layer
			//Sort Map Catalogue by name
			$map_catalogue = $layers_catalogue;

			$tmp_arr = array();
			foreach ($map_catalogue as $key => $row) {
					$tmp_arr[$key] = $row->post_title;
			}
			array_multisort($tmp_arr, SORT_ASC, $map_catalogue);
			//unset($map_catalogue[0]);

			return $map_catalogue;
		}//if terms_layer
	return;
}

function get_all_layers_by_term_id_grouped_by_subcategory( $term_id = 0, $exclude_cats =""){
	//Get layers of posts of term_id
	$query_layer = query_get_layer_posts($term_id, false, $exclude_cats);
	if($query_layer->have_posts() ){
		while ( $query_layer->have_posts() ) : $query_layer->the_post();
				if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
					$layers_catalogue[get_the_ID()] = get_layer_information_in_array(get_the_ID());
			}
		endwhile;
		wp_reset_postdata();
	}
	//Get Layers/posts grouped by subcategory of term id
	$children_term = get_terms("layer-category", array('parent' => $term_id, 'hide_empty' => 0, 'orderby' => 'name') );
	if(!empty($children_term)) {
		foreach($children_term as $child){
			$layers_catalogue[$child->term_id] = get_layers_of_sub_category( $child->term_id);
			//check if the sub category has children
			$has_children = get_terms("layer-category", array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'name') );
			if ( !empty($has_children) ) {
				foreach($has_children as $sub_child){
					$layers_catalogue[$sub_child->term_id] = get_layers_of_sub_category( $sub_child->term_id);
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
	echo '<h2 class="widget_headline">'.__("LEGEND", "opendev");
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
		  $get_post_by_id = get_post($individual_layer["ID"]);
		  if ( (odm_language_manager()->get_current_language() != "en") ){
			 $get_download_url = str_replace("?type=dataset", "", get_post_meta($get_post_by_id->ID, '_layer_download_link_localization', true));
		  }else {
			 $get_download_url = str_replace("?type=dataset", "", get_post_meta($get_post_by_id->ID, '_layer_download_link', true));
		  }

		  // get post content if has
		  if (function_exists('qtrans_use')){
				$get_post_content_by_id = qtrans_use(odm_language_manager()->get_current_language(), $get_post_by_id->post_content,false);
		  }else{
				$get_post_content_by_id = $get_post_by_id->post_content;
		  }
			if($get_download_url!="" ){
				  $ckan_dataset_id_exploded_by_dataset = explode("/dataset/", $get_download_url);
				  $ckan_dataset_id = $ckan_dataset_id_exploded_by_dataset[1];
				  $ckan_domain = $ckan_dataset_id_exploded_by_dataset[0];
				  // get ckan record by id
				  //$get_info_from_ckan = wpckan_api_package_show($ckan_domain,$ckan_dataset_id);
				  $showing_fields = array(
									  //  "title_translated" => "Title",
										"notes_translated" => "Description",
										"odm_source" => "Source(s)",
										"odm_date_created" => "Date of data",
										"odm_completeness" => "Completeness",
										"license_id" => "License"
									);
				  if($ckan_dataset_id!= ""):
					  wpckan_get_metadata_info_of_dataset_by_id($ckan_domain, $ckan_dataset_id, $get_post_by_id, 1,  $showing_fields);
				  endif;
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
	if ($map_ID == "" ){
		$map_ID = get_the_ID();
	}
	$get_layers = $GLOBALS['jeo_layers']->get_map_layers($map_ID); //called from parent theme

	if(!empty($get_layers)){
		foreach ($get_layers as $key => $layer) {
			$layer_ID = $layer['ID'];
			$layer_postmeta = get_post_meta_of_layer($layer_ID, true);
			$layer_postmeta['filtering'] = $layer['filtering'];
			$layer_postmeta['hidden'] = $layer['hidden']==""? 1: $layer['hidden'];
			$layer_postmeta['first_swap'] = $layer['first_swap'];
 		    $layers[$layer_ID] = $layer_postmeta;
		}//foreach
	}
	return $layers;
}

//List all legends' value into an array by post ID
function get_legend_of_map_by($post_ID = false){
	if ($post_ID != ""){
		$is_map = jeo_is_map($post_ID); //if postID is map post type
	}else{
		$post_ID = get_the_ID();
	}
	$legends = null;
	if ($is_map){
		$map_layers = get_post_meta($post_ID, '_jeo_map_layers', true);
		foreach ($map_layers as $key => $lay) {
		   $post_ID =  $lay['ID'];
		   if ( (odm_language_manager()->get_current_language() != "en") ){
			   $layer_legend = get_post_meta($post_ID , '_layer_legend_localization', true);
		   }else {
			   $layer_legend = get_post_meta($post_ID , '_layer_legend', true);
		   }

		   if($layer_legend!=""){
			   $legends[$post_ID ] = '<div class="legend">'. $layer_legend.'</div>';
		   }
		}//foreach
	}else {
		if ( (odm_language_manager()->get_current_language() != "en") ){
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

function opendev_get_interactive_map_data()
{
    $options = get_option('opendev_options');
    if ($options['map_data']) {
        return $options['map_data'];
    } else {
        return false;
    }
}


function get_layer_information_in_array($post_ID){
	//link to WP dataset page by dataset ID
	if ( (odm_language_manager()->get_current_language() != "en") ){
		$get_ckan_dataset_id = explode("/dataset/", str_replace("?type=dataset", "", get_post_meta($post_ID, '_layer_download_link_localization', true)));
	}else {
		$get_ckan_dataset_id = explode("/dataset/", str_replace("?type=dataset", "", get_post_meta($post_ID, '_layer_download_link', true)) );
	}
	/*
	if(function_exists("wpckan_api_package_show")):
		$dataset = wpckan_api_package_show(wpckan_get_ckan_domain(),$get_ckan_dataset_id[1]);
		if (is_array($dataset['resources'])) {
				foreach ($dataset['resources'] as $resource) {
						if($resource['format'] == "JPEG" || $resource['format'] == "JPG" || $resource['format'] == "PNG"):
							if($resource['odm_language'][0] == odm_language_manager()->get_current_language()){
								$thumbnail_url = $resource['url'];
							}
						endif;
					}
			}
	endif;
	*/

	$title_and_link = "<a class='item-title' target='_blank' href='".get_site_url()."/dataset/?id=".$get_ckan_dataset_id[1]."'>". get_the_title() . "</a>";
	$download_link = get_site_url()."/dataset/?id=".$get_ckan_dataset_id[1];
	//get category of post by post_id
	$layer_cat = wp_get_post_terms($post_ID, 'layer-category',  array("fields" => "all"));
	$layer = (object) array("ID" => get_the_ID(),
								"post_title" => get_the_title(),
								"download_link" => $download_link,
								"title_and_link" => $title_and_link,
								//"thumbnail_link" => $thumbnail_url,
								//"description" => get_the_content(),
								"category" => $layer_cat[0]->name,
								"parent" => $layer_cat[0]->parent
					);
	return $layer;
}

function get_layers_of_sub_category( $child_id, $layer_taxonomy= "layer-category", $post_type = "map-layer") {
	if($post_type == "map-layer"){
		$args_get_post = array(
		    'post_type' => $post_type,
				'orderby'   => 'name',
				//'order'   => 'asc',
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
		if($query_get_post->have_posts() ){
			$layers_list = "";
			while ( $query_get_post->have_posts() ) : $query_get_post->the_post();
				if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
					$get_layer_info = get_layer_information_in_array(get_the_ID());
					$permalink = $get_layer_info->download_link;
				  $layers_list .= "<li>".$get_layer_info->title_and_link."</li>";
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
										"title_and_link" => "<a class='item-title' target='_blank' href='". $permalink."' 	title='".$child_term->name."'>".$child_term->name."</a>",
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

?>
