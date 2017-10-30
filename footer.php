
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
  			<p><?php printf(__('This website is built on <a href="%s" target="_blank" rel="external"><b>WordPress</b></a> using the <a href="%s" target="_blank" rel="external"><b>JEO Beta</b></a> theme and <a href="%s" target="_blank" rel="external"><b>CKAN</b></a> as back-end for structured data. To learn more about the system architecture, read our  <a href="%s" target="_blank" rel="external"><b>documentation</b></a>.', 'odm'), 'http://wordpress.org', 'https://github.com/oeco/jeo', 'https://ckan.org', 'https://wiki.opendevelopmentmekong.net/public:system_s_architecture'); ?></p>
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
  var _mare_pk = '1e34dd25f7b430a1a7cb09454576b4612162fc56cff1ba7b4648e6fc9eacb7c4';
  var _mare_sc = 'ee0c15de450f47a43b0070298881dc21';
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
