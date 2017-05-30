<?php get_header(); ?>

<div class="section-title">

	<section class="container">
		<div class="row">
			<div class="sixteen columns align-right">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="sixteen columns">
			<div class="panel more-filters-content row">
				<?php odm_adv_nav_filters(); ?>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('advanced-search'); ?>

<?php get_footer(); ?>
