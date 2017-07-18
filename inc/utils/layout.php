<?php

function odm_get_thumbnail($post_id = false, $fallback = false, $size = 'thumbnail', $view_enlarge = false)
{
    global $post;
    $post_id = $post_id ? $post_id : $post->ID;
    $thumb_src = get_the_post_thumbnail( $post_id, $size);
    if ($thumb_src) {
      if($view_enlarge):
				$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'full');
				$thumbnail = '<a class="view-enlarge" href="#" title="' . __("View large image", "odm") . '" >'.get_the_post_thumbnail( $post_id, $size	). '</a>';
        $thumbnail .= '<div class="popup-overlay hide"><div class="toggle-close-icon"><i class="enlarge-close fa fa-times-circle '. odm_country_manager()->get_current_country().'-color" aria-hidden="true"></i></div>
                      <img class="popup-enlarge" src="'. $full_image_url[0] .'" title="'.get_the_title($post_id).'" /></div>';
      else:
        $thumbnail = get_the_post_thumbnail( $post_id, $size);
      endif;
      return $thumbnail;
    }

    if ($fallback):
      return '<img class="attachment-post-thumbnail size-post-thumbnail wp-post-image" src="' . get_stylesheet_directory_uri() .'/img/watermark.png" />';
    endif;

    return null;
}

function od_logo_icon($country_site ="")
{
	if($country_site == ""):
		$country_site = odm_country_manager()->get_current_country();
	endif;
	include_once(get_stylesheet_directory() ."/img/od-logo.svg");
  ?>
    <span id="icon-od-logo">
			<svg class="svg-od-logo <?php echo strtolower($country_site); ?>-logo"><use xlink:href="#icon-od-logo"></use></svg>
		</span>
  <?php
}

function odm_logo()
{
  ?>
  <div id="od-logo">
		<?php od_logo_icon(); ?>
		<?php
        if(odm_language_manager()->get_current_language() == "km"):
          echo '<img src="'. get_stylesheet_directory_uri() .'/img/odc-khmer-name.png" />';
        else:
          echo '<h1>Op<sup>e</sup>nDevelopment</h1>';
          echo '<div>';
          echo '<h2 class="side-title">'.ucfirst(odm_country_manager()->get_current_country()).'</h2>';
          echo '</div>';
        endif;
		?>
  </div>
  <?php
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

function should_open_row($layout_type,$index){
  if ($layout_type == 'list-2-cols' && $index%2 == 1){
    return true;
  }else if ($layout_type == 'list-4-cols' && $index%4 == 1){
    return true;
  }else if ($layout_type == 'list-1-cols'){
    return true;
  }else if ($layout_type == 'blog-layout-2-cols' && ($index-1)%2 == 1){
    return true;
  }
  return false;
}

function should_close_row($layout_type,$index){
  if ($layout_type == 'list-2-cols' && $index >= 2 && $index%2 == 0){
    return true;
  }else if ($layout_type == 'list-4-cols' && $index >=4 && $index%4 == 0){
    return true;
  }else if ($layout_type == 'list-1-cols'){
    return true;
  }
  return false;
}
 ?>
