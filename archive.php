<?php
if(is_category()) {
	include_once(get_stylesheet_directory() . '/category.php');
	die();
}
get_header(); ?>

<div class="container section-title main-title">
	<div class="sixteen columns">
		<h1 class="archive-title"><?php
				if( is_tag() || is_category() || is_tax() ) :
					printf( __( '%s', 'odm' ), single_term_title() );
				elseif ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'odm' ), get_the_date() );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'odm' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'odm' ) ) );
				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'odm' ), get_the_date( _x( 'Y', 'yearly archives date format', 'odm' ) ) );
				elseif ( is_post_type_archive() ) :
					_e(post_type_archive_title('', 0));
				else :
					_e( 'Archives', 'odm' );
				endif;
			?></h1>
	</div>
</div>

<?php get_template_part('loop'); ?>

<?php get_footer(); ?>
