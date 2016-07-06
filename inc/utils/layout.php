<?php

function opendev_get_thumbnail($post_id = false, $fallback = false)
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

function opendev_logo()
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
    echo '<h2 class="side-title">'.opendev_country_manager()->get_current_country().'</h2>';
    echo '</div>';
}

 ?>
