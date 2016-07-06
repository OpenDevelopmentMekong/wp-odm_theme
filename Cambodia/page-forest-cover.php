<?php
/*
Template Name: Forest Cover and Forest Cover Analysis
*/
?>

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

            		<section class="content">
    					<div class="post-content">
    						<?php if (is_page('forest-cover'))
                            {
                            ?>
                                <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri()?>/css/forest-cover.css"/>
                                <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri()?>/js/swfobject.js"> </script>
                                <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri()?>/js/youtube_playlist.js"></script>

                                <?php  include qtrans_getLanguage().'-page-forestcover-2014.php'; // kh/kh-page-forestcover.php or en/en-page-forestcover.php ?>

                            <?php
                            }
                           	else if (is_page('forest-cover-analysis-1973-2013'))
         					{
                            ?>
                            	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri()?>/css/forest-cover.css"/>
                            	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
								<?php  include qtrans_getLanguage().'-page-forestcover-analysis-2013.php'; // kh/kh-page-forestcover.php or en/en-page-forestcover.php ?>

                            <?php
							}
                            else if (is_page('forest-cover-analysis-1973-2014'))
                            {
                            ?>
                                <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri()?>/css/forest-cover.css"/>
                                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                                <?php  include qtrans_getLanguage().'-page-forestcover-analysis-2014.php'; // kh/kh-page-forestcover.php or en/en-page-forestcover.php ?>

                            <?php
                            }
                            ?>

                        </div>
    					<?php
    					wp_link_pages( array(
    						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
    						'after'       => '</div>',
    						'link_before' => '<span>',
    						'link_after'  => '</span>',
    					) );
    					?>
        				</div>
    	       	   </section>
    				<div class="three columns offset-by-one">
    					<aside id="sidebar">
    						<ul class="widgets">
    							<li class="widget share-widget">
    								<?php odm_get_template('social-share',array(),true); ?>
    							</li>
    							<li class="widget">
    								<?php odm_summary(); ?>
    							</li>
    							<?php dynamic_sidebar('topic'); ?>
    							<?php if (get_group('related_link') != "" && get_group('related_link') != NULL) { ?>
                            	<li class="widget widget_odm_related_link_widget" style="clear:left">
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
