<?php
/*
Template Name: Profile page
*/

// CONFIG
$ELC_RESOURCE_IDS = array(
  "en" => array(
    "csv" => "7686745b-516d-4ea4-b8ad-d7dc19ad76ea",
    "geojson" => "",
    "kml" => ""
  ),
  "km" => array(
    "csv" => "a1dd1689-f5e4-480e-9272-2bfd7e5429f1",
    "geojson" => "",
    "kml" => ""
  )
);

$ELC_DOWNLOAD_URLS = array(
  "en" => array(
    "csv" => "https://pp-data.opendevelopmentmekong.net/dataset/91187871-7835-49a5-aba1-dbf60e6b0887/resource/7686745b-516d-4ea4-b8ad-d7dc19ad76ea/download/ELCtemplate---ELCen.csv",
    "geojson" => "",
    "kml" => ""
  ),
  "km" => array(
    "csv" => "https://pp-data.opendevelopmentmekong.net/dataset/91187871-7835-49a5-aba1-dbf60e6b0887/resource/a1dd1689-f5e4-480e-9272-2bfd7e5429f1/download/ELCtemplate---ELCkm.csv",
    "geojson" => "",
    "kml" => ""
  )
);

?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php
    $filter_dev_nation = htmlspecialchars($_GET["dev_nation"]);

    $lang = 'en';
    if (function_exists("qtranxf_getLanguage")){
      $lang = qtranxf_getLanguage();
    }

    $profiles = get_elc_profiles($ELC_RESOURCE_IDS[$lang]["csv"]);

  ?>

  <section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
        <div class="row">
  				<div class="twelve columns">
  					<span><h1><?php the_title(); ?></h1> <?php _e( $headline, 'sub_title_taxonomy' ); ?><span>
  				</div>
        </div>
			</div>
		</header>
    <div class="container">
      <div class="row">
  			<div class="twelve columns">
          <div id="profiles_map"></div>
        </div>
      </div>
    </div>
		<div class="container">
			<div class="nine columns">
        <?php the_content(); ?>
        <table class="data_table" id="profiles">
          <thead>
            <tr>
              <th><?php _e( 'Data class', 'data_class' );?></th>
              <th><?php _e( 'Developer', 'developer' );?></th>
              <th><?php _e( 'District', 'district' );?></th>
              <th><?php _e( 'Contract date', 'contract_0' );?></th>
              <th><?php _e( 'Size', 'original_s' );?></th>
              <th><?php _e( 'Developer/Nationality', 'dev_nation' );?></th>
            </tr>
          </thead>
          <tbody
            <?php foreach ($profiles as $profile): ?>
              <?php if (IsNullOrEmptyString($profile['data_class'])){
                continue;
              }?>
              <tr>
                <td class="data_class">
                  <?php echo $profile['data_class'];?>
                </td>
                <td class="entry_title">
                  <?php echo $profile['developer'];?>
                </td>
                <td class="district">
                  <?php echo $profile['district'];?>
                </td>
                <td class="contract_0">
                  <?php echo $profile['contract_0'];?>
                </td>
                <td class="original_s">
                  <?php echo $profile['original_s'];?>
                </td>
                <td class="dev_nation">
                  <?php echo $profile['dev_nation'];?>
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
              <?php _e( 'SEARCH', 'search' );?></span> <?php _e( 'in', 'in' );?> <?php _e( 'Profiles', 'profiles' ); ?>
					</div>
					<div class="sidebar_box_content">
						<input type="text" id="search_all" placeholder="Search all profiles">
            <?php if (!IsNullOrEmptyString($filter_dev_nation)): ?>
              <a href="/profile"><?php _e( 'Clear filter', 'clear_filter' ) ?>
            <?php endif; ?>
					</div>
				</div>

        <div class="sidebar_box">
					<div class="sidebar_header">
						<span class="big">
              <?php _e( 'DOWNLOAD', 'search' );?></span>
					</div>
					<div class="sidebar_box_content download_buttons">
						<?php foreach ($ELC_DOWNLOAD_URLS[$lang] as $key => $value) : ?>
              <span><a href="<?php echo $value; ?>"><?php echo $key; ?></a></span>
						<?php endforeach; ?>
					</div>
				</div>

			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">

jQuery(document).ready(function($) {

  console.log("profile pages init");

  $.fn.dataTableExt.oApi.fnFilterAll = function (oSettings, sInput, iColumn, bRegex, bSmart) {
   var settings = $.fn.dataTableSettings;
   for (var i = 0; i < settings.length; i++) {
     settings[i].oInstance.fnFilter(sInput, iColumn, bRegex, bSmart);
   }
  };

  var oTable = $("#profiles").dataTable({
    "columnDefs": [
      {
        "visible": false,
        "targets": 0
      }
    ],
    "dom": '<"top"<"info"i><"pagination"p><"length"l>>rt',
    "processing": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "columns": [
      null,
      null,
      null,
      null,
      null,
      null
    ],
    "order": [[ 0, 'asc' ]],
    "displayLength": 25,
    "drawCallback": function ( settings ) {
      var api = this.api();
      var rows = api.rows( {page:'current'} ).nodes();
      var last=null;

      api.column(0, {
        page:'current'
      }
    ).data().each( function ( group, i ) {
        if ( last !== group ) {
          $(rows).eq( i ).before(
            '<tr class="group"><td colspan="6">'+ group +'</td></tr>'
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

<script>
  window.onload = function() {
    console.log(cartodb);
    cartodb.createVis('map', 'https://odm.cartodb.com/api/v2/viz/f07860f2-bc4a-11e5-bea2-0ecd1babdde5/viz.json');
  }
</script>
