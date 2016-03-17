<?php
  $wpDomain=$_SERVER["HTTP_HOST"];
  $domain='opendevelopmentmekong.net';
  $preprod = false;
/*
  if ($wpDomain == 'cambodia.opendevelopmentmekong.net'){$country='cambodia';$country_short='kh';}
  else if ($wpDomain == 'laos.opendevelopmentmekong.net'){$country='laos';$country_short='la';}
  else if ($wpDomain == 'myanmar.opendevelopmentmekong.net'){$country='myanmar';$country_short='mm';}
  else if ($wpDomain == 'thailand.opendevelopmentmekong.net'){$country='thailand';$country_short='th';}
  else if ($wpDomain == 'vietnam.opendevelopmentmekong.net'){$country='vietnam';$country_short='vn';}
  else {$country='mekong';$country_short='';}


  if ($wpDomain == 'pp-cambodia.opendevelopmentmekong.net'){$country='cambodia';$country_short='kh';$preprod=true;}
  else if ($wpDomain == 'pp-laos.opendevelopmentmekong.net'){$country='laos';$country_short='la';$preprod=true;}
  else if ($wpDomain == 'pp-myanmar.opendevelopmentmekong.net'){$country='myanmar';$country_short='mm';$preprod=true;}
  else if ($wpDomain == 'pp-thailand.opendevelopmentmekong.net'){$country='thailand';$country_short='th';$preprod=true;}
  else if ($wpDomain == 'pp-vietnam.opendevelopmentmekong.net'){$country='vietnam';$country_short='vn';$preprod=true;}
  else {$country='mekong';$country_short='';} */

  if (COUNTRY_NAME == 'cambodia'){$country='cambodia';$country_short='kh';$preprod=true;}
  else if (COUNTRY_NAME == 'laos'){$country='laos';$country_short='la';$preprod=true;}
  else if (COUNTRY_NAME == 'myanmar'){$country='myanmar';$country_short='mm';$preprod=true;}
  else if (COUNTRY_NAME == 'thailand'){$country='thailand';$country_short='th';$preprod=true;}
  else if (COUNTRY_NAME == 'vietnam'){$country='vietnam';$country_short='vn';$preprod=true;}
  else {$country='mekong';$country_short='';}

  setcookie("odm_transition_country", $country, time()+3600, "/", ".opendevelopmentmekong.net");

  if ($wpDomain == '192.168.33.10'){
    $ckanDomain='192.168.33.10:8081';
  }
  else {
    $ckanDomain='data.opendevelopmentmekong.net';
    if ($preprod == true){
      $ckanDomain='pp-data.opendevelopmentmekong.net';
    }
  }
?>
<?php ?>
<!DOCTYPE html>
<html data-country="<?php echo $country; ?>" <?php language_attributes(); ?>>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
    global $page, $paged;

    wp_title('|', true, 'right');

    bloginfo('name');

    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        echo " | $site_description";
    }

    if ($paged >= 2 || $page >= 2) {
        echo ' | '.__('Page', 'jeo').max($paged, $page);
    }

    ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php $sit_name = str_replace('Open Development ', '', get_bloginfo('name')); ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/<?php echo strtolower($sit_name); ?>-favicon.ico" type="image/x-icon" />

<?php wp_head(); ?>

</head>
<body <?php body_class(get_bloginfo('language')); ?>>
  <div class="content_wrapper">
	<header class="white"id="od-head">

		<div class="organization">
			<div class="six columns left_organization">
				<div class="site-meta">
					<?php opendev_logo(); ?>
				</div>
			</div>
			<div class="language float-right">
					<?php if(function_exists(qtranxf_generateLanguageSelectCode)) qtranxf_generateLanguageSelectCode($type,$id);?>
				</div>
			<div class="right_organization">
				<div class="search">
					<div id="live-search">
						  <input type="text" placeholder="<?php _e('Search site... &#128269;', 'opendev');?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('Search site... &#128269;', 'opendev');?>'" />
						  <img src="<?php bloginfo('stylesheet_directory');?>/img/loading.gif" alt="loading" id="loading" />
						<div class="results-container"></div>
					</div><!-- live-search -->
				</div>
				<div class="social">
					<nav id="social-nav">
					  <?php
						$fb = opendev_get_facebook_url();
						if ($fb) :
					  ?>
						<a class="icon-facebook" href="<?php echo $fb; ?>" target="_blank" rel="external" title="Facebook"></a>
					  <?php
						endif;
					  ?>
					  <?php
						$tw = opendev_get_twitter_url();
						if ($tw) :
					  ?>
						<a class="icon-twitter" href="<?php echo $tw; ?>" target="_blank" rel="external" title="Twitter"></a>
					  <?php
						endif;
					  ?>
					  <?php
						$contact_id = opendev_get_contact_page_id();
						if ($contact_id) :
					  ?>
						<a class="icon-envelop" href="<?php echo get_permalink($contact_id); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/envelop.svg"></a>
					  <?php
						else:
					  ?>
					  <?php
						endif;
					  ?>
					</nav>
				</div>
			</div>
		</div>
		<!-- NEW NAV -->
	<div class="contentNavigation">
      <ul id="cNavNew" class="level1 clearfix current-site-mekong menu-<?php echo $country;?>">

        <!-- build top topics nav -->
        <?php if(function_exists(qtranxf_getLanguage)) buildStyledTopTopicNav(qtranxf_getLanguage());
              else buildStyledTopTopicNav('en');
        ?>

        <!-- <li class="one-line"><a href="/laws" target="_self"><?//php _e("Laws and agreements")?><span class="cNavState"></span></a>


        </li> -->

        <li class="one-line"><a class="library" href="http://<?php echo $ckanDomain; ?>/library_record<?php if ($country !='mekong') echo '?extras_odm_spatial_range=' . $country_short; ?>" target="_self"><?php _e("Publications Library"); ?><span class="cNavState"></span></a>


        </li>

        <li class="one-line">
          <a class="datahub" href="http://<?php echo $ckanDomain; ?><?php if ($country !='mekong' ) echo '/dataset?extras_odm_spatial_range=' . $country_short; ?>" target="_self"><?php _e("Data")?><span class="cNavState"></span></a>
          <ul class="level2">
            <li class="first"><a href="http://<?php echo $ckanDomain; ?><?php if ($country !='mekong') echo '/dataset?extras_odm_spatial_range=' . $country_short; ?>"target="_self"><?php _e("All records")?><span class="cNavState"></span></a></li>

            <li class="by_country"><a href="#" target="_self"><?php _e("Records by country")?><span class="cNavState"></span></a>
              <ul class="level3">
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=kh" target="_self"><?php _e("Cambodia")?><span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=la" target="_self"><?php _e("Laos")?><span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=mm" target="_self"><?php _e("Myanmar")?><span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=th" target="_self"><?php _e("Thailand")?><span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=vn" target="_self"><?php _e("Vietnam")?><span class="cNavState"></span></a></li>
                <span class="border"></span>
              </ul>
            </li>

            <li class="last by_country"><a href="#" target="_self"><?php _e("Records by language")?><span class="cNavState"></span></a>
              <ul class="level3">
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_language=en" target="_self"><?php _e("English")?><span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_language=km" target="_self"><?php _e("Khmer")?><span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_language=vi" target="_self"><?php _e("Vietnamese")?><span class="cNavState"></span></a></li>
                <span class="border"></span>
              </ul>

            </li>

            <span class="border"></span>
          </ul>

        </li>

        <li class="one-line">
          <a href="/map-explorer" target="_self"><?php _e("Maps")?><span class="cNavState"></span></a>
        </li>

        <?php wp_nav_menu( array(
          'menu' => 'country-specific-menu',
          'container'       => false,
          'items_wrap'      => '%3$s',
          'fallback_cb'     => false,
          'walker'          => new country_specific_sub_menus(),

       )); ?>
    </ul>

		<!-- end nav new -->
		<!-- #################### -->


	</div>
		<nav id="ms-nav">
			<div class="container">
				<div class="twelve columns">
					<?php opendev_ms_nav(); ?>
				</div>
			</div>
		</nav>
		<?php
        $options_msg = get_option('opendev_options');
        if (isset($options_msg['notice_message']) && $options_msg['notice_message'] != '') {
            ?>
            <nav id="notification-message">
                <div class="container">
                    <div class="twelve columns">
                        <div class="notification-message-box">
                          <?php echo $options_msg['notice_message'];
            ?>
                       </div>
                    </div>
    			</div>
            </nav>
        <?php

        } ?>
		<?php if (!is_front_page()) {
    ?>
		<nav id="main-breadcrumb"><br />
            <div class="container">
                <div class="twelve columns">
	               <?php the_breadcrumb();
    ?>
                </div>
			</div>
        </nav>
        <?php

} ?>
	</header>
    <?php //Add Contact form button
        if (function_exists('button_user_feedback_form')) {
            button_user_feedback_form();
        }
    ?>
		<div id="mainNav" class="mainNavOdc">
      <div class="mainNav-logo">
        <a href="/" target="_self">
          <span class="icon-od-logo"></span>

        </a>
      </div>

      <div class="mainNav-inner">
          <ul id="mainNavElement" class="level1 clearfix">
          <?php
          $get_all_sites = wp_get_sites();
          $site_index[1] = "first";
          $site_index[2] = "second";
          $site_index[3] = "third";
          $site_index[4] = "fourth";
          $site_index[5] = "fift";
          $site_index[6] = "last";
          //H.E I have no idea what is used for (Christ did them)
          $site_uid[1] = "uid-2";
          $site_uid[2] = "uid-3";
          $site_uid[3] = "uid-4";
          $site_uid[4] = "uid-42";
          $site_uid[5] = "uid-5";
          $site_uid[6] = "uid-5142";

          foreach ($get_all_sites as $site) {
               $blog_id = $site["blog_id"];
               $domain = get_site_url($blog_id);
               $site_details = get_blog_details($blog_id, 1);
               $country_name = str_replace('Open Development ', '', $site_details->blogname);
               $site_name = str_replace('Open Development ', '', get_bloginfo('name'));
            ?>
              <li class="<?php echo $site_index[$blog_id]?> <?php if (strtolower($country_name)=='mekong') echo 'jtop'; ?> <?php if ($country==strtolower($country_name)) echo 'act'; ?>">
                <a class="toCkan" data-country="<?php echo strtolower($country_name)?>" href="<?php echo $domain;?>" target="_self" id="<?php echo $site_uid[$blog_id]?> "><?php _e($country_name, "opendev");?>
                </a>
              </li>
          <?php } //end foreach ?>
        </ul>
        <!--<ul id="mainNavElement" class="level1 clearfix">
          <?php /*if ($preprod==true): ?>
	          <li class="first <?php if ($country=='mekong') echo 'act'; ?>"><a class="toCkan" data-country="mekong" href="https://pp.<?php echo $domain;?>" target="_self" id="uid-2"><?php _e("Mekong", "opendev");?></a></li>
	          <li class="second <?php if ($country=='cambodia') echo 'act'; ?>"><a class="toCkan" data-country="cambodia" href="https://pp-cambodia.<?php echo $domain;?>" id="uid-3"><?php _e("Cambodia", "opendev");?></a></li>
	          <li class="third <?php if ($country=='laos') echo 'act'; ?>"><a class="toCkan" data-country="laos" href="https://pp-laos.<?php echo $domain;?>" target="_self" id="uid-4"><?php _e("Laos", "opendev");?></a></li>
	          <li class="fourth <?php if ($country=='myanmar') echo 'act'; ?>"><a class="toCkan" data-country="myanmar" href="https://pp-myanmar.<?php echo $domain;?>" target="_self" id="uid-42"><?php _e("Myanmar", "opendev");?></a></li>
	          <li class="fift <?php if ($country=='thailand') echo 'act'; ?>"><a class="toCkan" data-country="thailand" href="https://pp-thailand.<?php echo $domain;?>" target="_self" id="uid-5"><?php _e("Thailand", "opendev");?></a></li>
	          <li class="last <?php if ($country=='vietnam') echo 'act'; ?>"><a class="toCkan" data-country="vietnam" href="https://pp-vietnam.<?php echo $domain;?>" target="_self" id="uid-5142"><?php _e("Vietnam", "opendev");?></a></li>
          <?php else: ?>
            <li class="first jtop <?php if ($country=='mekong') echo 'act'; ?>"><a class="toCkan" data-country="mekong" href="https://<?php echo $domain;?>" target="_self" id="uid-2"><?php _e("Mekong", "opendev");?></a></li>
	          <li class="second <?php if ($country=='cambodia') echo 'act'; ?>"><a class="toCkan" data-country="cambodia" href="https://cambodia.<?php echo $domain;?>" id="uid-3"><?php _e("Cambodia", "opendev");?></a></li>
	          <li class="third <?php if ($country=='laos') echo 'act'; ?>"><a class="toCkan" data-country="laos" href="https://laos.<?php echo $domain;?>" target="_self" id="uid-4"><?php _e("Laos", "opendev");?></a></li>
	          <li class="fourth <?php if ($country=='myanmar') echo 'act'; ?>"><a class="toCkan" data-country="myanmar" href="https://myanmar.<?php echo $domain;?>" target="_self" id="uid-42"><?php _e("Myanmar", "opendev");?></a></li>
	          <li class="fift <?php if ($country=='thailand') echo 'act'; ?>"><a class="toCkan" data-country="thailand" href="https://thailand.<?php echo $domain;?>" target="_self" id="uid-5"><?php _e("Thailand", "opendev");?></a></li>
	          <li class="last <?php if ($country=='vietnam') echo 'act'; ?>"><a class="toCkan" data-country="vietnam" href="https://vietnam.<?php echo $domain;?>" target="_self" id="uid-5142"><?php _e("Vietnam", "opendev");?></a></li>
          <?php endif; */ ?>
        </ul> -->
      </div>

    </div>
