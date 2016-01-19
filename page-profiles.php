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

    // NOTE: This is a hack to harmonize language code between WP and CKAN.
    // Current country code for CAmbodia is set to KH on WP, after that is moved to KM, this needs to be replaced.
    if ($lang == "kh"){
      $lang = "km";
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
          <div class="disclaimer">

            <p><strong>Disclaimer:</strong> This dataset contains data for ELCs in Cambodia with known contract dates starting from 1996 to 2012. The list was updated in June 2015 to include adjustments to ELCs as a result of the Order 01, starting in 2012, such as land cuts and cancellations of licenses. Due to the lack of publicly available information, this dataset does not include information on reductions of contract durations as a result of the evaluation process in 2015. </p>
            <p>Open Development Cambodia endeavors to keep all company and investment data up to date and accurate; however, this information is constantly changing, and ODC cannot guarantee that the data reflects the actual company and investment situation. Some companies have abandoned ELCs or left them inactive, and some ELCs have been cancelled or resized. There are also projects that have been approved but that are not included here because ODC does not have access to reliable data. We recommend that site users make the effort to verify any data they take from ODC. Please inform us if you identify any mistakes or omissions.</p>
            <p><strong>Note:</strong> Publicly available information on land area cut from ELCs does not include maps or spatial data demarcating the excisions. Thus, ODC cannot present the reductions in land area as shapes. As a result, the ELC projects that are visualized on the interactive map represent the original sizes.</p>

          </div>
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
                  <th><?php _e( 'Developer', 'developer' );?></th>
									<th><?php _e( 'Developer country', 'dev_nation' );?></th>
									<th><?php _e( 'Contract date', 'contract_0' );?></th>
									<th><?php _e( 'Contract term (year)', 'contract_d' );?></th>
									<th><?php _e( 'Granted land area (hectare)', 'original_s' );?></th>
									<th><?php _e( 'Source of land size', 'size_refer' );?></th>
									<th><?php _e( 'Intended investment ', 'intended_p' );?></th>
                  <th><?php _e( 'Intended crop or project', 'inv_intent' );?></th>
									<th><?php _e( 'Province/Capital city', 'province' );?></th>
									<th><?php _e( 'Contract authority', 'contractin' );?></th>
									<th><?php _e( 'Previous land use', 'land_conv' );?></th>
									<th><?php _e( 'Sub-decree reclassifying land use', 'sub_decree' );?></th>
									<th><?php _e( 'Legal documents', 'legal_docu' );?></th>
									<th><?php _e( 'Developer land use plan', 'land_utili' );?></th>
									<th><?php _e( 'EIA status', 'eia_status' );?></th>
									<th><?php _e( 'Adjustment classification ', 'adjustment' );?></th>
									<th><?php _e( 'Data classification', 'data_class' );?></th>
                  <th><?php _e( 'Map id', 'map_id' );?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($profiles as $profile): ?>
                  <?php if (IsNullOrEmptyString($profile['data_class'])){
                    continue;
                  }?>
                  <tr>
                    <td class="entry_title">
                      <a href="?map_id=<?php echo $profile['map_id'];?>"><?php echo $profile['developer'];?></a>
                    </td>
                    <td>
                      <?php echo $profile['dev_nation'];?>
                    </td>
										<td>
                      <?php echo $profile['contract_0'];?>
                    </td>
										<td>
                      <?php echo $profile['contract_d'];?>
                    </td>
										<td>
                      <?php echo $profile['original_s'];?>
                    </td>
										<td>
                      <?php echo $profile['size_refer'];?>
                    </td>
										<td>
                      <?php echo $profile['intended_p'];?>
                    </td>
										<td>
                      <?php echo $profile['inv_intent'];?>
                    </td>
										<td>
                      <?php echo $profile['province'];?>
                    </td>
										<td>
                      <?php echo $profile['contractin'];?>
                    </td>
										<td>
                      <?php echo $profile['land_conv'];?>
                    </td>
										<td>
                      <?php echo $profile['sub_decree'];?>
                    </td>
										<td>
                      <?php echo $profile['legal_docu'];?>
                    </td>
										<td>
                      <?php echo $profile['land_utili'];?>
                    </td>
										<td>
                      <?php echo $profile['eia_status'];?>
                    </td>
										<td>
                      <?php echo $profile['adjustment'];?>
                    </td>
										<td>
                      <?php echo $profile['data_class'];?>
                    </td>
                    <td>
                      <?php echo $profile['map_id'];?>
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

var singleProfile = false;
var singleProfileMapId;
var mapViz;
var oTable;
var mapIdColNumber = 17;
var cartodbSql = new cartodb.SQL({ user: cartodbConfig.user });

var filterEntriesMap = function(mapIds){
  var layers = mapViz.getLayers();
	var mapIdsString = "('" + mapIds.join('\',\'') + "')";
	var sql = "SELECT * FROM " + cartodbConfig.elc.table + " WHERE map_id in " + mapIdsString;
	var bounds = cartodbSql.getBounds(sql).done(function(bounds) {
		mapViz.getNativeMap().fitBounds(bounds);
	});
  if (mapIds.length==1){
    mapViz.map.set({
      maxZoom: 10
    });
  }
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
      scrollX: true,
      responsive: false,
      dom: '<"top"<"info"i><"pagination"p><"length"l>>rt',
      processing: true,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      order: [[ 0, 'asc' ]],
      displayLength: 25
    });
  }

  $("#search_all").keyup(function () {
    oTable.fnFilterAll(this.value);
    var filtered = oTable._('tr', {"filter":"applied"});
    filterEntriesMap(_.pluck(filtered,mapIdColNumber));
  });

});

window.onload = function() {

  cartodb.createVis('profiles_map', cartodbConfig.elc.vizUrl, {
		search: false,
		shareable: true,
    zoom: 7,
    center_lat: 12.54384,
    center_long: 105.60059,
		https: true
	}).done(function(vis, layers) {
    singleProfile = $('#profiles').length <= 0;
    console.log("cartodb viz created. singleProfile: " + singleProfile);
		mapViz = vis;
    if (singleProfile){
      singleProfileMapId  = $("#profile-map-id").text();
      filterEntriesMap([singleProfileMapId]);
    }
	});

}

</script>
