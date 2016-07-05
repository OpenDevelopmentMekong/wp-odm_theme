<?php $post = $params[0]; ?>

<div id="post-<?php echo $post->ID; ?>" class="three columns post-list-item">
	<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a></h3>
	<div class="meta">
		<?php show_post_meta($post); ?>
	</div>
	<?php
		$thumb_src = opendev_get_thumbnail($post->ID);
		if (isset($thumb_src)):
			echo $thumb_src;
		endif; ?>
</div>
