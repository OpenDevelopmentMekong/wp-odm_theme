<nav>
	<?php
			$fb = odm_get_facebook_url();
			if ($fb) : ?>
				<a href="<?php echo $fb; ?>" target="_blank" rel="external" title="Facebook"><i class="fa fa-facebook-official"></i></a>
		<?php
			endif; ?>
	<?php
			$tw = odm_get_twitter_url();
			if ($tw) : ?>
				<a href="<?php echo $tw; ?>" target="_blank" rel="external" title="Twitter"><i class="fa fa-twitter-square"></i></a>
	<?php
			endif; ?>
	<?php
			$contact_id = odm_get_contact_page_id();
			if ($contact_id) : ?>
				<a href="<?php echo get_permalink($contact_id); ?>"><i class="fa fa-envelope"></i></a>
	<?php
			endif; ?>
</nav>
