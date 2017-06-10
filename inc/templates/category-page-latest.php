<?php

$queried_post_type = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'dataset';
$param_page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$param_page_solr = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? ((int)$_GET['page'] -1) : 0;  
$term = $wp_query->queried_object;

?>

<div class="container category-container">

  <?php
    if (!class_exists("WP_Odm_Solr_CKAN_Manager") || !WP_Odm_Solr_CKAN_Manager()->ping_server() || !function_exists("wp_solr_get_search_page_template")):  ?>
      <div class="row">
        <div class="sixteen columns">
            <p class="error">
              <?php _e("wp-odm_solr plugin is not properly configured. Please contact the system's administrator","wp-odm_solr"); ?>
            </p>
        </div>
      </div>
  <?php
    else: ?>

    <section class="container section-title main-title">
  		<header class="row">
  			<div class="eight columns">
          <h1 class="archive-title"><?php single_cat_title(); ?></h1>
  			</div>
        <div class="eight columns align-right">
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
									<h3><?php _e('Topic briefing','odm'); ?></h3>
                  <?php
                    odm_get_template('post-highlighted-single-1-cols',array(
                        "post" => $post,
                        "show_meta" => true,
												"meta_fields" => array("date","categories","tags"),
                        "show_source_meta" => true,
                        "show_thumbnail" => true,
                        "show_excerpt" => true,
                        "show_summary_translated_by_odc_team" => true,
                        "header_tag" => true,
												"show_more_link" => true
                    ),true);
                   ?>
                </div>
              </div>
          <?php
            else:
              $post = end($topic_posts);?>
              <div class="twelve columns">
                <div class="row">
									<h3><?php _e('Topic briefing','odm'); ?></h3>
                  <?php
                    odm_get_template('post-highlighted-single-1-cols',array(
                        "post" => $post,
                        "show_meta" => true,
												"meta_fields" => array("date","categories","tags"),
                        "show_source_meta" => true,
                        "show_thumbnail" => true,
                        "show_excerpt" => true,
                        "show_summary_translated_by_odc_team" => true,
                        "header_tag" => false,
												"show_more_link" => true
                    ),true);
                   ?>

                </div>
              </div>

              <div class="four columns">
                <div class="row">
                  <h3><?php _e('Related briefings','odm'); ?></h3>
                  <?php
                    foreach ($topic_posts as $post):
                      if ($post != end($topic_posts)): ?>
                        <?php
                          odm_get_template('post-link-single-1-cols',array(
                              "post" => $post
                          ),true); ?>
                        </br>
                    <?php
                      endif;
                    endforeach; ?>
                </div>
              </div>
          <?php
            endif;
          endif; ?>
      </div>
    </section>

    <section class="search-results container">
      <div class="row">
        <div class="twelve columns">					
          <?php wp_solr_get_search_page_template("category-page"); ?>					
				</div>
			
	      <div class="four columns">
	        <?php dynamic_sidebar('category-page-sidebar'); ?>
	      </div>

	    </div>
  	</section>

  <?php
  endif; ?>

</div>
