<?php get_header(); ?>

<?php

  if (is_front_page()):
    $options = get_option('opendev_options');
    if ($options['frontpage_slider_id']) : ?>

     <section id="featured-content" class="page-section">
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

 <section id="homepage-area-1" class="page-section">
   <div class="container">
     <div class="row">
      <div class="twelve columns">
        <?php dynamic_sidebar('homepage-area-1'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-2" class="page-section">
   <div class="container">
     <div class="row">
      <div class="twelve columns">
        <?php dynamic_sidebar('homepage-area-2'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-3" class="page-section">
   <div class="container">
     <div class="row">
      <div class="twelve columns">
        <?php dynamic_sidebar('homepage-area-3'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-4-5" class="page-section">
   <div class="container">
     <div class="row">
      <div class="six columns">
        <?php dynamic_sidebar('homepage-area-4'); ?>
      </div>
      <div class="six columns">
        <?php dynamic_sidebar('homepage-area-5'); ?>
      </div>
    </div>
   </div>
 </section>

<?php endif; ?>

<?php get_footer(); ?>
