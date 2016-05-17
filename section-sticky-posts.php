<?php
    $filter_by_lang = strtolower(get_localization_language_by_language_code(qtrans_getLanguage()));
    $tag_name = trim($tag_name);
      if (SITE_NAME == "Cambodia"){
          $sticky = new WP_Query(array(
          	'posts_per_page' => 6,
          	'post__in' => get_option('sticky_posts'),
          	//'tag' => $tag_name,
          	'post_type' => 'post',
            'language'=> $filter_by_lang,
          	'post_status' => 'publish',
            'caller_get_posts' => 0,
          	'ignore_sticky_posts' => 1,
            'tax_query' => array(
                'taxonomy' => 'language',
                'field' => 'slug',
                'terms' =>  $filter_by_lang
            )
          ));
      }else {
          $sticky = new WP_Query(array(
            'posts_per_page' => 6,
            'post__in' => get_option('sticky_posts'),
            //'tag' => $tag_name,
            'post_type' => 'post',
            'post_status' => 'publish',
            'caller_get_posts' => 0,
            'ignore_sticky_posts' => 1
          ));

      }

      //print_r($sticky);
    $number_sticky_post = 0;
    $number_two_item_in_column = 0;
    $number_item = 0; //count number of posts
  ?>
	<div class="sticky-posts scroll-sticky-posts" id="sticky-tag-<?php echo strtolower($tag_name);?>"  data-mcs-theme="dark">
        <?php if($sticky->have_posts()) :?>
            <?php while($sticky->have_posts()) : $sticky->the_post(); ?>
                <?php if (!is_sticky()) continue; ?>
                <?php
                    $number_sticky_post++;
                    $number_item++;
                    if($number_item > 1 && $number_item <= 4 ){
                             $group_sticky_item = " three_per_row";
                      }else if ($number_item > 4){
                              $number_two_item_in_column = $number_two_item_in_column +1;
                              $group_sticky_item = " two_per_row";
                              $group_sticky_item_index = " two_per_row".$number_two_item_in_column;
                      }else {
                             $group_sticky_item = "";
                      }
                ?>
                <div class="sticky-item<?php echo $group_sticky_item . $group_sticky_item_index; ?>" id="<?php the_ID(); ?>" data-postid="<?php the_ID(); ?>">
                     <?php show_queried_posts(); ?>
               </div>
           <?php endwhile; ?>
        <?php endif;
              wp_reset_query();
        ?>
        <?php
        //Query the latest posts to fill the number of maximun number of post, but ignoring sticky post
            //$number_latest_post = 6 - $sticky->found_posts;
            if (SITE_NAME == "Cambodia"){
                $get_latest_post = 100 - $number_sticky_post;
                $number_latest_post = 20 - $number_sticky_post;
                $latest_post = new WP_Query(array(
                	'posts_per_page' => $get_latest_post,
                	'post__not_in' => get_option('sticky_posts'),
                	'tag' => $tag_name,
                	'post_type' => 'post',
                	'post_status' => 'publish',
					        //'language'=> $filter_by_lang,
                	'ignore_sticky_posts' => 1,
                  'caller_get_posts'=> 1,
                  'tax_query' => array(   // Note: using tax_query will get all post from any post type, even the post type is set
                                    array(
                                        'taxonomy' => 'language',
                                        'field' => 'slug',
                                        'terms' => $filter_by_lang
                                    )
                  )
                ));
            }else {
                $number_latest_post = 20 - $number_sticky_post;
                $latest_post = new WP_Query(array(
                	'posts_per_page' => $number_latest_post,
                	'post__not_in' => get_option('sticky_posts'),
                	//'tag' => $tag_name,
                	'post_type' => 'post',
                	'post_status' => 'publish',
                	'ignore_sticky_posts' => 1
                ));
            }


            ?>
		<!-- List lastest posts that is not sticky -->
		<?php  if($latest_post->have_posts()) :?>
           <?php while($latest_post->have_posts()) : $latest_post->the_post(); ?>
               <?php
                 if ($number_item < $number_latest_post) {
                   if (get_post_type() == 'post') {
                      $number_item++;
                      if($number_item > 1 && $number_item <= 4 ){
                               $group_sticky_item = " three_per_row";
                        }else if ($number_item > 4){
                                $number_two_item_in_column = $number_two_item_in_column +1;
                                $group_sticky_item = " two_per_row";
                                $group_sticky_item_index = " two_per_row".$number_two_item_in_column;
                        }else {
                               $group_sticky_item = "";
                        }
                      ?>
                      <div class="sticky-item<?php echo $group_sticky_item . $group_sticky_item_index; ?>" id="<?php the_ID(); ?>"  data-postid="<?php the_ID(); ?>">
                          <?php show_queried_posts(); ?>
                     </div>
                 <?php
                    } //if (get_post_type() == 'post')
                  }else {
                      break;
                  }
               ?>
           <?php endwhile; ?>
       <?php endif; ?>
       </div> <!-- sticky-posts -->
<?php //} //filter_by_tag   ?>

<?php function show_queried_posts(){    ?>
        <article id="sticky-post-<?php the_ID(); ?>" <?php post_class(); ?>>
         <div class="post-area">
              <header class="post-header">
                    <?php if(has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail(array(300, 250)); ?>
                            </a>
                        </div>
                   <?php endif; ?>
                   <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                   <!--show date and source-->
                   <?php show_date_and_source_of_the_post(); ?>
              </header>
              <section class="post-content">
                <?php
                   if($post->post_excerpt){
                     the_excerpt();
                   }
                   else{
                     echo excerpt(20, __('Keep reading', 'opendev'));
                   }
                ?>
              </section>
         </div>
         <footer class="post-actions">
              <!-- <a class="button" href="<?php the_permalink(); ?>">
              <img src="<?php // echo get_stylesheet_directory_uri()?>/img/info-icon.png"/>
              <?php // _e('Read more', 'opendev'); ?></a>-->
         </footer>
        </article>
<?php
    }
?>
<script>
(function($) {
    $('.sticky-posts .sticky-item:first').addClass('sticky-posts-active');
})(jQuery);
</script>
