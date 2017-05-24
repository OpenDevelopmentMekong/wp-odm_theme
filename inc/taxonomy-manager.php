<?php

/*
 * OpenDev
 * Taxonomy Manager
 */

class Odm_Taxonomy_Manager {

  var $taxonomy_tree;
  var $taxonomy_list = array();
	var $taxonomy_top_tier = array();

	function __construct() {
    $this->init_taxonomy_manager();
	}

  function init_taxonomy_manager(){
    $path_to_taxonomy_file = dirname(dirname(__FILE__)) . '/odm-taxonomy/taxonomy_en.json';
    $string = file_get_contents($path_to_taxonomy_file);
    $this->taxonomy_tree = json_decode($string, true);

    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->taxonomy_tree));
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

  function get_taxonomy_tree(){
    return $this->taxonomy_tree;
  }

  function get_taxonomy_list(){
    return $this->taxonomy_list;
  }

	function get_taxonomy_top_tier(){
    return $this->taxonomy_top_tier;
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
