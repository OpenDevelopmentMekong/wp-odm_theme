<?php

/*
 * OpenDev
 * Language Manager
 */

class Odm_Language_Manager {

  var $current_language = 'en';

  var $supported_languages = array(
    "en" => "English",
    "km" => "Khmer",
    "vi" => "Vietnamese",
    "th" => "Thai",
    "my" => "Burmese",
    "la" => "Lao"
  );

  var $languages_by_theme = array(
    "mekong" => "en",
    "cambodia" => "km",
    "vietnam" => "vi",
    "thailand" => "th",
    "myanmar" => "my",
    "laos" => "la"
  );

	function __construct() {
    add_action( 'after_setup_theme', array($this,'init_language_manager'));
	}

  function init_language_manager(){

    if (function_exists('qtranxf_getLanguage')) {
      $local_lang = qtranxf_getLanguage();
      $this->current_language = $local_lang;
    }

  }

  function get_the_language_by_language_code($lang_code = 'en')
  {
      return $supported_languages[$lang_code];
  }

  function get_the_language_by_site($site = null){
    $theme = isset($site) ? $site : odm_country_manager()->get_current_country();
    return $this->languages_by_theme[$theme];
  }

  function get_current_language(){
    return $this->current_language;
  }

  function get_supported_languages(){
    return $this->supported_languages;
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
