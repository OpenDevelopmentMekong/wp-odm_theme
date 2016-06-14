<?php

/*
 * OpenDev
 * Country Manager
 */

class OpenDev_Country_Manager {

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
    $current_country = 'mekong';
    if ( function_exists( 'wp_get_sites' )):
      $options = get_site_option('opendev_options');
      $current_country = $options['style'];
    endif;
  }

  function init_country_manager(){

    if ( function_exists( 'wp_get_sites' )):
      $get_all_sites = wp_get_sites();
      foreach ($get_all_sites as $site):
        switch_to_blog($site["blog_id"]);
        $options = get_site_option('opendev_options');
        $country = $options['style'];
        $this->countries[$country]['url'] = $site['domain'];
      endforeach;
      restore_current_blog();
    endif;

    $options = get_site_option('opendev_options');
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

$GLOBALS['opendev_country_manager'] = new OpenDev_Country_Manager();

function opendev_country_manager() {
	return $GLOBALS['opendev_country_manager'];
}

?>
