<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<article id="content" class="single-post">
		<?php get_template_part('section', 'related-datasets'); ?>

			<div class="container">
				<div class="eight columns">
				    <header class="single-post-header" class="clearfix">
    					<h1><?php the_title(); ?></h1>
    					<div class="categories">
    						  <span class="lsf">&#9776;</span> <?php echo __( 'Filed under:', 'jeo' ); ?> <?php the_category(); ?>
    					</div>
            		</header>
            		<?php
            		if(jeo_has_marker_location()) {
            			?>
            			<section id="featured-media" class="row">
            				<div class="container">
            					<div class="twelve columns">
            						<div style="height:400px;">
            							<?php jeo_map(); ?>
            						</div>
            					</div>
            				</div>
            			</section>
            			<?php
            		}
            		?>
            		<section class="content">
    					<div class="post-content">
    						<?php the_content(); ?>
    					</div>
    					<?php
    					wp_link_pages( array(
    						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
    						'after'       => '</div>',
    						'link_before' => '<span>',
    						'link_after'  => '</span>',
    					) );
    					?>
    					<?php comments_template(); ?>
        				</div>
    	       	   </section>
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
    							<li class="widget">
    								<?php opendev_summary(); ?>
    							</li>
    							<?php dynamic_sidebar('topic'); ?>
    							<?php if (get_group('related_link') != "" && get_group('related_link') != NULL) { ?>
                            	<li class="widget widget_opendev_related_link_widget" style="clear:left">
                                    <div>
                                    <h2 class="widget-title">Related Links</h2>
    							<?php
                                        $related_link  = get_group('related_link');
                                        echo '<ul>';
                                        foreach ($related_link as $related) {
                                            echo '<li>';
                                            if($related['related_link_link'][1]!="")
    											echo '<a title="Click to view." href="'.$related['related_link_link'][1].'">';
    										if($related['related_link_label'][1]!="")
    										 	echo $related['related_link_label'][1];
    										if($related['related_link_link'][1]!="")
                                            	echo '</a>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                    ?>
                                    </div>
    							</li>
    						<?php } ?>
    						</ul>
    					</aside>
    				</div>
    			</div>
	</article>

<?php endif; ?>

<?php get_footer(); ?>
