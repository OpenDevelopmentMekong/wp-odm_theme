<?php
// dbug
require 'lib/kint/Kint.class.php';
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
	<section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
				<div class="twelve columns">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<div class="container laws-container">
			<div class="eight columns">
				<?php the_content(); ?>
				<?php
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
				?>
					<?php
						$laws_sorted=get_law_datasets_sorted_by_document_type();
						foreach ($laws_sorted as $key => $law){?>
							<div class="document_type_header"><?php echo $key?></div>
								<table class="law_datasets" id="law_datasets_<?php echo $key;?>">
									<thead>
							        <tr>
							            <th>Column 1</th>
							            <th>Column 2</th>
													<th>Column 3</th>
													<th>Column 4</th>
													<th>Column 5</th>
													<th>Column 6</th>
							        </tr>
							    </thead>
									<tbody
										<?php foreach ($law as $title => $law_record) {?>
											<tr>
												<td class="law_title">
													<a href="<?php echo $law_record['wpckan_dataset_title_url'];?>"><?php echo $title;?></a>
												<td class="law_status">
													<?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_laws_status'];?>
												</td>
												<td class="law_version">
													<?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_laws_version_date'];?>
												</td>
												<td class="law_download_en law_download">
													<span class="law_download en">
														<?php foreach ($law_record['wpckan_resources_list'] as $key => $resource) {?>
															<?php if ($resource['wpckan_resource_language'] == 'en'){?>
																<a href="<?php echo $resource['wpckan_resource_name_link'];?>/download/<?php echo $title;?>">
																	<span class="icon-arrow-down"></span>EN</span></a>
															<?php } ?>
														<?php } ?>
												</td>
												<td class="law_download_km law_download">
													<span class="law_download km">
														<?php foreach ($law_record['wpckan_resources_list'] as $key => $resource) {?>
															<?php if ($resource['wpckan_resource_language'] == 'km'){?>
																<a href="<?php echo $resource['wpckan_resource_name_link'];?>/download/<?php echo $resource['wpckan_resource_name'];?>">
																	<span class="icon-arrow-down"></span>KM</span></a>
															<?php } ?>
														<?php } ?>
												</td>
												<td class="law_download_th law_download">
													<span class="law_download th">
														<?php foreach ($law_record['wpckan_resources_list'] as $key => $resource) {?>
															<?php if ($resource['wpckan_resource_language'] == 'th'){?>
																<a href="<?php echo $resource['wpckan_resource_name_link'];?>/download/<?php echo $resource['wpckan_resource_name'];?>">
																	<span class="icon-arrow-down"></span>TH</span></a>
															<?php } ?>
														<?php } ?>
												</td>
											</tr>
											<?php// d($law_record);?>

										<?php } ?>
									</tbody>
								</table>
				<?php } ?>

				<!-- debug -->
				<?php d($laws_sorted);?>
				<input type="text" id="Search_All">
			</div>
		</div>

	</section>
<?php endif; ?>

<?php get_footer(); ?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	// $('#law_datasets_anukretsub-decree').dataTable();

// multiple tables
$.fn.dataTableExt.oApi.fnFilterAll = function (oSettings, sInput, iColumn, bRegex, bSmart) {
               var settings = $.fn.dataTableSettings;

               for (var i = 0; i < settings.length; i++) {
                   settings[i].oInstance.fnFilter(sInput, iColumn, bRegex, bSmart);
               }
           };

           $(document).ready(function () {
               $('#law_datasets_anukretsub-decree').dataTable({
                   "bPaginate": false,

               });
               var oTable0 = $("#law_datasets_anukretsub-decree").dataTable();

               $("#Search_All").keyup(function () {
                   // Filter on the column (the index) of this element
                   oTable0.fnFilterAll(this.value);
               });
           });

           $(document).ready(function () {
               $('#law_datasets_other').dataTable({
                   "bPaginate": false,

               });
               var oTable1 = $("#law_datasets_other").dataTable();

               $("#Search_All").keyup(function () {
                   // Filter on the column (the index) of this element
                   oTable1.fnFilterAll(this.value);
               });
           });








});

</script>
