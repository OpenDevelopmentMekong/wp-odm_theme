<?php
    $wpDomain=$_SERVER["HTTP_HOST"];
    $domain='opendevelopmentmekong.net';
    $preprod = false;

    if($wpDomain == 'opendevelopmentmekong.net'){$country='mekong';}
    else if ($wpDomain == 'cambodia.opendevelopmentmekong.net'){$country='cambodia';$country_short='kh';}
    else if ($wpDomain == 'laos.opendevelopmentmekong.net'){$country='laos';$country_short='la';}
    else if ($wpDomain == 'myanmar.opendevelopmentmekong.net'){$country='myanmar';$country_short='mm';}
    else if ($wpDomain == 'thailand.opendevelopmentmekong.net'){$country='thailand';$country_short='th';}
    else if ($wpDomain == 'vietnam.opendevelopmentmekong.net'){$country='vietnam';$country_short='vn';}
    else {$country='mekong';$country_short='';}

    if($wpDomain == 'pp.opendevelopmentmekong.net'){$country='mekong';$preprod=true;}
    else if ($wpDomain == 'pp-cambodia.opendevelopmentmekong.net'){$country='cambodia';$country_short='kh';$preprod=true;}
    else if ($wpDomain == 'pp-laos.opendevelopmentmekong.net'){$country='laos';$country_short='la';$preprod=true;}
    else if ($wpDomain == 'pp-myanmar.opendevelopmentmekong.net'){$country='myanmar';$country_short='mm';$preprod=true;}
    else if ($wpDomain == 'pp-thailand.opendevelopmentmekong.net'){$country='thailand';$country_short='th';$preprod=true;}
    else if ($wpDomain == 'pp-vietnam.opendevelopmentmekong.net'){$country='vietnam';$country_short='vn';$preprod=true;}
    else {$country='mekong';$country_short='';}

    setcookie("odm_transition_country", $country, time()+3600, "/", ".opendevelopmentmekong.net");

    if ($wpDomain == '192.168.33.10'){$ckanDomain='192.168.33.10:8081';}
    else {$ckanDomain='pp-data.opendevelopmentmekong.net';}
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
		<div class="container">
			<div class="six columns organization">
				<div class="site-meta">
          <?php opendev_logo(); ?>
				</div>
			</div>
      <div class="three columns social">
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
			<div class="three columns social">
        <div id="live-search">
          <div class="container">
            <div class="three columns">
              <input type="text" placeholder="<?php _e('Search', 'opendev');?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('Search &#128270;', 'opendev');?>'" />
              <img src="<?php bloginfo('stylesheet_directory');?>/img/loading.gif" alt="loading" id="loading" />
            </div>
            <div class="results-container"></div>
          </div>
        </div><!-- live-search -->

  		</div>

		</div><!-- four column -->

		<!-- NEW NAV -->
		<div class="contentNavigation">

      <ul id="cNavNew" class="level1 clearfix current-site-mekong">

        <!-- build top topics nav -->
        <?php buildStyledTopTopicNav('en');?>

        <li class="one-line"><a href="/laws" target="_self">LAWS AND AGREEMENTS<span class="cNavState"></span></a>


        </li>

        <li class="one-line"><a class="library" href="http://<?php echo $ckanDomain; ?>/library_record<?php if ($country !='mekong') echo '?extras_odm_spatial_range=' . $country_short; ?>" target="_self">Publications Library<span class="cNavState"></span></a>


        </li>

        <li class="one-line">
          <a class="datahub" href="http://<?php echo $ckanDomain; ?><?php if ($country !='mekong' ) echo '/dataset?extras_odm_spatial_range=' . $country_short; ?>" target="_self">Data<span class="cNavState"></span></a>
          <ul class="level2">
            <li class="first"><a href="http://<?php echo $ckanDomain; ?><?php if ($country !='mekong') echo '/dataset?extras_odm_spatial_range=' . $country_short; ?>"target="_self">All records<span class="cNavState"></span></a></li>

            <li class="by_country"><a href="#" target="_self">Records by country<span class="cNavState"></span></a>
              <ul class="level3">
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=cambodia" target="_self">Cambodia<span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=laos" target="_self">Laos<span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=myanmar" target="_self">Myanmar<span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=thailand" target="_self">Thailand<span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_spatial_range=vietnam" target="_self">Vietnam<span class="cNavState"></span></a></li>
                <span class="border"></span>
              </ul>
            </li>

            <li class="last by_country"><a href="#" target="_self">Records by language<span class="cNavState"></span></a>
              <ul class="level3">
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_language=en" target="_self">EN<span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_language=km" target="_self">KH<span class="cNavState"></span></a></li>
                <li class="first"><a href="http://<?php echo $ckanDomain; ?>/dataset?extras_odm_language=vi" target="_self">VI<span class="cNavState"></span></a></li>
                <span class="border"></span>
              </ul>

            </li>

            <span class="border"></span>
          </ul>

        </li>

        <li class="one-line">
          <a href="/map-explorer" target="_self">Maps<span class="cNavState"></span></a>
        </li>

        <li class="one-line">
          <a href="/economic-land-concessions" target="_self">ELC<span class="cNavState"></span></a>
        </li>

        <?php wp_nav_menu( array(
          'menu' => 'country-specific-menu',
          'container'       => false,
          'items_wrap'      => '%3$s',
          'fallback_cb'     => false

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
          <?php if ($preprod==true): ?>
	          <li class="first jtop <?php if ($country=='mekong') echo 'act'; ?>"><a class="toCkan" data-country="mekong" href="https://pp.<?php echo $domain;?>" target="_self" id="uid-2">MEKONG</a></li>
	          <li class="second <?php if ($country=='cambodia') echo 'act'; ?>"><a class="toCkan" data-country="cambodia" href="https://pp-cambodia.<?php echo $domain;?>" id="uid-3">CAMBODIA</a></li>
	          <li class="third <?php if ($country=='laos') echo 'act'; ?>"><a class="toCkan" data-country="laos" href="https://pp-laos.<?php echo $domain;?>" target="_self" id="uid-4">LAOS</a></li>
	          <li class="fourth <?php if ($country=='myanmar') echo 'act'; ?>"><a class="toCkan" data-country="myanmar" href="https://pp-myanmar.<?php echo $domain;?>" target="_self" id="uid-42">MYANMAR</a></li>
	          <li class="fift <?php if ($country=='thailand') echo 'act'; ?>"><a class="toCkan" data-country="thailand" href="https://pp-thailand.<?php echo $domain;?>" target="_self" id="uid-5">THAILAND</a></li>
	          <li class="last <?php if ($country=='vietnam') echo 'act'; ?>"><a class="toCkan" data-country="vietnam" href="https://pp-vietnam.<?php echo $domain;?>" target="_self" id="uid-5142">VIETNAM</a></li>
          <?php else: ?>
            <li class="first jtop <?php if ($country=='mekong') echo 'act'; ?>"><a class="toCkan" data-country="mekong" href="https://<?php echo $domain;?>" target="_self" id="uid-2">MEKONG</a></li>
	          <li class="second <?php if ($country=='cambodia') echo 'act'; ?>"><a class="toCkan" data-country="cambodia" href="https://cambodia.<?php echo $domain;?>" id="uid-3">CAMBODIA</a></li>
	          <li class="third <?php if ($country=='laos') echo 'act'; ?>"><a class="toCkan" data-country="laos" href="https://laos.<?php echo $domain;?>" target="_self" id="uid-4">LAOS</a></li>
	          <li class="fourth <?php if ($country=='myanmar') echo 'act'; ?>"><a class="toCkan" data-country="myanmar" href="https://myanmar.<?php echo $domain;?>" target="_self" id="uid-42">MYANMAR</a></li>
	          <li class="fift <?php if ($country=='thailand') echo 'act'; ?>"><a class="toCkan" data-country="thailand" href="https://thailand.<?php echo $domain;?>" target="_self" id="uid-5">THAILAND</a></li>
	          <li class="last <?php if ($country=='vietnam') echo 'act'; ?>"><a class="toCkan" data-country="vietnam" href="https://vietnam.<?php echo $domain;?>" target="_self" id="uid-5142">VIETNAM</a></li>
          <?php endif; ?>
        </ul>
      </div>

    </div>
