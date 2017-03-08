<?php

function isSiteAdmin(){
  return in_array('administrator',  wp_get_current_user()->roles);
}

$post_types_to_index = array(
	'news-article','topic','dashboard','dataviz','profiles','tabular','announcement','site-update','story','map-layer'
);

if(!is_user_logged_in() && !isSiteAdmin()):

  echo('You do not have access to this functionality');

else:

	echo "Clearing WP index" . nl2br("\n");

  Odm_Solr_WP_Manager()->clear_index();

	foreach ( $post_types_to_index as $post_type):

			$args = array(
		    'post_type' => $post_type,
				'posts_per_page' => -1
			);

			$posts = get_posts($args);

			echo(count($posts) . "posts found with post type:" . $post_type . nl2br("\n"));

			foreach ( $posts as $post):

				echo("Indexing post with title:" . $post->post_title . nl2br("\n"));
				Odm_Solr_WP_Manager()->index_post($post);

			endforeach;

			wp_reset_postdata();

	endforeach;

endif;

?>
