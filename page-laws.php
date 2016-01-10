<?php
/*
Template Name: Laws page
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php $laws = get_law_datasets(); ?>

  <section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
				<div class="twelve columns">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<div class="container">
			<div class="ten columns">
				<?php
  				wp_link_pages( array(
  					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
  					'after'       => '</div>',
  					'link_before' => '<span>',
  					'link_after'  => '</span>',
  				) );
				?>
        <?php the_content(); ?>
        <table class="law_datasets" id="law_datasets">
          <thead>
            <tr>
              <th>Document type</th>
              <th>Title</th>
              <th>Document number</th>
              <th>Promulgation date</th>
              <th>Download</th>
            </tr>
          </thead>
          <tbody
            <?php foreach ($laws["wpckan_dataset_list"] as $law_record): ?>
              <tr>
                <td class="law_odm_document_type">
                  <?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'];?>
                </td>
                <td class="law_title">
                  <a href="<?php echo $law_record['wpckan_dataset_title_url'];?>"><?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-title_translated']['en'];?></a>
                </td>
                <td class="law_odm_document_number">
                  <?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_number']['en'];?>
                </td>
                <td class="law_odm_promulgation_date">
                  <?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_promulgation_date'];?>
                </td>
                <td class="law_download">
                  <span class="law_download en">
                    <?php foreach ($law_record['wpckan_resources_list'] as $resource) :?>
                      <?php if ($resource['odm_language'][0] == 'en'){?>
                        <a href="<?php echo $resource['url'];?>">
                          <span class="icon-arrow-down"></span>EN</span></a>
                      <?php } ?>
                    <?php endforeach; ?>
                  <span class="law_download km">
                    <?php foreach ($law_record['wpckan_resources_list'] as $resource) :?>
                      <?php if ($resource['odm_language'][0] == 'km'){?>
                        <a href="<?php echo $resource['url'];?>">
                          <span class="icon-arrow-down"></span>KM</span></a>
                      <?php } ?>
                    <?php endforeach; ?>
                </td>
              </tr>
    				<?php endforeach; ?>
  				</tbody>
  			</table>
			</div>
			<div class="two columns">
				<div class="law_search_box">
					<div class="law_search_box_header">
						<span class="big">SEARCH</span> in Laws
					</div>
					<div class="law_search_box_wrapper">
						<input type="text" id="search_all" placeholder="Search all Laws">
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">

jQuery(document).ready(function($) {

  console.log("laws pages init");

  $.fn.dataTableExt.oApi.fnFilterAll = function (oSettings, sInput, iColumn, bRegex, bSmart) {
   var settings = $.fn.dataTableSettings;
   for (var i = 0; i < settings.length; i++) {
     settings[i].oInstance.fnFilter(sInput, iColumn, bRegex, bSmart);
   }
  };

  var oTable = $("#law_datasets").dataTable();

  $("#search_all").keyup(function () {
    console.log("filtering page " + this.value);
    oTable.fnFilterAll(this.value);
 });

});

</script>
