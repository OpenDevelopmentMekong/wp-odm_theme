<?php
/*
Template Name: Laws page
*/
?>

<?php
require_once('page-laws-config.php');
?>

<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php
    $filter_odm_document_type = htmlspecialchars($_GET["odm_document_type"]);
    $filter_odm_taxonomy = htmlspecialchars($_GET["odm_taxonomy"]);
    $laws = get_law_datasets($filter_odm_taxonomy,$filter_odm_document_type);
    $lang = 'en';
    $headline = $filter_odm_taxonomy;

    if (function_exists("qtranxf_getLanguage")){
      $lang = qtranxf_getLanguage();
    }

  ?>

  <section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
        <div class="row">
  				<div class="twelve columns">
  					<h1 class="align-left"><?php the_title(); ?></h1>
            <h1 class="align-left"><?php _e( $headline, 'sub_title_taxonomy' ); ?></h1>
  				</div>
        </div>
			</div>
		</header>
		<div class="container">
			<div class="nine columns">
        <?php the_content(); ?>
        <table id="law_datasets" class="data-table">
          <thead>
            <tr>
              <th><?php _e( 'Title', 'title' );?></th>
              <th><?php _e( 'Document type', 'document_type' );?></th>
              <th><?php _e( 'Document number', 'document_number' );?></th>
              <th><?php _e( 'Promulgation date', 'promulgation_date' );?></th>
              <th><?php _e( 'Download', 'download' );?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($laws as $law_record): ?>
              <?php if (IsNullOrEmptyString($law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_document_type'])){
                continue;
              }?>
              <tr>
                <td class="entry_title">
                  <a href="<?php echo $law_record['wpckan_dataset_title_url'];?>"><?php echo getMultilingualValueOrFallback($law_record['wpckan_dataset_extras']['wpckan_dataset_extras-title_translated'],$lang);?></a>
                </td>
                <td>
                  <?php
                    $doc_type = $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_document_type'];
                    echo $LAWS_DOCUMENT_TYPE[$doc_type];
                  ?>
                </td>
                <td>
                  <?php echo $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_document_number'][$lang];?>
                </td>
                <td>
                  <?php echo $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_promulgation_date'];?>
                </td>
                <td class="download_buttons">
                    <?php foreach ($law_record['wpckan_resources_list'] as $resource) :?>
                      <?php if ($resource['odm_language'][0] == 'en'){?>
                        <span><a href="<?php echo $resource['url'];?>">
                          <span class="icon-arrow-down"></span>EN</a></span>
                      <?php } ?>
                    <?php endforeach; ?>
                    <?php foreach ($law_record['wpckan_resources_list'] as $resource) :?>
                      <?php if ($resource['odm_language'][0] == 'km'){?>
                        <span><a href="<?php echo $resource['url'];?>">
                          <span class="icon-arrow-down"></span>KM</a></span>
                      <?php } ?>
                    <?php endforeach; ?>
                </td>
              </tr>
    				<?php endforeach; ?>
  				</tbody>
  			</table>
			</div>
			<div class="three columns">

				<div class="sidebar_box">
					<div class="sidebar_header">
						<span class="big">
              <?php _e( 'SEARCH', 'search' );?></span> <?php _e( 'in', 'in' );?> <?php _e( $headline  ?: 'Laws', 'sub_title_taxonomy' ); ?>
					</div>
					<div class="sidebar_box_content">
						<input type="text" id="search_all" placeholder="Search all Laws">
            <?php if (!IsNullOrEmptyString($filter_odm_document_type) || !IsNullOrEmptyString($filter_odm_taxonomy)): ?>
              <a href="/laws"><?php _e( 'Clear filter', 'clear_filter' ) ?>
            <?php endif; ?>
					</div>
				</div>

        <div class="sidebar_box">
					<div class="sidebar_header">
						<span class="big"><?php _e( 'LAW COMPENDIUM', 'law_compendium' );?></span>
					</div>
					<div class="sidebar_box_content">
            <?php echo buildStyledTopTopicListForLaws($lang); ?>
					</div>
				</div>

        <div class="law_search_box">
					<div class="sidebar_header">
						<span class="big"><?php _e( 'TYPE OF LAWS', 'type_of_laws' );?></span>
					</div>
					<div class="sidebar_box_content">
            <ul>
              <li><a href="/laws/?odm_document_type=anukretsub-decree"><?php _e( 'Anukret/Sub-Decree', 'anukretsub-decree' );?></a></li>
              <li><a href="/laws/?odm_document_type=chbablawkram"><?php _e( 'Chbab/Law/Kram', 'chbablawkram' );?></a></li>
              <li><a href="/laws/?odm_document_type=constitution-of-cambodia"><?php _e( 'Constitution of Cambodia', 'constitution-of-cambodia' );?></a></li>
              <li><a href="/laws/?odm_document_type=international-treatiesagreements"><?php _e( 'International Treaties/Agreements', 'international-treatiesagreements' );?></a></li>
              <li><a href="/laws/?odm_document_type=kech-sonyacontractagreement"><?php _e( 'Kech Sonya/Contract/Agreement', 'kech-sonyacontractagreement' );?></a></li>
              <li><a href="/laws/?odm_document_type=kolkar-nenomguidelines"><?php _e( 'Kolkar Nenom/Guidelines', 'kolkar-nenomguidelines' );?></a></li>
              <li><a href="/laws/?odm_document_type=kolnyobaypolicy"><?php _e( 'Kolnyobay/Policy', 'kolnyobaypolicy' );?></a></li>
              <li><a href="/laws/?odm_document_type=likhetletter"><?php _e( 'Likhet/Letter', 'likhetletter' );?></a></li>
              <li><a href="/laws/?odm_document_type=prakasjoint-prakasproclamation"><?php _e( 'Prakas/Joint-Prakas/Proclamation', 'prakasjoint-prakasproclamation' );?></a></li>
              <li><a href="/laws/?odm_document_type=preah-reach-kramroyal-kram"><?php _e( 'Preah Reach Kram/Royal Kram', 'preah-reach-kramroyal-kram' );?></a></li>
              <li><a href="/laws/?odm_document_type=sarachorcircular"><?php _e( 'Sarachor/Circular', 'sarachorcircular' );?></a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-chhun-damneoungnoticeannouncement"><?php _e( 'Sechkdei Chhun  Damneoung/Notice/Announcement', 'sechkdei-chhun-damneoungnoticeannouncement' );?></a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-nenuminstruction"><?php _e( 'Sechkdei Nenum/Instruction', 'sechkdei-nenuminstruction' );?></a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-preang-chbabdraft-laws-amp-regulations"><?php _e( 'Sechkdei Preang Chbab/Draft Laws & Regulations', 'sechkdei-preang-chbabdraft-laws-amp-regulations' );?></a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-samrechdecision"><?php _e( 'Sechkdei Samrech/Decision', 'sechkdei-samrechdecision' );?></a></li>
              <li><a href="/laws/?odm_document_type=others"><?php _e( 'Others', 'others' );?></a></li>
            </ul>
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

  var oTable = $("#law_datasets").dataTable({
    "scrollX": false,
    "responsive": {
      details: false
    },
    "dom": '<"top"<"info"i><"pagination"p><"length"l>>rt',
    "processing": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "order": [[ 0, 'asc' ]],
    "displayLength": 25
  });

  $("#search_all").keyup(function () {
    console.log("filtering page " + this.value);
    oTable.fnFilterAll(this.value);
 });

});

</script>
