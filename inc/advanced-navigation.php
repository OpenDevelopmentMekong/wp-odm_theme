<?php

/*
 * opendev
 * Advanced navigation
 */

class odm_AdvancedNav {

	var $prefix = 'odm_filter_';
	var $slug = 'explore';

	function __construct() {

		add_filter('query_vars', array($this, 'query_vars'));
		add_filter('body_class', array($this, 'body_class'));
		add_action('pre_get_posts', array($this, 'pre_get_posts'), 100);
		add_action('generate_rewrite_rules', array($this, 'generate_rewrite_rules'));
		add_action('template_redirect', array($this, 'template_redirect'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 110);

	}

	function query_vars($vars) {
		$vars[] = 'odm_advanced_nav';
		return $vars;
	}

	function body_class($class) {
		if(get_query_var('odm_advanced_nav'))
			$class[] = 'advanced-nav';
		return $class;
	}

	function generate_rewrite_rules($wp_rewrite) {
		$widgets_rule = array(
			$this->slug . '$' => 'index.php?odm_advanced_nav=1'
		);
		$wp_rewrite->rules = $widgets_rule + $wp_rewrite->rules;
	}

	function template_redirect() {
		if(get_query_var('odm_advanced_nav')) {
			add_filter('template_include', array($this, 'template'));
		}
	}

	function template() {
		return get_stylesheet_directory() . '/search.php';
	}

	function pre_get_posts($query) {

		if($query->is_main_query()) {

			if($query->get('odm_advanced_nav')) {
				$query->is_home = false;
				$query->set('posts_per_page', 30);
				$query->set('ignore_sticky_posts', true);
			}

			if(isset($_GET[$this->prefix . 's'])) {
				$query->set('s', $_GET[$this->prefix . 's']);
			}

			if(isset($_GET[$this->prefix . 'post_type'])) {
				$query->set('post_type', $_GET[$this->prefix . 'post_type']);
			}

			if(isset($_GET[$this->prefix . 'category'])) {
				$query->set('category__in', $_GET[$this->prefix . 'category']);
			}

			if(isset($_GET[$this->prefix . 'date_start'])) {

				$after = $_GET[$this->prefix . 'date_start'];
				$before = $_GET[$this->prefix . 'date_end'];

				if($after) {

					if(!$before)
						$before = date('Y-m-d H:i:s');

					$query->set('date_query', array(
						array(
							'after' => date('Y-m-d H:i:s', strtotime($after)),
							'before' => date('Y-m-d H:i:s', strtotime($before)),
							'inclusive' => true
						)
					));
				}

			}

		}

	}

	function enqueue_scripts() {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-ui-smoothness', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
	}

	function form() {

		$s = isset($_GET[$this->prefix . 's']) ? $_GET[$this->prefix . 's'] : '';
		$s = !$s && isset($_GET['s']) ? $_GET['s'] : $s;
		?>

		<section class="container">
			<div class="row">
				<div class="sixteen columns">
					<?php get_template_part('section', 'query-actions'); ?>
				</div>
			</div>
		</div>
		
		<form class="advanced-nav-filters <?php if(isset($_GET[$this->prefix])) echo 'active'; ?>">
			<input type="hidden" name="odm_advanced_nav" value="1" />
			<input type="hidden" name="<?php echo $this->prefix; ?>" value="1" />
			<div class="three columns">
				<div class="search-input adv-nav-input">
					<p class="label"><label for="<?php echo $this->prefix; ?>s"><?php _e('Text search', 'odm'); ?></label></p>
					<input type="text" id="<?php echo $this->prefix; ?>s" name="<?php echo $this->prefix; ?>s" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $s; ?>" />
				</div>
			</div>
			<?php
			$categories = get_categories();
			$active_cats = isset($_GET[$this->prefix . 'category']) ? $_GET[$this->prefix . 'category'] : array();
			if($categories) :
				?>
				<div class="three columns">
					<div class="category-input adv-nav-input">
						<p class="label"><label for="<?php echo $this->prefix; ?>category"><?php _e('Categories', 'odm'); ?></label></p>
						<select id="<?php echo $this->prefix; ?>category" name="<?php echo $this->prefix; ?>category[]" multiple data-placeholder="<?php _e('Select categories', 'odm'); ?>">
							<?php wp_list_categories(array('title_li' => '', 'walker' => new Odm_Walker_CategoryDropdown_Multiple(), 'selected' => $active_cats)); ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<?php
			$oldest = get_posts(array('posts_per_page' => 1, 'order' => 'ASC', 'orderby' => 'date'));
			$oldest = array_shift($oldest);
			$newest = get_posts(array('posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'date'));
			$newest = array_shift($newest);

			$before = $oldest->post_date;
			$after = $newest->post_date;
			?>
			<?php
			$post_types = get_post_types(array('public' => true), 'object');
			if($post_types) :
				unset($post_types['map']);
				unset($post_types['map-layer']);
				unset($post_types['map-group']);
				unset($post_types['attachment']);
				unset($post_types['rssmi_feed']);
				unset($post_types['rssmi_feed_item']);
				$active_types = isset($_GET[$this->prefix . 'post_type']) ? $_GET[$this->prefix . 'post_type'] : array();
				?>
				<div class="three columns">
					<div class="post-type-input adv-nav-input">
						<p class="label"><label for="<?php echo $this->prefix; ?>post_type"><?php _e('Content type', 'odm'); ?></label></p>
						<select id="<?php echo $this->prefix; ?>post_type" name="<?php echo $this->prefix; ?>post_type[]" multiple data-placeholder="<?php _e('Select content types', 'odm'); ?>">
							<?php foreach($post_types as $post_type) : ?>
								<option value="<?php echo $post_type->name; ?>" <?php if(in_array($post_type->name, $active_types)) echo 'selected'; ?>><?php echo $post_type->labels->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<div class="four columns">
				<div class="date-input adv-nav-input">
					<p class="label"><label for="<?php echo $this->prefix; ?>date_start"><?php _e('Date range', 'odm'); ?></label></p>
					<div class="date-range-inputs">
						<div class="date-from-container">
							<input type="text" class="date-from" id="<?php echo $this->prefix; ?>date_start" name="<?php echo $this->prefix; ?>date_start" placeholder="<?php _e('From', 'odm'); ?>" value="<?php echo (isset($_GET[$this->prefix . 'date_start'])) ? $_GET[$this->prefix . 'date_start'] : ''; ?>" />
						</div>
						<div class="date-to-container">
							<input type="text" class="date-to" id="<?php echo $this->prefix; ?>date_end" name="<?php echo $this->prefix; ?>date_end"  placeholder="<?php _e('To', 'odm'); ?>" value="<?php echo (isset($_GET[$this->prefix . 'date_end'])) ? $_GET[$this->prefix . 'date_end'] : ''; ?>" />
						</div>
					</div>
				</div>
			</div>
			<div class="three columns">
				<input class="button" type="submit" value="<?php _e('Search Filter', 'odm'); ?>"/>
			</div>
		</form>
		<script type="text/javascript">
			(function($) {

				$(document).ready(function() {

					var advNav = $('.advanced-nav-filters');

					if(advNav.hasClass('active')) {
						$('.toggle-more-filters a').text('<?php _e('Cancel filters', 'odm'); ?>');
					}

					$('.toggle-more-filters a').click(function() {

						if(advNav.hasClass('active')) {
							$(advNav).removeClass('active');
							window.location = '<?php echo remove_query_arg(array($this->prefix, $this->prefix . 's', $this->prefix . 'category', $this->prefix . 'date_start', $this->prefix . 'date_end')); ?>';
							$(this).text('<?php _e('More filters', 'odm'); ?>');
						} else {
							$(advNav).addClass('active');
							$(this).text('<?php _e('Cancel filters', 'odm'); ?>');
						}

						return false;

					});

					$('.post-type-input select').chosen();

					$('.category-input select').chosen();

					var min = moment('<?= $before; ?>').toDate();
					var max = moment('<?= $after; ?>').toDate();

					$('.date-range-inputs .date-from').datepicker({
						defaultDate: min,
						changeMonth: true,
						changeYear: true,
						numberOfMonths: 1,
						maxDate: max,
						minDate: min
					});

					$('.date-range-inputs .date-to').datepicker({
						defaultDate: max,
						changeMonth: true,
						changeYear: true,
						numberOfMonths: 1,
						maxDate: max,
						minDate: min
					});

				});

			})(jQuery);
		</script>
		<?php

	}

}

$GLOBALS['odm_adv_nav'] = new odm_AdvancedNav();

function odm_adv_nav_filters() {
	return $GLOBALS['odm_adv_nav']->form();
}

?>
