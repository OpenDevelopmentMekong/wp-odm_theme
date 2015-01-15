<?php get_header(); ?>

<?php
if(is_front_page()) {
	?>
	<section id="site-intro">
		<div class="container">
			<div id="intro-texts" class="row">
				<div class="four columns">
					<p class="icon-map"></p>
					<h3>Introduction text 1</h3>
					<p>Lorem ipsum dolor sit</p>
				</div>
				<div class="four columns">
					<p class="icon-database"></p>
					<h3>Introduction text 2</h3>
					<p>Lorem ipsum dolor sit</p>
				</div>
				<div class="four columns">
					<p class="icon-docs"></p>
					<h3>Introduction text 3</h3>
					<p>Lorem ipsum dolor sit</p>
				</div>
			</div>
		</div>
		<div id="live-search">
			<div class="container">
				<div class="twelve columns">
					<input type="text" placeholder="Search for posts, maps and briefings..." />
					<div class="results-container"></div>
				</div>
			</div>
		</div>
	</section>
	<section id="news" class="page-section row">
		<div class="section-title">
			<div class="container">
				<div class="twelve columns">
					<h2><?php _e('Geolocated news', 'opendev'); ?></h2>
				</div>
			</div>
		</div>
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
	<div class="container">
		<div class="nine columns">
			<section id="briefs" class="page-section">
				<div class="section-title">
					<h2><?php _e('Issue briefs', 'opendev'); ?></h2>
				</div>
				<?php for($i = 1; $i <= 3; $i++) { ?>
					<article id="brief-<?php echo $i; ?>" class="row">
						<div class="two columns alpha">
							<img src="http://lorempixum.com/400/400/?<?php echo $i; ?>" class="scale-with-grid" />
						</div>
						<div class="three columns">
							<h3>A Brief</h3>
						</div>
						<div class="four columns omega">
							<p>Lorem ipsum</p>
						</div>
					</article>
				<?php } ?>
			</section>
		</div>
		<div class="three columns">
			<section id="site-updates">
				<div class="section-title">
					<h2><?php _e('Site updates', 'opendev'); ?></h2>
				</div>
				<h3>Um</h3>
			</section>
			<section id="events">
				<div class="section-title">
					<h2><?php _e('Events and opportunities', 'opendev'); ?></h2>
				</div>
				<h3>Um</h3>
			</section>
		</div>
	</div>

<?php } ?>
<?php
/*
<div class="section-title">
	<div class="container">
		<div class="twelve columns">
			<h2><?php _e('Latest articles', 'jeo'); ?></h2>
		</div>
	</div>
</div>
<?php get_template_part('loop'); ?>

*/
?>

<?php // get_template_part('content', 'interactive-map'); ?>

<?php get_footer(); ?>