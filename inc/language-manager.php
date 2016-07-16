<?php

/*
 * OpenDev
 * Language Manager
 */

class Odm_Language_Manager {

  var $odm_language_manager()->get_current_language() = 'en';

	function __construct() {
    add_action( 'after_setup_theme', array($this,'init_language_manager'));
	}

  function init_language_manager(){

    if (function_exists('qtranxf_getLanguage')) {
      $local_lang = qtranxf_getLanguage();
      $this->odm_language_manager()->get_current_language() = $local_lang;
    }

  }

  function get_odm_language_manager()->get_current_language()(){
    return $this->odm_language_manager()->get_current_language();
  }

  function echo_language_selectors(){
    if (function_exists('qtranxf_generateLanguageSelectCode')) {
        qtranxf_generateLanguageSelectCode('image');
    }
  }

}

$GLOBALS['odm_language_manager'] = new Odm_Language_Manager();

function odm_language_manager() {
	return $GLOBALS['odm_language_manager'];
}

?>
