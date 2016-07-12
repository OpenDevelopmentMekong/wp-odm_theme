<?php
$maps_query = new WP_Query(array(
	'post_type' => 'map',
	'posts_per_page' => -1
));

$maps_data = array(
	'groupID' => 'interactive_map',
	'maps' => array(),
	'disableHash' => true
);

if($maps_query->have_posts()) {
	while($maps_query->have_posts()) {
		$maps_query->the_post();
		$maps_data['maps'][$post->ID] = jeo_get_map_data($post->ID);
		wp_reset_postdata();
	}
}

$categorized_maps = array();

foreach($maps_data['maps'] as $map) {
	foreach($map['categories'] as $category) {
		if(!$categorized_maps[$category->term_id])
			$categorized_maps[$category->term_id] = array('term' => $category, 'maps' => array());

		$categorized_maps[$category->term_id]['maps'][] = $map;
	}
}

?>
<div class="interactive-map-container">
	<div id="interactive-map_<?php echo $maps_data['groupID']; ?>" class="interactive-map">
		<div class="map-nav-container">
			<ul class="map-nav">
				<?php
				foreach($categorized_maps as $category) {
					?>
					<li>
						<?php echo $category['term']->name; ?>
						<ul>
							<?php foreach($category['maps'] as $map) {
								$post = get_post($map['postID']);
								setup_postdata($post);
								?>
								<li><a href="<?php the_permalink(); ?>" data-map="<?php the_ID(); ?>"><?php the_title(); ?></a></li>
								<?php
								wp_reset_postdata();
							} ?>
						</ul>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="map-container">
			<div id="interactive-map_<?php echo $maps_data['postID']; ?>_map" class="map">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var group = interactiveMap(<?php echo json_encode($maps_data); ?>);
</script>
