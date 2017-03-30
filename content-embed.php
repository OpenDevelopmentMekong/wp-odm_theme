<html id="map-embed" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . __('Page', 'odi') . max($paged, $page);

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" type="image/x-icon" />
<?php wp_head(); ?>
</head>
<body <?php body_class(get_bloginfo('language')); ?>>
	<?php
	if(isset($_SERVER['HTTP_REFERER'])):
		$reffer_domain = parse_url($_SERVER['HTTP_REFERER']);
	endif;

	if(isset($reffer_domain)):
		if($reffer_domain['host'] != $_SERVER["HTTP_HOST"]) {
			$add_odlogo = "-has-odlogo";
		?>
			<header id="embed-header">
				<h1><a href="<?php echo home_url('/'); ?>" target="_blank"><?php od_logo_icon(); ?> <?php bloginfo('name'); ?><span>&nbsp;</span></a></h1>
				<h2 id="embed-title" style="display:none;"><?php wp_title('', true, 'right'); ?></h2>
			</header>
 	<?php }
	endif; ?>
	<?php
	$mapID = get_embedded_map_id();
	$layerID = get_embedded_layer_id();
	?>
	<?php
	if(function_exists("display_embedded_map")){
		display_embedded_map($mapID, $layerID);
	}
	?>

	<input type="hidden" id="latitude" />
	<input type="hidden" id="longitude" />
	<input type="hidden" id="zoom" />
<?php wp_footer(); ?>
</body>
</html>
