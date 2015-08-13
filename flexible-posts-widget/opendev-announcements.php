<?php
/**
 * Flexible Posts Widget: Old Default widget template
 *
 * @since 1.0.0
 *
 * This is the ORIGINAL default template used by the plugin.
 * There is a new default template (default.php) that will be
 * used by default if no template was specified in a widget.
 */

// Block direct requests
if ( !defined('ABSPATH') )
 die('-1');

echo $before_widget;

if ( !empty($title) )
 echo $before_title . $title . $after_title;

if( $flexible_posts->have_posts() ):
?>
 <div class="announcements-list">
  <ul class="dpe-flexible-posts">
  <?php while( $flexible_posts->have_posts() ) : $flexible_posts->the_post(); global $post; ?>
   <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <a href="<?php echo the_permalink(); ?>">
     <h4><?php the_title(); ?></h4>
    </a>
    <p>
     <div class="post-widget-image">
      <?php
       if( $thumbnail == true ) {
        // If the post has a feature image, show it
        if( has_post_thumbnail() ) {
         the_post_thumbnail( $thumbsize );
        // Else if the post has a mime type that starts with "image/" then show the image directly.
        } elseif( 'image/' == substr( $post->post_mime_type, 0, 6 ) ) {
         echo wp_get_attachment_image( $post->ID, $thumbsize );
        }
       }
      ?>
     </div>
     <?php the_excerpt(); ?>
    </p>
   </li>
  <?php endwhile; ?>   
  <p style="background:#f4f4f4;text-align:center"><a href="<?php echo  get_bloginfo('url')."/". get_post_type();?>">See all</a></p>
  </ul><!-- .dpe-flexible-posts -->
 </div>
<?php else: // We have no posts ?>
 <div class="dpe-flexible-posts no-posts">
  <p><?php _e( 'No post found', 'flexible-posts-widget' ); ?></p>
 </div>
<?php
endif; // End have_posts()

echo $after_widget;
