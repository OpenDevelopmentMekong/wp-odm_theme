<?php if(have_posts()) : ?>
	<section class="posts-section row">
		<div class="container">
			<?php if(is_post_type_archive('topic')) : ?>
				<div class="twelve columns">
					<section id="briefs" class="list">
						<?php
						while(have_posts()) :
							the_post();
							?>
							<article id="topic-<?php the_ID(); ?>" class="row">
								<header>
									<div class="three columns alpha">
										<?php if(has_post_thumbnail()) : ?>
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
										<?php else : ?>
											<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/thumbnail.png" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />
										<?php endif; ?>
									</div>
									<!-- <div class="four columns">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>
									</div> -->
								</header>
								<div class="nine columns omega">
								    <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>
										<!-- <p><span class="icon-user"></span> <?php //the_author(); ?></p> -->
									<?php the_excerpt(); ?>
								</div>
							</article>
						<?php endwhile; ?>
					</section>
				</div>
			<?php else : ?>
				<div class="nine columns">
					<?php get_template_part('section', 'query-actions'); ?>
	              <?php if(is_search() || get_query_var('opendev_advanced_nav')) : ?>
												<?php $search_results =& new WP_Query("s=$s & showposts=-1");
	                            $NumResults = $search_results->post_count; ?>
	                      <div id="advanced_search_results"><h2>Site Results (<?php echo $NumResults; ?>)</h2> </div>
	              <?php endif; ?>

					<ul class="opendev-posts-list">
						<?php while(have_posts()) : the_post(); ?>
							<li id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
								<article id="post-<?php the_ID(); ?>">
									<header class="post-header">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<?php if(get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page') { ?>
											<div class="meta">
													<div class="date">
														 <span class="lsf">&#xE12b;</span>
															 <?php
															 if (function_exists(qtrans_getLanguage)){
															 		if (qtrans_getLanguage() =="kh"){
																		echo convert_date_to_kh_date(get_the_time('j.M.Y'));
																	}else {
																		echo get_the_time('j F Y');
																  }
															 }else {
																echo get_the_time('j F Y');
															 } ?>
													</div>
													&nbsp;
													<div class="news-source">
														<?php
															if (taxonomy_exists('news_source'))
															$terms_news_source = get_the_terms( $post->ID, 'news_source' );

																				 if ( $terms_news_source && ! is_wp_error( $terms_news_source ) ) {
																						 if ($terms_news_source){
																			$news_sources = "";
																								 echo '<span class="icon-news"></span> ';
																			foreach ($terms_news_source as $term) {
																			$term_link = get_term_link( $term, 'news_source' );
																			if( is_wp_error( $term_link ) )
																				continue;
																			//We successfully got a link. Print it out.
																			 $news_sources .= '<a href="' . $term_link . '"><srong>' . $term->name . '</srong></a>,';
																		}
																		echo substr($news_sources, 0, -1);
																}
															}else if (get_post_meta($post->ID, "rssmi_source_feed", true)){
																						 echo '<span class="icon-news"></span> ';
																						 $news_source_id = get_post_meta($post->ID, "rssmi_source_feed", true);
																						 echo get_the_title($news_source_id);
																				 }
															?>
													</div>
												<!--<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>-->
											</div>
										<?php } ?>
									</header>
									<section class="post-content">
										<div class="post-excerpt">
											<?php the_excerpt(); ?>
										</div>
									</section>
									<aside class="actions clearfix">
										<a href="<?php the_permalink(); ?>"><?php _e('Read more', 'jeo'); ?></a>
									</aside>
								</article>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
				<?php if(is_search() || get_query_var('opendev_advanced_nav')) : ?>
					<section id="wpckan_search_results" class="four columns">
						<h2><?php _e('Data results'); ?></h2>
						<?php echo do_shortcode('[wpckan_query_datasets query="' . $_GET['s'] . '" limit="10" include_fields_resources="format"]'); ?>
						<?php
						$data_page_id = opendev_get_data_page_id();
						if($data_page_id) {
							?>
							<a class="button" href="<?php echo get_permalink($data_page_id); ?>?ckan_s=<?php echo $_GET['s']; ?>"><?php _e('View all data results', 'opendev'); ?></a>
							<?php
						}
						?>
					</section>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						if(!$('.wpckan_dataset_list ul li').length)
							$('#wpckan_search_results').hide();
					})
				</script>
				<?php else : ?>
					<!--<div class="three columns offset-by-one move-up">-->
					<div class="three columns move-up">
						<aside id="sidebar">
							<ul class="widgets">
								<li class="widget share-widget">
									<div class="share clearfix">
										<ul>
											<!--<li>
												<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-show-faces="false" data-send="false"></div>
											</li>-->
											<li>
												<div class="fb-share-button" data-href="<?php echo get_permalink( $post->ID )?>" data-send="false" data-layout="button" data-show-faces="false"></div>
											</li>
											<li>
												<div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a></div>
											</li>
											<li>
												<div class="g-plusone" data-width="50" data-annotation="none" data-size="tall" data-href="<?php the_permalink(); ?>" data-count="false"></div>
											</li>
										</ul>
									</div>
								</li>
								<li id="opendev_taxonomy_widget" class="widget widget_opendev_taxonomy_widget">
									<?php list_category_by_post_type(); ?>
								</li>
								<?php if ( get_post_type() == 'mekong-storm-flood'){
                                          dynamic_sidebar('mekong-storm-flood');
                                      } else{
								          dynamic_sidebar('general');
                                       }
                                ?>
							</ul>
						</aside>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="twelve columns">
				<div class="navigation">
					<?php posts_nav_link(); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
