<?php
/*
Template Name: News archive page
*/
?>
<?php
	get_header();
?>
<?php
    $filter_by_lang = strtolower(get_localization_language_by_language_code(qtrans_getLanguage()));
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		if (SITE_NAME == "Cambodia"){
				$number_latest_post = 25;
				query_posts(array(
					'posts_per_page' => $number_latest_post,
					'post__not_in' => get_option('sticky_posts'),
					'post_type' => 'post',
					'post_status' => 'publish',
					'paged' => $paged,
					//'language'=> $filter_by_lang,
					'tax_query' => array(   // Note: using tax_query will get all post from any post type, even the post type is set
														array(
																'taxonomy' => 'language',
																'field' => 'slug',
																'terms' => $filter_by_lang
														)
					)
				));
		}else {
				$number_latest_post = 25;
				query_posts(array(
					'posts_per_page' => $number_latest_post,
					'post__not_in' => get_option('sticky_posts'),
					'paged' => $paged,
					'post_type' => 'post',
					'post_status' => 'publish'
				));
		}
?>
<?php	if(have_posts()) : ?>
				<div class="section-title">
					<div class="container">
						<div class="eight columns">
									<h1><?php the_title() ?></h1>
						</div>
					</div>
				</div>
  			<?php get_template_part('loop'); ?> 
<?php
			endif;
			wp_reset_query();
?>

<?php get_footer(); ?>
