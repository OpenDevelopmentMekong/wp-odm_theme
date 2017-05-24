<?php
get_header();

$options = get_option('odm_options');
$template = isset($options['category_page_template']) ? $options['category_page_template'] : "default";

if ($template == 'latest'):
  include plugin_dir_path(__FILE__). 'inc/templates/category-page-latest.php';
else:
  include plugin_dir_path(__FILE__). 'inc/templates/category-page-default.php';
endif;

?>

<?php get_footer(); ?>
