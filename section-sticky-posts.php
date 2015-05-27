<?php
$sticky = new WP_Query(array(
	'posts_per_page' => 20,
	'post__in' => get_option('sticky_posts'),
	'ignore_sticky_posts' => 1
));
//Query the latest posts to fill the number of maximun number of post, but ignoring sticky post
$number_latest_post = 20 - $sticky->found_posts;
$latest_post = new WP_Query(array(
	'posts_per_page' => $number_latest_post,
	'post__not_in' => get_option('sticky_posts'),
	'ignore_sticky_posts' => 1
));

if($sticky->have_posts()) :
?>
	<div class="sticky-posts">
		<?php while($sticky->have_posts()) : $sticky->the_post(); ?>
			<div class="sticky-item" data-postid="<?php the_ID(); ?>">
				<article id="sticky-post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-area">
						<header class="post-header">
							<?php if(has_post_thumbnail()) : ?>
								<div class="post-thumbnail">
									<?php the_post_thumbnail('thumbnail'); ?>
								</div>
							<?php endif; ?>
							<h2><?php the_title(); ?></h2>
							<p class="date"><?php echo get_the_date(); ?></p>
							<a class="link" href="<?php the_permalink(); ?>"></a>
						</header>
						<section class="post-content">
							<?php the_excerpt(); ?>
						</section>
					</div>
					<?php
					/*
					<footer class="post-actions">
						<a class="button" href="<?php the_permalink(); ?>"><?php _e('Read more', 'opendev'); ?></a>
						<a class="button share-button" href="<?php echo jeo_get_share_url(array('p' => get_the_ID())); ?>"><?php _e('Share', 'opendev'); ?></a>
					</footer>
					*/
					?>
				</article>
			</div>
		<?php endwhile; ?>
		<!-- List lastest posts  -->
		<?php while($latest_post->have_posts()) : $latest_post->the_post(); ?>
			<div class="sticky-item" data-postid="<?php the_ID(); ?>">
				<article id="sticky-post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-area">
						<header class="post-header">
							<?php if(has_post_thumbnail()) : ?>
								<div class="post-thumbnail">
									<?php the_post_thumbnail('thumbnail'); ?>
								</div>
							<?php endif; ?>
							<h2><?php the_title(); ?></h2>
							<p class="date"><?php echo get_the_date(); ?></p>
							<a class="link" href="<?php the_permalink(); ?>"></a>
						</header>
						<section class="post-content">
							<?php the_excerpt(); ?>
						</section>
					</div>
				</article>
			</div>
		<?php endwhile; ?>
	</div>
<?php
endif;
?>
