<?php $post = $params[0]; ?>

<div class="post-list-item">
	<a class="post-list-item-title" href="#"><?php echo $post->post_title; ?></a>
	<?php show_post_meta($post); ?>
	<div class="post-list-item-content">
		<?php
			$thumb_src = opendev_get_thumbnail($post->ID, true);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
		<p><?php echo $post->post_content; ?></p>
	</div>
</div>
