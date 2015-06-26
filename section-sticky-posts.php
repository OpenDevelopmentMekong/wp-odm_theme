<?php
$options_news_tags = get_option('opendev_options');
if ($options_news_tags['news_tags']) {
    $filter_by_tags = preg_replace('/,$/', '', $options_news_tags['news_tags']);
    $filter_by_tags = explode(",", $filter_by_tags);
}else{
    $filter_by_tags  = array('Regional','Cambodia','Laos','Myanmar','Thailand','Vietnam');
}
foreach ($filter_by_tags as $tag_name){
    $tag_name = trim($tag_name);
    $sticky = new WP_Query(array(
    	'posts_per_page' => 6,
    	'post__in' => get_option('sticky_posts'),
    	'tag' => $tag_name,
    	'post_type' => 'post',
    	'post_status' => 'publish',
        'caller_get_posts' => 0,
    	'ignore_sticky_posts' => 1
    ));
    $number_sticky_post = 0;
  ?>

	<div class="sticky-posts" id="sticky-tag-<?php echo strtolower($tag_name);?>">
        <?php if($sticky->have_posts()) :?>
            <?php while($sticky->have_posts()) : $sticky->the_post(); ?>
                <?php if (!is_sticky()) continue; ?>
                <?php $number_sticky_post++; ?>
                <div class="sticky-item" data-postid="<?php the_ID(); ?>">
                    <article id="sticky-post-<?php the_ID(); ?>" <?php post_class(); ?>>
                     <div class="post-area">
                          <header class="post-header">
                                <?php if(has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <?php the_post_thumbnail(array(100, 100)); ?>
                                    </div>
                               <?php endif; ?>
                               <a href="<?php the_permalink(); ?>"><h3><?php echo the_title(); ?></h3></a>
                               <p class="date"><?php echo get_the_date(); ?></p>
                          </header>
                          <section class="post-content">

                               <?php the_excerpt(); ?>
                          </section>
                     </div>
                     <footer class="post-actions">
                          <a class="button" href="<?php the_permalink(); ?>"><?php _e('Read more', 'opendev'); ?></a>
                          <a class="button share-button" href="<?php echo jeo_get_share_url(array('p' => get_the_ID())); ?>"><?php _e('Share', 'opendev'); ?></a>
                     </footer>
                    </article>
               </div>
           <?php endwhile; ?>
        <?php endif; ?>
        <?php
        //Query the latest posts to fill the number of maximun number of post, but ignoring sticky post
            //$number_latest_post = 6 - $sticky->found_posts;
            $number_latest_post = 6 - $number_sticky_post;
            $latest_post = new WP_Query(array(
            	'posts_per_page' => $number_latest_post,
            	'post__not_in' => get_option('sticky_posts'),
            	'tag' => $tag_name,
            	'post_type' => 'post',
            	'post_status' => 'publish',
            	'ignore_sticky_posts' => 1
            ));
            ?>
		<!-- List lastest posts that is not sticky -->
		<?php  if($latest_post->have_posts()) :?>
           <?php while($latest_post->have_posts()) : $latest_post->the_post(); ?>
                <div class="sticky-item" data-postid="<?php the_ID(); ?>">
                    <article id="sticky-post-<?php the_ID(); ?>" <?php post_class(); ?>>
                     <div class="post-area">
                          <header class="post-header">
                                <?php if(has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <?php the_post_thumbnail(array(100, 100)); ?>
                                    </div>
                               <?php endif; ?>
                               <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                               <p class="date"><?php echo get_the_date(); ?></p>
                          </header>
                          <section class="post-content">

                               <?php the_excerpt(); ?>
                          </section>
                     </div>
                     <footer class="post-actions">
                          <a class="button" href="<?php the_permalink(); ?>"><?php _e('Read more', 'opendev'); ?></a>
                          <a class="button share-button" href="<?php echo jeo_get_share_url(array('p' => get_the_ID())); ?>"><?php _e('Share', 'opendev'); ?></a>
                     </footer>
                    </article>
               </div>
           <?php endwhile; ?>
       <?php endif; ?>
       </div> <!-- sticky-posts -->
<?php } //filter_by_tag  ?> 