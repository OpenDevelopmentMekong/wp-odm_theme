
</article>

<footer id="od-footer">
  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
	<div class="container">
		<!-- Legal disclaimer -->
    <div class="row">
      <div class="twelve columns">
        <?php
              $disclaimer = opendev_get_legal_disclaimer();
              if ($disclaimer) : ?>
      			<div class="twelve columns row">
      				<?php echo wpautop($disclaimer); ?>
      			</div>
    		<?php endif; ?>
      </div>
    </div>

		<!-- Footer menu -->
		<div class="row">
      <div class="seven columns">
  			<nav id="footer-nav">
  				<?php wp_nav_menu(array('theme_location' => 'footer_menu')); ?>
  			</nav>
  		</div>
  		<div class="five columns">
  			<div class="credits">
  				<p><?php printf(__('This website is built on <a href="%s" target="_blank" rel="external">WordPress</a> using the <a href="%s" target="_blank" rel="external">JEO Beta</a> theme', 'jeo'), 'http://wordpress.org', 'https://github.com/oeco/jeo'); ?></p>
  			</div>
  		</div>
    </div>

	</div>
</footer>

<?php wp_footer(); ?>

</div>
</body>
</html>

<script type="text/javascript">

jQuery(document).ready(function() {
    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
    });

    jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});

</script>
