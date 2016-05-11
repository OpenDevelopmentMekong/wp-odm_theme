<?php
get_header();
$term = $wp_query->queried_object;
$post_types = get_post_types(array('public' => true));
$tax_post_types = array();
foreach($post_types as $pt) {
  if (in_array($pt, array('post', 'topic', 'announcement'))){
		$pt_tax = get_object_taxonomies($pt);
		if(in_array($term->taxonomy, $pt_tax)) {
			$tax_post_types[] = $pt;
		}
  }
}
?>
<div class="section-title main-title">
	<div class="container">
		<div class="twelve columns">
			<?php
			if($term->parent) :
				$parent = get_term($term->parent, $term->taxonomy);
				?>
				<h3 class="parent-term"><a href="<?php echo get_term_link($parent); ?>"><?php echo $parent->name; ?></a></h3>
				<?php
			endif;
			?>
			<h1 class="archive-title"><?php single_cat_title(); ?></h1>
		</div>
	</div>

  <?php
	// Children
	/*$children = get_terms($term->taxonomy, array('child_of' => $term->term_id));
	if($children && !empty($children)) :
		?>
		<nav id="main-title-nav">
			<div class="container">
				<div class="twelve columns">
					<ul class="term-children clearfix">
						<?php foreach($children as $child) : ?>
							<li><a href="<?php echo get_term_link($child); ?>"><?php echo $child->name; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</nav>
		<?php
	endif;*/
  ?>
</div>
<div class="container category-container">
	<div class="eight columns">
	<?php get_template_part('section', 'query-actions'); ?>
		<section class="tabbed-posts-section">
			<?php if(count($tax_post_types) > 1) : ?>
				<nav id="tabbed-post-type-nav">
					<ul>
						<?php
						$current_pt = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
						foreach($tax_post_types as $pt) :
							$pt = get_post_type_object($pt);
							$title = $pt->labels->name;
							if($pt->name == 'post')
								$title = __('News', 'opendev');
							?>
							<li <?php if($current_pt == $pt->name) echo 'class="active"'; ?>>
              <a href="<?php echo add_query_arg(array('post_type' => $pt->name)); ?>"><?php _e($title, "opendev"); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
			<?php if(have_posts()) : ?>
				<ul class="tabbed-posts-list"><ul class="opendev-posts-list">
					<?php while(have_posts()) : the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<article id="post-<?php the_ID(); ?>">
								<header class="post-header">
									<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
									<?php //if(get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page') { ?>
									<?php if(!in_array(get_post_type(), array('map', 'map-layer', 'page', 'topic'))) { ?>
										<div class="meta">
											<!--<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>-->
											<!-- <p><span class="icon-user"></span> <?php _e('by', 'jeo'); ?> <?php //the_author(); ?></p> -->
											 <?php show_date_and_source_of_the_post(); ?>

										</div>
									<?php } ?>
								</header>
                  <section class="post-content">
                    <?php
                    //Get Cover image
                      if (get('cover')=="" && get('cover'.$local_lang)==""){
                        echo "";
                      }
                      else{
                        $img_attr = array("h" => 600, "w" => 800, "zc" => 1, "q" =>100);
                        echo '<div class="announcements-singlepage-img">';
                        if(get('cover'.$lang)!=""){
                          $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover'.$lang,1,1,0).'">';
                          $large_img = get_image('cover'.$lang,1,1,0,null,$img_attr);
                        }
                        else{
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

                  <div class="post-excerpt">
										<?php the_excerpt(); ?>
									</div>
								</section>
							</article>
						</li>
					<?php endwhile; ?>
				</ul>
				<div class="navigation">
					<?php posts_nav_link(); ?>
				</div>
			<?php else : ?>
				<h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'opendev'); ?></h3>
			<?php endif; ?>
		</section>
	</div>
	<div class="three columns move-up">
		<aside id="sidebar">
			<ul class="widgets">
				<li class="widget share-widget">
					<div class="share clearfix">
						<ul>
							<!--<li>
								<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-show-faces="false" data-send="false"></div>
							</li> -->
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
				<li id="opendev_taxonomy_widget" class="widget widget_opendev_taxonomy_widget">
					<?php list_category_by_post_type($_GET['post_type']); ?>
				</li>
				<?php dynamic_sidebar('general'); ?>
			</ul>
		</aside>
	</div>
</div>

<?php get_footer(); ?>
