<?php

/*
 * OpenDev
 * Country Manager
 */

class Odm_Country_Manager {

  var $countries = [
    'mekong' => array('name' => 'Mekong', 'theme' => 'mekong', 'code' => 'mekong', 'lang' => null, 'url' => null),
    'cambodia' => array('name' => 'Cambodia', 'theme' => 'cambodia', 'code' => 'kh', 'lang' => 'km', 'url' => null),
    'thailand' => array('name' => 'Thailand', 'theme' => 'thailand', 'code' => 'th', 'lang' => 'th', 'url' => null),
    'laos' => array('name' => 'Laos', 'theme' => 'laos', 'code' => 'lo', 'lang' => 'la', 'url' => null),
    'myanmar' => array('name' => 'Myanmar', 'theme' => 'myanmar', 'code' => 'mm', 'lang' => 'my', 'url' => null),
    'vietnam' => array('name' => 'Vietnam', 'theme' => 'vietnam', 'code' => 'vn', 'lang' => 'vi', 'url' => null)
  ];

	function __construct() {
		add_action( 'init', array($this,'init_country_manager'));
	}

  function get_current_country(){
    $options = get_option('odm_options');
    $current_country = 'mekong';
    if ( isset($options['style'])):
      $current_country = $options['style'];
    endif;
    return $current_country;
  }

  function get_country_themes(){
    return [
      'Mekong' => 'mekong',
      'Cambodia' => 'cambodia',
      'Thailand' => 'thailand',
      'Laos' => 'laos',
      'Myanmar' => 'myanmar',
      'Vietnam' => 'vietnam'
    ];
  }

  function init_country_manager(){

    if ( function_exists( 'wp_get_sites' )):
      $get_all_sites = wp_get_sites();
      foreach ($get_all_sites as $site):
        switch_to_blog($site["blog_id"]);
        $options = get_site_option('odm_options');
        $country = $options['style'];
        $this->countries[$country]['url'] = $site['domain'];
      endforeach;
      restore_current_blog();
    endif;

    $options = get_site_option('odm_options');
  }

  function echo_country_selectors(){
  ?>
    <ul class="country-selector">
      <?php
        foreach ($this->countries as $country): ?>
          <li><a href="<?php echo $country['url']; ?>"><?php echo __($country['name']);?></a></li>
      <?php
        endforeach;
      ?>
    </ul>
  <?php
  }

}

$GLOBALS['odm_country_manager'] = new Odm_Country_Manager();

function odm_country_manager() {
	return $GLOBALS['odm_country_manager'];
}

?>
