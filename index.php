<?php get_header(); ?>

<?php
  if (is_front_page()):
    $options = get_option('opendev_options');
    if ($options['frontpage_slider_id']) : ?>

     <section id="featured-content" class="page-section row">
      <div class="container">
       <div class="twelve columns">
        <div class="section-featured-content">
          <?php
            if (function_exists('fa_display_slider')):
                fa_display_slider($options['frontpage_slider_id']);
            endif;
          ?>
        </div>
       </div>
      </div>
     </section>

 <?php endif;?>

 <section id="announcements-and-updates" class="page-section row">
   <div class="container">
     <div class="row">
      <div class="eight columns">
        <?php dynamic_sidebar('frontpage-footer-left'); ?>
      </div>
      <div class="four columns">
        <?php dynamic_sidebar('frontpage-footer-right'); ?>
      </div>
    </div>
   </div>
 </section>

<?php endif; ?>

<?php get_footer(); ?>
