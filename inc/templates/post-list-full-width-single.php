<?php $post = $params[0]; ?>

<div class="post-list-item">
	<a class="post-list-item-title" href="#"><?php echo $post->post_title; ?></a>
	<div class="post-list-item-content">
		<?php
			$thumb_src = opendev_get_thumbnail($post->ID, true);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
		<div class="post-list-content-wrapper">
			<?php show_post_meta($post); ?>	
			<p class="post-excerpt"><?php echo $post->post_excerpt; ?></p>
			<span class="readmore"><a href="">(read more)</a></span>
		</div>
		
	</div>
</div>
