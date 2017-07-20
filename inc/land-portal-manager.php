<?php

/*
 * OpenDev
 * Land Portal manager
 */

class Odm_Land_Portal_Manager {

	function __construct() {
    //
	}

  function get_filter_values(){

    $current_country = odm_country_manager()->get_current_country();
    $country_codes = odm_country_manager()->get_country_codes()[$current_country];
    if ($current_country == "mekong"):
      return 'MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/8e61f777-e9e1-4cbd-9f78-1a643f9a7af2> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/61b3d714-bf64-4267-b12a-4f7ec12b8405> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/35ad7f49-d4da-4c46-a38b-6827678d6771> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/718119ec-9b57-4624-87bd-504b38dda0f5> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/06d0a096-dec8-4225-a143-16e9e2efec07> } VALUES ?country  { <http://data.landportal.info/geo/KHM> <http://data.landportal.info/geo/LAO> <http://data.landportal.info/geo/MMR> <http://data.landportal.info/geo/THA> <http://data.landportal.info/geo/VNM> }';
    endif;

    return 'MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/8e61f777-e9e1-4cbd-9f78-1a643f9a7af2> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/61b3d714-bf64-4267-b12a-4f7ec12b8405> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/35ad7f49-d4da-4c46-a38b-6827678d6771> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/718119ec-9b57-4624-87bd-504b38dda0f5> } MINUS {?llr edm:dataProvider <http://data.landportal.info/organization/06d0a096-dec8-4225-a143-16e9e2efec07> } VALUES ?country  { <http://data.landportal.info/geo/' . strtoupper($country_codes["iso3"]) . '>}';

  }

  function get_more_url(){

    return '/more-on-the-land-portal-library/';

  }

}

$GLOBALS['odm_land_portal_manager'] = new Odm_Land_Portal_Manager();

function odm_land_portal_manager() {
	return $GLOBALS['odm_land_portal_manager'];
}

?>
