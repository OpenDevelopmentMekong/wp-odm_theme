<?php get_header();

$options = get_option('odm_options');
$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "created"; ?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Database','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

    <div class="row">

      <div class="sixteen columns">
        <?php while (have_posts()) : the_post();
  				odm_get_template('post-grid-single-4-cols',array(
  					"post" => get_post(),
  					"show_meta" => true,
						"meta_fields" => array("date"),
						"order" => $date_to_show
  			),true);
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

</div>


<?php get_footer(); ?>
