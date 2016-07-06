<?php

/*
 * OpenDev
 * Language Manager
 */

class Odm_Language_Manager {

  var $current_language = 'en';

	function __construct() {
    add_action( 'init', array($this,'init_language_manager'));
	}

  function init_language_manager(){

    if (function_exists('qtranxf_getLanguage')) {
      $local_lang = qtranxf_getLanguage();
      $this->current_language = $local_lang;
    }

  }

  function get_current_language(){
    return $this->current_language;
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
