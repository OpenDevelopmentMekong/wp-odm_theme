<article id="post-<?php the_ID(); ?>">
	<header class="post-header">
		<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<?php if (get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page'): ?>
			<div class="meta">
				<?php echo_post_meta(get_post());?>
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
