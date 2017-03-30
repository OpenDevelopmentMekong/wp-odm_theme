<?php
if(is_category()) {
	include_once(get_stylesheet_directory() . '/category.php');
	die();
}
get_header();
?>

<div class="section-title">
	<div class="container">
		<div class="sixteen columns">
			<h1 class="archive-title"><?php
					if( is_tag() || is_category() || is_tax() ) :
						printf( __( '%s', 'odi' ), single_term_title() );
					elseif ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'odi' ), get_the_date() );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'odi' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'odi' ) ) );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'odi' ), get_the_date( _x( 'Y', 'yearly archives date format', 'odi' ) ) );
					elseif ( is_post_type_archive() ) :
						_e(post_type_archive_title('', 0));
					else :
						_e( 'Archives', 'odi' );
					endif;
				?></h1>
		</div>
	</div>
</div>
<?php get_template_part('loop'); ?>

<?php get_footer(); ?>
