<?php get_header(); ?>
<?php require_once (STYLESHEETPATH ."/inc/mapping.php"); ?>
<div class="interactive-map">
	<?php jeo_map(); ?>
	<?php
	//Get all layers of map by map_id
	if(isset($GET['map_id']) && ($GET['map_id'] !="")){
		$mapID =  $GET['map_id'];
	}else {
		$mapID =  get_the_ID();
	}
	display_baselayer_navigation();
	$layers = get_selected_layers_of_map_by_mapID($mapID);
	if (!empty($layers)){
		echo '<div class="category-map-layers box-shadow hide_show_container">';
			echo '<h2 class="sidebar_header map_headline widget_headline">'.__("Map Layers", "opendev");
				echo "<i class='fa fa-caret-down hide_show_icon'></i>";
			echo '</h2>';
			echo '<div class="interactive-map-layers dropdown">';
				echo "<ul class='cat-layers switch-layers'>";
					foreach ($layers as $id => $layer) {
						display_layer_as_menu_item_on_mapNavigation($layer['ID']);
					}
				echo "</ul>";
				echo '<div class="news-marker">';
				echo '<label><input class="news-marker-toggle" type="checkbox" value="1"/>';
				 	echo '<span class="label">'.__("Hide news icons", "opendev")."</span>";
				echo '</label>';
				echo '</div>';
			echo '</div>'; //interactive-map-layers dropdown
		echo '</div>'; //category-map-layers  box-shadow

		//show legend box
		display_legend_container();

		//show layer information
    display_layer_information($layers);
	}

	$base_layers = get_post_meta_of_all_baselayer();
	$layers_legend = get_legend_of_map_by($mapID);
	?>
</div>
<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
query_posts(array(
	'post_type' => jeo_get_mapped_post_types(),
	'paged' => $paged,
	'post_status' => 'publish',
	'meta_query' => array(
        'relation' => 'AND',
        array(
            'key'     => 'has_maps',
            'value'   => 1 ),
        array('
            key'      => 'maps',
            'value'   => get_the_ID()
        )
    ),
	's' => isset($_GET['s']) ? $_GET['s'] : null
));
if(have_posts()) :
?>

<section class="container">
	<header class="row">
		<div class="eight columns">
			<h2><?php _e('Latest articles on', 'jeo'); ?> <?php the_title(); ?></H2>
		</div>
		<div class="eight columns">
			<?php get_template_part('section', 'query-actions'); ?>
		</div>
	</header>
</section>

	<?php get_template_part('loop'); ?>
<?php
endif;
wp_reset_query();
?>
<script type="text/javascript">
	var all_baselayer_value = <?php echo json_encode($base_layers) ?>;
	var all_layers_value = <?php echo json_encode($layers) ?>;
	var all_layers_legends = <?php echo json_encode($layers_legend) ?>;
</script>

<?php get_footer(); ?>
