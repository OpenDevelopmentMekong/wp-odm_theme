<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : true;
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : true;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	$order = isset($params["order"]) ? $params["order"] : 'created';
	?>

<div class="eight columns post-grid-item">
	<div class="grid-content-wrapper">
		<?php
			if ($show_post_type):
				$post_type_name = get_post_type($post->ID);
				$post_type = get_post_type_object($post_type_name);
				?>
		<a class="item-post-type" href="<?php echo $post_type->rewrite['slug'] ?>"><?php echo $post_type->labels->name ?></a>
		<?php
			endif; ?>
		<div class="meta">
			<?php
	      $link = isset($post->dataset_link) ? $post->dataset_link : get_permalink($post->ID);
				$title = apply_filters('translate_text', $post->post_title, odm_language_manager()->get_current_language()); ?>
			<a class="item-title" href="<?php echo $link; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			<?php
				if ($show_meta):
					echo_post_meta($post,array('date','sources','categories'),$order);
			 	endif;
			?>
		</div>
		<?php
			if ($show_thumbnail):
				echo odm_get_thumbnail($post->ID, true, array( 300, 'auto'));  
			endif; ?>
	</div>
</div>
