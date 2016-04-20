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
    // End of hack

    $filter_map_id = NULL;
    if (isset($_GET["map_id"])){
      $filter_map_id = htmlspecialchars($_GET["map_id"]);
    }
    $dataset = get_dataset_by_id($CKAN_DOMAIN,$ELC_DATASET_ID);
    $profile = null;
    $ref_docs_profile = array();
    $ref_docs_tracking = array();
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
    <?php if (!IsNullOrEmptyString($filter_map_id)): ?>
      <div class="container">
        <div class="row">
          <div class="ten columns">
            <div id="profiles_map" class="profiles_map"></div>
          </div>
        </div>
        <div class="row">
          <div class="eight columns">
            <div id="profile-map-id" class="hidden"><?php echo $filter_map_id; ?></div>
            <div class="profile-metadata">
              <h2><?php echo $profile["developer"]; ?></h2>
              <table id="profile" class="data-table">
                <tbody>
                  <?php foreach ($ELC_METADATA as $key => $value): ?>
                  <tr>
                    <td class="row-key"><?php _e( $ELC_METADATA[$key], "opendev" ); ?></td>
                    <td><?php if (isset($profile[$key])){
                        echo $profile[$key];
                    } ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="eight columns">
            <?php if (count($ammendements) > 0): ?>
              <div class="profile-metadata">
                <h2>Amendments</h2>
                <table id="tracking" class="data-table">
                  <tbody>
                    <thead>
                      <tr>
                        <?php foreach ($ELC_TRACKING as $key => $value): ?>
                          <td class="row-key"><?php _e( $ELC_TRACKING[$key], 'opendev'); ?></td>
                        <?php endforeach; ?>
                      </tr>
                    </thead>
                    <?php foreach ($ammendements as $key => $ammendement):
                      if (!IsNullOrEmptyString($ammendement["reference"])){
                        array_push($ref_docs_tracking,$ammendement["reference"]);
                      }
                      ?>
                      <tr>
                        <?php foreach ($ELC_TRACKING as $key => $value): ?>
                          <td>
                            <?php
                              if (isset($ammendement[$key])){
                                  echo $ammendement[$key];
                                }
                            ?>
                          </td>
                        <?php endforeach; ?>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
            <?php
              $ref_docs_profile = explode(";", $profile["reference"]);
              $ref_docs = array_merge($ref_docs_profile,$ref_docs_tracking);
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
                          <a target="_blank" href="<?php echo $CKAN_DOMAIN . "/dataset/" . $metadata["name"] ?>"><?php echo getMultilingualValueOrFallback($metadata['title_translated'],$lang) ?></a></br>
                          <?php if ($metadata["type"]=="laws_record" && !(IsNullOrEmptyString($metadata["odm_promulgation_date"]))): ?>
                            <?php echo "(" . $metadata["odm_promulgation_date"] . ")" ?>
                          <?php elseif ($metadata["type"]=="library_records" && !(IsNullOrEmptyString($metadata["odm_publication_date"]))):  ?>
                            <?php echo "(" . $metadata["odm_publication_date"]  . ")" ?>
                          <?php endif; ?>
                        </td>
                        <td><?php echo getMultilingualValueOrFallback($metadata['notes_translated'],$lang); ?></td>
                      </tr>
                      <?php
                         endforeach;
                        endif;
                     endforeach;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php else: ?>
      <div class="container">
        <div class="row">
    			<div class="nine columns">
            <div id="profiles_map" class="profiles_map"></div>
          </div>
          <div class="three columns">
            <div class="sidebar_box">
              <div class="sidebar_header">
                <span class="big">
                  <?php _e( 'SEARCH', 'opendev' );?></span> <?php _e( 'in', 'opendev' );?> <?php _e( 'Profiles', 'opendev' ); ?>
              </div>
              <div class="sidebar_box_content">
                <input type="text" id="search_all" placeholder="<?php _e( 'Search', 'opendev'); ?>">
              </div>
            </div>

            <div class="sidebar_box">
              <div class="sidebar_header">
                <span class="big">
                  <?php _e( 'DOWNLOAD', 'opendev' );?></span>
              </div>
              <div class="sidebar_box_content download_buttons">
                <?php foreach ($dataset["resources"] as $key => $resource) : ?>
                  <span><a href="<?php echo $resource['url']; ?>"><?php echo $resource['format']; ?></a></span>
                <?php endforeach; ?>
              </div>
            </div>
          </div><!--three-->
        </div>

        <header class="single-post-header">
    			<div class="twelve columns">
            <h1 class="align-left"><a href="<?php get_page_link(); ?>"><?php the_title(); ?></a></h1>
    			</div>
    		</header>
        <div class="row no-margin-buttom">
          <div class="fixed_top_bar"></div>
          <div class="twelve columns table-column-container">
      			<div id="filter_by"><div class="label"><?php _e("Filter by Classifications", "opendev"); ?></div> </div>
            <table id="profiles" class="data-table">
              <thead>
                <tr>
                  <th><div class='th-value'><?php _e( 'Developer', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Developer country', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Contract date', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Contract term (year)', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Granted land area (hectare)', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Source of land size', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Intended investment ', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Intended crop or project', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Province/Capital city', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Contract authority', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Previous land use', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Sub-decree reclassifying land use', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Legal documents', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Developer land use plan', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'EIA status', 'opendev' );?></th>
									<th><div class='th-value'><?php _e( 'Adjustment classification ', 'opendev' );?></div></th>
									<th><div class='th-value'><?php _e( 'Data classification', 'opendev' );?></div></th>
                  <th><div class='th-value'><?php _e( 'Map id', 'opendev' );?></div></th>
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
                      <div class="td-value"><?php echo $profile['dev_nation'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['contract_0'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['contract_d'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['original_s'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['size_refer'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['inv_intent'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['intended_p'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['province'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['contractin'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['land_conv'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['sub_decree'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['legal_docu'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['land_utili'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['eia_status'];?></div>
                    </td>
										<td>
                      <div class="td-value"><?php echo $profile['adjustment'];?></div>
                    </td>
										<td><div class="td-value">
                      <?php if($lang == "en")
                                echo ucwords($profile['data_class']);
                            else
                                echo $profile['data_class'];
                      ?></div>
                    </td>
                    <td>
                      <div class="td-value"><?php echo $profile['map_id'];?></div>
                    </td>
                  </tr>
        				<?php endforeach; ?>
      				</tbody>
      			</table>
          </div>

        </div>

        <div class="row">
          <div class="twelve columns">
            <div class="disclaimer">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
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
  //console.log("profile pages init");
  $.fn.dataTableExt.oApi.fnFilterAll = function (oSettings, sInput, iColumn, bRegex, bSmart) {
   var settings = $.fn.dataTableSettings;
   for (var i = 0; i < settings.length; i++) {
     settings[i].oInstance.fnFilter(sInput, iColumn, bRegex, bSmart);
   }
  };

  if (!singleProfile){
    /***** Fixed Header */
  	var get_datatable = $('#profiles').position().top;
  	    get_datatable = get_datatable +230;

  	$(".content_wrapper").scroll(function(){
  			if ($(".content_wrapper").scrollTop()   >= get_datatable) {
          //console.log($(".content_wrapper").scrollTop()  + " > = " + get_datatable);
  				$('.dataTables_scrollHead').css('position','fixed').css('top','50px');
  				$('.dataTables_scrollHead').css('z-index',9999);
  				$('.dataTables_scrollHead').width($('.dataTables_scrollBody').width());
  				$('.fixed_top_bar').width($('.dataTables_scrollBody').width());
  				$('.dataTables_scrollBody').css('top','120px');
          $('.fixed_top_bar').show();
  		   }
  		   else {
  				$('.dataTables_scrollHead').css('position','static');
          $('.fixed_top_bar').hide();
  				$('.dataTables_scrollBody').css('top','0');
  		   }
       });
     /***** end Fixed Header */


     oTable = $("#profiles").dataTable({
       scrollX: true,
       responsive: false,
       //dom: '<"top"<"info"i><"pagination"p><"length"l>>rt', //show pagination on top
       "sDom": 'T<"H"lf>t<"F"ip>', //show pagination on bottom:
       //'l' - Length changing, 'f' - Filtering input, 't' - The table!, 'i' - Information, 'p' - Pagination, 'r' - pRocessing
       processing: true,
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       //order: [[ 0, 'asc' ]],
       displayLength: -1, //0
       columnDefs: [ //Hide collumns
         {
           "targets": [ 17 ],
           "visible": false
         }
       ],
       "aaSortingFixed": [[ 16, 'asc' ]],
       //"aaSorting": [[ 0, 'asc' ]],
       <?php if($lang == "kh" || $lang == "km"){ ?>
       "oLanguage": {
           "sLengthMenu": 'បង្ហាញទិន្នន័យចំនួន <select>'+
               '<option value="10">10</option>'+
               '<option value="25">20</option>'+
               '<option value="50">50</option>'+
               '<option value="-1">ទាំងអស់</option>'+
             '</select> ក្នុងមួយទំព័រ',
           "sZeroRecords": "ព័ត៌មានពុំអាចរកបាន",
           "sInfo": "បង្ហាញពីទី _START_ ដល់ _END_ នៃទិន្នន័យចំនួន _TOTAL_",
           "sInfoEmpty": "បង្ហាញពីទី 0 ដល់ 0 នៃទិន្នន័យចំនួន 0",
           "sInfoFiltered": "(ទាញចេញពីទិន្នន័យសរុបចំនួន _MAX_)",
           "sSearch":"ស្វែងរក",
           "oPaginate": {
             "sFirst": "ទំព័រដំបូង",
             "sLast": "ចុងក្រោយ",
             "sPrevious": "មុន",
             "sNext": "បន្ទាប់",
           }
       },
       <?php } ?>
       "drawCallback": function ( settings ) {  //Group colums
             var api = this.api();
             var rows = api.rows( {page:'current'} ).nodes();
             var last=null;
             api.column(16, {page:'current'} ).data().each( function ( group, i ) {
                 if ( last !== group ) {
                     $(rows).eq( i ).before(
                         '<tr class="group" id="cambodia-bgcolor"><td colspan="17">'+group+'</td></tr>'
                     );
                     last = group;
                 }
             } );
         }
     });

    var columnIndex = 15; //15 is index of Adjustment Classifications
    var column_oTable = oTable.api().columns( columnIndex );
    var select = $('<select><option value=""><?php _e("All", "opendev"); ?></option></select>')
        .appendTo($("#filter_by") )
        .on( 'change', function () {
            // Escape the expression so we can perform a regex match
            var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
            );

            column_oTable
                .search( val ? '^'+val+'$' : '', true, false )
                .draw();
        } );
        column_oTable.data().eq( 0 ).unique().sort().each( function ( d, j ) {
                    var val = d.replace('<div class="td-value">', '');
                        val = val.replace('</div>', '');
                        select.append( '<option value="'+val+'">'+val+'</option>' )
                } );

    //Set width of table header and body equally
    var widths = [];
    var $tableBodyCell = $('.dataTables_scrollBody #profiles tbody tr:nth-child(2) td');
    var $headerCell = $('.dataTables_scrollHead thead tr th');
    var $max_width;
    $tableBodyCell.each(
      function(){
        widths.push($(this).width());
    });
    $tableBodyCell.each(
          function(i, val){
            //console.log($(this).width() +" == "+ $headerCell.eq(i).width());
            if ( $(this).width() >= $headerCell.eq(i).width() ){
                 $max_width =   widths[i];
                 $headerCell.eq(i).children('.th-value').css('width', $max_width);
                // $('.dataTables_scrollBody #profiles tbody tr').each(function(){
                   if(!$(this).hasClass('group'))
                    $tableBodyCell.eq(i).children('.td-value').css('width', $max_width);
                // });
            }else if ( $(this).width() < $headerCell.eq(i).width() ){
                 $max_width =   $headerCell.eq(i).width();
                 //$('.dataTables_scrollBody #profiles tbody tr').each(function(){
                   //if(!$(this).hasClass('group'))
                     $tableBodyCell.eq(i).children('.td-value').css('width', $max_width);
                 //});
                 $headerCell.eq(i).children('.th-value').css('width', $max_width);
            }
        });

     // Enable the filter_by and Show entry bar on scroll up as fixed items
     ////**** Can't place it above the oTable
     var $filter_data = $("#filter_by").clone(true); // Filter Data type
     var $fg_search_filter_bar = $(".dataTables_filter").clone(true);  // search entry
     var $fg_show_entry_bar = $(".dataTables_length").clone(true);  // show entry

     $(".fixed_top_bar").prepend($filter_data);
     $(".fixed_top_bar").append($fg_show_entry_bar);
     $(".fixed_top_bar").append($fg_search_filter_bar);
     $('.fixed_top_bar #filter_by select').val($('.table-column-container #filter_by select').val());
     $('.fixed_top_bar .dataTables_length select').val($('.table-column-container .dataTables_length select').val());
     $('.fixed_top_bar #filter_by select').on( 'change', function () {
        $('.table-column-container #filter_by select').val($(this).val());
     });
     $('.fixed_top_bar .dataTables_length select').on( 'change', function () {
        $('.table-column-container .dataTables_length select').val($(this).val());
     });
     $('.table-column-container #filter_by  select').on( 'change', function () {
        $('.fixed_top_bar #filter_by select').val($(this).val());
     });
     $('.table-column-container .dataTables_length select').on( 'change', function () {
        $('.fixed_top_bar .dataTables_length select').val($(this).val());
     });
     // End Enable the filter_by and Show entry bar

     //Enable header scroll bar
     $('.dataTables_scrollHead').scroll(function(e){
            $('.dataTables_scrollBody').scrollLeft(e.target.scrollLeft);
     });
  }//if single page

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
    //console.log("cartodb viz created. singleProfile: " + singleProfile);
		mapViz = vis;
    if (singleProfile){
      singleProfileMapId  = $("#profile-map-id").text();
      filterEntriesMap([singleProfileMapId]);
    }
	});

}

</script>
