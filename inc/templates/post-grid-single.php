<?php $post = $params[0]; ?>

<div id="post-<?php echo $post->ID; ?>" class="three columns post-grid-item">
	<div class="grid-content-wrapper">
		<a class="post-grid-item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
		<div class="meta">
				<?php show_post_meta($post); ?>
		</div>
		<?php
			$thumb_src = opendev_get_thumbnail($post->ID);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>	
	</div>
</div>
