<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
  <section class="container main-title">
		<header class="row">
			<div class="sixteen columns">
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
	</section>
	<section id = "map">
			<?php
				$mapID = get_embedded_map_id();
				$layerID = get_the_ID();
				?>
				<?php
				if(function_exists("display_embedded_map")){
					display_embedded_map($mapID, $layerID);
				}
			?>
	</section>
<?php endif; ?>
<?php get_footer(); ?>
