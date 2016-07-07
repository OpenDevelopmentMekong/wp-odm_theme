<?php get_header(); ?>

<article>

  <section id="content" class="single-post">

  	<div class="container">
      <div class="row">
        <div class="four columns offset-by-six align-center">
          <h1><?php _e('Not found!','odm'); ?></h1>
          <img class="full-width" src="<?php echo get_stylesheet_directory_uri()?>/img/info-icon.svg"></img>
    	  </div>
      </div>

      <div class="row">
        <div class="six columns offset-by-six align-center">
          <p><?php _e('The contents that you are looking for are not available, you have following options:','odm'); ?></p>
          <p><a class="button" onClick="history.go(-1)"><?php _e('Back to the previous page','odm'); ?></a></p>
          <p><a class="button" ><?php _e('Contact us','odm'); ?></a></p>
    	  </div>
      </div>

  </section>

</article>

<?php get_footer(); ?>
