<?php

/*
 * OpenDev
 * Screen Manager
 */

class Odm_Screen_Manager {

  var $detect = null;

	function __construct() {
		add_action( 'after_setup_theme', array($this,'init_screen_manager'));
	}

  function init_screen_manager(){
		$this->detect = new Mobile_Detect;
	}

  function is_mobile(){
    return $this->detect->isMobile();
  }

	function is_tablet(){
    return $this->detect->isTablet();
  }

	function is_desktop(){
    return !$this->detect->isMobile() && !$this->detect->isTablet();
  }

}

$GLOBALS['odm_screen_manager'] = new Odm_Screen_Manager();

function odm_screen_manager() {
	return $GLOBALS['odm_screen_manager'];
}

?>
