<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">

      <section class="container">
        <header class="row">
          <div class="twelve columns post-title">
            <h1><?php the_title(); ?></h1>
            <?php echo_post_meta(get_post()); ?>
          </div>
          <div class="four columns">
            <div class="widget share-widget">
              <?php odm_get_template('social-share',array(),true); ?>
            </div>
          </div>
        </header>
      </section>

      <section class="container">
    		<div class="row">
    			<div class="sixteen columns">
            <?php dynamic_sidebar('story-top'); ?>
          </div>
        </div>
      </section>

      <section class="container">
    		<div class="row">
    			<div class="ten columns offset-by-three">
            <?php the_content(); ?>
            <?php odm_echo_extras(); ?>
          </section>
        </div>
      </div>
    </section>

    <section class="container">
      <div class="row">
        <div class="sixteen columns">
          <?php dynamic_sidebar('story-bottom'); ?>
        </div>
      </div>
    </section>

  </article>

<?php endif; ?>

<?php get_footer(); ?>
