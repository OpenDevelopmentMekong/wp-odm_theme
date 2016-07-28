<?php
	$post = $params["post"];
	$show_meta = $params["show_meta"];
	$show_post_type = $params["show_post_type"];
	?>

<div class="four columns post-grid-item">
	<div class="grid-content-wrapper">
		<?php
		if ($show_post_type):
			$post_type_name = get_post_type($post->ID);
			$post_type = get_post_type_object($post_type_name);
			?>
			<a class="item-post-type" href="<?php echo $post_type->rewrite['slug'] ?>"><?php echo $post_type->labels->name ?></a>
			<?php
		endif; ?>

		<?php
		if (isset($post->title_and_link) && $post->title_and_link != "") :
			echo $post->title_and_link;
		else: ?>
			<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
			<?php
		endif; ?>
 
		<?php if ($show_meta): ?>
			<div class="meta">
					<?php echo_post_meta($post,array('date','sources','categories')); ?>
			</div>
		<?php endif; ?>

		<?php $thumb_src = odm_get_thumbnail($post->ID);
		if (isset($thumb_src)):
			echo $thumb_src;
		endif; ?>
	</div>
</div>
