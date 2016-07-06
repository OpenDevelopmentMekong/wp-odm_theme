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
								$title = __('News', 'odm');
							?>
							<li <?php if($current_pt == $pt->name) echo 'class="active"'; ?>><a href="<?php echo add_query_arg(array('post_type' => $pt->name)); ?>"><?php echo $title; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
			<?php if(have_posts()) : ?>
				<ul class="tabbed-posts-list"><ul class="odm-posts-list">
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
											 <?php show_post_meta(get_post()); ?>

										</div>
									<?php } ?>
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
				<h3 style="padding: 0 20px 10px;"><?php _e('No results found.', 'odm'); ?></h3>
			<?php endif; ?>
		</section>
	</div>
	<div class="three columns move-up">
		<aside id="sidebar">
			<ul class="widgets">
				<li class="widget share-widget">
					<?php odm_get_template('social-share',array(),true); ?>
				</li>
        <?php if (isset($_GET['post_type'])): ?>
  				<li id="odm_taxonomy_widget" class="widget widget_odm_taxonomy_widget">
  					<?php list_category_by_post_type($_GET['post_type']); ?>
  				</li>
        <?php endif; ?>
				<?php dynamic_sidebar('general'); ?>
			</ul>
		</aside>
	</div>
</div>

<?php get_footer(); ?>
