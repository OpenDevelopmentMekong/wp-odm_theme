<?php

/*
 * OpenDev
 * Taxonomy Manager
 */

class Odm_Taxonomy_Manager {

  var $taxonomy_tree;

	function __construct() {

	}

  function init_taxonomy_tree(){

  }

  function get_taxonomy_tree(){
    return $taxonomy_tree;
  }

}

$GLOBALS['odm_taxonomy_manager'] = new Odm_Taxonomy_Manager();

function odm_taxonomy_manager() {
	return $GLOBALS['odm_taxonomy_manager'];
}

?>
