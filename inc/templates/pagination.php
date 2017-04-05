<div class="navigation">
	<?php
		$pagination = isset($params["paging_arg"]) ? $params["paging_arg"] : array();
		global $wp_query, $page, $paged;

		$big = 999999999; // need an unlikely integer
		if(!empty($pagination)){
			echo "<span class='page-numbers pagexofy'>".__("Page", "odm"). " " . max(1, $pagination['current']).__(" of", "odm"). " " . $pagination['total_pages']."</span> ";
			echo paginate_links(array(
					'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
					'format' => '?paged=%#%',
					'current' => max(1, $pagination['current']),
					'total' => $pagination['total_pages']
			));
		}else{
			echo "<span class='page-numbers pagexofy'>".__("Page", "odm"). " " . max(1, $paged).__(" of", "odm"). " " . $wp_query->max_num_pages."</span> ";
			echo paginate_links(array(
					'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
					'format' => '?paged=%#%',
					'current' => max(1, $paged),
					'total' => $wp_query->max_num_pages
			));
		}
	?>
</div>
