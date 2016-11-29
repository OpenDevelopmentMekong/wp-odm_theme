<?php

function isSiteAdmin(){
  return in_array('administrator',  wp_get_current_user()->roles);
}

$keywords_by_metadata = array(
	"odm_company" => array(
		"prefix" => get_site_url() . "/related/",
		"keywords" => array(
      array("gtla-cambodia","GTLA Cambodia",),
      array("grant-thornton-law-and-associates","Grant thornton",)
      )
		)
);

$post_type = 'post';

if(!is_user_logged_in() && !isSiteAdmin()):

  echo('You do not have access to this functionality');

else:

	echo "Getting posts by post type: " . $post_type . nl2br("\n");

  $args = array(
    'post_type' => $post_type
  );

  $posts = get_posts($args);
  echo(count($posts) . "posts found with post type:" . $post_type . nl2br("\n"));

  foreach ( $posts as $post):

    echo("Analizing post with title:" . $post->post_title . nl2br("\n"));

  	foreach ( $keywords_by_metadata as $metadata_field => $config):

  		foreach ( $config["keywords"] as $keyword):

  			echo("Looking for keyword:" . $keyword[1] . nl2br("\n"));

			  $keyword_found = false;

        if (strstr($post->post_title,$keyword[1])):
          $keyword_found = true;
        endif;

        if (strstr($post->post_content,$keyword[1])):
          $keyword_found = true;
        endif;

        if ($keyword_found):

          echo("Found keyword on post title or content" . nl2br("\n"));

          $related_content = get_post_meta($post->ID,'related_content',false);
  				$metadata_found = false;
  				$related_content_object = array();
  				$related_content = empty($related_content) ? null : $related_content[0];

  				if ($related_content != null):
  					echo("related_content meta field found, checking for values..." . nl2br("\n"));
  					print_r($related_content);

  					$related_content_object = json_decode($related_content);

  					foreach ( $related_content_object as $related_content):
  						if ($related_content->type === $metadata_field
  								&& $related_content->url === $config["prefix"] . $keyword[0]):
  							$metadata_found = true;
  						endif;
  					endforeach;
  				endif;

  				if ($metadata_found === false):
  					echo("Value not found. Setting related_content metadata field..." . nl2br("\n"));
  					array_push($related_content_object, array(
  						"type" => $metadata_field,
  						"url" => $config["prefix"] . $keyword[0]
  					));

  					$related_content_json = json_encode($related_content_object);
  					update_post_meta($post->ID,'related_content',$related_content_json,$related_content);
  					print_r($related_content_json);
  				endif;

  				echo("Looking for " . $metadata_field . " metadata field" . nl2br("\n"));

  				$metadata = get_post_meta($post->ID,$metadata_field,false);
  				$value_available = false;

  				if (!empty($metadata)):

  					echo($metadata_field . " meta field found, checking for values..." . nl2br("\n"));
  					print_r($metadata);

  					foreach ($metadata as $metadata_value):
  						if ($metadata_value == $keyword[0]):
  							$value_available = true;
  						endif;
  					endforeach;

  				endif;

  				if ($value_available === false):
  					echo("Value not found. Setting " . $metadata_field . " metadata field" . nl2br("\n"));
  					add_post_meta($post->ID, $metadata_field, $keyword[0], false );
  				endif;

        endif;

			endforeach;

		endforeach;

    echo(nl2br("\n\n"));

	endforeach;

  wp_reset_postdata();

endif;

?>
