<?php if(is_home() && !is_paged()) : ?>
	<div class="map-sidebar">
		<div class="viewing-post">
		</div>
		<?php
		if(is_home() && !is_paged())
			get_template_part('section', 'sticky-posts');
		?>
	</div>
<?php endif; ?>
<div class="map-container">
	<div id="map_<?php echo jeo_get_map_id(); ?>" class="map"></div>
	<?php if(is_single()) : ?>
		<?php if(jeo_has_marker_location()) : ?>
			<div class="highlight-point transition has-end" data-end="1300"></div>
		<?php endif; ?>
	<?php endif; ?>
	<?php do_action('jeo_map'); ?>
</div>
<?php
	$map_conf = jeo_get_map_conf();
	if(get_post_type()=="news-article"):
			$map_conf['news_markers'] = true; //show if true
	else:
			$map_conf['news_markers'] = false; //show if true
	endif;
?>
<script type="text/javascript">
jeo(<?php echo json_encode($map_conf); ?>);
</script>
