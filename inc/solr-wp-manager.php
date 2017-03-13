<?php

 use Solarium\Solarium;
 use Solarium\QueryType\Select\Query\Query as Select;
/*
 * OpenDev
 * Solr Manager
 */

class Odm_Solr_WP_Manager {

  var $client = null;

  var $server_config = array(
    'endpoint' => array(
        'localhost' => array(
            'host' => 'solr.pp.opendevelopmentmekong.net',
            'port' => 443,
            'path' => '/solr/',
						'core' => 'wordpress_content',
						'scheme' => 'https'
        )
    )
	);

	function __construct() {
		$this->client = new \Solarium\Client($this->server_config);

		$options = get_option('odm_options');
		$solr_config = $options['solr_config'];
    $this->client->getEndpoint()->setAuthentication($solr_config['solr_user'],$solr_config['solr_pwd']);

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

	function index_post($post){
    $update = $this->client->createUpdate();

		$doc = $update->createDocument();
		$doc->id = $post->ID;
		$doc->blogid = get_current_blog_id();
		$doc->blogdomain = get_site_url();
		$doc->title = $post->post_title;
		$doc->permalink = get_permalink($post);
		$doc->author = $post->post_author;
		$doc->content = $post->post_content;
		$doc->excerpt = $post->post_excerpt;
		$doc->type = $post->post_type;
		$doc->categories = wp_get_post_categories($post->ID, array('fields' => 'names'));
		$doc->tags = wp_get_post_tags($post->ID, array('fields' => 'names'));
		$date = new DateTime($post->post_date);
		$doc->date = $date->format('Y-m-d\TH:i:s\Z');
		$modified = new DateTime($post->post_modified);
		$doc->modified = $modified->format('Y-m-d\TH:i:s\Z');
		$update->addDocument($doc);
		$update->addCommit();
		$result = $this->client->update($update);

    return $result;
  }

	function clear_index(){

		// get an update query instance
		$update = $this->client->createUpdate();

		// add the delete query and a commit command to the update query
		$update->addDeleteQuery('title:*');
		$update->addCommit();

		// this executes the query and returns the result
		$result = $this->client->update($update);

		return $result;
  }

	function query($text, $typeFilter = null){
		$query = $this->client->createSelect();
		$query->setQuery($text);
		if (isset($typeFilter)):
			$query->createFilterQuery('type')->setQuery('type:' . $typeFilter);
		endif;

    $current_country = odm_country_manager()->get_current_country();
    if ( $current_country != "mekong"):
			$query->createFilterQuery('country_site')->setQuery('country_site:' . $current_country);
		endif;

		$resultset = $this->client->select($query);
		return $resultset;
	}

}

$GLOBALS['Odm_Solr_WP_Manager'] = new Odm_Solr_WP_Manager();

function Odm_Solr_WP_Manager() {
	return $GLOBALS['Odm_Solr_WP_Manager'];
}

?>
