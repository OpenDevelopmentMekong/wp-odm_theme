<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
<?php
if (function_exists(qtranxf_getLanguage)){
    if (qtranxf_getLanguage() <> "en") $lang = "_". qtranxf_getLanguage(); else $lang = "";
    //Get all languages that is available
    $languages = qtranxf_getSortedLanguages();
    $local_language = $languages[1];
    $local_lang =  "_".$languages[1];
}else $lang ="";
?>
	<article id="content" class="single-post">
			<div class="container">
				<div class="eight columns">
		        <header class="single-post-header" class="clearfix">
    					<h1><?php the_title(); ?></h1>
              <!--show date-->
              <?php show_date_and_source_of_the_post(); ?>

    					<?php if (has_category()){ ?>
        					<div class="categories">
        						<span class="lsf">&#9776;</span> <?php echo __( 'Filed under:', 'jeo' ); ?>
                                <?php the_category();  ?>
        					</div>
              <?php } ?>
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
    					<div class="announcements-singlepage">
            					<?php
                                //Get Cover image
                                if (get('cover')=="" && get('cover'.$local_lang)==""){
                                    echo "";
                                }else{
                                   $img_attr = array("h" => 600, "w" => 800, "zc" => 1, "q" =>100);
                                echo '<div class="announcements-singlepage-img">';
                                    if(get('cover'.$lang)!=""){
                                        $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover'.$lang,1,1,0).'">';
                                        $large_img = get_image('cover'.$lang,1,1,0,null,$img_attr);
                                    }else{
                                        if(get('cover')!=""){
                                            $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover',1,1,0).'">';
                                            $large_img = get_image('cover',1,1,0,null,$img_attr);
                                        }
                                        else {
                                            $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover'.$local_lang,1,1,0).'">';                                        $large_img = get_image('cover'.$local_lang,1,1,0,null,$img_attr);
                                        }
                                    }
                                    echo '<a target="_blank" href="'.$large_img.'" rel="" >'.$get_img.'</a>';
                                echo '</div>'; //<!-- announcements-singlepage-img -->
                                }
                      ?>
    				    	<div class="announcements-singlepage-content">
                            <?php if (function_exists(qtranxf_getLanguage)){
                                if ((qtranxf_getLanguage() == "en") && (has_term('english-translated','language'))){ ?>
                                    <p class="translated-by-odc"><strong><?php _e("Summary translated by ODC Team"); ?></strong></p>
                                <?php } ?>
                                <?php if ((qtranxf_getLanguage() == $local_language) && (has_term('khmer-translated','language'))){ ?>
                                    <p class="translated-by-odc"><strong><?php _e("Summary translated by ODC Team"); ?></strong></p>
                                <?php } ?>
                            <?php } ?>
                            <?php the_content(); ?>

                            <?php
                            //Get Download files
                            if (get('upload_document')=="" && get('upload_document'.$local_lang)==""){
                                echo "";
                            }
                            else{
                                echo "<span>";
                                _e("Download: ");
                                //Get English PDF
                                if(get('upload_document')!=""){
                                    $file_name_en = substr(strrchr(get('upload_document'), '/'), 1);
                                    echo '<a target="_blank" href="'.get_bloginfo("url").'/pdf-viewer/?pdf=files_mf/'.$file_name_en.'">';
                                        echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/united-states.png" /> ';
                                        _e ('English PDF');
                                    echo '</a>';
                                }
                                else{
                                    echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/united-states.png" /> ';
                                    _e("English PDF not available");
                                }
                                echo "&nbsp; &nbsp;";
                                //Get Khmer PDF
                                if(get('upload_document'.$local_lang)!=""){
                                    $file_name = substr(strrchr(get('upload_document'.$local_lang), '/'), 1);
                                    echo '<a target="_blank" href="'.get_bloginfo("url").'/pdf-viewer/?pdf=files_mf/'.$file_name.'">';
                                        echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/cambodia.png" /> ';
                                        _e ('Khmer PDF');
                                    echo '</a>';
                                }
                                else{
                                    echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/cambodia.png" /> ';
                                    _e("Khmer PDF not available");
                                }
                                echo "</span>";
                            }
                            ?>

                                <div class="announcement-source">
            					<?php
            					    if (get('author')=="" && get('author'.$lang)==""){
                                        echo "";
                          }
                          else{
            					        echo '<br />';
                                        _e('Source: '); //'<span class="icon-news"></span> ';
                                        echo trim(get('author'.$lang));
                          }
                					if (taxonomy_exists('public_announcement_source')){
                					    $terms_public_announcement_source = get_the_terms($post->ID,'public_announcement_source');
                					    if ($terms_public_announcement_source){
                					        $public_announcement_source = ", ";
                        					foreach ($terms_public_announcement_source as $term) {
                    							$term_link = get_term_link( $term, 'news_source' );
                    							if( is_wp_error( $term_link ) )
                    								continue;
                    							//We successfully got a link. Print it out.
                    							 $public_announcement_source .= '<a href="' . $term_link . '"><srong>' . $term->name . '</srong></a>,';
                    						}
                						    echo substr($public_announcement_source, 0, -1);
                						}
                					}
            				        ?>
                                </div><!-- news-source -->

                                <?php //Show tags
                                    if (has_tag()) { ?>
                    					<div class="post-tags">
                    						<span class="lsf">&#xE128;</span> <?php echo __( 'Tags:', 'opendev' ); ?> <?php the_tags('',''); ?>
                    					</div>
                    			<?php } ?>
            					<?php
            					wp_link_pages( array(
            						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
            						'after'       => '</div>',
            						'link_before' => '<span>',
            						'link_after'  => '</span>',
            					) );
            					?>
            					<?php //comments_template(); ?>
                            </div><!-- announcements-singlepage-content -->
                        </div><!-- announcements-singlepage-content -->


            </section>
				</div> <!-- eight -->
				<div class="three columns offset-by-one">
            					<aside id="sidebar">
            						<ul class="widgets">
                          <!--social media sidebar-->
                          <li class="widget share-widget">
            								<div class="share clearfix">
            									<ul>
            										<!--<li>
            											<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-show-faces="false" data-send="false"></div>
            										</li>-->
            										<li>
            											<div class="fb-share-button" data-href="<?php echo get_permalink( $post->ID )?>" data-send="false" data-layout="button" data-show-faces="false"></div>
            										</li>
            										<li>
            											<div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a></div>
            										</li>
            										<li>
            											<div class="g-plusone" data-width="50" data-annotation="none" data-size="tall" data-href="<?php the_permalink(); ?>" data-count="false"></div>
            										</li>
            									</ul>
            								</div>
            							</li>

                          <!-- category sidebar-->
                          <?php dynamic_sidebar('post'); ?>

            						</ul>
            					</aside>
				</div>    <!-- three -->
			</div><!-- container -->

	</article>

<?php endif; ?>

<?php get_footer(); ?>
