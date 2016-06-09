
</article>

<footer id="colophon">
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
