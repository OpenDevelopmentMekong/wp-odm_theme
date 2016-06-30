<div class="share clearfix">
	<ul>
		<li>
			<div class="fb-share-button" data-href="<?php echo get_permalink( $post->ID )?>" data-send="false" data-layout="button" data-show-faces="false"></div>
		</li>
		<li>
			<div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a></div>
		</li>
		<li>
			<div class="g-plusone" data-width="50" data-annotation="none" data-size="tall" data-href="<?php the_permalink(); ?>" data-count="false"></div>
		</li>
	</ul>
</div>
