<?php
//Excluding 20 latest posts from the loop including the sticky post (eg sticky post 2 + latest post 18=20)
//count sticky post
$sticky_count = new WP_Query(array(
	'posts_per_page' => 20,
	'post__in' => get_option('sticky_posts'),
	'ignore_sticky_posts' => 1
));
$number_excluded = 20 - $sticky_count->found_posts;

$latest_post = new WP_Query(array(
	'posts_per_page' => 12,
	'offset'=> $number_excluded,
	'post__not_in' => get_option('sticky_posts')
));
?>
<ul class="list-posts">
	<?php while($latest_post->have_posts()) : $latest_post->the_post(); ?>
		<li id="post-<?php the_ID(); ?>" <?php post_class('post-item four columns'); ?>>
			<article>
				<header class="post-header">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="meta clearfix">
						<span class="date">
							<span class="icon-calendar"></span>
							<span class="date-content"><?php echo get_the_date(_x('m/d/Y', 'reduced date format', 'opendev')); ?></span>
						</span>
					</p>
				</header>
				<section class="post-content">
					<?php the_excerpt(); ?>
				</section>
				<footer class="post-actions">
					<div class="buttons">
						<a class="button" href="<?php the_permalink(); ?>"><?php _e('Read more', 'opendev'); ?></a>
						<?php if(jeo_has_marker_location()) : ?>
							<a class="button" href="<?php echo jeo_get_share_url(array('p' => $post->ID)); ?>"><?php _e('Share', 'opendev'); ?></a>
						<?php endif; ?>
					</div>
				</footer>
			</article>
		</li>
	<?php endwhile; ?>
</ul>
<div class="twelve columns">
	<?php if(function_exists('wp_paginate')) wp_paginate(); ?>
</div>