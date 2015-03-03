<?php get_header(); ?>

<?php
if(is_front_page()) {
	?>
	<div id="live-search">
		<div class="container">
			<div class="twelve columns">
				<input type="text" placeholder="Search for posts, maps and briefings..." />
				<div class="results-container"></div>
			</div>
		</div>
	</div>
	<section id="news" class="page-section row">
		<div class="container">
			<div class="twelve columns">
				<div class="section-map">
					<?php
					jeo_map();
					?>
				</div>
			</div>
		</div>
	</section>
	<section id="site-intro">
		<?php
		$intro_texts = opendev_get_intro_texts();
		if(!empty($intro_texts)) :
			?>
			<div class="container">
				<div id="intro-texts" class="row">
					<?php if($intro_texts[1]) : ?>
						<div class="four columns">
							<div class="text-item">
								<div class="icon">
									<?php if($intro_texts[1]['icon']) : ?>
										<p class="icon-<?php echo $intro_texts[1]['icon']; ?>"></p>
									<?php endif; ?>
								</div>
								<div class="content">
									<h3><?php echo $intro_texts[1]['title']; ?></h3>
									<p><?php echo $intro_texts[1]['content']; ?></p>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php if($intro_texts[2]) : ?>
						<div class="four columns">
							<div class="text-item">
								<div class="icon">
									<?php if($intro_texts[2]['icon']) : ?>
										<p class="icon-<?php echo $intro_texts[2]['icon']; ?>"></p>
									<?php endif; ?>
								</div>
								<div class="content">
									<h3><?php echo $intro_texts[2]['title']; ?></h3>
									<p><?php echo $intro_texts[2]['content']; ?></p>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php if($intro_texts[3]) : ?>
						<div class="four columns">
							<div class="text-item">
								<div class="icon">
									<?php if($intro_texts[3]['icon']) : ?>
										<p class="icon-<?php echo $intro_texts[3]['icon']; ?>"></p>
									<?php endif; ?>
								</div>
								<div class="content">
									<h3><?php echo $intro_texts[3]['title']; ?></h3>
									<p><?php echo $intro_texts[3]['content']; ?></p>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</section>

	<section id="latest-news">
		<div class="section-title">
			<div class="container">
				<div class="twelve columns">
					<h2><?php _e('Latest news', 'opendev'); ?></h2>
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
		<div class="container">
			<?php get_template_part('loop', 'latest'); ?>
		</div>
	</section>

	<?php //get_template_part('section', 'content-summary'); ?>

<?php } ?>

<?php // get_template_part('content', 'interactive-map'); ?>

<?php get_footer(); ?>