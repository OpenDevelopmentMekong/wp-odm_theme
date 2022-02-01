<?php
/*
 * Template Name: PDF Viewer
 *
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <section class="container section-title main-title">
		<header class="row">
			<div class="sixteen columns">
				<?php odm_title($post,array()); ?> 
			</div>
		</header>
	</section>

	<section class="container">
		<div class="sixteen columns">
			<?php if (isset($_GET['pdf']) and $_GET['pdf']<> ""){
                  $site_id = get_current_blog_id();
              ?>
             		 <div class="download_pdf"><a target="_blank" href="<?php echo site_url()."/wp-content/blogs.dir/".$site_id."/".esc_attr(trim($_GET['pdf'])); ?>"><?php _e("Download"); ?></a></div>
             		 <iframe src="https://docs.google.com/viewer?url=<?php echo site_url()."/wp-content/blogs.dir/".$site_id."/".esc_attr(trim($_GET['pdf'])); ?>&embedded=true" width="95%" height="800"></iframe>
			<?php } else { ?>
				 <div class="download_pdf"><a target="_blank" href="<?php echo esc_url(trim($_GET['url'])); ?>"><?php _e("Download"); ?></a></div>
            		 <iframe src="https://docs.google.com/viewer?url=<?php echo esc_url(trim($_GET['url'])); ?>&embedded=true" width="95%" height="800"></iframe>
			<?php }?>
		</div>
	</section>
  
<?php endif; ?>

<?php get_footer(); ?>
