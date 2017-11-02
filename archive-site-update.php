<?php get_header();

$options = get_option('odm_options');
$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created"; ?>

<section class="container section-title main-title">
	<header class="row">
		<div class="eight columns">
			<h1 class="ellipsis"><?php _e('Site updates','odm') ?></h1>
		</div>
    <div class="eight columns align-right">
			<?php get_template_part('section', 'query-actions'); ?>
		</div>
	</header>
</section>

<section class="container">

  <div class="row">

    <div class="sixteen columns">
			<?php
				$index = 1;
				while (have_posts()) : the_post();
				if (should_open_row("list-2-cols",$index)): ?>
					<div class="row">
				<?php endif;
				odm_get_template('post-list-single-2-cols',array(
					"post" => get_post(),
					"show_meta" => true,
					"meta_fields" => array("date","categories","tags","summary_translated"),
					"show_source_meta" => true,
					"show_thumbnail" => true,
					"show_excerpt" => true,
					"header_tag" => true,
					"order" => $date_to_show
			),true);
			if (should_close_row("list-2-cols",$index)): ?>
				</div>
			<?php endif;
			$index++;
			endwhile; ?>
    </div>

  </div>

</section>

<section class="container">
	<div class="row">
		<div class="sixteen columns">
			<?php odm_get_template('pagination',array(),true); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
