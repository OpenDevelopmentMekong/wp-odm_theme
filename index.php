<?php get_header(); ?>

<?php

  if (is_front_page()):
    $options = get_option('odm_options');
    if ($options['frontpage_slider_id']) : ?>

    <section id="featured-content" class="page-section hideOnMobile">
      <div class="container">
        <div class="row">
          <div class="sixteen columns">
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

 <section id="homepage-area-1-2" class="page-section">
   <div class="container">
     <div class="row">
      <div class="eight columns">
        <?php dynamic_sidebar('homepage-area-1'); ?>
      </div>
      <div class="eight columns">
        <?php dynamic_sidebar('homepage-area-2'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-2" class="page-section">
   <div class="container">
     <div class="row">
      <div class="sixteen columns">
        <?php dynamic_sidebar('homepage-area-2'); ?>
      </div>
    </div>
   </div>
 </section>

 <section id="homepage-area-3-4" class="page-section">
   <div class="container">
     <div class="row">
      <div class="eight columns">
        <?php dynamic_sidebar('homepage-area-3'); ?>
      </div>
      <div class="eight columns">
        <?php dynamic_sidebar('homepage-area-4'); ?>
      </div>
    </div>
   </div>
 </section>

<?php endif; ?>

<?php get_footer(); ?>
