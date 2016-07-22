<?php
	$post = $params["post"];
	$show_meta = $params["show_meta"];
	$show_excerpt = $params["show_excerpt"];
	$show_post_type = $params["show_post_type"];
	?>

<div class="sixteen columns post-list-item">
	<?php
		if ($show_post_type):
			$post_type_name = get_post_type($post->ID);
			$post_type = get_post_type_object($post_type_name);
			?>
	<a class="item-post-type" href="<?php echo $post_type->rewrite['slug'] ?>"><?php echo $post_type->labels->name ?></a>
	<?php
		endif; ?>
	<p><a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a></p>
	<div class="item-content">
			<?php if ($show_excerpt):
				$thumb_src = odm_get_thumbnail($post->ID,false);
				if (isset($thumb_src)):
					echo $thumb_src;
				endif; ?>
				<div class="post-excerpt">
					<?php echo odm_excerpt($post); ?>
				</div>
			<?php endif; ?>
			<?php if ($show_meta): ?>
			<div class="meta">
					<?php echo_post_meta($post,array('date','sources','categories')); ?>
			</div>
			<?php endif; ?>
	</div>
</div>
