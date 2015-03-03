<ul class="list-posts">
	<?php while(have_posts()) : the_post(); ?>
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