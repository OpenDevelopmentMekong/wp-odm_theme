<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : false;
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : false;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	?>

<div class="four columns post-grid-item post-grid-item-caption-bolow <?php echo odm_country_manager()->get_current_country(); ?>-bgdarkcolor">
	<div class="grid-content-wrapper">
		<?php if ($show_meta): ?>
		<div class="meta">
				<?php echo_post_meta($post,array('date','sources','categories')); ?>
		</div>
		<?php endif; ?>
		<?php
			$thumb_src = odm_get_thumbnail($post->ID,  false, array( 300, 'auto'));
			if (isset($thumb_src)):
				echo $thumb_src;
			endif;
		?>

		<?php
		if (isset($post->title_and_link) && $post->title_and_link != "") :
			echo $post->title_and_link;
		else:
			?>
			<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
			<?php
		endif;
		?>
		<?php /*
		if (isset($post->description) && $post->description != "") :?>
			<div class="post-grid-item-list">
					<?php echo $post->description;   ?>
			</div>
			<?php
		endif;*/
		?>
	</div>
</div>
