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
function display_layer_as_menu_item_on_mapNavigation($post_ID){
	$get_post = get_post($post_ID);
	if (function_exists( qtrans_use)){
		$title = qtrans_use(odm_language_manager()->get_current_language(), $get_post->post_title,false);//get TITLE by langauge
		$content = qtrans_use(odm_language_manager()->get_current_language(), $get_post->post_content,false);//get CONTENT by langauge
	}else {
		$title = $get_post->post_title;
		$content = $get_post->post_content;
	}
	?>
	<li class="layer-item" data-layer="<?php echo $post_ID; ?>" id="post-<?php echo $post_ID; ?>">
		<img class="list-loading" src="<?php echo get_stylesheet_directory_uri() ?>/img/loading-map.gif">
		<span class="list-circle-active"></span>
		<span class="list-circle-o"></span>
		<span class="layer-item-name"><?php echo $title; ?></span>
		<?php
		if ( (odm_language_manager()->get_current_language() != "en") ){
			$layer_download_link = get_post_meta($post_ID, '_layer_download_link_localization', true);
			$layer_profilepage_link = get_post_meta($post_ID, '_layer_profilepage_link_localization', true);
			}else {
			$layer_download_link = get_post_meta($post_ID, '_layer_download_link', true);
			$layer_profilepage_link = get_post_meta($post_ID, '_layer_profilepage_link', true);
		}

		if($layer_download_link!=""){ ?>
			<a class="download-url" href="<?php echo $layer_download_link; ?>" target="_blank"><i class="fa fa-arrow-down"></i></a>
			<a class="toggle-info" alt="Info" href="#"><i id="<?php echo $post_ID; ?>" class="fa fa-info-circle"></i></a>
			<?php
		}else if($content!= ""){ ?>
			<a class="toggle-info" alt="Info" href="#"><i id="<?php echo $post_ID; ?>" class="fa fa-info-circle"></i></a>
			<?php
		}
		if($layer_profilepage_link!=""){ ?>
			<a class="profilepage_link" href="<?php echo $layer_profilepage_link; ?>" target="_blank"><i class="fa fa-table"></i></a>
		<?php } ?>
	</li>
  <?php // use reset postdata to restore orginal query
  wp_reset_postdata();

}

//Query all baselayers' post meta into an array
function query_get_layer_posts($term_id, $num=-1, $include_children=false){
	$args_layer = array(
		'post_type' => 'map-layer',
		'orderby'   => 'name',
		'order'   => 'asc',
		'posts_per_page' => $num,
		'tax_query' => array(
							array(
							  'taxonomy' => 'layer-category',
							  'field' => 'id',
							  'terms' => $term_id, // Where term_id of Term 1 is "1".
							  'include_children' => false
							)
						  )
	);
	$layers = new WP_Query( $args_layer );
	return $layers;
}

function display_legend_container(){
	echo '<div class="box-shadow map-legend-container hide_show_container">';
	echo '<h2 class="widget_headline">'.__("LEGEND", "opendev");
	echo '<i class="fa fa-caret-down hide_show_icon"></i>';
	echo '</h2>';
	echo '<div class="map-legend dropdown">';
	echo '<hr class="color-line" />';
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
		  if (function_exists( qtrans_use)){
			$get_post_content_by_id = qtrans_use(odm_language_manager()->get_current_language(), $get_post_by_id->post_content,false);
		  }else{
			$get_post_content_by_id = $get_post_by_id->post_content;
		  }
			if($get_download_url!="" ){
				  $ckan_dataset_id_exploded_by_dataset = explode("/dataset/", $get_download_url);
				  $ckan_dataset_id = $ckan_dataset_id_exploded_by_dataset[1];
				  $ckan_domain = $ckan_dataset_id_exploded_by_dataset[0];
				  // get ckan record by id
				  $get_info_from_ckan = wpckan_get_dataset_by_id($ckan_domain,$ckan_dataset_id);
				  $showing_fields = array(
									  //  "title_translated" => "Title",
										"notes_translated" => "Description",
										"odm_source" => "Source(s)",
										"odm_date_created" => "Date of data",
										"odm_completeness" => "Completeness",
										"license_id" => "License"
									);
				  if($ckan_dataset_id!= ""):
					  wpckan_get_metadata_info_of_dataset_by_id(CKAN_DOMAIN, $ckan_dataset_id, $get_post_by_id, 1,  $showing_fields);
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
  if (function_exists(extended_jeo_get_layer)){
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
?>
