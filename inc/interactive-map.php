<?php

/*
 * Open Development
 * Interactive Map
 */

class OpenDev_InteractiveMap {

	function __construct() {

		add_shortcode('odmap', array($this, 'shortcode'));

	}

	function shortcode() {

		$layer_query = new WP_Query(array(
			'post_type' => 'map-layer',
			'posts_per_page' -1
		));

		$layers = array();

		$categories = get_terms('layer-category');

		$parsed_cats = array();

		if($layer_query->have_posts()) {
			while($layer_query->have_posts()) {
				$layer_query->the_post();
				$layer = array();
				$layer['filtering'] = 'switch';
				$layer['hidden'] = 1;
				foreach($categories as $cat) {
					if(is_object_in_term(get_the_ID(), 'layer-category', $cat->term_id)) {
						if(!isset($parsed_cats[$cat->term_id]))
							$parsed_cats[$cat->term_id] = array();
						$parsed_cats[$cat->term_id][] = get_the_ID();
					}
				}
				$layer = array_merge($layer, jeo_get_layer(get_the_ID()));
				$layers[] = $layer;
				wp_reset_postdata();
			}
		}

		$map = opendev_get_interactive_map_data();
		$map['dataReady'] = true;
		$map['postID'] = 'interactive_map';
		$map['layers'] = $layers;
		$map['count'] = 0;
		$map['title'] = __('Interactive Map', 'opendev');
		if($map['base_layer']) {
			array_unshift($map['layers'], array(
				'type' => 'tilelayer',
				'tile_url' => $map['base_layer']['url']
			));
		}

		// print_r($map);

		ob_start();
		?>
		<div class="interactive-map">
			<div class="map-container">
				<div id="map_interactive_map_0" class="map"></div>
			</div>
			<div class="interactive-map-layers">
				<ul class="categories">
					<?php wp_list_categories(array('taxonomy' => 'layer-category', 'title_li' => '')); ?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
			(function($) {

				var term_rel = <?php echo json_encode($parsed_cats); ?>;

				jeo(jeo.parseConf(<?php echo json_encode($map); ?>));

				jeo.mapReady(function(map) {
					var $layers = $('.interactive-map .interactive-map-layers');
					if(map.postID == 'interactive_map') {
						//map.$.find('.jeo-filter-layers').appendTo($layers);
						for(var key in term_rel) {
							var $item = $layers.find('.cat-item-' + key);
							$item.find(' > a').after($('<ul class="cat-layers switch-layers" />'));
							$.each(term_rel[key], function(i, layerId) {
								var $layer = map.$.find('[data-layer="' + layerId + '"]');
								$layer.appendTo($item.find('.cat-layers'));
							});
						}
						$('.jeo-filter-layers').hide();
					}

					$layers.find('.categories ul').hide();

					$layers.find('.categories li a').on('click', function() {
						if($(this).hasClass('active')) {
							$(this).removeClass('active');
							$(this).parent().find('ul').hide();
						} else {
							$(this).addClass('active');
							$(this).parent().find('> ul').show();
						}
						return false;
					});

					$layers.find('.cat-layers li').on('click', function() {
						map.filterLayers._switchLayer($(this).data('layer'));
						if(map.filterLayers._getStatus($(this).data('layer')).on) {
							$(this).addClass('active');
						} else {
							$(this).removeClass('active');
						}
					});
				});

			})(jQuery);
		</script>
		<?php
		$html = ob_get_clean();
		return $html;
	}

}

new OpenDev_InteractiveMap();