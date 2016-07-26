<div class="navigation">
	<?php
		global $wp_query, $page, $paged;

		$big = 999999999; // need an unlikely integer


		echo paginate_links(array(
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => '?paged=%#%',
				'current' => max(1, $paged),
				'prev_next' => ture,
				'total' => $wp_query->max_num_pages,
				//'prev_text' => '',
				'before_page_number' => "&nbsp;",
				'after_page_number' => "&nbsp;",
		));
	?>
</div>
