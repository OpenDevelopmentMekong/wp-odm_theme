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
      return 'VALUES ?country  { <http://data.landportal.info/geo/KHM> <http://data.landportal.info/geo/LAO> <http://data.landportal.info/geo/MMR> <http://data.landportal.info/geo/THA> <http://data.landportal.info/geo/VNM> }';
    endif;

    return 'VALUES ?country  { <http://data.landportal.info/geo/' . strtoupper($country_codes["iso3"]) . '>}';

  }

  function get_values(){

    $current_country = odm_country_manager()->get_current_country();
    $country_codes = odm_country_manager()->get_country_codes()[$current_country];
    if ($current_country == "mekong"):
      return 'VALUES ?country  { <http://data.landportal.info/geo/KHM> <http://data.landportal.info/geo/LAO> <http://data.landportal.info/geo/MMR> <http://data.landportal.info/geo/THA> <http://data.landportal.info/geo/VNM> }';
    endif;

    return 'VALUES ?country  { <http://data.landportal.info/geo/' . strtoupper($country_codes["iso3"]) . '>}';

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
