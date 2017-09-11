
</article>

<footer id="od-footer">
  <!-- back-to-top -->
  <div class="container">
    <div class="row">
      <div class="sixteen colunms">
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
  			<p><?php printf(__('This website is built on <a href="%s" target="_blank" rel="external">WordPress</a> using the <a href="%s" target="_blank" rel="external">JEO Beta</a> theme', 'odm'), 'http://wordpress.org', 'https://github.com/oeco/jeo'); ?></p>
  		</div>
			<?php
		  if(odm_country_manager()->get_current_country() != 'cambodia'): ?>
				<div class="three columns">
					<a href="http://ewmi.org/ODI" target="_blank"><img src="<?php echo get_stylesheet_directory_uri()?>/img/odi_logo.png"></img></a>
				</div>
			<?php
			endif;
			?>
    </div>

	</div>
</footer>

<?php wp_footer(); ?>

</div>

<script type="text/javascript">
  var _mare_pk = '30b24caf7c83f7a2eefb630abe4da0eb64b90de36d0eabeb12b0d3421c688e0f';
  var _mare_sc = '00fdba319ea2ad38cdce9fe19ea4bb7b';
  (function() {
    var mare = document.createElement('script'); mare.type = "text/javascript"; mare.async = true;
    mare.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'mare.io/API/script.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mare, s);
  })();
</script>


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
