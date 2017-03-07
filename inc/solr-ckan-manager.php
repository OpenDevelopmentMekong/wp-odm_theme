<?php

 use Solarium\Solarium;
/*
 * OpenDev
 * Solr Manager
 */

class Odm_Solr_CKAN_Manager {

  var $client = null;

  var $server_config = array(
    'endpoint' => array(
        'localhost' => array(
            'host' => 'solr.pp.opendevelopmentmekong.net',
            'port' => 443,
            'path' => '/solr/collection1/',
						'scheme' => 'https'
        )
    )
	);

	function __construct() {
		$this->client = new \Solarium\Client($this->server_config);
	}

  function ping_server(){
    $ping = $this->client->createPing();

    try {
      $result = $this->client->ping($ping);
    } catch (Solarium\Exception $e) {
      return false;
    }

    return true;
  }

	function query($text){
		$query = $this->client->createSelect();
		$dismax = $query->getDisMax();
		$dismax->setQueryFields('title name');
		$query->setQuery('%P1%', array($text));
		$resultset = $this->client->select($query);
		return $resultset;
	}

}

$GLOBALS['Odm_Solr_CKAN_Manager'] = new Odm_Solr_CKAN_Manager();

function Odm_Solr_CKAN_Manager() {
	return $GLOBALS['Odm_Solr_CKAN_Manager'];
}

?>
