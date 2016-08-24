<?php get_header(); ?>

<article>

  <section id="content" class="single-post">

  	<div class="container">
      <div class="row">
        <div class="six columns offset-by-five align-center">
          <h1><?php _e('Not found!','odm'); ?></h1>
          <i class="fa fa-map-signs fa-6" aria-hidden="true"></i>
    	  </div>
      </div>

      <div class="row">
        <div class="six columns offset-by-five align-center">
          <p><?php _e('If you received this error after selecting a new geographic location from the top menu, it means the new location doesn\'t have an information page on this topic that the old location had. To see what information does exist for this new location you\'ve chosen simply click any of the navigation main menu items (News, Topics, Maps, Data, etc..) from the navigation menu above this message .','odm'); ?></p>
          <p><a class="button" onClick="history.go(-1)"><?php _e('Back to the previous page','odm'); ?></a></p>
          <p><a class="button" href="/"><?php _e('Go to the current site\'s home','odm'); ?></a></p>
          <p><a class="button" ><?php _e('Contact us','odm'); ?></a></p>
    	  </div>
      </div>

  </section>

</article>

<?php get_footer(); ?>
