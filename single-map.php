<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
	<section id="map" class="section-title">
		<?php
			if(isset($GET['map_id']) && ($GET['map_id'] !="")){
				$mapID =  $GET['map_id'];
			}else {
				$mapID =  get_the_ID();
			}

			$layerID = get_embedded_layer_id();
			?>
			<?php
			if(function_exists("display_embedded_map")){
				display_embedded_map($mapID, $layerID);
			}
			?>
	</section>

<?php endif; ?>
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
			<h2><?php _e('Latest articles on', 'odi'); ?> <?php the_title(); ?></H2>
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
<?php get_footer(); ?>
