<?php
/*
Template Name: Data archive
*/
?>
<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

    <section class="container section-title main-title">
  		<header class="row">
  			<div class="sixteen columns">
  				<h1><?php the_title(); ?></h1>
          <p class="access-database"><a class="button" href="<?php echo get_option('setting_ckan_url'); ?>" target="_blank"><?php _e('Access the complete database', 'odi'); ?></a></p>
  			</div>
  		</header>
  	</section>

		<section class="container">
			<div class="eleven columns">
				<?php the_content(); ?>
			</div>
			<div class="sixteen columns">
				<div id="wpckan_archive">
					<form id="wpckan_search_form" method="GET">
						<input id="wpckan_search" type="text" name="ckan_s" placeholder="<?php _e('Type your search and hit enter', 'odi'); ?>" value="<?php echo $_GET['ckan_s']; ?>" />
					</form>
					<div class="row">
						<?php
              $search = isset($_GET['ckan_s']) ? $_GET['ckan_s'] : '';
              $limit = 8;
              $page = isset($_GET['ckan_page']) ? $_GET['ckan_page'] : 1;
              echo do_shortcode('[wpckan_query_datasets query="'.$search.'" limit="'.$limit.'" page="'.$page.'"]'); ?>
					</div>
					<nav id="wpckan_nav">
						<?php if ($page > 1) : ?>
							<a class="prev-page button" href="<?php echo add_query_arg(array('ckan_page' => $page - 1)); ?>"><?php _e('Previous page', 'odi'); ?></a>
						<?php endif; ?>
						<a class="next-page button" href="<?php echo add_query_arg(array('ckan_page' => $page + 1)); ?>"><?php _e('Next page', 'odi'); ?></a>
					</nav>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#wpckan_archive').each(function() {
								$(this).find('.wpckan_dataset_list > ul').isotope({
									layoutMode: 'masonry'
								});
							});
							if($('#wpckan_archive .wpckan_dataset_list > ul > li').length < 8) {
								$('.next-page').hide();
							}
						});
					</script>
				</div>
			</div>
			<div class="four columns offset-by-one">
				<aside id="sidebar">
					<ul class="widgets">
						<?php dynamic_sidebar('general'); ?>
					</ul>
				</aside>
			</div>
	  </section>
<?php endif; ?>

<?php get_footer(); ?>
