<?php
	$post = $params["post"];
	$show_meta = $params["show_meta"];
	$show_excerpt = $params["show_excerpt"];
	$show_post_type = $params["show_post_type"];
	$show_author_and_url_source = $params["show_author_and_url_source"];
	$show_summary_translated_by_odc_team = $params["show_summary_translated_by_odc_team"];
?>

<div class="sixteen columns post-list-item">

	<p>
		<?php
			if ($show_post_type):
				$post_type_name = get_post_type($post->ID);
				$post_type = get_post_type_object($post_type_name);
		?>

		<a class="item-post-type" href="<?php echo $post_type->rewrite['slug'] ?>">
			<?php echo $post_type->labels->name ?>
		</a>
		<?php endif; ?>

		<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">
			<?php echo $post->post_title; ?>
		</a>
	</p>

	<div class="item-content">

			<?php if ($show_meta): ?>
				<?php echo_post_meta($post,array('date','sources')); ?>
			<?php endif; ?>

			<?php if ($show_summary_translated_by_odc_team): ?>
				<?php if (function_exists('qtranxf_getLanguage')):

					if ((odm_language_manager()->get_current_language() == 'en') && (has_term('english-translated', 'language'))): ?>
						<p class="translated-by-odc">
							<strong><?php _e('Summary translated by ODC Team');?></strong>
						</p>
					<?php endif; ?>

					<?php if (isset($local_language) && (odm_language_manager()->get_current_language() == $local_language) && (has_term('khmer-translated', 'language'))):?>
						<p class="translated-by-odc">
							<strong><?php _e('Summary translated by ODC Team');?></strong>
						</p>
					<?php endif; ?>

				<?php endif; ?>
			<?php endif; ?>

			<?php if ($show_excerpt):
				$thumb_src = odm_get_thumbnail($post->ID,false);

				if (isset($thumb_src)):
					echo $thumb_src;
				endif; ?>

				<div class="post-excerpt">
					<?php echo odm_excerpt($post); ?>
				</div>
			<?php endif; ?>

			<?php if ($show_meta):
				odm_echo_extras();
			endif; ?>

	</div>
</div>
