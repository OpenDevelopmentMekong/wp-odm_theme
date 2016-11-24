<?php

function isSiteAdmin(){
  return in_array('administrator',  wp_get_current_user()->roles);
}

$tags_by_metadata = array(
	"odm_company" => array(
		"prefix" => get_site_url() . "/related/",
		"tags" => array("gtla-cambodia", "grant-thornton-law-and-associates")
		)
);

if(!is_user_logged_in() && !isSiteAdmin()):

  echo('You do not have access');

else:

	echo "Getting posts by tags\n";

	foreach ( $tags_by_metadata as $metadata_field => $config):

		foreach ( $config["tags"] as $value):

			echo("Looking for posts with tag:" . $value . "\n");

			$args = array(
		    'tag_id' => $value
			);

			$posts = get_posts($args);
			echo(count($posts) . "posts found with tag:" . $value . "\n");

			foreach ( $posts as $post):

				echo("Analizing post with title:" . $post->post_title . "\n");

				$related_content = get_post_meta($post->ID,'related_content',false);
				if (!empty($related_content)):
					echo("related_content meta field found\n");
					print_r($related_content);
					echo("adapting...\n");
					$related_content_object = json_decode($related_content[0]);
					$metadata_found = false;

					foreach ( $related_content_object as $related_content):
						if ($related_content["type"] === $metadata_field
								&& $related_content["url"] === $config["prefix"] . $value):
							$metadata_found = true;
						endif;

						if ($metadata_found == true):
							echo("this metadata field is already on the related_content object...\n");
						else:
							echo("Adding metadata field to related_content object...\n");
							array_push($related_content_object, array(
								"type" => $metadata_field,
								"url" => $config["prefix"] . $value
							));
						endif;
					endforeach;

					echo("Adapted related_content object...\n");
					$related_content_json = json_encode($related_content_object);
					update_post_meta($post->ID,'related_content',$related_content_json,$related_content[0]);
					print_r($related_content_json);
				else:
					echo("Setting related_content\n");

				endif;

				$metadata = get_post_meta($post->ID,$metadata_field,false);
				if (!empty($related_content)):
					print_r($related_content);
				else:
					add_post_meta($post->ID, 'odm_company', $value, false );
				endif;

			endforeach;

			wp_reset_postdata();

		endforeach;
	endforeach;

endif;

?>
