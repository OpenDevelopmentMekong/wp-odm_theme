<?php get_header(); ?>

<?php jeo_map(); ?>

<?php 
/* $get_post_type = jeo_get_mapped_post_types();
unset($get_post_type['rssmi_feed']);
unset($get_post_type['rssmi_feed_item']);
unset($get_post_type['site-update']); */
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
		<div class="section-title">
			<div class="container">
				<div class="twelve columns">
					<h2><?php _e('Latest articles on', 'jeo'); ?> <?php the_title(); ?></H2>
				</div>
			</div>
		</div>
		<?php get_template_part('loop'); ?>
<?php
endif;
wp_reset_query();
?>

<?php get_footer(); ?>