<?php

/*
 * opendev
 * Advanced navigation
 */

class odm_AdvancedNav {

	var $prefix = 'filter_';
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
		if(get_query_var('odm_advanced_nav') && !is_post_type_archive() && !empty($_GET[$this->prefix . 'post_type'])) {
			add_filter('template_include', array($this, 'template'));
		}
	}

	function template() {
			return get_stylesheet_directory() . '/search.php';
	}

	function pre_get_posts($query) {
		if($query->is_main_query() && !is_admin()) {
			if($query->get('odm_advanced_nav')) {
				$query->is_home = false;
				$query->set('posts_per_page', 30);
				$query->set('ignore_sticky_posts', true);
			}

			if(isset($_GET[$this->prefix . 's'])  && !empty($_GET[$this->prefix . 's'])) {
				$query->set('s', $_GET[$this->prefix . 's']);
			}

			if(isset($_GET[$this->prefix . 'post_type'])) {
				$query->set('post_type', $_GET[$this->prefix . 'post_type']);
			}

			if(isset($_GET[$this->prefix . 'category'])) {
				$query->set('category__in', $_GET[$this->prefix . 'category']);
			}

			if(isset($_GET[$this->prefix . 'taxonomy'])) {
				$taxonomy_name = array_keys($_GET[$this->prefix . 'taxonomy']);
				$selected_terms = $_GET[$this->prefix . 'taxonomy'][$taxonomy_name[0]];
				$term_ids = array_values($selected_terms);
		    $taxquery = array(
							        array(
							            'taxonomy' => $taxonomy_name[0],
							            'field' => 'id',
							            'terms' => $term_ids,
							            'operator'=> 'IN'
							        )
		    						);

			  $query->set( 'tax_query', $taxquery );
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
							'before' => date('Y-m-d H:i:s', strtotime($before. ' +1 day')),
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

	function form($filter_arg = null) {
		if(!isset($filter_arg) || empty($filter_arg)){
			$filter_arg =	array(
													'search_box' => true,
													'cat_selector' => true,
													'con_selector' => true,
													'date_rang' => true
												 );
		}
		$filter_taxonomy = "category";
		$taxonomy_depth = 2;

		if(isset($filter_arg['taxonomy']) && !empty($filter_arg['taxonomy'])):
			$filter_taxonomy = $filter_arg['taxonomy'];
		endif;

		if(isset($filter_arg['depth']) && !is_null($filter_arg['depth'])):
			$taxonomy_depth = $filter_arg['depth'];
		endif;

		$s = isset($_GET[$this->prefix . 's']) ? $_GET[$this->prefix . 's'] : '';
		$s = !$s && isset($_GET['s']) ? $_GET['s'] : $s;
		?>

		<form class="advanced-nav-filters <?php if(isset($_GET[$this->prefix])) echo 'active'; ?>">
			<input type="hidden" name="odm_advanced_nav" value="1" />
			<input type="hidden" name="<?php echo $this->prefix; ?>" value="1" />
			<?php
			if(!$filter_arg['con_selector'] && isset($filter_arg['post_type'])):
				$filter_posttype = isset($_GET[$this->prefix . 'post_type']) ? $_GET[$this->prefix . 'post_type'][0] : $filter_arg['post_type'];
				?>
				<input type="hidden" name="<?php echo $this->prefix; ?>post_type[]" value="<?php echo $filter_posttype; ?>" />
			<?php endif; ?>

			<?php if($filter_arg['search_box']): ?>
				<div class="four columns">
					<div class="search-input adv-nav-input">
						<p class="label"><label for="<?php echo $this->prefix; ?>s"><?php _e('Text search', 'odm'); ?></label></p>
						<input type="text" id="<?php echo $this->prefix; ?>s" name="<?php echo $this->prefix; ?>s" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $s; ?>" />
					</div>
				</div>
			<?php endif;?>
			<?php if($filter_arg['cat_selector']):?>
				<?php
				$categories = get_categories();
				if($filter_taxonomy != "category"):
					$categories = get_terms($filter_taxonomy);
				endif;
				if($categories) :
					?>
					<div class="four columns">
						<div class="category-input adv-nav-input">
						<p class="label"><label for="<?php echo $this->prefix . $filter_taxonomy; ?>"><?php _e('Topic', 'odm'); ?></label></p>
						<?php
						$active_cats = isset($_GET[$this->prefix . 'category']) ? $_GET[$this->prefix . 'category'] : array();
						if($filter_taxonomy == "category"):
							?>
							<select id="<?php echo $this->prefix; ?>category" name="<?php echo $this->prefix; ?>category[]" multiple data-placeholder="<?php _e('Select categories', 'odm'); ?>">
								<?php wp_list_categories(array('title_li' => '', 'category' => $filter_taxonomy, 'walker' => new Odm_Walker_CategoryDropdown_Multiple(), 'depth' => $taxonomy_depth, 'selected' => $active_cats)); ?>
							</select>
							<?php
						else:
							$active_cats = isset($_GET[$this->prefix . 'taxonomy']) ? $_GET[$this->prefix . 'taxonomy'][$filter_taxonomy] : array();
							?>
							<select id="<?php echo $this->prefix; ?>taxonomy" name="<?php echo $this->prefix; ?>taxonomy[<?php echo $filter_taxonomy; ?>][]" multiple data-placeholder="<?php _e('Select categories', 'odm'); ?>">
								<?php wp_list_categories(array('title_li' => '', 'taxonomy' => $filter_taxonomy, 'walker' => new Odm_Walker_CategoryDropdown_Multiple(), 'depth' => $taxonomy_depth, 'selected' => $active_cats)); ?>
							</select>
						<?php
						endif;
						?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if($filter_arg['con_selector'] && !is_post_type_archive() ):?>
				<?php
				$post_types = available_post_types_search("object");

				if($post_types) :
					$active_types = isset($_GET[$this->prefix . 'post_type']) ? $_GET[$this->prefix . 'post_type'] : array();
					?>
					<div class="four columns">
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
			<?php endif; ?>

			<?php if($filter_arg['date_rang']):?>
				<?php
				$oldest_post_arg = array('posts_per_page' => 1, 'order' => 'ASC', 'orderby' => 'date' );
				$newest_post_arg = array('posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'date');

				if(isset($filter_arg['post_type'])):
					$oldest_post_arg['post_type'] = $filter_arg['post_type'];
					$newest_post_arg['post_type'] = $filter_arg['post_type'];
				else:
					$oldest_post_arg['post_type'] = available_post_types_search();
					$newest_post_arg['post_type'] = available_post_types_search();
				endif;

				$oldest = get_posts($oldest_post_arg);
				$oldest = array_shift($oldest);
				$newest = get_posts($newest_post_arg);
				$newest = array_shift($newest);
				$before = $oldest->post_date;
				$after = $newest->post_date;
				?>
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
			<?php endif; ?>
			<div class="four columns">
				<input class="button" type="submit" value="<?php _e('Search filter', 'odm'); ?>"/>
				<a class="float-right" href="<?php echo get_bloginfo("url").strtok($_SERVER["REQUEST_URI"],'?'); ?>"><?php _e("Clear filter", "odm"); ?></a>
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
						minDate: min,
						dayNamesMin: [
								"<?php _e("Su","odm");?>",
								"<?php _e("Mo","odm");?>",
								"<?php _e("Tu","odm");?>",
								"<?php _e("We","odm");?>",
								"<?php _e("Th","odm");?>",
								"<?php _e("Fr","odm");?>",
								"<?php _e("Sa","odm");?>"
						],
						monthNamesShort: [
								"<?php _e("Jan","odm");?>",
								"<?php _e("Feb","odm");?>",
								"<?php _e("Mar","odm");?>",
								"<?php _e("Apr","odm");?>",
								"<?php _e("May","odm");?>",
								"<?php _e("Jun","odm");?>",
								"<?php _e("Jul","odm");?>",
								"<?php _e("Aug","odm");?>",
								"<?php _e("Sep","odm");?>",
								"<?php _e("Oct","odm");?>",
								"<?php _e("Nov","odm");?>",
								"<?php _e("Dec","odm");?>" ]
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

function odm_adv_nav_filters($filter_arg=null) {
	return $GLOBALS['odm_adv_nav']->form($filter_arg);
}

?>
