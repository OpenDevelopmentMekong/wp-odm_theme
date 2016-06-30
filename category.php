<?php
get_header();
$term = $wp_query->queried_object;
$post_types = get_post_types(array('public' => true));
$tax_post_types = array();
$available_post_types = available_post_types();
$available_post_types_names = array_map(function($item){
  return $item->name;
}, $available_post_types);
foreach($post_types as $pt) {
  if (in_array($pt, $available_post_types_names)){
		$pt_tax = get_object_taxonomies($pt);
		if(in_array($term->taxonomy, $pt_tax)) {
			$tax_post_types[] = $pt;
		}
  }
}
?>

<article id="content" class="section-title main-title">

  <section class="container">
    <header class="row">
      <div class="six columns">
        <?php
        if($term->parent) :
          $parent = get_term($term->parent, $term->taxonomy); ?>
          <h3 class="parent-term"><a href="<?php echo get_term_link($parent); ?>"><?php echo $parent->name; ?></a></h3>
          <?php
        endif; ?>
        <h1><?php single_cat_title(); ?></h1>
      </div>
      <div class="six columns">
        <?php get_template_part('section', 'query-actions'); ?>
      </div>
    </header>
  </section>

  <section class="container">
    <div class="row">

      <div class="eight columns">
      	<section class="tabbed-posts-section">
      		<?php if(count($tax_post_types) > 1) : ?>
      			<nav id="tabbed-post-type-nav">
      				<ul>
      					<?php
      					$current_pt = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
      					foreach($tax_post_types as $pt) :
      						$pt = get_post_type_object($pt);
      						$title = $pt->labels->name; ?>
      						<li <?php if($current_pt == $pt->name) echo 'class="active"'; ?>><a href="<?php echo add_query_arg(array('post_type' => $pt->name)); ?>"><?php echo $title; ?></a></li>
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
      							</header>
      							<section class="post-content">
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

      <div class="three columns">
    		<aside id="sidebar">
    			<ul class="widgets">
    				<li class="widget share-widget">
    					<?php opendev_get_template('social-share',array(),true); ?>
    				</li>
            <?php if (isset($_GET['post_type'])): ?>
      				<li id="odm_taxonomy_widget" class="widget widget_opendev_taxonomy_widget">
      					<?php list_category_by_post_type($_GET['post_type']); ?>
      				</li>
            <?php endif; ?>
    				<?php dynamic_sidebar('general'); ?>
    			</ul>
    		</aside>
    	</div>
    </div>

  </section>

</article>

<?php get_footer(); ?>
