<?php

$term = $wp_query->queried_object;
$supported_wp_post_types = odm_get_wp_post_types_for_category_page();
$supported_ckan_post_types = odm_get_ckan_post_types_for_category_page();
$ckan_post_types_names = array(
	"dataset" => "Datasets",
	"library_record" => "Publications",
	"laws_record" => "Laws",
	"agreement" => "Agreements"
);

?>

<div class="container category-container">

  <?php
    if (!class_exists("WP_Odm_Solr_CKAN_Manager") || !WP_Odm_Solr_CKAN_Manager()->ping_server()):  ?>
      <div class="row">
        <div class="sixteen columns">
            <p class="error">
              <?php _e("wp-odm_solr plugin is not properly configured. Please contact the system's administrator","wp-odm_solr"); ?>
            </p>
        </div>
      </div>
  <?php
    else: ?>

    <section class="container">
  		<header class="row">
  			<div class="eight columns">
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
									<h3><?php _e('Main topical briefing','odm'); ?></h3>
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
									<h3><?php _e('Main topical briefing','odm'); ?></h3>
                  <?php
                    odm_get_template('post-list-single-1-cols',array(
                        "post" => $post,
                        "show_meta" => true,
                        "show_source_meta" => true,
                        "show_thumbnail" => true,
                        "show_excerpt" => true,
                        "show_summary_translated_by_odc_team" => true,
                        "header_tag" => false
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
      		<section class="category-post-type-section container">

            <?php
              $current_pt = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'dataset';
  						$total_results_found = count($supported_ckan_post_types) + count($supported_wp_post_types);
              if( $total_results_found > 0) : ?>
      				<nav id="category-post-type-nav">
      					<ul>
      						<?php
  								foreach ($supported_ckan_post_types as $pt):
                    $post_type_name = array_key_exists($pt,$ckan_post_types_names) ? $ckan_post_types_names[$pt] : $pt ?>
  									<li <?php if($current_pt == $pt) echo 'class="active"'; ?>><a href="<?php echo add_query_arg(array('queried_post_type' => $pt)); ?>"><?php echo $post_type_name; ?></a></li>
  								<?php
  								endforeach;

                  foreach($supported_wp_post_types as $pt):
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
  					elseif (in_array($current_pt,$supported_ckan_post_types)):

  						$attrs = array(
  							'dataset_type' => $current_pt,
  							'extras_taxonomy' => $term->name,
  							'capacity' => 'public'
  						);

  						$control_attrs = array();

  						$result = WP_Odm_Solr_CKAN_Manager()->query(null,$attrs,null);
  						$results = $result["resultset"]; ?>

  						<div class="solr_results result_container container">

  							<?php
  								if (isset($results) && $results->getNumFound() > 0):
  									foreach($results as $document):
  										odm_get_template('solr-result-single',array(),true);
  									endforeach;
  								else: ?>

  								<h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'odm'); ?></h3>

  							<?php
  								endif; ?>
  						</div>

  					<?php
            endif; ?>
      		</section>
      	</div>

        <div class="four columns">
          <?php dynamic_sidebar('category-page-sidebar'); ?>
        </div>

      </div>
    </section>

  <?php
  endif; ?>

</div>
