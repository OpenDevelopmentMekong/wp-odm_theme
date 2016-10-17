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
     <div class="sixteen columns">
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

 <section class="container section-title main-title">
   <header class="row">
     <div class="sixteen columns">
       <h1><?php the_title(); ?></h1>
     </div>
   </header>
 </section>

 <section class="container">
   <div class="sixteen columns">
     <?php the_content(); ?>
   </div>
 </section>

 <section id="homepage-area-1" class="page-section">
   <div class="container">
     <div class="row">
      <div class="sixteen columns">
        <?php dynamic_sidebar('homepage-area-1'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-2-3" class="page-section">
   <div class="container">
     <div class="row">
      <div class="eight columns">
        <?php dynamic_sidebar('homepage-area-2'); ?>
      </div>
      <div class="eight columns">
        <?php dynamic_sidebar('homepage-area-3'); ?>
      </div>
    </div>
   </div>
 </section>
<?php endif; ?>

<?php get_footer(); ?>
