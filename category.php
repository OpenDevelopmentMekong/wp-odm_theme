<?php
get_header();
$term = $wp_query->queried_object;
$supported_post_types = odm_get_post_types_for_category_page();
// foreach($supported_post_types as $pt):
//   if (in_array($pt, $post_types)):
// 		$pt_tax = get_object_taxonomies($pt);
// 		if(in_array($term->taxonomy, $pt_tax)):
// 			$tax_post_types[] = $pt;
// 		endif;
//   endif;
// endforeach;
?>

<div class="container category-container">
  <section class="container">
		<header class="row">
			<div class="eight columns">
        <?php
  			if($term->parent):
  				$parent = get_term($term->parent, $term->taxonomy);
  				?>
  				<h3 class="parent-term"><a href="<?php echo get_term_link($parent); ?>"><?php echo $parent->name; ?></a></h3>
  				<?php
  			endif; ?>
        <h1 class="archive-title"><?php single_cat_title(); ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

  <?php
    $pt = get_post_type_object("topic");
    $args = array(
      'post_type' => $pt->name,
      'post_status' => 'publish',
      'tax_query' => array(
        array(
          'taxonomy' => 'category',
          'field' => 'slug',
          'terms' => $term->name,
          'operator' => 'IN'
        )
        )
      );

    $topic_posts = get_posts($args); ?>

  <section class="container">
    <div class="row">

      <?php
        if (count($topic_posts) > 0): ?>

        <?php
          if (count($topic_posts) == 1):
            $post = current($topic_posts); ?>
            <div class="sixteen columns">
              <div class="row">
                <?php
                  odm_get_template('post-list-single-1-cols',array(
                      "post" => $post,
                      "show_meta" => true,
                      "show_source_meta" => true,
                      "show_thumbnail" => true,
                      "show_excerpt" => true,
                      "show_summary_translated_by_odc_team" => true,
                      "header_tag" => true
                  ),true);
                 ?>
              </div>
            </div>
        <?php
          else:
            $post = end($topic_posts);?>
            <div class="twelve columns">
              <div class="row">
                <?php
                  odm_get_template('post-list-single-1-cols',array(
                      "post" => $post,
                      "show_meta" => true,
                      "show_source_meta" => true,
                      "show_thumbnail" => true,
                      "show_excerpt" => true,
                      "show_summary_translated_by_odc_team" => true,
                      "header_tag" => true
                  ),true);
                 ?>
              </div>
            </div>

            <div class="four columns">
              <div class="row">
                <h3><?php _e('Related briefings','odm'); ?></h3>
                <ul>
                <?php
                  foreach ($topic_posts as $post):
                    if ($post != end($topic_posts)): ?>
                      <li>
                      <?php
                        odm_get_template('post-link-single-1-cols',array(
                            "post" => $post
                        ),true); ?>
                      </li>
                  <?php
                    endif;
                  endforeach; ?>
                </ul>
              </div>
            </div>
        <?php
          endif;
        endif; ?>
    </div>
  </section>

  <section class="container">
    <div class="row">
      <div class="twelve columns">
    		<section class="tabbed-posts-section container">

          <?php
            $current_pt = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'news-article';
            if(count($supported_post_types) > 1) : ?>
    				<nav id="tabbed-post-type-nav">
    					<ul>
    						<?php

                foreach($supported_post_types as $pt):
                  $pt = get_post_type_object($pt);
                  if (isset($pt)):
      							$title = $pt->labels->name;?>
      							<li <?php if($current_pt == $pt->name) echo 'class="active"'; ?>><a href="<?php echo add_query_arg(array('queried_post_type' => $pt->name)); ?>"><?php echo $title; ?></a></li>
    						<?php
                  endif;
                endforeach; ?>
    					</ul>
    				</nav>
    			<?php endif; ?>

        <?php
          $pt = get_post_type_object($current_pt);
          if (isset($pt)):
            $args = array(
          		'post_type' => $pt->name,
          		'post_status' => 'publish',
          		'tax_query' => array(
  							array(
  							  'taxonomy' => 'category',
  							  'field' => 'slug',
  							  'terms' => $term->name,
  								'operator' => 'IN'
  							)
  						  )
  						);

            $posts = get_posts($args);
            if (count($posts) > 0):
              foreach ($posts as $post) : ?>
      					<?php
    						echo '<div class="row">';
                  odm_get_template('post-list-single-1-cols',array(
    			  					"post" => $post,
    			  					"show_meta" => true,
    			  					"show_source_meta" => true,
    									"show_thumbnail" => true,
    									"show_excerpt" => true,
    									"show_summary_translated_by_odc_team" => true,
    									"header_tag" => true
    			  			),true);
    						echo '</div>';
              endforeach;
            else : ?>
            <h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'odm'); ?></h3>
          <?php
            endif;
          endif; ?>
    		</section>
    	</div>

      <div class="four columns">
        <?php dynamic_sidebar('category-page-sidebar'); ?>
      </div>

    </div>
  </section>

</div>

<?php get_footer(); ?>
