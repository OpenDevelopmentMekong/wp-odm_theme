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
					<input type="text" placeholder="Search for posts, maps, briefings..." />
					<div class="results-container"></div>
				</div>
			</div>
		</div>
	</section>
	<?php
}
?>

<div class="section-title">
	<div class="container">
		<div class="twelve columns">
			<h2><?php _e('Latest articles', 'jeo'); ?></H2>
		</div>
	</div>
</div>
<?php get_template_part('loop'); ?>

<?php // get_template_part('content', 'interactive-map'); ?>

<?php get_footer(); ?>