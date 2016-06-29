<?php
if(is_category()) {
	include_once(get_stylesheet_directory() . '/category.php');
	die();
}
get_header();
?>

<div class="section-title main-title">

	<section class="container">
		<div class="row">
			<div class="twelve columns">
				<h2>Map goes here</h2>
			</div>
		</div>
		<div class="row">
			<?php while (have_posts()) : the_post();
				opendev_get_template('custom-post-grid-single',array(get_post()),true);
			endwhile; ?>
		</div>
	</section>

	<section class="container">
		<div class="row">
			<div class="twelve columns">
				<?php opendev_get_template('pagination',array(),true); ?>
			</div>
		</div>
	</section>

</div>


<?php get_footer(); ?>
