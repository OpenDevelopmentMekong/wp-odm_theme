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
    "lo" => "Lao"
  );

  var $languages_by_theme = array(
    "mekong" => "en",
    "cambodia" => "km",
    "vietnam" => "vi",
    "thailand" => "th",
    "myanmar" => "my",
    "laos" => "lo"
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
      return $this->supported_languages[$lang_code];
  }

  function get_the_language_by_site($site = null){
    $theme = isset($site) ? $site : odm_country_manager()->get_current_country();
    $lang_code = $this->languages_by_theme[$theme];
    return $this->supported_languages[$lang_code];
  }

	function get_the_language_code_by_site($site = null){
    $theme = isset($site) ? $site : odm_country_manager()->get_current_country();
    return $this->$languages_by_theme[$theme];
  }

  function get_current_language(){
    return $this->current_language;
  }

  function get_supported_languages(){
    return $this->supported_languages;
  }

  function get_supported_languages_by_site($site = null){
    $theme = isset($site) ? $site : odm_country_manager()->get_current_country();

    if ($theme == 'mekong'):
      return $this->supported_languages;
    endif;

    $supported_languages = array(
      "en" => "English"
    );
    $local_lang_code = $this->languages_by_theme[$theme];
    $supported_languages[$local_lang_code] = $this->supported_languages[$local_lang_code];
    return $supported_languages;
  }

  function echo_language_selectors(){
    if (function_exists('qtranxf_generateLanguageSelectCode')) {
        qtranxf_generateLanguageSelectCode('image');
    }
  }

	function get_path_to_flag_image($lang){
    return get_stylesheet_directory_uri().'/img/'.$lang.'.png';
  }

  function print_language_flags_for_post($post){

    foreach ($this->supported_languages as $lang_code => $lang):
      if(function_exists('qtranxf_isAvailableIn') && qtranxf_isAvailableIn($post->ID, $lang_code)){ // no En content
        $path_to_flag = odm_language_manager()->get_path_to_flag_image($lang_code);
        if (!empty($path_to_flag)):
          echo '<img class="lang_flag" alt="' . $lang . '" src="' . $path_to_flag .'"></img>';
        endif;
      }
    endforeach;



  }

}

$GLOBALS['odm_language_manager'] = new Odm_Language_Manager();

function odm_language_manager() {
	return $GLOBALS['odm_language_manager'];
}

?>
