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
	<!-- <span class="query-actions-title"><?php _e('Content API', 'odm'); ?></span> -->
	<a class="button rss" target="_blank" href="<?php echo $rss; ?>">
		<i class="fa fa-rss"></i>
		<?php _e('RSS Feed', 'odm'); ?>
	</a>
	<?php if($use_api && (!is_post_type_archive( 'map-layer' ))  ) : ?> 
		<a class="button geojson" target="_blank" href="<?php echo esc_url($geojson); ?>">
			<i class="fa fa-globe"></i>
			<?php _e('Get GeoJSON', 'odm'); ?>
		</a>
		<a class="button download" target="_blank" href="<?php echo esc_url($download); ?>">
			<i class="fa fa-download"></i>
			<?php _e('Download', 'odm'); ?>
		</a>
	<?php endif; ?>
</div>
