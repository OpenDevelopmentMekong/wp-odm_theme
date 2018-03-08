<?php
  $options = get_option('odm_options');
  $date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created";
	$template = (get_post_type() == "post")? "blog-layout" : "list-single";
?>
<?php if( have_posts() ): ?>
  <section id="blog" class="container">
    <div id="post-content" class="row">
      <div class="sixteen columns">
        <?php
  				$index = 1;
  				while (have_posts()) : the_post();
  					if (should_open_row($template."-2-cols",$index)): ?>
  					<div class="row">
		  			<?php endif;
		  				odm_get_template('post-'.$template.'-2-cols',array(
		  					"post" => get_post(),
		  					"show_meta" => true,
		  					"meta_fields" => array("date","categories","tags","sources","summary_translated"),
		  					"show_source_meta" => true,
		  					"show_thumbnail" => true,
		  					"show_excerpt" => true,
		  					"header_tag" => true,
		  					"order" => $date_to_show,
		  					"index" => $index
		  			),true);
		  			if (should_close_row("blog-layout-2-cols",$index)): ?>
  				</div>
  			<?php endif;
  			$index++;
  			endwhile; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

  <section class="container">
  	<div class="row">
  		<div class="sixteen columns">
  			<?php odm_get_template('pagination',array(),true); ?>
  		</div>
  	</div>
  </section>
