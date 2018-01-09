<?php get_header(); ?>

<article>

  <section id="content" class="single-post">

  	<div class="container">
      <div class="row">
        <div class="eight columns offset-by-four align-center">
          <h1><?php _e('Not found!','odm'); ?></h1>
    	  </div>
      </div>

      <div class="row">
        <div class="eight columns offset-by-four align-center">
          <p><?php _e('The contents you are requesting do not seem to exist. Please try using the search function on the top-right area of the screen or browsing other country sites.','odm'); ?></p>
    	  </div>
      </div>

      <div class="row">
        <div class="four columns offset-by-six align-center">
          <p><a class="button" onClick="history.go(-1)"><?php _e('Back to the previous page','odm'); ?></a></p>
          <p><a class="button" href="/"><?php _e('Go to the current site\'s home','odm'); ?></a></p>
          <?php
              if (function_exists('button_user_feedback_form')): ?>
                  <p><a class="button" id="user_feedback_form" ><?php _e('Contact us','odm'); ?></a></p>
              <?php endif; ?>
    	  </div>
      </div>

  </section>

</article>

<?php get_footer(); ?>
