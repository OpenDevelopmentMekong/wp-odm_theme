<?php
/*
Template Name: Map explorer
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>


 <section id="map">
  <?php echo do_shortcode('[odmap]'); ?>
  <?php //get_template_part('content', 'interactive-map'); ?>

  
 </section>

<?php endif; ?>

<?php get_footer(); ?>
