<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Map catalogue','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

    <div class="row">

      <div class="eleven columns">
      <?php
			//List cetegory and layer by cat for menu items
					$map_catalogue = get_all_layers_grouped_by_subcategory();

					//Pagination
					$pagination = get_pagination_of_layers_grouped_by_subcategory($map_catalogue);
					foreach ($map_catalogue as $key => $layer) {
						if($key >= $pagination["start_post"] && $key <= $pagination["end_post"] ):
							if($key == $pagination["start_post"]):
								echo "<div class='grid-row'>";
							elseif ($key % 4 == 1):
									echo "<div class='grid-row'>";
							endif;
							odm_get_template('post-grid-single-4-cols-caption-below',array( "post" => $layer, "show_meta" => false), true);
							if($key % 4 == 0 || $key == $pagination["end_post"]) :
								echo "</div>";
							endif;
						endif;
					}
				?>
      </div>

      <div class="four columns offset-by-one">
        <?php dynamic_sidebar('archive-sidebar'); ?>
      </div>

    </div>

	</section>

	<section class="container">
		<div class="row">
			<div class="sixteen columns">
				<?php odm_get_template('pagination', array("paging_arg" => $pagination["paging_arg"]), true); ?>
			</div>
		</div>
	</section>

</div>


<?php get_footer(); ?>
