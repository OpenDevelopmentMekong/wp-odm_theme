<script>
    (function($){
        var three_per_row_highest;
        $(window).load(function(){
            /* $(".scroll-sticky-posts").mCustomScrollbar({
                 axis:"y",
                 theme:"dark",
                 autoHideScrollbar: 1
            });
            var highestBox = 0;
            $(".three_per_row").each(function(){
                if($(this).height() > highestBox)
                   highestBox = $(this).height();
            });
            three_per_row_highest = highestBox;
            $(".three_per_row").height(highestBox);

            // Set of the two colum of each posts items in News Container equal height
            for(var i = 1; i <= $(".two_per_row").length; i+=2) {
                var next = i+1;
                var highestCol = Math.max($(".two_per_row"+i).height(),$(".two_per_row"+next).height());
                $(".two_per_row"+i).height(highestCol);
                $(".two_per_row"+next).height(highestCol);
            }   */
        });
    })(jQuery);
</script>

<?php
// some parts in code were commented because the news container won't filter by country news any more
/* $options_news_tags = get_option('opendev_options');
if ($options_news_tags['news_tags']) {
    $filter_by_tags = preg_replace('/,$/', '', $options_news_tags['news_tags']);
    $filter_by_tags = explode(",", $filter_by_tags);
}else{
    $filter_by_tags  = array('Regional','Cambodia','Laos','Myanmar','Thailand','Vietnam');
} */
//foreach ($filter_by_tags as $tag_name){

    $site_name = str_replace('Open Development ', '', get_bloginfo('name'));
    $tag_name = trim($tag_name);
      if ($site_name == "Cambodia"){
          $sticky = new WP_Query(array(
          	'posts_per_page' => 6,
          	'post__in' => get_option('sticky_posts'),
          	//'tag' => $tag_name,
          	'post_type' => 'post',
            'language'=> strtolower(get_localization_language_by_language_code(qtrans_getLanguage())),
          	'post_status' => 'publish',
              'caller_get_posts' => 0,
          	'ignore_sticky_posts' => 1
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
    $number_sticky_post = 0;
    $number_two_item_in_column = 0;
    $number_item = 0; //count number of posts
  ?>
	<div class="sticky-posts scroll-sticky-posts" id="sticky-tag-<?php echo strtolower($tag_name);?>"  data-mcs-theme="dark">
        <?php if($sticky->have_posts()) :?>
            <?php while($sticky->have_posts()) : $sticky->the_post(); ?>
                <?php if (!is_sticky()) continue; ?>
                <?php $number_sticky_post++; ?>
                <?php
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
        <?php endif; ?>
        <?php
        //Query the latest posts to fill the number of maximun number of post, but ignoring sticky post
            //$number_latest_post = 6 - $sticky->found_posts;
            $number_latest_post = 20 - $number_sticky_post;

            if ($site_name == "Cambodia"){
                $latest_post = new WP_Query(array(
                	'posts_per_page' => $number_latest_post,
                	'post__not_in' => get_option('sticky_posts'),
                	//'tag' => $tag_name,
                	'post_type' => 'post',
                  'language'=> strtolower(get_localization_language_by_language_code(qtrans_getLanguage())),
                	'post_status' => 'publish',
                	'ignore_sticky_posts' => 1
                ));
            }else {
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
                   <div class="date">
					     <span class="lsf">&#xE12b;</span>
						 <?php
						 if (qtrans_getLanguage() =="kh"){
							echo convert_date_to_kh_date(get_the_time('j.M.Y'));
						 }else {
							echo get_the_time('j F Y');
						 } ?>
					</div>
					&nbsp;
					<div class="news-source">
						<?php
    					if (taxonomy_exists('news_source'))
    					$terms_news_source = get_the_terms( $post->ID, 'news_source' );

                        if ( $terms_news_source && ! is_wp_error( $terms_news_source ) ) {
                            if ($terms_news_sources){
    					        $news_sources = "";
                                echo '<span class="icon-news"></span> ';
            					foreach ($terms_news_sources as $term) {
        							$term_link = get_term_link( $term, 'news_source' );
        							if( is_wp_error( $term_link ) )
        								continue;
        							//We successfully got a link. Print it out.
        							 $news_sources .= '<a href="' . $term_link . '"><srong>' . $term->name . '</srong></a>,';
        						}
    						    echo substr($news_sources, 0, -1);
    						}
    					}else if (get_post_meta($post->ID, "rssmi_source_feed", true)){
                            echo '<span class="icon-news"></span> ';
                            $news_source_id = get_post_meta($post->ID, "rssmi_source_feed", true);
                            echo get_the_title($news_source_id);
                        }
    					?>
					</div>
              </header>
              <section class="post-content">
                <?php
                   if($post->post_excerpt) the_excerpt();
                   else echo excerpt(20, __('Keep reading', 'opendev'));
                ?>
              </section>
         </div>
         <footer class="post-actions">
              <!-- <a class="button" href="<?php the_permalink(); ?>">
              <img src="<?php // echo get_stylesheet_directory_uri()?>/img/info-icon.png"/>
              <?php // _e('Read more', 'opendev'); ?></a>-->
             <div class="share-box">
                  <div class="fb-share-button" data-href="<?php echo get_permalink( $post->ID )?>" data-send="false" data-layout="button" data-show-faces="false"></div>
                  <div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a></div>
                  <div class="g-plusone" data-width="50" data-annotation="none" data-size="tall" data-href="<?php the_permalink(); ?>" data-count="false"></div>
                  <!-- <a class="button share-button" href="<?php //echo jeo_get_share_url(array('p' => get_the_ID())); ?>">
                  <img src="<?php //echo get_stylesheet_directory_uri()?>/img/share-icon.png"/> -->
                  <?php //_e('Share', 'opendev'); ?></a>
              </div>
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
