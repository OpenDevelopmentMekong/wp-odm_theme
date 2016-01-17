<?php
/*
Template Name: Profile page
*/
?>

<?php
require_once('page-profiles-config.php');
?>

<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php

    $lang = 'en';
    if (function_exists("qtranxf_getLanguage")){
      $lang = qtranxf_getLanguage();
    }
    $filter_map_id = htmlspecialchars($_GET["map_id"]);
    $profile = null;
    $profiles = null;
    if (!IsNullOrEmptyString($filter_map_id)){
      $profile = get_elc_profile($CKAN_DOMAIN,$ELC_RESOURCE_IDS[$lang]["csv"],$filter_map_id);
    }else{
      $profiles = get_elc_profiles($CKAN_DOMAIN,$ELC_RESOURCE_IDS[$lang]["csv"]);
    }

  ?>

  <section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
        <div class="row">
  				<div class="twelve columns">
            <h1 class="align-left"><?php the_title(); ?></h1>
            <?php if (!IsNullOrEmptyString($profile)): ?>
              <h2 class="align-left"><?php echo $profile["developer"]; ?></h2>
            <?php endif; ?>
  				</div>
        </div>
			</div>
		</header>
    <div class="container">
      <div class="row">
  			<div class="twelve columns">
          <div id="profiles_map" class="profiles_map"></div>
        </div>
      </div>
    </div>
		<div class="container">
      <div class="row">
  			<?php the_content(); ?>
        <?php if (!IsNullOrEmptyString($filter_map_id)): ?>
          <div class="twelve columns">
            <div id="profile-map-id" class="hidden"><?php echo $filter_map_id; ?></div>
            <h1><?php echo $profile["Developer"]; ?><h1>
          </div>
        <?php else: ?>
          <div class="nine columns">
            <table id="profiles" class="data_table">
              <thead>
                <tr>
                  <th><?php _e( 'Map id', 'map_id' );?></th>
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
                    <td class="map_id">
                      <?php echo $profile['map_id'];?>
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
        <?php endif; ?>
      </div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">

var singleProfile = $("#profiles") == null;
var singleProfileMapId = $("#profile-map-id").text();
var mapViz;
var oTable;
var cartodbSql = new cartodb.SQL({ user: cartodbConfig.user });

var filterEntriesMap = function(mapIds){
  var layers = mapViz.getLayers();
	var mapIdsString = "('" + mapIds.join('\',\'') + "')";
	var sql = "SELECT * FROM " + cartodbConfig.elc.table + " WHERE map_id in " + mapIdsString;
	var bounds = cartodbSql.getBounds(sql).done(function(bounds) {
		mapViz.getNativeMap().fitBounds(bounds);
	});
	layers[1].getSubLayer(0).setSQL(sql);
}

jQuery(document).ready(function($) {

  console.log("profile pages init");

  $.fn.dataTableExt.oApi.fnFilterAll = function (oSettings, sInput, iColumn, bRegex, bSmart) {
   var settings = $.fn.dataTableSettings;
   for (var i = 0; i < settings.length; i++) {
     settings[i].oInstance.fnFilter(sInput, iColumn, bRegex, bSmart);
   }
  };

  if (!singleProfile){
    oTable = $("#profiles").dataTable({
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
      "order": [[ 1, 'asc' ]],
      "displayLength": 25
    });
  }

  $("#search_all").keyup(function () {
    oTable.fnFilterAll(this.value);
    var filtered = oTable._('tr', {"filter":"applied"});
    filterEntriesMap(_.pluck(filtered,'0'));
  });

});

window.onload = function() {

	cartodb.createVis('profiles_map', cartodbConfig.elc.vizUrl, {
		search: false,
		shareable: true,
		https: true
	}).done(function(vis, layers) {
		mapViz = vis;
    if (!singleProfile){
      mapViz.map.set({
        maxZoom: 10
      });
      filterEntriesMap([singleProfileMapId]);
    }
	});

}

</script>
