<?php if (have_posts()) : ?>
	<section class="posts-section row">
		<div class="container">

		<!-- Advanced search screen -->
		<?php
		if (is_search() || get_query_var('opendev_advanced_nav')) :
			$search_term = isset($s) ? $s : $_REQUEST['s'];
			$search_results = content_types_breakdown_for_query($search_term,5);?>

			<!-- Content API -->
			<div class="twelve columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>

			<!-- Results grouped by post type -->
			<div class="eight columns">
				<?php
					foreach( $search_results as $post_type): ?>
						<h1><?php echo $post_type['title'] ?></h1>
						<?php
							foreach( $post_type['posts'] as $raw_post):
								$post = get_post($raw_post['ID']);
								opendev_get_template('post-list-full-width-single',array($post),true);
							endforeach;
					endforeach; ?>
			</div>

			<!-- Sidebar -->
			<section id="wpckan_search_results" class="four columns">
				<h2><?php _e('Data results'); ?></h2>
				<?php echo do_shortcode('[wpckan_query_datasets query="'.$s.'" limit="10" include_fields_dataset="title" include_fields_resources="format" blank_on_empty="true"]'); ?>
				<?php
            $data_page_id = opendev_get_data_page_id();
            if ($data_page_id) : ?>
							<a class="button" href="<?php echo get_permalink($data_page_id); ?>?ckan_s=<?php echo $_GET['s']; ?>"><?php _e('View all data results', 'opendev'); ?></a>
						<?php endif; ?>
			</section>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					if(!$('.wpckan_dataset_list ul li').length)
						$('#wpckan_search_results').hide();
				})
			</script>

		<!-- Normal loop -->
		<?php else: ?>

			<!-- Main content -->
			<div class="eight columns">
				<ul class="opendev-posts-list">
					<?php while (have_posts()) : the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
							<article id="post-<?php the_ID(); ?>">
								<header class="post-header">
									<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
									<?php if (get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page'):?>
										<div class="meta">
												<?php show_post_meta(get_post());?>
										</div>
									<?php endif; ?>
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

			<!-- Sidebar -->
			<div class="three columns move-up">
				<aside id="sidebar">
					<ul class="widgets">
						<li class="widget share-widget">
							<?php opendev_get_template('social-share',array(),true); ?>
						</li>
						<li id="odm_taxonomy_widget" class="widget widget_opendev_taxonomy_widget">
							<?php list_category_by_post_type(); ?>
						</li>
					</ul>
				</aside>
			</div>

			<!-- Pagination -->
			<div class="twelve columns">
				<div class="navigation">
					<?php
            global $wp_query;

            $big = 999999999; // need an unlikely integer

            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $wp_query->max_num_pages,
            ));
          ?>
				</div>
			</div>

		<?php endif; ?>

		</div>
	</section>
<?php endif; ?>
