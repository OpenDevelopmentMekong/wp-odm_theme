<?php
/*
Template Name: Upcoming site
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

 <?php if($options['frontpage_slider_id']) : ?>
   <section id="featured-content" class="page-section row">
    <div class="container">
     <div class="twelve columns">
      <div class="section-featured-content">
        <?php
          if( function_exists('fa_display_slider') ){
            fa_display_slider( $options['frontpage_slider_id'] );
          }
        ?>
      </div>
     </div>
    </div>
   </section>
 <?php endif; ?>

 <section id="page-content" style="margin-top:0">
  <div class="section-title no-margin-buttom">
   <div class="container">
    <div class="twelve columns">
      <header class="single-post-header">
				<h1><?php the_title(); ?></h1>
	    </header>
      <?php the_content(); ?>
    </div>
   </div>
  </div>
 </section>

 <section id="homepage-area-1" class="page-section">
   <div class="container">
     <div class="row">
      <div class="twelve columns">
        <?php dynamic_sidebar('homepage-area-1'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-2-3" class="page-section">
   <div class="container">
     <div class="row">
      <div class="six columns">
        <?php dynamic_sidebar('homepage-area-2'); ?>
      </div>
      <div class="six columns">
        <?php dynamic_sidebar('homepage-area-3'); ?>
      </div>
    </div>
   </div>
 </section>
<?php endif; ?>

<?php get_footer(); ?>
