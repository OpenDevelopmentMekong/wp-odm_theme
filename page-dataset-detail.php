<?php
/*
 * Template Name: WPCKAN Dataset detail
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
	<section id="content" class="container single-post">
		<div class="row">
			<div class="eleven columns">
				<?php the_content(); ?>
				<?php
					$dataset_id = $_GET['id'];
					if (isset($dataset_id)):
						echo do_shortcode('[wpckan_dataset_detail id="' . $dataset_id . '"]');
					else:
						echo "<p>" . _e('Please provide an id as parameter', 'wpckan') . "</p>";
					endif;
				?>
			</div>

			<aside id="sidebar" class="four columns offset-by-one">

				<?php
					if (is_user_logged_in()): ?>
						<div class="sixteen columns widgets">
							<div class="widget">
								<h2 class="widget-title"><?php _e('Manage on CKAN','odm') ?></h2>
								</br>
								<a target="_blank" class="button" href="<?php echo wpckan_get_ckan_domain(); ?>/dataset/edit/<?php echo $dataset_id;?>"><?php _e('Manage', 'odm')?></a>
							</div>
						</div>
				<?php
					endif;?>

				<div class="sixteen columns widgets">
					<div class="widget">
						<h2 class="widget-title"><?php _e('Download metadata','odm') ?></h2>
						</br>
						<a target="_blank" class="button download" href="<?php echo wpckan_get_ckan_domain(); ?>/dataset/<?php echo $dataset_id;?>.xml"><?php _e('XML', 'odm')?></a>
						<a target="_blank" class="button download" href="<?php echo wpckan_get_ckan_domain(); ?>/api/3/action/package_show?id=<?php echo $dataset_id;?>"><?php _e('JSON', 'odm')?></a>
						<a target="_blank" class="button download" href="<?php echo wpckan_get_ckan_domain(); ?>/dataset/<?php echo $dataset_id;?>.rdf"><?php _e('RDF', 'odm')?></a>
					</div>
				</div>

				<div class="sixteen columns">
					<ul class="widgets">
						<?php dynamic_sidebar('wpckan-dataset-detail-sidebar'); ?>
					</ul>
				</div>

		</aside>
	</section>
<?php endif; ?>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.download').prepend($('<i class="fa fa-download"></i> '));
  })
</script>

<?php get_footer(); ?>
