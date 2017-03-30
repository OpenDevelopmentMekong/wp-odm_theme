<?php
$mapgroup = jeo_get_mapgroup_data();
$main_maps = $more_maps = array();
// separate main maps from "more" maps
foreach($mapgroup['maps'] as $map) {
	if(!isset($map['more']))
		$main_maps[] = $map;
	else
		$more_maps[] = $map;
}
?>
<div class="mapgroup-container">
	<div id="mapgroup_<?php echo jeo_get_the_ID(); ?>" class="mapgroup">
		<div class="map-nav-container">
			<ul class="map-nav">
				<?php
				foreach($main_maps as $map) :
					$post = get_post($map['id']);
					setup_postdata($post);
					?>
					<li><a href="<?php the_permalink(); ?>" data-map="<?php the_ID(); ?>"><?php the_title(); ?></a></li>
					<?php
					wp_reset_postdata();
				endforeach; ?>
				<?php if($more_maps) : ?>
					<li class="more-tab">
						<a href="#" class="toggle-more"><?php _e('More...', 'odi'); ?></a>
						<ul class="more-maps-list">
							<?php foreach($more_maps as $map) :
								$post = get_post($map['id']);
								setup_postdata($post);
								?>
								<li class="more-item"><a href="<?php the_permalink(); ?>" data-map="<?php the_ID(); ?>"><?php the_title(); ?></a></li>
								<?php
								wp_reset_postdata();
							endforeach; ?>
							<li><a href="<?php echo qtrans_convertURL(get_post_type_archive_link('map')); ?>"><?php _e('View all maps', 'odi'); ?></a></li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
		</div>
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
			<div id="mapgroup_<?php echo jeo_get_the_ID(); ?>_map" class="map">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var group = jeo.group(<?php echo jeo_mapgroup_conf(); ?>);
</script>
