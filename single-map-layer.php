<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <section class="container section-title main-title">
		<header class="row">
			<div class="sixteen columns">
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
		<div class="sixteen columns">
			<?php
				$mapID = get_embedded_map_id();
				$layerID = get_the_ID();
				?>
				<?php
				if(function_exists("display_embedded_map")){
					display_embedded_map($mapID, $layerID);
				}
			?>
	</div>
</section>
<?php endif; ?>
<?php get_footer(); ?>
