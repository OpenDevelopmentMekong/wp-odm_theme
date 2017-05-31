<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
?>

<div class="sixteen columns">
	<div class="post-list-item single_result_container">
		<p>
			<h3>
				<?php
					$localized_title = apply_filters('translate_text', $post->post_title, odm_language_manager()->get_current_language());
				 ?>
				<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $localized_title; ?>">
					<?php
						if ($show_post_type):
							$post_type_name = get_post_type($post->ID); ?>
							<i class="<?php echo get_post_type_icon_class($post_type_name); ?>"></i>
						<?php
						endif; ?>
					<?php echo $localized_title; ?>
				</a>
			</h3>
		</p>
	</div>
</div>
