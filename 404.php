<?php get_header(); ?>

<article>

  <section id="content" class="single-post">

  	<div class="container">
      <div class="row">
        <div class="eight columns offset-by-four align-center">
          <h1><?php _e('Not found!','odi'); ?></h1>
    	  </div>
      </div>

      <div class="row">
        <div class="eight columns offset-by-four align-center">
          <p><?php _e('If you received this error after selecting a new geographic location from the top menu, it means the new location doesn\'t have an information page on this topic that the old location had. To see what information does exist for this new location you\'ve chosen simply click any of the navigation main menu items (News, Topics, Maps, Data, etc..) from the navigation menu above this message .','odi'); ?></p>
    	  </div>
      </div>

      <div class="row">
        <div class="four columns offset-by-six align-center">
          <p><a class="button" onClick="history.go(-1)"><?php _e('Back to the previous page','odi'); ?></a></p>
          <p><a class="button" href="/"><?php _e('Go to the current site\'s home','odi'); ?></a></p>
          <?php
              if (function_exists('button_user_feedback_form')): ?>
                  <p><a class="button" id="user_feedback_form" ><?php _e('Contact us','odi'); ?></a></p>
              <?php endif; ?>
    	  </div>
      </div>

  </section>

</article>

<?php get_footer(); ?>
