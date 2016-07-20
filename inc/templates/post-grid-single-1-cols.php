<?php
	$post = $params["post"];
	$show_meta = $params["show_meta"];
	?>

<div class="sixteen columns post-grid-item">
	<div class="grid-content-wrapper">
		<a class="post-grid-item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
		<?php if ($show_meta): ?>
		<div class="meta">
				<?php echo_post_meta($post,array('date','sources','categories')); ?>
		</div>
		<?php endif; ?>
		<?php
			$thumb_src = odm_get_thumbnail($post->ID);
			if (isset($thumb_src)):
				echo $thumb_src;
			endif; ?>
	</div>
</div>
