<?php

/*
 * OpenDev
 * Country Manager
 */

class Odm_Country_Manager {

  var $countries = [
    'mekong' => array('name' => 'Mekong',
		      'theme' => 'mekong',
		      'code' => 'mekong',
		      'lang' => null,
		      'url' => 'https://opendevelopmentmekong.net',
		      'url_pp' => 'https://wp.odm2.staging.derilinx.com',
		      'twitter' => 'opendevmekong'),
    'cambodia' => array('name' => 'Cambodia',
			'theme' => 'cambodia',
			'code' => 'kh',
			'lang' => 'km',
			'url' => 'https://opendevelopmentcambodia.net',
			'url_pp' => 'https://odc.wp.odm2.staging.derilinx.com',
			'twitter' => 'opendevcam'),
    'laos' => array('name' => 'Laos',
		    'theme' => 'laos',
		    'code' => 'la',
		    'lang' => 'lo',
		    'url' => 'https://laos.opendevelopmentmekong.net',
		    'url_pp' => 'https://odl.wp.odm2.staging.derilinx.com',
		    'twitter' => 'opendevmekong'),
    'myanmar' => array('name' => 'Myanmar',
		       'theme' => 'myanmar',
		       'code' => 'mm',
		       'lang' => 'my',
		       'url' => 'https://opendevelopmentmyanmar.net',
		       'url_pp' => 'https://odmy.wp.staging.derilinx.com',
		       'twitter' => 'opendevmekong'),
    'thailand' => array('name' => 'Thailand',
			'theme' => 'thailand',
			'code' => 'th',
			'lang' => 'th',
			'url' => 'https://thailand.opendevelopmentmekong.net',
			'url_pp' => 'https://odt.wp.odm2.staging.derilinx.com',
			'twitter' => 'opendevmekong'),
    'vietnam' => array('name' => 'Vietnam',
		       'theme' => 'vietnam',
		       'code' => 'vn',
		       'lang' => 'vi',
		       'url' => 'https://vietnam.opendevelopmentmekong.net',
		       'url_pp' => 'https://odv.wp.odm2.staging.derilinx.com',
		       'twitter' => 'opendevmekong')
		    ];

	function __construct() {
    //
	}

  function is_pp(){
    return strpos($_SERVER['HTTP_HOST'], 'staging.') !== false;
  }

  function get_current_country(){
    $options = get_option('odm_options');
    $current_country = 'mekong';
    if ( isset($options['style'])):
      $current_country = $options['style'];
    endif;
    return $current_country;
  }

	function get_current_country_code(){
    $current_country = $this->get_current_country();
		return $this->countries[$current_country]['code'];
  }
  
  function get_current_twitter(){
    $current_country = $this->get_current_country();
		return $this->countries[$current_country]['twitter'];
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

  function get_country_codes_iso2_list(){
    return array('kh','th','la','mm','vn');
  }

  function get_country_codes(){
    return [
      'mekong' => array(
        'iso2' =>'mekong',
        'iso3' =>'mekong'
      ),
      'cambodia' => array(
        'iso2' =>'kh',
        'iso3' =>'khm'
      ),
      'thailand' => array(
        'iso2' =>'th',
        'iso3' =>'tha'
      ),
      'laos' => array(
        'iso2' =>'la',
        'iso3' =>'lao'
      ),
      'myanmar' => array(
        'iso2' =>'mm',
        'iso3' =>'mmr'
      ),
      'vietnam' => array(
        'iso2' =>'vn',
        'iso3' =>'vnm'
      ),
    ];
  }

  function get_country_name($country){

    if (!array_key_exists($country,$this->countries)):
      return null;
    endif;

    return $this->countries[$country]['name'];
  }

	function get_country_name_by_country_code($country_code){

    foreach ($this->countries as $country):
			if ($country["code"] == $country_code):
				return $country['name'];
			endif;
		endforeach;

    return;
  }

  function echo_country_selectors(){
  ?>
    <ul class="country-selector">
      <?php
        foreach ($this->countries as $country):
          $url = $this->is_pp() ? $country['url_pp'] : $country['url'];
          if ($this->is_current_country($country)): ?>
            <li class="active-country"><?php od_logo_icon($country['name']); echo __($country['name']);?></li>
          <?php else: ?>
            <li><a href="<?php echo $url; ?>"><?php od_logo_icon($country['name']); ?><?php echo __($country['name']);?></a></li>
          <?php endif; ?>
      <?php
        endforeach;
      ?>
    </ul>
  <?php
  }

  function is_current_country($country){
    return $this->get_current_country() == $country['theme'];
  }

}

$GLOBALS['odm_country_manager'] = new Odm_Country_Manager();

function odm_country_manager() {
	return $GLOBALS['odm_country_manager'];
}

?>
