<?php

/*
 * OpenDev
 * Language Manager
 */

class OpenDev_Language_Manager {

  var $current_language = 'en';

	function __construct() {
    add_action( 'init', array($this,'init_language_manager'));
	}

  function init_language_manager(){

    if (function_exists('qtranxf_getLanguage')) {
        if (qtranxf_getLanguage() == 'kh') {
            $local_lang = 'km';
        } else {
            $local_lang = qtranxf_getLanguage();
        }
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

$GLOBALS['opendev_language_manager'] = new OpenDev_Language_Manager();

function opendev_language_manager() {
	return $GLOBALS['opendev_language_manager'];
}

?>
