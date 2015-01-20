<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<article id="content" class="single-post">
		<header class="single-post-header" class="clearfix">
			<div class="container">
				<div class="eight columns">
					<?php the_category(); ?>
					<h1><?php the_title(); ?></h1>
				</div>
				<div class="three columns offset-by-one">
					<div class="post-meta">
						<p class="author"><span class="lsf">&#xE137;</span> <?php _e('by', 'jeo'); ?> <?php the_author(); ?></p>
						<p class="date"><span class="lsf">&#xE12b;</span> <?php the_date(); ?></p>
						<?php the_tags('<p class="tags"><span class="lsf">&#xE128;</span> ', ', ', '</p>'); ?>
					</div>
				</div>
			</div>
		</header>
		<section id="featured-media" class="row">
			<div class="container">
				<div class="twelve columns">
					<div style="height:500px;">
						<?php
						if(jeo_has_marker_location()) {
							jeo_map();
						}
						?>
					</div>
				</div>
			</div>
		</section>
		<?php
		$datasets = opendev_get_related_datasets();
		$groupby = 'groups';
		if(!empty($datasets)) {
			$grouped = array();
			foreach($datasets as $dataset) {
				if(!empty($dataset[$groupby])) {
					foreach($dataset[$groupby] as $group) {
						if(!$grouped[$group['id']]) {
							$grouped[$group['id']] = $group;
							$grouped[$group['id']]['datasets'] = array();
						}
						$grouped[$group['id']]['datasets'][] = $dataset;
					}
				} else {
					if(!$grouped['_other'])
						$grouped['_other'] = array(
							'display_name' => __('Other', 'opendev'),
							'datasets' => array()
						);
					$grouped['_other']['datasets'][] = $dataset;
				}
			}
		}
		?>
		<?php if(isset($grouped) && !empty($grouped)) : ?>
			<section id="related-datasets" class="row">
				<div class="container">
					<div class="box-section twelve columns">
						<div class="box-title">
							<h2><?php _e('Related resources', 'opendev'); ?></h2>
						</div>
						<div class="box-items">
							<?php
							foreach($grouped as $group) :
								if(!empty($group['datasets'])) :
									?>
									<div class="group-item box-item">
										<?php if(count($grouped) > 1) : ?>
											<h3><?php echo isset($group['title']) ? $group['title'] : $group['display_name']; ?></h3>
										<?php endif; ?>
										<ul class="dataset-list">
											<?php foreach($group['datasets'] as $dataset) : ?>
												<li class="dataset-item">
													<h4>
														<a href="<?php echo $dataset['']; ?>"><?php echo $dataset['title']; ?></a>
													</h4>
													<?php if(isset($dataset['description'])) : ?>
														<p class="dataset-description"><?php echo $dataset['description']; ?></p>
													<?php elseif(isset($dataset['notes'])) : ?>
														<p class="dataset-description"><?php echo $dataset['notes']; ?></p>
													<?php endif; ?>
													<ul class="dataset-resources clearfix">
														<?php
														$i = 0;
														foreach($dataset['resources'] as $resource) :
															if($i > 3)
																continue;
															$i++;
															?>
															<li class="resource-item">
																<a href="<?php echo $resource['url']; ?>" target="_blank" rel="external">
																	<?php echo $resource['description']; ?>
																	<?php if($resource['format']) : ?>
																		<span class="format"><?php echo $resource['format']; ?></span>
																	<?php endif; ?>
																</a>
															</li>
														<?php endforeach; ?>
													</ul>
													<p><a href="<?php echo $dataset['']; ?>"><?php _e('Read more', 'opendev'); ?></a></p>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php
								endif;
							endforeach;
							?>
						</div>
						<a class="toggle-resources"><?php _e('Show resources details', 'opendev'); ?></a>
						<script type="text/javascript">
							jQuery(document).ready(function($) {
								$('.dataset-resources,.dataset-description').hide();

								var viewingAll = false;

								$('.toggle-resources').on('click', function() {
									toggle();
								});

								function toggle(node) {

									node = node || false;

									if(!node) {
										if(viewingAll) {
											$('.dataset-resources,.dataset-description').hide();
											viewingAll = false;
										} else {
											$('.dataset-resources,.dataset-description').show();
											viewingAll = true;
										}
									}

								}
							});
						</script>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<section class="content">
			<div class="container">
				<div class="eight columns">
					<?php the_content(); ?>
					<?php
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );
					?>
					<?php comments_template(); ?>
				</div>
				<div class="three columns offset-by-one">
					<aside id="sidebar">
						<ul class="widgets">
							<li class="widget">
								<div class="share clearfix">
									<ul>
										<li>
											<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-show-faces="false" data-send="false"></div>
										</li>
										<li>
											<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a>
										</li>
										<li>
											<div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div>
										</li>
									</ul>
								</div>
							</li>
							<?php dynamic_sidebar('post'); ?>
						</ul>
					</aside>
				</div>
			</div>
		</section>
	</article>

<?php endif; ?>

<?php get_footer(); ?>