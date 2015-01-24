<?php

// Query multisite
require_once(STYLESHEETPATH . '/inc/query-multisite.php');

// Theme options
require_once(STYLESHEETPATH . '/inc/theme-options.php');

// Briefings
require_once(STYLESHEETPATH . '/inc/briefings.php');

// Announcements
require_once(STYLESHEETPATH . '/inc/announcements.php');

// Site updates
require_once(STYLESHEETPATH . '/inc/site-updates.php');

// Map category
require_once(STYLESHEETPATH . '/inc/layer-category.php');

// summary
require_once(STYLESHEETPATH . '/inc/summary.php');

// Live search
require_once(STYLESHEETPATH . '/inc/live-search/live-search.php');

// Interactive map
require_once(STYLESHEETPATH . '/inc/interactive-map.php');

function opendev_styles() {

	$options = get_option('opendev_options');

	$css_base = get_stylesheet_directory_uri() . '/css/';

	//wp_dequeue_style('jeo-main');

	wp_register_style('webfont-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700');
	wp_register_style('webfont-opendev', get_stylesheet_directory_uri() . '/font/style.css');
	wp_register_style('opendev-base',  $css_base . 'opendev.css', array('webfont-droid-serif', 'webfont-opendev'));
	wp_register_style('opendev-cambodia',  $css_base . 'cambodia.css');
	wp_register_style('opendev-thailand',  $css_base . 'thailand.css');
	wp_register_style('opendev-laos',  $css_base . 'laos.css');
	wp_register_style('opendev-myanmar',  $css_base . 'myanmar.css');
	wp_register_style('opendev-vietnam',  $css_base . 'vietnam.css');

	wp_enqueue_style('opendev-base');
	if($options['style']) {
		wp_enqueue_style('opendev-' . $options['style']);
	}

}
add_action('wp_enqueue_scripts', 'opendev_styles', 15);

function opendev_jeo_scripts() {

	wp_dequeue_script('jeo-site');
	wp_enqueue_script('jquery-isotope');

	wp_register_script('twttr', 'http://platform.twitter.com/widgets.js');

	// custom marker system
	global $jeo_markers;
	wp_deregister_script('jeo.markers');
	wp_register_script('jeo.markers', get_stylesheet_directory_uri() . '/js/markers.js', array('jeo', 'underscore', 'twttr'), '0.3.17', true);
	wp_localize_script('jeo.markers', 'opendev_markers', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'query' => $jeo_markers->query(),
		'stories_label' => __('stories', 'opendev'),
		'home' => (is_home() && !is_paged() && !$_REQUEST['opendev_filter_']),
		'copy_embed_label' => __('Copy the embed code', 'opendev'),
		'share_label' => __('Share', 'opendev'),
		'print_label' => __('Print', 'opendev'),
		'embed_base_url' => home_url('/embed/'),
		'share_base_url' => home_url('/share/'),
		'marker_active' => array(
			'iconUrl' => get_stylesheet_directory_uri() . '/img/marker_active.png',
			'iconSize' => array(26, 30),
			'iconAnchor' => array(13, 30),
			'popupAnchor' => array(0, -40),
			'markerId' => 'none'
		),
		'site_url' => home_url('/'),
		'read_more_label' => __('Read more', 'opendev'),
		'lightbox_label' => array(
			'slideshow' => __('Open slideshow', 'opendev'),
			'videos' => __('Watch video gallery', 'opendev'),
			'video' => __('Watch video', 'opendev'),
			'images' => __('View image gallery', 'opendev'),
			'image' => __('View fullscreen image', 'opendev'),
			'infographic' => __('View infographic', 'opendev'),
			'infographics' => __('View infographics', 'opendev')
		),
		'enable_clustering' => jeo_use_clustering() ? true : false,
		'default_icon' => jeo_formatted_default_marker()
	));

	if(is_home())
		wp_enqueue_script('opendev-sticky', get_stylesheet_directory_uri() . '/js/sticky-posts.js', array('jeo.markers', 'jquery'), '0.1.2');

	//wp_enqueue_script('opendev-interactive-map', get_stylesheet_directory_uri() . '/inc/interactive-map.js', array('jeo'));
}
add_action('wp_enqueue_scripts', 'opendev_jeo_scripts', 100);

// custom marker data
function opendev_marker_data($data, $post) {
	global $post;

	$permalink = $data['url'];

	if(function_exists('qtrans_getLanguage'))
		$permalink = add_query_arg(array('lang' => qtrans_getLanguage()), $permalink);

	$data['permalink'] = $permalink;
	$data['url'] = $permalink;
	$data['content'] = get_the_excerpt();
	if(get_post_meta($post->ID, 'geocode_zoom', true))
		$data['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);

	// thumbnail
	$data['thumbnail'] = opendev_get_thumbnail();

	return $data;
}
add_filter('jeo_marker_data', 'opendev_marker_data', 10, 2);

function opendev_get_thumbnail($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumb');
	if($thumb_src)
		return $thumb_src[0];
	else
		return get_post_meta($post->ID, 'picture', true);
}

function opendev_logo() {

	$logo = opendev_get_logo();

	if($logo && !is_multisite()) {
		?>
		<h1 class="with-logo">
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php bloginfo('name'); ?>
				<?php echo $logo; ?>
			</a>
		</h1>
		<?php
	} else {
		?>
		<h1>
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php // bloginfo('name'); ?>
				<span class="icon-od-logo"></span>
				Op<sup>e</sup>nDevelopment
			</a>
		</h1>
		<?php
	}

}

function opendev_social_apis() {

	// Facebook
	?>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=1413694808863403";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<?php

	// Twitter
	?>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<?php

	// Google Plus
	?>
	<script type="text/javascript">
	  (function() {
	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	    po.src = 'https://apis.google.com/js/plusone.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
	<?php
}
add_action('wp_footer', 'opendev_social_apis');

function opendev_ms_nav() {

	if(is_multisite()) {
		$sites = wp_get_sites();
		if(!empty($sites) && count($sites) > 1) {
			$current = get_current_blog_id();
			// $name = str_replace('Open Development ', '', get_bloginfo('name'));
			// $logo = opendev_get_logo();
			// if($logo)
			// 	$name = $logo;
			?>
			<ul class="ms-nav">
				<?php
				foreach($sites as $site) {
					if($current != $site['blog_id']) {
						$details = get_blog_details($site['blog_id']);
						$name = str_replace('Open Development ', '', $details->blogname);
						$siteurl = $details->siteurl;
						switch_to_blog($site['blog_id']);
						?>
						<li class="site-item">
							<a href="<?php echo $siteurl; ?>"><?php echo $name; ?></a>
							<div class="sub-menu">
								<ul class="first-menu">
									<li data-content="news"><a href="<?php echo $siteurl; ?>">
										<span class="icon-text"></span> <?php _e('News', 'opendev'); ?>
									</a></li>
									<li data-content="issues"><a href="<?php echo get_post_type_archive_link('briefing'); ?>">
										<span class="icon-docs"></span> <?php _e('Issues', 'opendev'); ?>
									</a></li>
									<li data-content="maps"><a href="<?php echo get_post_type_archive_link('map'); ?>">
										<span class="icon-map"></span> <?php _e('Maps', 'opendev'); ?>
									</a></li>
									<?php
									$data_page_id = opendev_get_data_page_id();
									if($data_page_id) :
										?>
										<li data-content="data"><a href="<?php echo get_permalink($data_page_id); ?>">
											<span class="icon-archive"></span> <?php _e('Data', 'opendev'); ?>
										</a></li>
										<?php
									endif;
									?>
								</ul>
								<div class="content">

									<?php query_posts(array('posts_per_page' => 3, 'without_map_query' => true)); ?>
									<?php if(have_posts()) : ?>
										<div class="news content-item">
											<h2><?php _e('Latest news', 'opendev'); ?></h2>
											<ul>
												<?php while(have_posts()) : the_post(); ?>
													<li>
														<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
														<?php the_excerpt(); ?>
													</li>
												<?php endwhile; ?>
											</ul>
										</div>
									<?php endif; ?>
									<?php wp_reset_query(); ?>

									<?php query_posts(array('post_type' => 'briefing', 'posts_per_page' => 3, 'without_map_query' => true)); ?>
									<?php if(have_posts()) : ?>
										<div class="issues content-item">
											<h2><?php _e('Latest issues', 'opendev'); ?></h2>
											<ul>
												<?php while(have_posts()) : the_post(); ?>
													<li>
														<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
														<?php the_excerpt(); ?>
													</li>
												<?php endwhile; ?>
											</ul>
										</div>
									<?php endif; ?>
									<?php wp_reset_query(); ?>

									<?php query_posts(array('post_type' => 'map', 'posts_per_page' => 3, 'without_map_query' => true)); ?>
									<?php if(have_posts()) : ?>
										<div class="maps content-item">
											<h2><?php _e('Latest maps', 'opendev'); ?></h2>
											<ul>
												<?php while(have_posts()) : the_post(); ?>
													<li>
														<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
														<?php the_excerpt(); ?>
													</li>
												<?php endwhile; ?>
											</ul>
										</div>
									<?php endif; ?>
									<?php wp_reset_query(); ?>

								</div>
							</div>
						</li>
						<?php
						restore_current_blog();
					}
				}
				?>
			</ul>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('.ms-nav').each(function() {
						$nav = $(this);

						$nav.find('.site-item').each(function() {

							var $siteNav = $(this);

							$siteNav.find('.content-item').hide();

							$siteNav.find('.content-item:first-child').show();
							$siteNav.find('.first-menu li:first-child').addClass('active');

							$siteNav.find('.first-menu li').on('mouseover', function() {

								var $content = $siteNav.find('.content-item.' + $(this).data('content'));

								if($content.length) {

									$siteNav.find('.first-menu li').removeClass('active');
									$(this).addClass('active');

									$siteNav.find('.content-item').hide();
									$siteNav.find('.content-item.' + $(this).data('content')).show();

								}
							});

						});
					});
				});
			</script>
			<?php
		}
	}

}

function opendev_wpckan_post_types() {
	return array('post','page','briefing','layer');
}
add_filter('wpckan_post_types', 'opendev_wpckan_post_types');

function opendev_get_related_datasets($atts = false) {

	if(!$atts)
		$atts = array();

	if(!isset($atts['post_id']))
		$atts['post_id'] = get_the_ID();

	$related_datasets_json = get_post_meta( $atts['post_id'], 'wpckan_related_datasets', true );
	$related_datasets = array();
	if (!IsNullOrEmptyString($related_datasets_json))
		$related_datasets = json_decode($related_datasets_json,true);

	$dataset_array = array();

	foreach ($related_datasets as $dataset){
		$dataset_atts = array("id" => $dataset["dataset_id"]);
		try{
			array_push($dataset_array,wpckan_api_get_dataset($dataset_atts));
		} catch(Exception $e) {
			wpckan_log($e->getMessage());
		}
		if (array_key_exists("limit",$atts) && (count($dataset_array) >= (int)($atts["limit"]))) break;
	}
	return $dataset_array;
}

function opendev_wpckan_api_query_datasets($atts) {

	if (is_null(wpckan_get_ckan_settings()))
		wpckan_api_settings_error("wpckan_api_query_datasets");

	if (!isset($atts['query']))
		wpckan_api_call_error("wpckan_api_query_datasets",null);

	try {

		$settings = wpckan_get_ckan_settings();
		$ckanClient = CkanClient::factory($settings);
		$commandName = 'PackageSearch';
		$arguments = array('q' => $atts['query']);

		if (isset($atts['limit'])){
			$arguments['rows'] = (int)$atts['limit'];

			if (isset($atts['page'])){
				$page = (int)$atts['page'];
				if ($page > 0) $arguments['start'] = (int)$atts['limit'] * ($page - 1);
			}
		}

		$filter = null;
		if (isset($atts['organization'])) $filter = $filter . "+owner_org:" . $atts['organization'];
		if (isset($atts['organization']) && isset($atts['group'])) $filter = $filter . " ";
		if (isset($atts['group'])) $filter = $filter . "+groups:" . $atts['group'];
		if (!is_null($filter)){
			$arguments["fq"] = $filter;
		}
		$command = $ckanClient->getCommand($commandName,$arguments);
		$response = $command->execute();

		wpckan_log("wpckan_api_query_datasets commandName: " . $commandName . " arguments: " . print_r($arguments,true) . " settings: " . print_r($settings,true));

		if ($response["success"]==false){
			wpckan_api_call_error("wpckan_api_query_datasets",null);
		}

	} catch (Exception $e){
		wpckan_api_call_error("wpckan_api_query_datasets",$e->getMessage());
	}

	return $response;

}