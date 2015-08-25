<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
<?php
if (function_exists(qtrans_getLanguage)){
    if (qtrans_getLanguage() <> "en") $lang = "_". qtrans_getLanguage(); else $lang = "";
    //Get all languages that is available
    $languages = qtrans_getSortedLanguages();
    $local_language = $languages[1];
    $local_lang =  "_".$languages[1];
}else $lang ="";
?>
	<article id="content" class="single-post">
			<div class="container">
				<div class="eight columns">
		            <header class="single-post-header" class="clearfix">
    					<h1><?php the_title(); ?></h1>
    					<div class="date">
    							<span class="lsf">&#xE12b;</span> <?php the_date(); ?>
    					</div>
    					&nbsp;
    					<div class="news-source">
							<?php
        					if (taxonomy_exists('news_source')){
        					    $terms_news_sources = get_the_terms($post->ID,'news_source');
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

    					<div class="categories">
    						  <span class="lsf">&#9776;</span> <?php echo __( 'Filed under:', 'jeo' ); ?> <?php the_category(); ?>
    					</div>
		            </header>

					<?php get_template_part('section', 'related-datasets'); ?>
            		<section class="content section-content">
    					<?php
    					if(jeo_has_marker_location()) {
    						?>
    						<section id="featured-media" class="row">
    							<div style="height:350px;">
    								<?php jeo_map(); ?>
    							</div>
    						</section>
    						<?php
    					}
    					?>
    					<?php
                        if (function_exists(qtrans_getLanguage)){
                            if ((qtrans_getLanguage() == "en") && (has_term('english-translated','language'))){ ?>
                                <p class="translated-by-odc"><strong><?php _e("Summary translated by ODC Team"); ?></strong></p>
                            <?php } ?>
                            <?php if ((qtrans_getLanguage() == $local_language) && (has_term('khmer-translated','language'))){ ?>
                                <p class="translated-by-odc"><strong><?php _e("Summary translated by ODC Team"); ?></strong></p>
                            <?php } ?>
                        <?php } ?>
    					<?php the_content(); ?>

                        <!-- News Source: author and link -->
                        <?php
                        if (function_exists(qtrans_getLanguage)){
                            if (qtrans_getLanguage() <> "en") $lang = "_". qtrans_getLanguage(); else $lang = "";
                        }
                        //Get author
                        if (get('author')=="" && get('author'.$lang)==""){
                            echo "";
                        }else{
                            $news_source_info = '<span class="lsf">&#xE041;</span> ';
                            if (get('author'.$lang)!= "") $news_source_info .= get('author'.$lang)."<br />" ; else $news_source_info .= get('author')."<br />"; ?>
                  <?php }
                        //Get url
                        if (get('article_link')=="" && get('article_link'.$lang)==""){
                            echo "";
                        }else{
                            if (get('article_link'.$lang)!= "") $source = get('article_link'.$lang); else $source = get('article_link');
                			if($source !=""){
            					if(substr($source, 0, 7)!= "http://") {
                                    $news_source_info .= '<a href="http://'.$source.'" target="_blank">http://'.$source.'</a>';
                                }else{
                                    $news_source_info .= '<a href="'.$source.'" target="_blank">'.$source.'</a>';
                                }
                		   }
                        }
                        if ($news_source_info!="") echo "<p>".$news_source_info."</p>";
               	?>
    					<div class="post-tags">
    						  <span class="lsf">&#xE128;</span> <?php echo __( 'Tags:', 'opendev' ); ?> <?php the_tags('',''); ?>
    					</div>
    					<?php
    					wp_link_pages( array(
    						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
    						'after'       => '</div>',
    						'link_before' => '<span>',
    						'link_after'  => '</span>',
    					) );
    					?>

    					<?php //comments_template(); ?>
            		</section>
				</div> <!-- eight -->
				<div class="three columns offset-by-one">
            					<aside id="sidebar">
            						<ul class="widgets">
            							<li class="widget share-widget">
            								<div class="share clearfix">
            									<ul>
            										<li>
            											<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-show-faces="false" data-send="false"></div>
            										</li>
            										<li>
            											<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a>
            										</li>
            										<li>
            											<div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div>
            										</li>
            									</ul>
            								</div>
            							</li>
            							<?php dynamic_sidebar('post'); ?>
            						</ul>
            					</aside>
				</div>    <!-- three -->
			</div><!-- container -->

	</article>

<?php endif; ?>

<?php get_footer(); ?>
