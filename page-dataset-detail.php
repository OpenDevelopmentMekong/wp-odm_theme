<?php
/*
 * Template Name: WPCKAN Dataset detail
 */
?>
<?php get_header(); ?>

<?php
  $search_query = isset($_GET["search_query"]) ? base64_decode($_GET["search_query"]) : null; ?>

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

          <?php
            if (isset($search_query)):
              $result = WP_Odm_Solr_UNIFIED_Manager()->query_by_params($search_query);
							if (count($result["resultset"]) > 0): ?>
	              <div class="sixteen columns widgets">
	                <div class="widget">
	      						<h2 class="widget-title"><?php _e('Other search results', 'odm'); ?></h2>
	                  <ul>
	                  <?php
	                    foreach ($result["resultset"] as $document):
	                      $link_to_dataset = wpckan_get_link_to_dataset($document->name,$search_query);?>
	                      <li>
	                        <h5><a target="_blank" href="<?php echo $link_to_dataset; ?>"><?php echo $document->title; ?></a></h5>
	                      </li>
	                  <?php
	                    endforeach; ?>
	                  </ul>
	      					</div>
	              </div>
          <?php
							endif;
            endif; ?>

        <div class="sixteen columns">
          <ul class="widgets">
            <?php dynamic_sidebar('wpckan-dataset-detail-sidebar'); ?>
          </ul>
        </div>

	    </aside>
    </div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.download').prepend($('<i class="fa fa-download"></i> '));
  })
</script>
