<?php get_header();

$options = get_option('odm_options');
$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created"; ?>

<div class="section-title main-title">

    <section class="container">
        <header class="row">
            <div class="eight columns">
                <h1 class="ellipsis"><?php _e('Topics', 'odm') ?></h1>
            </div>
            <div class="eight columns align-right">
                <?php get_template_part('section', 'query-actions'); ?>
            </div>
        </header>
    </section>

    <section class="container">
        <div class="row">
            <div class="sixteen columns filter-container">
                <div class="panel more-filters-content row">
                    <?php
                    $filter_arg = array(
                        'search_box'    => true,
                        'cat_selector'  => true,
                        'con_selector'  => false,
                        'date_rang'     => true,
                        'post_type'     => get_post_type()
                    );
                    odm_adv_nav_filters($filter_arg);
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="sixteen columns">
                <?php
                $taxonomy_categories_1 = ["agriculture-and-fishing", "disaster-and-emergency-response", "environment-and-natural-resources", "extractive-industries", "land"];
                $taxonomy_categories_2 = ["economy-and-commerce", "energy", "industry", "infrastructure", "labor", "science-and-technology"];
                $taxonomy_categories_3 = ["aid-and-development", "government", "law-and-judiciary", "population-and-censuses", "social-development", "urban-administration-and-development"];

                $sdg = get_term_by('slug', 'sustainable-development-goals', 'category');
                if ($sdg) {
                    $sdg_children = get_terms('category', array('parent' => $sdg->term_id));
                    $taxonomy_categories_4 = array();

                    foreach ($sdg_children as $child) {
                        array_push($taxonomy_categories_4, $child->slug);
                    }
                ?>

                    <h3 class="clearfix"><a href="/topics/sustainable-development-goals"><?php _e($sdg->name, 'odm'); ?></a></h3>
                <?php
                    while (have_posts()) : the_post();
                        $post = get_post();
                        $top_level_cat_names = get_top_level_category_slugs(get_the_category($post->ID));
                        if (arrays_have_common_items($top_level_cat_names, $taxonomy_categories_4)) :
                            odm_get_template('post-grid-single-4-cols', array(
                                'post'          => $post,
                                'show_meta'     => true,
                                'meta_fields'   => array('date'),
                                'orderby'       => 'title',
                                'order'         => 'asc'
                            ), true);
                        endif;
                    endwhile;

                    rewind_posts();
                }
                ?>

                <h3 class="clearfix"><?php _e('Environment and land', 'odm'); ?></h3>

                <?php
                while (have_posts()) : the_post();
                    $post = get_post();
                    $top_level_cat_names = get_top_level_category_slugs(get_the_category($post->ID));
                    if (arrays_have_common_items($top_level_cat_names, $taxonomy_categories_1)) :
                        odm_get_template('post-grid-single-4-cols', array(
                            "post"          => $post,
                            "show_meta"     => true,
                            "meta_fields"   => array("date"),
                            "order"         => $date_to_show
                        ), true);
                    endif;
                endwhile;

                rewind_posts(); ?>

                <h3 class="clearfix"><?php _e('Economy and industry', 'odm'); ?></h3>

                <?php
                while (have_posts()) : the_post();
                    $post = get_post();
                    $top_level_cat_names = get_top_level_category_slugs(get_the_category($post->ID));

                    if (arrays_have_common_items($top_level_cat_names, $taxonomy_categories_2)) :
                        odm_get_template('post-grid-single-4-cols', array(
                            "post"          => $post,
                            "show_meta"     => true,
                            "meta_fields"   => array("date"),
                            "order"         => $date_to_show
                        ), true);
                    endif;
                endwhile;

                rewind_posts(); ?>

                <h3 class="clearfix"><?php _e('People and government', 'odm'); ?></h3>

                <?php
                while (have_posts()) : the_post();
                    $post = get_post();
                    $top_level_cat_names = get_top_level_category_slugs(get_the_category($post->ID));

                    if (arrays_have_common_items($top_level_cat_names, $taxonomy_categories_3)) :
                        odm_get_template('post-grid-single-4-cols', array(
                            "post"          => $post,
                            "show_meta"     => true,
                            "meta_fields"   => array("date"),
                            "order"         => $date_to_show
                        ), true);
                    endif;
                endwhile; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer();
