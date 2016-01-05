<?php
/*
Template Name: Laws page
*/
?>
<?php
// dbug
// require 'lib/kint/Kint.class.php';
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
				<div class="pagination">
					show
					<select id="law_pagination" name="law_pagination">
					  <option value="10" selected>10</option>
						<option value="25">25</option>
					  <option value="50">50</option>
					  <option value="100">100</option>
					</select>
					entries
				</div>
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
													<?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_promulgation_date'];?>
												</td>
												<td class="law_version">
													<?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_application_date'];?>
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

				<!-- dbug -->
				<?php// d($laws_sorted);?>


			</div>
			<div class="one column">&nbsp;</div>
			<div class="three columns">
				<div class="law_search_box">
					<div class="law_search_box_header">
						<span class="big">SEARCH</span> in Laws
					</div>
					<div class="law_search_box_wrapper">
						<input type="text" id="Search_All" placeholder="Search all Laws">
					</div>
				</div>
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
               $('.law_datasets').dataTable({
                   "bPaginate": true,

               });
							//  set datatables
               var oTable0 = $("#law_datasets_anukretsub-decree").dataTable();
							 var oTable1 = $("#law_datasets_chbablawkram").dataTable();
							 var oTable2 = $("#law_datasets_constitution-of-cambodia").dataTable();
							 var oTable3 = $("#law_datasets_international-treatiesagreements").dataTable();
							 var oTable4 = $("#law_datasets_kech-sonyacontractagreement").dataTable();
							 var oTable5 = $("#law_datasets_kolkar-nenomguidelines").dataTable();
							 var oTable6 = $("#law_datasets_kolnyobaypolicy").dataTable();
							 var oTable7 = $("#law_datasets_likhetletter").dataTable();
							 var oTable8 = $("#law_datasets_prakasjoint-prakasproclamation").dataTable();
							 var oTable9 = $("#law_datasets_preah-reach-kramroyal-kram").dataTable();
							 var oTable10 = $("#law_datasets_sarachorcircular").dataTable();
							 var oTable11 = $("#law_datasets_sechkdei-chhun-damneoungnoticeannouncement").dataTable();
							 var oTable12 = $("#law_datasets_sechkdei-nenuminstruction").dataTable();
							 var oTable13 = $("#law_datasets_sechkdei-preang-chbabdraft-laws-amp-regulations").dataTable();
							 var oTable14 = $("#law_datasets_sechkdei-samrechdecision").dataTable();
							 var oTable15 = $("#law_datasets_other").dataTable();

               $("#Search_All").keyup(function () {
                   // Filter on the column (the index) of this element
									//  set filters
                   oTable0.fnFilterAll(this.value);
									 oTable1.fnFilterAll(this.value);
									 oTable2.fnFilterAll(this.value);
									 oTable3.fnFilterAll(this.value);
									 oTable4.fnFilterAll(this.value);
									 oTable5.fnFilterAll(this.value);
									 oTable5.fnFilterAll(this.value);
									 oTable6.fnFilterAll(this.value);
									 oTable7.fnFilterAll(this.value);
									 oTable8.fnFilterAll(this.value);
									 oTable9.fnFilterAll(this.value);
									 oTable10.fnFilterAll(this.value);
									 oTable11.fnFilterAll(this.value);
									 oTable12.fnFilterAll(this.value);
									 oTable13.fnFilterAll(this.value);
									 oTable14.fnFilterAll(this.value);
									 oTable15.fnFilterAll(this.value);

               });
           });

          //  $(document).ready(function () {
          //      $('#law_datasets_other').dataTable({
          //          "bPaginate": true,
					 //
          //      });
          //      var oTable1 = $("#law_datasets_other").dataTable();
					 //
          //      $("#Search_All").keyup(function () {
          //          // Filter on the column (the index) of this element
          //          oTable1.fnFilterAll(this.value);
          //      });
          //  });


					// detect pagination change
					// $(document).ready(function () {
					// 	$('#law_pagination').on('change', function() {
					// 		$('div.dataTables_length select').val(this.value);
					// 		// alert( this.value ); // or $(this).val()
					// 	});
					//
					// });





});

</script>
