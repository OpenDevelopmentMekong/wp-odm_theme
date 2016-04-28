<?php
/*
Template Name: Profile page
*/
?>

<?php
//require_once('page-profiles-config.php');
?>

<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php

  $lang = CURRENT_LANGUAGE;
  // NOTE: This is a hack to harmonize language code between WP and CKAN.
  // Current country code for CAmbodia is set to KH on WP, after that is moved to KM, this needs to be replaced.
  if ($lang == "kh"){
    $lang = "km";
  }
  // End of hack
  $ammendements = null;
  $profile = null;
  $profiles = null;
  $filter_map_id = NULL;
  if (isset($_GET["map_id"])){
    $filter_map_id = htmlspecialchars($_GET["map_id"]);
  }

  //Get Dataset URL of each profile page in Profiles post type
  if ( (CURRENT_LANGUAGE != "en") ){
    $map_visualization_url = str_replace("?type=dataset", "", get_post_meta($post->ID, '_map_visualization_url_localization', true));
    $ckan_dataset = str_replace("?type=dataset", "", get_post_meta($post->ID, '_csv_resource_url_localization', true));
    $ckan_dataset_tracking = str_replace("?type=dataset", "", get_post_meta($post->ID, '_tracking_csv_resource_url_localization', true));
    $filtered_by_column_index = str_replace("?type=dataset", "", get_post_meta($post->ID, '_filtered_by_column_index_localization', true)) -1;  // index start from zero
    $group_data_by_column_index = str_replace("?type=dataset", "", get_post_meta($post->ID, '_group_data_by_column_index_localization', true))-1;
    $total_number_by_attribute_name = str_replace("?type=dataset", "", get_post_meta($post->ID, '_total_number_by_attribute_name_localization', true));
  }else {
     $map_visualization_url = str_replace("?type=dataset", "", get_post_meta($post->ID, '_map_visualization_url', true));
     $ckan_dataset = str_replace("?type=dataset", "", get_post_meta($post->ID, '_csv_resource_url', true));
     $ckan_dataset_tracking = str_replace("?type=dataset", "", get_post_meta($post->ID, '_tracking_csv_resource_url', true));
     $filtered_by_column_index = str_replace("?type=dataset", "", get_post_meta($post->ID, '_filtered_by_column_index', true)) -1; // index start from zero
     $group_data_by_column_index = str_replace("?type=dataset", "", get_post_meta($post->ID, '_group_data_by_column_index', true)) -1;
     $total_number_by_attribute_name = str_replace("?type=dataset", "", get_post_meta($post->ID, '_total_number_by_attribute_name', true));
  }
  if($map_visualization_url){
     $cartodb_url = $map_visualization_url;
     $cartodb_json = file_get_contents($cartodb_url);
     $cartodb_json_data = json_decode($cartodb_json, true);
     $cartodb_layer_option = $cartodb_json_data['layers'][1]['options'];
     $cartodb_layer_name = $cartodb_layer_option['layer_definition']['layers']['options']['layer_name'];
  }
  if($ckan_dataset != ""){
    $ckan_dataset_exploded_by_dataset = explode("/dataset/", $ckan_dataset);
    $ckan_dataset_exploded_by_resource = explode("/resource/", $ckan_dataset_exploded_by_dataset[1]);
    $ckan_dataset_id = $ckan_dataset_exploded_by_resource[0];
    $ckan_dataset_csv_id = $ckan_dataset_exploded_by_resource[1];

    $dataset = get_dataset_by_id(CKAN_DOMAIN,$ckan_dataset_id);
    if (!IsNullOrEmptyString($filter_map_id)){
      $profile = get_datastore_resources_filter(CKAN_DOMAIN,$ckan_dataset_csv_id,"map_id",$filter_map_id)[0];
    }else{
      $profiles = get_datastore_resource(CKAN_DOMAIN,$ckan_dataset_csv_id);
    }
  }

  //for tracking
  if($ckan_dataset_tracking != ""){
    $ckan_dataset_tracking_exploded_by_dataset = explode("/dataset/", $ckan_dataset_tracking);
    $ckan_dataset_tracking_exploded_by_resource = explode("/resource/", $ckan_dataset_tracking_exploded_by_dataset[1]);
    $ckan_dataset_tracking_id = $ckan_dataset_tracking_exploded_by_resource[0];
    $ckan_dataset_tracking_csv_id = $ckan_dataset_tracking_exploded_by_resource[1];
    if (!IsNullOrEmptyString($filter_map_id)){
      $ammendements = get_datastore_resources_filter(CKAN_DOMAIN,$ckan_dataset_tracking_csv_id,"map_id",$filter_map_id);
    }
  }
  //For Attributes
  if ( (CURRENT_LANGUAGE != "en") ){
    $ckan_attribute = get_post_meta($post->ID, '_attributes_csv_resource_localization', true);
    $ckan_attribute_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking_localization', true);
  }else {
    $ckan_attribute = trim(get_post_meta($post->ID, '_attributes_csv_resource', true));
    $ckan_attribute_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking', true);
  }
  //echo $ckan_attribute;
  if ($ckan_attribute!=""){
    $temp_ckan_attribute = explode("\r\n", $ckan_attribute);
    $array_attribute = array();
    foreach ($temp_ckan_attribute as $value) {
       $array_value = explode('=>', trim($value));
       $array_attribute[trim($array_value[0])] = trim($array_value[1]);
    }
    $DATASET_ATTRIBUTE = $array_attribute;
  }//END IF $ckan_attribute

  if ($ckan_attribute_tracking!=""){
    $temp_ckan_attribute_tracking = explode("\r\n", $ckan_attribute_tracking);
    $array_attribute = array();
    foreach ($temp_ckan_attribute_tracking as $value) {
       $array_value_tracking = explode('=>', trim($value));
       $array_attribute_tracking[trim($array_value_tracking[0])] = trim($array_value_tracking[1]);
    }
    $DATASET_ATTRIBUTE_TRACKING = $array_attribute_tracking;
  }//END IF $ckan_attribute_tracking

  $ref_docs_profile = array();
  $ref_docs_tracking = array();

    //for tracking
    if($ckan_dataset_tracking != ""){
      $ckan_dataset_tracking_exploded_by_dataset = explode("/dataset/", $ckan_dataset_tracking);
      $ckan_dataset_tracking_exploded_by_resource = explode("/resource/", $ckan_dataset_tracking_exploded_by_dataset[1]);
      $ckan_dataset_tracking_id = $ckan_dataset_tracking_exploded_by_resource[0];
      $ckan_dataset_tracking_csv_id = $ckan_dataset_tracking_exploded_by_resource[1];
      if (!IsNullOrEmptyString($filter_map_id)){
        $ammendements = get_datastore_resources_filter(CKAN_DOMAIN,$ckan_dataset_tracking_csv_id,"map_id",$filter_map_id);
      }
    }
    //For Attributes
    if ( (CURRENT_LANGUAGE != "en") ){
      $ckan_attribute = get_post_meta($post->ID, '_attributes_csv_resource_localization', true);
      $ckan_attribute_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking_localization', true);
    }else {
      $ckan_attribute = trim(get_post_meta($post->ID, '_attributes_csv_resource', true));
      $ckan_attribute_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking', true);
    }
    //echo $ckan_attribute;
    if ($ckan_attribute!=""){
      $temp_ckan_attribute = explode("\r\n", $ckan_attribute);
      $array_attribute = array();
      foreach ($temp_ckan_attribute as $value) {
         $array_value = explode('=>', trim($value));
         $array_attribute[trim($array_value[0])] = trim($array_value[1]);
      }
      $DATASET_ATTRIBUTE = $array_attribute;
    }//END IF $ckan_attribute

    if ($ckan_attribute_tracking!=""){
      $temp_ckan_attribute_tracking = explode("\r\n", $ckan_attribute_tracking);
      $array_attribute = array();
      foreach ($temp_ckan_attribute_tracking as $value) {
         $array_value_tracking = explode('=>', trim($value));
         $array_attribute_tracking[trim($array_value_tracking[0])] = trim($array_value_tracking[1]);
      }
      $DATASET_ATTRIBUTE_TRACKING = $array_attribute_tracking;
    }//END IF $ckan_attribute_tracking

    $ref_docs_profile = array();
    $ref_docs_tracking = array();
  ?>

  <section id="content" class="single-post">
    <?php if (!IsNullOrEmptyString($filter_map_id)):  //view single conccesion
              include("page-profiles-single-page.php");
          else: ?>
      <div class="container">
        <div class="row">
          <div class="twelve columns">
              <?php
                $count_company = array_count_values(array_map(function($value){return $value['developer'];}, $profiles));
                $count_project =  array_count_values(array_map(function($value){return $value['map_id'];}, $profiles));
                $count_data_complete =  array_count_values(array_map(function($value){return $value['data_class'];}, $profiles));
                //print_r($count_data_complete);
                $data_complete​ = __('Government data complete', 'opendev');
                $data_partial​ = __('Government data partial', 'opendev');
                $data_secondary = __('Secondary source data', 'opendev');
                $data_other​ = __('Other data', 'opendev');
              ?>
              <div class="total_listed">
                <ul>
                  <li><strong><?php _e("Total Projects Listed", "opendev"); ?><?php _e(":", "opendev"); ?></strong></li>
                  <li class="total_elc_count"><strong><?php echo convert_to_kh_number(count($count_project)); ?></strong></li>
                  <li><strong><?php _e("Total Companies Listed", "opendev"); ?><?php _e(":", "opendev"); ?></strong></li>
                  <li class="total_elc_count"><strong><?php echo convert_to_kh_number(count($count_company)); ?></strong></li>
                </ul>
                <ul>
                  <li><?php _e("Government data complete", "opendev"); ?><?php _e(":", "opendev"); ?></li>
                  <li class="total_elc_count"><strong>
                      <?php echo $count_data_complete[$data_complete​]==""? convert_to_kh_number("0"):convert_to_kh_number($count_data_complete[$data_complete​]);?></strong>
                  </li>
                  <li><?php _e("Government data partial", "opendev"); ?><?php _e(":", "opendev"); ?></li>
                  <li class="total_elc_count"><strong>
                      <?php echo $count_data_complete[$data_partial​]==""? convert_to_kh_number("0"):convert_to_kh_number($count_data_complete[$data_partial​]);?></strong>
                  </li>
                  <li><?php _e("Secondary source data", "opendev"); ?><?php _e(":", "opendev"); ?></li>
                  <li class="total_elc_count"><strong>
                      <?php echo $count_data_complete[$data_secondary]==""? convert_to_kh_number("0"):convert_to_kh_number($count_data_complete[$data_secondary]);?></strong>
                  </li>
                  <li><?php _e("Other data", "opendev"); ?><?php _e(":", "opendev"); ?></li>
                  <li class="total_elc_count"><strong>
                      <?php echo $count_data_complete[$data_other​]==""? convert_to_kh_number("0"):convert_to_kh_number($count_data_complete[$data_other​]);?></strong>
                  </li>
                </ul>
            </div>
          </div>
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
            <div id="filter_by_classification" class="filter_by_column_index_<?php echo $filtered_by_column_index ?>">
            </div>
            <table id="profiles" class="data-table">
              <thead>
                <tr>
                  <?php foreach ($DATASET_ATTRIBUTE as $key => $value): ?>
                          <th><div class='th-value'><?php _e( $DATASET_ATTRIBUTE[$key], "opendev" ); ?></div></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($profiles as $profile): ?>
                  <?php if (IsNullOrEmptyString($profile['data_class'])){
                    continue;
                  }?>
                  <tr>
                  <?php
                    foreach ($DATASET_ATTRIBUTE as $key => $value): ?>
                      <?php
                          if (in_array($key, array("developer", "name") )) { ?>
                            <td class="entry_title td-value">
                                <a href="?map_id=<?php echo $profile["map_id"];?>"><?php echo $profile[$key];?></a>
                            </td>
                          <?php
                        }else if (in_array($key, array("data_class", "adjustment_classification", "adjustment") ) ){ ?>
        										<td><div class="td-value"><?php
                              if($lang == "en") echo ucwords(trim($profile[$key]));
                              else echo trim($profile[$key]);
                              ?> <?php data_classification_definition( $profile[$key]);  ?></div>
                            </td>
                          <?php
                          }else {  ?>
                          <td><div class="td-value"><?php
                            echo $profile[$key] == ""? __("Not found", "opendev"): str_replace(";", "<br/>", trim($profile[$key]));
                            ?></div>
                          </td>
                          <?php
                          }
                          ?>
                    <?php endforeach; ?>
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
var mapIdColNumber = 0;
var cartodb_user = "<?php echo $cartodb_layer_option['user_name']; ?>";
var cartodb_layer_table = "<?php  echo $cartodb_layer_name; ?>";
var cartodbSql = new cartodb.SQL({ user: cartodb_user });


var filterEntriesMap = function(mapIds){
  var layers = mapViz.getLayers();
	var mapIdsString = "('" + mapIds.join('\',\'') + "')";
	var sql = "SELECT * FROM " + cartodb_layer_table + " WHERE map_id in " + mapIdsString;
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

  <?php if ($filter_map_id == ""){  ?>
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
  				$('.dataTables_scrollBody').css('top','60px');
          $('.fixed_top_bar').show();
  		   }
  		   else {
  				$('.dataTables_scrollHead').css('position','static');
          $('.fixed_top_bar').hide();
  				$('.dataTables_scrollBody').css('top','0');
  		   }
       });
     /***** end Fixed Header */

     var group_column = <?php echo $group_data_by_column_index ; ?>;
     oTable = $("#profiles").dataTable({
       scrollX: true,
       responsive: false,
       //dom: '<"top"<"info"i><"pagination"p><"length"l>>rt', //show pagination on top
       "sDom": 'T<"H"lf>t<"F"ip>', //show pagination on bottom:
       //'l' - Length changing, 'f' - Filtering input, 't' - The table!, 'i' - Information, 'p' - Pagination, 'r' - pRocessing
       processing: true,
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       //order: [[ 0, 'asc' ]],
       displayLength: -1 //0
       /*columnDefs: [ //Hide collumns
         {
           "targets": [ 17 ],
           "visible": false
         }
       ],*/
       //"aaSorting": [[ 0, 'asc' ]],
       <?php if($lang == "kh" || $lang == "km"){ ?>
       , "oLanguage": {
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
             "sNext": "បន្ទាប់"
           }
       }
       <?php } ?>
       <?php if($group_data_by_column_index !="") { ?>
         , "aaSortingFixed": [[<?php echo $group_data_by_column_index; ?>, 'asc' ]] //sort data in Data Classifications first before grouping
         , "drawCallback": function ( settings ) {  //Group colums
               var api = this.api();
               var rows = api.rows( {page:'current'} ).nodes();
               var last=null;
               api.column(<?php echo $group_data_by_column_index; ?>, {page:'current'} ).data().each( function ( group, i ) {
                   if ( last !== group ) {
                       $(rows).eq( i ).before(
                           '<tr class="group" id="cambodia-bgcolor"><td colspan="17">'+group+'</td></tr>'
                       );
                       last = group;
                   }
               } );
           }
      <?php } ?>
     });

     // Filter by Adjustmemt
     <?php if ($filtered_by_column_index !="") { ?>
         var columnIndex = <?php echo $filtered_by_column_index ?>; //15 is index of Adjustment Classifications

         var column_filter_oTable = oTable.api().columns( columnIndex );
         var column_header = $("#profiles").find("th:eq( "+columnIndex+" )" ).text();
          <?php if (CURRENT_LANGUAGE =="kh" || CURRENT_LANGUAGE == "km") { ?>
                   var label_filter = $('<div class="label"><?php _e("Filter by", "opendev");?> </div>');
                   label_filter.appendTo( $('.filter_by_column_index_'+columnIndex));
                   var select = $('<select><option value="">'+column_header+'<?php _e("all", "opendev"); ?></option></select>');
          <?php
               }else {?>
                   var label_filter = $('<div class="label"><?php _e("Filter by", "opendev");?> </div>');
                   label_filter.appendTo( $('.filter_by_column_index_'+columnIndex));
                   var select = $('<select><option value=""><?php _e("All ", "opendev"); ?>'+column_header+'</option></select>');
          <?php } ?>
             select.appendTo( $('.filter_by_column_index_'+columnIndex) )
             .on( 'change', function () {
                 // Escape the expression so we can perform a regex match
                 var val = $.fn.dataTable.util.escapeRegex(
                     $(this).val()
                 );
                 column_filter_oTable
                     .search( val ? '^'+val : '', true, false )  //beginning with the str
                     .draw();
                      //.search( val ? '^'+val+'$' : '', true, false )   // match to the str only
             } );
             column_filter_oTable.data().eq( 0 ).unique().sort().each( function ( d, j ) {
               console.log(d);
                 var value = d.split(' &nbsp; <div class="tooltip');
                     value = value[0];
                 var val = value.replace('<div class="td-value">', '');
                     val = val.trim();
                     select.append( '<option value="'+val+'">'+val+'</option>' )
             } );
     <?php } ?> //If filter columnIndex exists

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
             //console.log("TD: "+$(this).width() +" =? "+ $headerCell.eq(i).width());
             if ( $(this).width() >= $headerCell.eq(i).width() ){
                  $max_width =   widths[i];
                  $headerCell.eq(i).children('.th-value').css('width', $max_width);
                    if(!$(this).hasClass('group'))
                     $tableBodyCell.eq(i).children('.td-value').css('width', $max_width);
             }else if ( $(this).width() < $headerCell.eq(i).width() ){
                  $max_width =   $headerCell.eq(i).width();
                  $tableBodyCell.eq(i).children('.td-value').css('width', $max_width);
                  $headerCell.eq(i).children('.th-value').css('width', $max_width);
             }
         });

      // Enable the filter_by_classification and Show entry bar on scroll up as fixed items
      ////**** Can't place it above the oTable
      var $filter_data = $("#filter_by_classification").clone(true); // Filter Data type
      var $fg_search_filter_bar = $(".dataTables_filter").clone(true);  // search entry
      var $fg_show_entry_bar = $(".dataTables_length").clone(true);  // show entry

      $(".fixed_top_bar").prepend($filter_data);
      $(".fixed_top_bar").append($fg_show_entry_bar);
      $(".fixed_top_bar").append($fg_search_filter_bar);
      $('.fixed_top_bar #filter_by_classification select').val($('.table-column-container #filter_by_classification select').val());
      $('.fixed_top_bar .dataTables_length select').val($('.table-column-container .dataTables_length select').val());
      $('.fixed_top_bar #filter_by_classification select').on( 'change', function () {
         $('.table-column-container #filter_by_classification select').val($(this).val());
      });
      $('.fixed_top_bar .dataTables_length select').on( 'change', function () {
         $('.table-column-container .dataTables_length select').val($(this).val());
      });
      $('.table-column-container #filter_by_classification  select').on( 'change', function () {
         $('.fixed_top_bar #filter_by_classification select').val($(this).val());
      });
      $('.table-column-container .dataTables_length select').on( 'change', function () {
         $('.fixed_top_bar .dataTables_length select').val($(this).val());
      });
      // End Enable the filter_by_classification and Show entry bar

      //Enable header scroll bar
      $('.dataTables_scrollHead').scroll(function(e){
             $('.dataTables_scrollBody').scrollLeft(e.target.scrollLeft);
      });
 <?php } //if single page
 ?>

   $("#search_all").keyup(function () {
     oTable.fnFilterAll(this.value);
     var filtered = oTable._('tr', {"filter":"applied"});
     filterEntriesMap(_.pluck(filtered,mapIdColNumber));
   });

 });

 window.onload = function() {
   cartodb.createVis('profiles_map', '<?php echo $map_visualization_url; ?>', {
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
