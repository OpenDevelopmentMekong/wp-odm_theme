<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : true;
	$meta_fields = isset($params["meta_fields"]) ? $params["meta_fields"] : array('date');
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : true;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	$order = isset($params["order"]) ? $params["order"] : "metadata_created";
	$extra_classes = isset($params["extra_classes"]) ? $params["extra_classes"] : null;
	?>

<div class="four columns post-grid-item post-grid-item-caption-bolow <?php echo odm_country_manager()->get_current_country(); ?>-bgdarkcolor<?php if (isset($extra_classes)): echo " ". $extra_classes; endif; ?>)">
	<div class="grid-content-wrapper">
		<?php if ($show_meta): ?>
		<div class="meta">				
				<?php
		      $link = isset($post->dataset_link) ? $post->dataset_link : get_permalink($post->ID);
					$title = apply_filters('translate_text', $post->post_title, odm_language_manager()->get_current_language()); ?>
					<a class="item-title" href="<?php echo $link; ?>" title="<?php echo $title; ?>">
						<?php
							if ($show_post_type):
								$post_type_name = get_post_type($post->ID); ?>
								<i class="<?php echo get_post_type_icon_class($post_type_name); ?>"></i>
						<?php
							endif; ?>
						<?php echo $title; ?>
					</a>
					<?php
				if (isset($post->description) && $post->description != "") :?>
					<div class="post-grid-item-list">
							<?php echo $post->description;   ?>
					</div>
					<?php
				endif;
				?>
				<?php
					if ($show_meta):
						echo_post_meta($post,$meta_fields,$order,null,null);
				 	endif; ?>
		</div>
		<?php endif; ?>
		<?php
			$thumb_src = odm_get_thumbnail($post->ID,  false, array( 300, 'auto'));
			if (isset($thumb_src)):
				echo $thumb_src;
			endif;
		?>
	</div>
</div>
