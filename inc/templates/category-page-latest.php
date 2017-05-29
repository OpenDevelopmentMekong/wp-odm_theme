<?php

$queried_post_type = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'dataset';
$param_page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$param_page_solr = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? ((int)$_GET['page'] -1) : 0;
      
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
					
					<section class="category-post-type-section container">
						
						<div class="row">
			        <div class="sixteen columns">
      							
	            <?php
	  						$total_results_found = count($supported_ckan_post_types) + count($supported_wp_post_types);
	              if( $total_results_found > 0) : ?>
	      				<nav id="category-post-type-nav">
	      					<ul>
	      						<?php
	  								foreach ($supported_ckan_post_types as $pt):
	                    $post_type_name = array_key_exists($pt,$ckan_post_types_names) ? $ckan_post_types_names[$pt] : $pt;											
											$new_url = add_query_arg(array('queried_post_type' => $pt),remove_query_arg('page'));?>
	  									<li <?php if($queried_post_type == $pt): echo 'class="active"'; endif; ?>><a href="<?php echo $new_url; ?>"><i class="<?php echo get_post_type_icon_class($pt); ?>"></i> <?php echo $post_type_name; ?></a></li>
	  								<?php
	  								endforeach;

	                  foreach($supported_wp_post_types as $pt):
	                    $pt = get_post_type_object($pt);
	                    if (isset($pt)):
	        							$title = $pt->labels->name;												
												$new_url = add_query_arg(array('queried_post_type' => $pt->name),remove_query_arg('page')); ?>
	        							<li <?php if($queried_post_type == $pt->name): echo 'class="active"'; endif; ?>><a href="<?php echo $new_url; ?>"><i class="<?php echo get_post_type_icon_class($pt->name); ?>"></i> <?php echo $title; ?></a></li>
	      						<?php
	                    endif;
	                  endforeach; ?>
	      					</ul>
	      				</nav>
	      			<?php endif; ?>
							
						</div>
					</div>
					
					<div class="row">
						<div class="sixteen columns">

		          <?php					
		            $pt = get_post_type_object($queried_post_type);
		            if (isset($pt)):
		              $args = array(
		            		'post_type' => $pt->name,
		            		'post_status' => 'publish',
										'number' => 12,
										'offset' => $param_page,
		            		'tax_query' => array(
		    							array(
		    							  'taxonomy' => 'category',
		    							  'field' => 'slug',
		    							  'terms' => $term->name,
		    								'operator' => 'IN'
		    							)
		  						  )
		  						);
									
									$meta_fields = odm_country_manager()->get_current_country() == "mekong" ? array("language","country","date","categories","tags") : array("language","date","categories","tags");
									
		              $posts = get_posts($args);
		              if (count($posts) > 0):
		                foreach ($posts as $post) : ?>
		        					<?php
		      						echo '<div class="row">';
		                    odm_get_template('post-list-single-1-cols',array(
		      			  					"post" => $post,
		      			  					"show_meta" => true,
														"meta_fields" => $meta_fields,
		      			  					"show_source_meta" => true,
		      									"show_thumbnail" => true,
		      									"show_excerpt" => true,
		      									"show_summary_translated_by_odc_team" => true,
		      									"header_tag" => true
		      			  			),true);
		      						echo '</div>';
		                endforeach; ?>
										
										<section class="container">
											<div class="row">
												<div class="sixteen columns">
													<?php odm_get_template('pagination',array(),true); ?>
												</div>
											</div>
										</section>
										
									<?php
		              else : ?>
		              <h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'odm'); ?></h3>
		            <?php
		  						endif;
		  					elseif (in_array($queried_post_type,$supported_ckan_post_types)):

		  						$attrs = array(
		  							'dataset_type' => $queried_post_type,
		  							'extras_taxonomy' => $term->name,
		  							'capacity' => 'public'
		  						);

									$control_attrs = array(
							      "limit" => 12,
							      "page" => $param_page_solr
							    );

		  						$result = WP_Odm_Solr_CKAN_Manager()->query(null,$attrs,$control_attrs);
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
			              if (isset($results) && $results->getNumFound() > 0):
			                $total_pages = ceil($results->getNumFound()/$control_attrs['limit']);
			                if ($total_pages > 1): ?>
			                  <div class="row">
			                    <div class="pagination sixteen columns">
			                      <?php
			                      odm_get_template('pagination_solr', array(
			                                    "current_page" => $param_page,
			                                    "total_pages" => $total_pages
			                                  ),true); ?>
			                    </div>
			                  </div>
			              <?php
			                endif;
			              endif; ?>

		  					<?php
		            endif; ?>
      		
		    			</div>
						</div>											
								
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
