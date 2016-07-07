<?php $post = $params[0]; ?>

<div class="three columns post-list-item-small">
	<p><a class="post-list-item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a></p>
	<div class="post-list-item-content">
		<?php
			$thumb_src = odm_get_thumbnail($post->ID,true);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
			<p><?php echo $post->post_content; ?></p>
	</div>
</div>
