<?php
get_header();
$term = $wp_query->queried_object;
$post_types = get_post_types(array(
  'public' => true,
  '_builtin' => false
));
$tax_post_types = array();
foreach($post_types as $pt) {
  if (in_array($pt, array('news-article', 'topic', 'profile', 'announcement', 'site-update'))){
		$pt_tax = get_object_taxonomies($pt);
		if(in_array($term->taxonomy, $pt_tax)) {
			$tax_post_types[] = $pt;
		}
  }
}
?>

<div class="container category-container">

  <section class="container">
		<header class="row">
			<div class="eight columns">
        <?php
  			if($term->parent) :
  				$parent = get_term($term->parent, $term->taxonomy);
  				?>
  				<h3 class="parent-term"><a href="<?php echo get_term_link($parent); ?>"><?php echo $parent->name; ?></a></h3>
  				<?php
  			endif; ?>
        <h1 class="archive-title"><?php single_cat_title(); ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

  <section class="container">
    <div class="row">
      <div class="eleven columns">
    		<section class="tabbed-posts-section container">
    			<?php if(count($tax_post_types) > 1) : ?>
    				<nav id="tabbed-post-type-nav">
    					<ul>
    						<?php
    						$current_pt = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'post';
    						foreach($tax_post_types as $pt) :
    							$pt = get_post_type_object($pt);
    							$title = $pt->labels->name;?>
    							<li <?php if($current_pt == $pt->name) echo 'class="active"'; ?>><a href="<?php echo add_query_arg(array('queried_post_type' => $pt->name)); ?>"><?php echo $title; ?></a></li>
    						<?php endforeach; ?>
    					</ul>
    				</nav>
    			<?php endif; ?>
    			<?php if(have_posts()) : ?>
    					<?php while(have_posts()) : the_post();
                odm_get_template('post-list-single-1-cols',array(
        					"post" => get_post(),
                  "show_meta" => true
        			),true);
    					endwhile; ?>
    			<?php else : ?>
    				<h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'odm'); ?></h3>
    			<?php endif; ?>
    		</section>
    	</div>

    	<div class="four columns offset-by-one">
    		<aside id="sidebar">
    			<ul class="widgets">
    				<li class="widget share-widget">
    					<?php odm_get_template('social-share',array(),true); ?>
    				</li>
            <?php if (isset($_GET['queried_post_type'])): ?>
      				<li id="odm_taxonomy_widget" class="widget widget_odm_taxonomy_widget">
      					<?php list_category_by_post_type($_GET['queried_post_type']); ?>
      				</li>
            <?php endif; ?>
    				<?php dynamic_sidebar('general'); ?>
    			</ul>
    		</aside>
    	</div>
    </div>
  </section>

</div>

<?php get_footer(); ?>
