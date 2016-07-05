<?php $post = $params[0]; ?>

<div id="post-<?php echo $post->ID; ?>" class="three columns post-list-item-small">
	<a class="post-list-item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a></h3>
	<div class="post-list-item-content">
		<?php
			$thumb_src = opendev_get_thumbnail($post->ID,true);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
			<p><?php echo $post->post_content; ?></p>
	</div>
</div>
