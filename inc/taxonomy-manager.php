<?php

/*
 * OpenDev
 * Taxonomy Manager
 */

class Odm_Taxonomy_Manager {

  var $taxonomy_list = array();
	var $taxonomy_top_tier = array();
	var $taxonomy_tree = array(
		"en" => array(),
		"km" => array(),
		"vi" => array(),
		"th" => array(),
		"my" => array()
	);

	function __construct() {
    $this->init_taxonomy_manager();
	}

  function init_taxonomy_manager(){

		foreach (array_keys($this->taxonomy_tree) as $key):
			$path_to_taxonomy_file = dirname(dirname(__FILE__)) . '/odm-taxonomy/taxonomy_' . $key . '.json';
	    $string = file_get_contents($path_to_taxonomy_file);
	    $this->taxonomy_tree[$key] = json_decode($string, true);
		endforeach;

    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->taxonomy_tree["en"]));
    $iterator->setMaxDepth(2);
    foreach($iterator as $key=>$value) {
      array_push($this->taxonomy_list,$value);
    }
    // Delete first child
    unset($this->taxonomy_list[0]);

		$path_to_taxonomy_top_tier = ABSPATH . 'stats/top_tier_taxonomic_terms.json';
		$string = file_get_contents($path_to_taxonomy_top_tier);
		$this->taxonomy_top_tier = json_decode($string, true);
  }

  function get_taxonomy_tree($lang = "en"){
    return $this->taxonomy_tree[$lang];
  }

  function get_taxonomy_list(){
    return $this->taxonomy_list;
  }

	function get_taxonomy_top_tier(){
    return $this->taxonomy_top_tier;
  }

	function get_taxonomy_translations_for_lang($lang){
		$localizations = array();
		populate_localizations_array($localizations,$this->get_taxonomy_tree("en"),$this->get_taxonomy_tree($lang));
		return $localizations;
	}

  function get_top_tier_term_for_subterm($subterm){
    foreach( $this->taxonomy_top_tier as $top_tier => $children):
      if (in_array($subterm,array_values($children))):
        return $top_tier;
      endif;
    endforeach;

    return null;
  }

}

$GLOBALS['odm_taxonomy_manager'] = new Odm_Taxonomy_Manager();

function odm_taxonomy_manager() {
	return $GLOBALS['odm_taxonomy_manager'];
}

?>
