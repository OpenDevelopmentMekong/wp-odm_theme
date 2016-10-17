<?php get_header(); ?>
<?php require_once (STYLESHEETPATH ."/inc/utils/mapping.php"); ?>
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

	 //Show Menu Layers and legendbox
	display_map_layer_sidebar_and_legend_box($layers);

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

<section class="container">

	<div class="row">
		<?php while (have_posts()) : the_post();
			odm_get_template('post-grid-single-4-cols',array(
				"post" => get_post(),
				"show_meta" => true
		),true);
		endwhile; ?>
	</div>
</section>

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
