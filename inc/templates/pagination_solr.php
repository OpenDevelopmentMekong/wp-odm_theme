<div class="navigation">
	<?php
		$pagination = isset($params["paging_arg"]) ? $params["paging_arg"] : array();
		global $wp_query, $page, $paged;

		$big = 999999999; // need an unlikely integer
		echo "<span class='page-numbers pagexofy'>".__("Page", "odm"). " " . max(1, $params["current_page"]).__(" of", "odm"). " " . $params["total_pages"] ."</span> ";
		echo paginate_links(array(
				'format' => '?page=%#%',
				'current' => max(1, $params["current_page"]),
				'total' => $params["total_pages"]
		));

	?>
</div>
