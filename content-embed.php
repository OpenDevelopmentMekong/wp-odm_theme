<html id="map-embed" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . __('Page', 'jeo') . max($paged, $page);

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" type="image/x-icon" />
<?php wp_head(); ?>
</head>
<body <?php body_class(get_bloginfo('language')); ?>>

	<?php
	if(function_exists('extended_jeo_get_map_embed_conf')):
		$conf = extended_jeo_get_map_embed_conf();
	else:
		$conf = jeo_get_map_embed_conf();
	endif;

	//print_r(	 odm_get_interactive_map_data() );
			$get_map_setting = odm_get_interactive_map_data();
			$get_map_setting['disable_mousewheel'];
	$obj_conf = json_decode($conf, true);
	?>

	<header id="embed-header">
		<h1><a href="<?php echo home_url('/'); ?>" target="_blank"><?php bloginfo('name'); ?><span>&nbsp;</span></a></h1>
		<h2 id="embed-title" style="display:none;"><?php wp_title('', true, 'right'); ?></h2>
	</header>

	<div class="interactive-map" id="embeded-interactive-map">
		<div class="map-container"><div id="map_embed" class="map"></div></div>
		<?php
			$mapID = get_embedded_map_id();
			//show basemap
			display_baselayer_navigation();
			$layers = get_selected_layers_of_map_by_mapID($mapID);
			$base_layers = get_post_meta_of_all_baselayer();
			$layers_legend = get_legend_of_map_by($mapID);
			 //Show Menu Layers and legendbox
			display_map_layer_sidebar_and_legend_box($layers);
		?>
	</div>
	<input type="hidden" id="latitude" />
	<input type="hidden" id="longitude" />
	<input type="hidden" id="zoom" />
	<script type="text/javascript">
		var all_baselayer_value = <?php echo json_encode($base_layers) ?>;
		var all_layers_value = <?php echo json_encode($layers) ?>;
		var all_layers_legends = <?php echo json_encode($layers_legend) ?>;
	</script>

	<script type="text/javascript">
		(function($) {
			jeo(<?php echo $conf; ?>, function(map) {
				var track = function() {
					var c = map.getCenter();
					$('#latitude').val(c.lat);
					$('#longitude').val(c.lng);
					$('#zoom').val(map.getZoom());
				}

				map.on('zoomend', track);
				map.on('dragend', track);

			});

		})(jQuery);


		$(document).ready(function(){
			<?php if($obj_conf['map_only'] == true) { ?>
					$('input.news-marker-toggle').prop('checked', false);
			<?php }else {?>
					$('input.news-marker-toggle').prop('checked', true);
			<?php } ?>
		});
	</script>
	<?php if($obj_conf['map_only']) { ?>
		<style type="text/css">
			#map_embed .leaflet-marker-icon{
				display: block;
			}
		</style>
	<?php }?>
<?php wp_footer(); ?>
</body>
</html>
