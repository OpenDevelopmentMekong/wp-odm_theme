<?php
/*
Template Name: Map explorer
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <div id="map-explorer-wrapper">
    <section id="map">
     <?php echo do_shortcode('[odmap]'); ?>
    </section>
  </div>

<?php endif; ?>

<?php get_footer(); ?>
