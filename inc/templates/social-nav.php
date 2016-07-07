<nav id="social-nav">
	<?php
			$fb = odm_get_facebook_url();
			if ($fb) : ?>
				<a class="icon-facebook" href="<?php echo $fb; ?>" target="_blank" rel="external" title="Facebook"></a>
		<?php
			endif; ?>
	<?php
			$tw = odm_get_twitter_url();
			if ($tw) : ?>
				<a class="icon-twitter" href="<?php echo $tw; ?>" target="_blank" rel="external" title="Twitter"></a>
	<?php
			endif; ?>
	<?php
			$contact_id = odm_get_contact_page_id();
			if ($contact_id) : ?>
				<a class="icon-envelop" href="<?php echo get_permalink($contact_id); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/envelop.svg"></a>
	<?php
		else: ?>
	<?php
		endif; ?>
</nav>
