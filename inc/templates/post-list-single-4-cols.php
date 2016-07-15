<?php
	$post = $params["post"];
	?>

<div class="four columns post-list-item">
	<p><a class="post-list-item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a></p>
	<div class="post-list-item-content">
		<?php
			$thumb_src = odm_get_thumbnail($post->ID,false);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
			<p class="post-excerpt"><?php echo odm_excerpt($post); ?></p>
	</div>
</div>
