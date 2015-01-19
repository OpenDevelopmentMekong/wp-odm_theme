<?php

/*
 * OpenDev
 * Summary
 */

class OpenDev_Summary {

	function __construct() {
		add_action('the_content', array($this, 'hashed_heading'));
	}

	/*
	 * Inject content titles with hash link
	 */
	function hashed_heading($content) {

		$content .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

		$dom = new DOMDocument('1.0', 'UTF-8');
		@$dom->loadHTML($content);

		$tags = array('h1','h2','h3','h4','h5','h6');

		foreach($tags as $tag) {

			$elements = $dom->getElementsByTagname($tag);

			if($elements) {

				foreach($elements as $el) {

					if($el->getElementsByTagname('a')->length)
						continue;

					$name = $el->nodeValue;

					$el->setAttribute('id', sanitize_title($name));
					$el->setAttribute('class', 'summary-item');
					$link = $dom->createElement('a');
					$link->setAttribute('href', '#' . sanitize_title($name));
					$link->nodeValue = htmlspecialchars($name);

					/*
					 * Enable link for now
					 *
					$link = $dom->createElement('span');
					$link->setAttribute('data-href', '#' . sanitize_title($name));
					$link->nodeValue = $name;
					*/

					$el->nodeValue = '';
					$el->appendChild($link);

				}

			}

		}

		$content = $dom->saveHTML();

		return $content;

	}

	function summary() {
		wp_register_script('imagesloaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.min.js', array('jquery'));
		// wp_register_script('hashchange', get_stylesheet_directory_uri() . '/js/jquery.hashchange.min.js', array('jquery'));
		// wp_register_script('follow-scroll', get_stylesheet_directory_uri() . '/js/jquery.followScroll.js', array('jquery', 'imagesloaded'));
		wp_enqueue_script('opendev-summary', get_stylesheet_directory_uri() . '/js/summary.js', array('jquery'));
		?>
		<div class="opendev-summary">
			<h2 class="widget-title"><?php _e('Table of Contents', 'opendev'); ?></h2>
			<div class="table-of-contents">
			</div>
		</div>
		<?php
	}

}

$GLOBALS['opendev_summary'] = new OpenDev_Summary();


function opendev_summary() {
	global $opendev_summary;
	return $opendev_summary->summary();
}