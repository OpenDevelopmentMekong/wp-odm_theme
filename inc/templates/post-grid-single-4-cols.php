<?php
	$post = $params["post"];
	?>

<div class="four columns post-grid-item">
	<div class="grid-content-wrapper">
		<a class="post-grid-item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
		<div class="meta">
				<?php echo_post_meta($post); ?>
		</div>
		<?php
			$thumb_src = odm_get_thumbnail($post->ID);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
	</div>
</div>
