<?php
/*
Template Name: Upcoming site
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

 <!--blank row-->
 <section class="page-section row">
 </section>

 <section id="featured-content" class="page-section row">
  <div class="container">
   <div class="twelve columns">
    <div class="section-featured-content">
      <?php
        if( function_exists('fa_display_slider') ){
            fa_display_slider( 289173 );
        }
      ?>
    </div>
   </div>
  </div>
 </section>

 <section id="page-content">
  <div class="section-title">
   <div class="container">
    <div class="twelve columns">
     <h1><?php the_title(); ?></h1>
     <?php the_content(); ?>
    </div>
   </div>
  </div>
 </section>

 <section id="announcements-and-updates" class="page-section row">
   <div class="container">
     <div class="row">
      <div class="eight columns">
        <?php dynamic_sidebar('upcoming-footer-left'); ?>
      </div>
      <div class="four columns">
        <?php dynamic_sidebar('upcoming-footer-right'); ?>
      </div>
    </div>
   </div>
 </section>
<?php endif; ?>

<?php //get_template_part('section', 'content-summary'); ?>
<?php // get_template_part('content', 'interactive-map'); ?>

<?php get_footer(); ?>
