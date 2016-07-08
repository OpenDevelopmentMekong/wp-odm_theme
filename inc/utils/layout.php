<?php

function odm_get_thumbnail($post_id = false, $fallback = false)
{
    global $post;
    $post_id = $post_id ? $post_id : $post->ID;
    $thumb_src = get_the_post_thumbnail( $post_id, 'post-thumbnail');
    if ($thumb_src) {
      return $thumb_src;
    }

    if ($fallback):
      return '<img class="attachment-post-thumbnail size-post-thumbnail wp-post-image" src="' . get_stylesheet_directory_uri() .'/img/thumbnail.png"></img>';
    endif;

    return null;
}

function odm_logo()
{
  ?>
  <h1>
   <a href="<?php echo home_url('/');?>" title="<?php bloginfo('name');?>">
    <span class="icon-od-logo"></span>
    Op<sup>e</sup>nDevelopment
   </a>
  </h1>
  <?php
  echo '<div class="ms-dropdown-title">';
    echo '<h2 class="side-title">'.ucfirst(odm_country_manager()->get_current_country()).'</h2>';
    echo '</div>';
}

/**
 * Load a component into a template while supplying data.
 *
 * @param string $slug The slug name for the generic template.
 * @param array $params An associated array of data that will be extracted into the templates scope
 * @param bool $output Whether to output component or return as string.
 * @return string
 */
function odm_get_template($slug, array $params = array(), $output = true) {
    if(!$output) ob_start();
    if (!$template_file = locate_template("inc/templates/{$slug}.php", false, false)) {
      trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $slug), E_USER_ERROR);
    }
    extract($params, EXTR_SKIP);
    require($template_file);
    if(!$output) return ob_get_clean();
}


 ?>
