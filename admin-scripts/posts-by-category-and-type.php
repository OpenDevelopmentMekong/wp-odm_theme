<?php

function isSiteAdmin(){
  return in_array('administrator',  wp_get_current_user()->roles);
}

function get_post_count_by_category_and_type($category,$post_type){
	$args_get_post = array(
			'post_type' => $post_type,
			'tax_query' => array(
				array(
					'taxonomy' => $category->taxonomy,
					'field' => 'name',
					'terms' => $category->name,
					'include_children' => false
				)
			)
	);
	$query_get_post = new WP_Query( $args_get_post );
	return $query_get_post->post_count;
}

if(!is_user_logged_in() && !isSiteAdmin()):

  echo('You do not have access to this functionality');

else:

	echo "Getting list of categories" . nl2br("\n");

	$categories = get_categories(array(
		'number' => 0,
	));

	?>

	<table>
		<tr>
	    <th>Taxonomic term</th>
	    <th># Topics</th>
	    <th># News articles</th>
			<th># Maps</th>
			<th># Profiles</th>
			<th># Announcements</th>
			<th># Site updates</th>
			<th># Stories</th>
  	</tr>


		<?php


		foreach($categories as $category):
			$num_topics = get_post_count_by_category_and_type($category,'topic');
			$num_news = get_post_count_by_category_and_type($category,'news-article');
			$num_maps = get_post_count_by_category_and_type($category,'map-layer');
			$num_profiles = get_post_count_by_category_and_type($category,'profiles');
			$num_announcement = get_post_count_by_category_and_type($category,'announcement');
			$num_updates = get_post_count_by_category_and_type($category,'site-update');
			$num_stories = get_post_count_by_category_and_type($category,'story');
			?>

		<tr>
	    <td><?php echo $category->name; ?></td>
			<td><?php echo $num_topics; ?></td>
			<td><?php echo $num_news; ?></td>
			<td><?php echo $num_maps; ?></td>
			<td><?php echo $num_profiles; ?></td>
			<td><?php echo $num_announcement; ?></td>
			<td><?php echo $num_updates; ?></td>
			<td><?php echo $num_stories; ?></td>
	  </tr>

		<?php
	endforeach; ?>

	</table>

	<?php

endif;

?>
