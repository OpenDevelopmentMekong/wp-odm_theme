<?php
/*
Template Name: Forest Cover and Forest Cover Analysis
*/
?>
<?php get_header(); ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri()?>/Cambodia/forest-cover.css"/>
	<?php if (is_single('forest-cover')) { ?>
						<?php  include 'page-forestcover-2014.php'; ?>
	<?php } else if (is_single('forest-cover-analysis-1973-2013')) { ?>
						<script type="text/javascript" src="https://www.google.com/jsapi"></script>
						<?php include qtrans_getLanguage().'-page-forestcover-analysis-2013.php'; ?>
	<?php	} else if (is_single('forest-cover-analysis-1973-2014')) { ?> 
						<script type="text/javascript" src="https://www.google.com/jsapi"></script>
						<?php include qtrans_getLanguage().'-page-forestcover-analysis-2014.php'; ?>
						<?php
				}
				?>

	<?php if(have_posts()) : the_post(); ?>
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
    			<div class="eleven columns">
          <section class="content">
            <div class="post-content">
              <?php the_content(); ?>
            </div>
          </section>
        </div>

        <div class="four columns offset-by-one">
          <aside id="sidebar">
		        <ul class="widgets">
		        	<?php dynamic_sidebar('profile-area-1'); ?>
		        </ul>
          </aside>
        </div>
      </div>
    </section>

  </article>

<?php endif; ?>

<?php get_footer(); ?>
