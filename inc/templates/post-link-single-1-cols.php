<?php
	$post = isset($params["post"]) ? $params["post"] : null;
?>

<div class="sixteen columns">
	<div class="post-list-item single_result_container">
		<p>
			<h2>
				<?php
					$localized_title = apply_filters('translate_text', $post->post_title, odm_language_manager()->get_current_language());
				 ?>
				<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $localized_title; ?>">
					<?php echo $localized_title; ?>
				</a>
			</h2>
		</p>
	</div>
</div>
