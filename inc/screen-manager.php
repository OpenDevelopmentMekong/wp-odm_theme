<?php

/*
 * OpenDev
 * Screen Manager
 */

class Odm_Screen_Manager {

  var $detect = null;

	function __construct() {
    $detect = new Mobile_Detect;
	}

  function is_mobile(){
    return $detect->isMobile();
  }

	function is_tablet(){
    return $detect->isTablet();
  }

	function is_desktop(){
    return !$detect->isMobile() && !$detect->isTablet();
  }

}

$GLOBALS['odm_screen_manager'] = new Odm_Screen_Manager();

function odm_screen_manager() {
	return $GLOBALS['odm_screen_manager'];
}

?>
