<?php
/*
Template Name: Upcoming site
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

 <?php
  $options = get_option('opendev_options');
  if($options['site_intro']) :
   $intro_texts = opendev_get_intro_texts();
   if(!empty($intro_texts)) :
   ?>
   <section id="site-intro">
    <div class="container">
     <div id="intro-texts" class="row">
      <?php if($intro_texts[1]) : ?>
       <div class="four columns">
        <div class="text-item">
         <div class="icon">
          <?php if($intro_texts[1]['icon']) : ?>
           <p class="icon-<?php echo $intro_texts[1]['icon']; ?>"></p>
          <?php endif; ?>
         </div>
         <div class="content">
          <h3><?php echo $intro_texts[1]['title']; ?></h3>
          <p><?php echo $intro_texts[1]['content']; ?></p>
         </div>
        </div>
       </div>
      <?php endif; ?>
      <?php if($intro_texts[2]) : ?>
       <div class="four columns">
        <div class="text-item">
         <div class="icon">
          <?php if($intro_texts[2]['icon']) : ?>
           <p class="icon-<?php echo $intro_texts[2]['icon']; ?>"></p>
          <?php endif; ?>
         </div>
         <div class="content">
          <h3><?php echo $intro_texts[2]['title']; ?></h3>
          <p><?php echo $intro_texts[2]['content']; ?></p>
         </div>
        </div>
       </div>
      <?php endif; ?>
      <?php if($intro_texts[3]) : ?>
       <div class="four columns">
        <div class="text-item">
         <div class="icon">
          <?php if($intro_texts[3]['icon']) : ?>
           <p class="icon-<?php echo $intro_texts[3]['icon']; ?>"></p>
          <?php endif; ?>
         </div>
         <div class="content">
          <h3><?php echo $intro_texts[3]['title']; ?></h3>
          <p><?php echo $intro_texts[3]['content']; ?></p>
         </div>
        </div>
       </div>
      <?php endif; ?>
     </div>
    </div>
   </section>
  <?php endif; ?>
 <?php else: ?>
  <!--blank row-->
  <section class="page-section row">
  </section>
 <?php endif; ?>

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
      <div class="four columns">
        <?php dynamic_sidebar('upcoming-footer-left'); ?>
      </div>
      <div class="four columns">
        <?php dynamic_sidebar('upcoming-footer-middle'); ?>
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
