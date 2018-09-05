<?php
$term = $wp_query->queried_object;
$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created";
$post_types = get_post_types(array(
  'public' => true,
  '_builtin' => false
));

$tax_post_types = array();
$selected_posttype = odm_get_wp_post_types_for_category_page();
foreach($selected_posttype as $pt) {
  if (in_array($pt, $post_types)){
		$pt_tax = get_object_taxonomies($pt);
		if(in_array($term->taxonomy, $pt_tax)) {
			$tax_post_types[] = $pt;
		}
  }
}
?>

<div class="container category-container">
  <section class="container">
		<header class="row">
			<div class="eight columns">
        <?php
  			if($term->parent) :
  				$parent = get_term($term->parent, $term->taxonomy);
  				?>
  				<h3 class="parent-term"><a href="<?php echo get_term_link($parent); ?>"><?php echo $parent->name; ?></a></h3>
  				<?php
  			endif; ?>
        <h1 class="archive-title"><?php single_cat_title(); ?></h1>
			</div>
      <div class="eight columns align-right">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

  <section class="container">
    <div class="row">
      <div class="sixteen columns">
    		<section class="tabbed-posts-section container">
    			<?php if(count($tax_post_types) > 1) : ?>
    				<nav id="tabbed-post-type-nav">
    					<ul>
    						<?php
    						$current_pt = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'news-article';
    						foreach($tax_post_types as $pt) :
    							$pt = get_post_type_object($pt);
    							$title = $pt->labels->name;?>
    							<li <?php if($current_pt == $pt->name) echo 'class="active"'; ?>><a href="<?php echo add_query_arg(array('queried_post_type' => $pt->name)); ?>"><?php echo $title; ?></a></li>
    						<?php endforeach; ?>
    					</ul>
    				</nav>
    			<?php endif; ?>
    			<?php if(have_posts()) : ?>
	    					<?php
								$index = 1;
								echo '<div class="row">';
								while(have_posts()) : the_post();
	                odm_get_template('post-list-single-2-cols',array(
										"post" => get_post(),
				  					"show_meta" => true,
				  					"meta_fields" => array("date","categories","tags", "sources","summary_translated"),
				  					"show_source_meta" => true,
				  					"show_thumbnail" => true,
				  					"show_excerpt" => true,
				  					"header_tag" => true,
				  					"order" => $date_to_show,
				  					"index" => $index
				  			),true);
								if ($index % 2 == 0):
									echo '</div>';
									echo '<div class="row">';
							  endif;
								$index++;
	    					endwhile;
								echo '</div>';
						?>
    			<?php else : ?>
    				<h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'odm'); ?></h3>
    			<?php endif; ?>
    		</section>
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
