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
    $ammendements = null;
    $profiles = null;
    if (!IsNullOrEmptyString($filter_map_id)){
      $profile = get_datastore_resources_filter($CKAN_DOMAIN,$ELC_RESOURCE_IDS[$lang]["metadata"],"map_id",$filter_map_id)[0];
      $ammendements = get_datastore_resources_filter($CKAN_DOMAIN,$ELC_RESOURCE_IDS[$lang]["tracking"],"map_id",$filter_map_id);
    }else{
      $profiles = get_datastore_resource($CKAN_DOMAIN,$ELC_RESOURCE_IDS[$lang]["metadata"]);
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
            <div class="profile-metadata">
              <h2><?php echo $profile["developer"]; ?></h2>
              <table id="profile" class="data-table">
                <tbody>
                  <?php foreach ($ELC_METADATA as $key => $value): ?>
                  <tr>
                    <td class="row-key"><?php _e( $ELC_METADATA[$key], $value ); ?></td>
                    <td><?php echo $profile[$key]; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
						<?php if (count($ammendements) > 0): ?>
							<div class="profile-metadata">
	              <h2>Amendments</h2>
	              <table id="tracking" class="data-table">
	                <tbody>
										<thead>
											<tr>
												<?php foreach ($ELC_TRACKING as $key => $value): ?>
			                    <td class="row-key"><?php _e( $ELC_TRACKING[$key], $value ); ?></td>
												<?php endforeach; ?>
											</tr>
										</thead>
	                  <?php foreach ($ammendements as $key => $ammendement): ?>
											<tr>
												<?php foreach ($ELC_TRACKING as $key => $value): ?>
			                    <td><?php echo $ammendement[$key]; ?></td>
												<?php endforeach; ?>
	                  	</tr>
	                  <?php endforeach; ?>
	                </tbody>
	              </table>
							</div>
						<?php endif; ?>
						<?php
							$ref_docs = explode(";", $profile["reference"]);
							if ($ref_docs): ?>
							<div class="profile-metadata">
	              <h2>Reference documents</h2>
	              <table id="reference" class="data-table">
	                <tbody>
	                  <?php
	                    foreach ($ref_docs as $key => $ref_doc):
	                      $ref_doc_metadata = get_datasets_filter($CKAN_DOMAIN,"extras_odm_reference_document",$ref_doc);
												if (count($ref_doc_metadata) > 0):
													foreach ($ref_doc_metadata as $key => $metadata):
	                    ?>
	                    <tr>
	                      <td class="row-key">
													<a href="<?php echo $CKAN_DOMAIN . "/dataset/" . $metadata["name"] ?>"><?php echo $metadata["title_translated"][$lang] ?></a></br>
													<?php if ($metadata["type"]=="laws_record" && !(IsNullOrEmptyString($metadata["odm_promulgation_date"]))): ?>
		                        <?php echo "(" . $metadata["odm_promulgation_date"] . ")" ?>
		                      <?php elseif ($metadata["type"]=="library_records" && !(IsNullOrEmptyString($metadata["odm_publication_date"]))):  ?>
		                        <?php echo "(" . $metadata["odm_publication_date"]  . ")" ?>
		                      <?php endif; ?>
												</td>
	                      <td><?php echo $metadata["notes_translated"][$lang] ?></td>
	                    </tr>
	                    <?php
												 endforeach;
	                      endif;
	                   endforeach;
	                  ?>
	                </tbody>
	              </table>
	            </div>
						<?php endif; ?>
          </div>
        <?php else: ?>
          <div class="nine columns">
            <table id="profiles" class="data-table">
              <thead>
                <tr>
                  <th><?php _e( 'Map id', 'map_id' );?></th>
                  <th><?php _e( 'Developer', 'developer' );?></th>
                  <th><?php _e( 'Granted land area (hectare)/Khan', 'district' );?></th>
                  <th><?php _e( 'Contract date', 'contract_0' );?></th>
                  <th><?php _e( 'Size', 'original_s' );?></th>
                  <th><?php _e( 'Developer/Nationality', 'dev_nation' );?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($profiles as $profile): ?>
                  <?php if (IsNullOrEmptyString($profile['data_class'])){
                    continue;
                  }?>
                  <tr>
                    <td class="map_id">
                      <?php echo $profile['map_id'];?>
                    </td>
                    <td class="entry_title">
                      <a href="?map_id=<?php echo $profile['map_id'];?>"><?php echo $profile['developer'];?></a>
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

var showAllEntries = function(){
  var layers = mapViz.getLayers();
	var sql = "SELECT * FROM " + cartodbConfig.elc.table;
	var bounds = cartodbSql.getBounds(sql).done(function(bounds) {
		mapViz.getNativeMap().fitBounds(bounds);
	});
	layers[1].getSubLayer(0).setSQL(sql);
}

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
    }else{
      showAllEntries();
    }
	});

}

</script>
