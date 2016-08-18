
</article>

<footer id="od-footer">
  <!-- back-to-top -->
  <div class="container">
    <div class="row">
      <div class="sixteen columns">
        <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
      </div>
    </div>
  </div>

  <div class="container">
		<!-- Legal disclaimer -->
    <div class="row">
      <div class="sixteen columns">
        <?php
          $disclaimer = odm_get_legal_disclaimer();
          if ($disclaimer) :
  				      echo wpautop($disclaimer);
		      endif;
        ?>
      </div>
    </div>

		<!-- Footer menu -->
		<div class="row">
      <div class="nine columns">
  			<nav id="footer-nav">
  				<?php
          if (has_nav_menu('footer_menu'))
            wp_nav_menu(array('theme_location' => 'footer_menu'));
          ?>
  			</nav>
  		</div>

      <div class="four columns">
  			<p><?php printf(__('This website is built on <a href="%s" target="_blank" rel="external">WordPress</a> using the <a href="%s" target="_blank" rel="external">JEO Beta</a> theme', 'jeo'), 'http://wordpress.org', 'https://github.com/oeco/jeo'); ?></p>
  		</div>
      <div class="three columns">
  			<a href="http://ewmi.org/ODI" target="_blank"><img src="<?php echo get_stylesheet_directory_uri()?>/img/odi_logo.png"></img></a>
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
