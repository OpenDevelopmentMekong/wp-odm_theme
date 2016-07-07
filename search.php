<?php get_header(); ?>

<div class="section-title">
	<div class="container">
		<div class="twelve columns">
			<!--<h1><?php _e('Search results', 'jeo'); ?></h1-->
			<div class="panel more-filters-content row">
				<?php odm_adv_nav_filters(); ?>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('advanced-search'); ?>

<?php get_footer(); ?>
