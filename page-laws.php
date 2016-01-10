<?php
/*
Template Name: Laws page
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php $filter_odm_document_type = htmlspecialchars($_GET["odm_document_type"]); ?>
  <?php $filter_odm_taxonomy = htmlspecialchars($_GET["odm_taxonomy"]); ?>
  <?php $laws = get_law_datasets($filter_odm_taxonomy,$filter_odm_document_type); ?>

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
                  <?php echo $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_document_type'];?>
                </td>
                <td class="law_title">
                  <a href="<?php echo $law_record['wpckan_dataset_title_url'];?>"><?php echo $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-title_translated']['en'];?></a>
                </td>
                <td class="law_datasets_document_number_value">
                  <?php echo $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_document_number']['en'];?>
                </td>
                <td class="law_datasets_promulgation_date_value">
                  <?php echo $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_promulgation_date'];?>
                </td>
                <td class="law_datasets_download_value">
                  <span>
                    <?php foreach ($law_record['wpckan_resources_list'] as $resource) :?>
                      <?php if ($resource['odm_language'][0] == 'en'){?>
                        <a href="<?php echo $resource['url'];?>">
                          <span class="icon-arrow-down"></span>EN</span></a>
                      <?php } ?>
                    <?php endforeach; ?>
                  <span>
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
					<div class="sidebar_header">
						<span class="big">SEARCH</span> in Laws
					</div>
					<div class="sidebar_box_wrapper">
						<input type="text" id="search_all" placeholder="Search all Laws">
					</div>
				</div>

        <div class="law_search_box">
					<div class="sidebar_header">
						<span class="big">LAW COMPENDIUM</span>
					</div>
					<div class="sidebar_box_wrapper">
            <?php echo buildStyledTopTopicListForLaws('en'); ?>
					</div>
				</div>

        <div class="law_search_box">
					<div class="sidebar_header">
						<span class="big">TYPE OF LAWS</span>
					</div>
					<div class="sidebar_box_wrapper">
            <ul>
              <li><a href="/laws/?odm_document_type=anukretsub-decree">Anukret/Sub-Decree</a></li>
              <li><a href="/laws/?odm_document_type=chbablawkram">Chbab/Law/Kram</a></li>
              <li><a href="/laws/?odm_document_type=constitution-of-cambodia">Constitution of Cambodia</a></li>
              <li><a href="/laws/?odm_document_type=international-treatiesagreements">International Treaties/Agreements</a></li>
              <li><a href="/laws/?odm_document_type=kech-sonyacontractagreement">Kech Sonya/Contract/Agreemente</a></li>
              <li><a href="/laws/?odm_document_type=kolkar-nenomguidelines">Kolkar Nenom/Guidelines</a></li>
              <li><a href="/laws/?odm_document_type=kolnyobaypolicy">Kolnyobay/Policy</a></li>
              <li><a href="/laws/?odm_document_type=likhetletter">Likhet/Letter</a></li>
              <li><a href="/laws/?odm_document_type=prakasjoint-prakasproclamation">Prakas/Joint-Prakas/Proclamation</a></li>
              <li><a href="/laws/?odm_document_type=preah-reach-kramroyal-kram">Preah Reach Kram/Royal Kram</a></li>
              <li><a href="/laws/?odm_document_type=sarachorcircular">Sarachor/Circular</a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-chhun-damneoungnoticeannouncement">Sechkdei Chhun  Damneoung/Notice/Announcement</a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-nenuminstruction">Sechkdei Nenum/Instruction</a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-preang-chbabdraft-laws-amp-regulations">Sechkdei Preang Chbab/Draft Laws & Regulations</a></li>
              <li><a href="/laws/?odm_document_type=sechkdei-samrechdecision">Sechkdei Samrech/Decision</a></li>
              <li><a href="/laws/?odm_document_type=others">Others</a></li>
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

  var mapGroupLabel = {
    "anukretsub-decree": "Anukret/Sub-Decree",
    "chbablawkram": "Chbab/Law/Kram",
    "constitution-of-cambodia": "Constitution of Cambodia",
    "international-treatiesagreements": "International Treaties/Agreements",
    "kech-sonyacontractagreement": "Kech Sonya/Contract/Agreement",
    "kolkar-nenomguidelines": "Kolkar Nenom/Guidelines",
    "kolnyobaypolicy": "Kolnyobay/Policy",
    "likhetletter": "Likhet/Letter",
    "prakasjoint-prakasproclamation": "Prakas/Joint-Prakas/Proclamation",
    "preah-reach-kramroyal-kram": "Preah Reach Kram/Royal Kram",
    "sarachorcircular": "Sarachor/Circular",
    "sechkdei-chhun-damneoungnoticeannouncement": "Sechkdei Chhun  Damneoung/Notice/Announcement",
    "sechkdei-nenuminstruction": "Sechkdei Nenum/Instruction",
    "sechkdei-preang-chbabdraft-laws-amp-regulations": "Sechkdei Preang Chbab/Draft Laws & Regulations",
    "sechkdei-samrechdecision": "Sechkdei Samrech/Decision",
    "others": "Others"
  }

  var oTable = $("#law_datasets").dataTable({
    "columnDefs": [
      {
        "visible": false,
        "targets": 0
      }
    ],
    "dom": '<"top"<"six columns info no-padd"i><"two columns length no-padd"l><"two columns pagination no-padd"p>>rt<"bottom"<"six columns info no-padd"i><"two columns length no-padd"l><"two columns pagination no-padd"p>>',
    "processing": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "columns": [
      null,
      null,
      { className: "law_datasets_document_number_header" },
      { className: "law_datasets_promulgation_date_header" },
      { className: "law_datasets_download_header" }
    ],
    "order": [[ 0, 'asc' ]],
    "displayLength": 25,
    "drawCallback": function ( settings ) {
      var api = this.api();
      var rows = api.rows( {page:'current'} ).nodes();
      var last=null;

      api.column(0, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
          $(rows).eq( i ).before(
              '<tr class="group"><td colspan="5">'+mapGroupLabel[group]+'</td></tr>'
          );
          last = group;
        }
      });
    }
  });

  $("#search_all").keyup(function () {
    console.log("filtering page " + this.value);
    oTable.fnFilterAll(this.value);
 });

});

</script>
