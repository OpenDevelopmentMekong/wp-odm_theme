<?php
/*
Template Name: Map explorer
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

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

 <section id="map">
  <div class="section-title">
   <div class="container">
    <div class="twelve columns">
     <?php echo do_shortcode('[odmap]'); ?>
     <?php //get_template_part('content', 'interactive-map'); ?>
    </div>
   </div>
  </div>
 </section>

<?php endif; ?>

<?php get_footer(); ?>
