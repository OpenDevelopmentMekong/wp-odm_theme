<?php get_header(); ?>

<div class="section-title">
	<div class="container">
		<div class="twelve columns">
			<h1><?php _e('Search results', 'jeo'); ?></h1>
			<div class="more-filters-content">
				<?php opendev_adv_nav_filters(); ?>
			</div>
			<div class="clear"></div>
			<div class="query-actions">
				<?php
				$jeo_options = jeo_get_options();
				$use_api = isset($jeo_options['api']) && $jeo_options['api']['enable'] ? true : false;
				global $wp_query;
				$args = $wp_query->query;
				$args = array_merge($args, $_GET);
				$geojson = jeo_get_api_url($args);
				$download = jeo_get_api_download_url($args);
				$rss = add_query_arg(array('feed' => 'rss'));
				?>
				<a class="rss" href="<?php echo $rss; ?>"><?php _e('RSS Feed', 'infoamazonia'); ?></a>
				<?php if($use_api) : ?>
					<a class="geojson" href="<?php echo $geojson; ?>"><?php _e('Get GeoJSON', 'infoamazonia'); ?></a>
					<a class="download" href="<?php echo $download; ?>"><?php _e('Download', 'infoamazonia'); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('loop'); ?>

<?php get_footer(); ?>