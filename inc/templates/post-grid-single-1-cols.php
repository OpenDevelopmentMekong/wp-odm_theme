<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : true;
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : true;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	$order = isset($params["order"]) ? $params["order"] : 'created';
	?>

<div class="sixteen columns post-grid-item">
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
      $link = isset($post->dataset_link) ? $post->dataset_link : get_permalink($post->ID); ?>
		<a class="item-title" href="<?php echo $link; ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
		<?php if ($show_meta): ?>
		<div class="meta">
				<?php echo_post_meta($post,array('date','sources','categories'),$order); ?>
		</div>
		<?php endif; ?>
		<?php
			if ($show_thumbnail):
				$thumb_src = odm_get_thumbnail($post->ID, false, array( 300, 'auto'));
				if (isset($thumb_src)):
					echo $thumb_src;
				endif;
			endif; ?>
	</div>
</div>
