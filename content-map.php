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
<script type="text/javascript">jeo(<?php echo jeo_map_conf(); ?>);</script>