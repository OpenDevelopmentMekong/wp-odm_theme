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
	$rss = str_replace("news-archive/", "", $rss);
	?>
	<span class="query-actions-title"><?php _e('Content API', 'odm'); ?></span>
	<a class="rss" target="_blank" href="<?php echo $rss; ?>">
		<span class="icon-rss"></span>
		<?php _e('RSS Feed', 'infoamazonia'); ?>
	</a>
	<?php if($use_api) : ?>
		<a class="geojson" target="_blank" href="<?php echo $geojson; ?>">
			<span class="icon-hair-cross"></span>
			<?php _e('Get GeoJSON', 'infoamazonia'); ?>
		</a>
		<a class="download" target="_blank" href="<?php echo $download; ?>">
			<span class="icon-download"></span>
			<?php _e('Download', 'infoamazonia'); ?>
		</a>
	<?php endif; ?>
</div>
