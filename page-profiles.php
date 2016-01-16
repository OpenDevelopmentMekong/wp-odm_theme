<?php
/*
Template Name: Profile page
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <?php
    $filter_dev_nation = htmlspecialchars($_GET["dev_nation"]);

    $lang = 'en';
    if (function_exists("qtranxf_getLanguage")){
      $lang = qtranxf_getLanguage();
    }

    $profiles = get_elc_profiles($lang);

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
                  <?php echo $profiles['data_class'];?>
                </td>
                <td class="entry_title">
                  <?php echo $profiles['developer'];?>
                </td>
                <td class="district">
                  <?php echo $profiles['district'];?>
                </td>
                <td class="contract_0">
                  <?php echo $profiles['contract_0'];?>
                </td>
                <td class="original_s">
                  <?php echo $profiles['original_s'];?>
                </td>
                <td class="dev_nation">
                  <?php echo $profiles['dev_nation'];?>
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
            '<tr class="group"><td colspan="5">'+ group +'</td></tr>'
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
