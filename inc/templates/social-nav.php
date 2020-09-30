<nav>
	<?php
	$iconFacebook = odm_get_facebook_url();
	if ($iconFacebook) : ?>
		<a href="<?php echo $iconFacebook; ?>" target="_blank" rel="external" title="Facebook"><i class="fa fa-facebook-official"></i></a>
	<?php endif; ?>
	
	<?php
	$iconTwitter = odm_get_twitter_url();
	if ($iconTwitter) : ?>
		<a href="<?php echo $iconTwitter; ?>" target="_blank" rel="external" title="Twitter"><i class="fa fa-twitter-square"></i></a>
	<?php endif; ?>
	
	<?php
	$iconYoutube = odm_get_youtube_url();
	if ($iconYoutube) : ?>
		<a href="<?php echo $iconYoutube; ?>" target="_blank" rel="external" title="YouTube Channel"><i class="fa fa-youtube-play"></i></a>
	<?php endif; ?>

	<?php
	$iconSubscribe = odm_get_contact_page_id();
	if ($iconSubscribe) : ?>
		<a href="<?php echo get_permalink($iconSubscribe); ?>" title="Subscribe"><i class="fa fa-envelope"></i></a>
	<?php endif; ?>

	<?php
	$iconPrint = odm_show_print_icon();
	if ($iconPrint) : ?>
		<a class="btn-printer" onclick="window.print()" title="Print"><i class="fa fa-print"></i></a>
	<?php endif; ?>
</nav>