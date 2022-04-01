<?php

add_action('admin_menu', 'odm_add_modified_date_metabox');

function odm_add_modified_date_metabox()
{
    add_meta_box(
        'odm_modified_date_metabox',
        __('Updated date', 'odm'),
        'odm_render_modified_date_metabox',
        'topic',
        'side',
        'core'
    );
}

function odm_render_modified_date_metabox($post)
{
    wp_nonce_field(basename(__FILE__), 'odm_modified_date_metabox_nounce');

    $value = get_post_meta($post->ID, '_odm_modified_date_metabox', true);
?>
    <input role="presentation" autocomplete="off" type="text" name="odm_modified_date_metabox" id="date-picker" value="<?php echo (!empty($value)) ? $value : get_the_modified_date('j F Y'); ?>" />

    <script>
        jQuery(document).ready(function($) {
            $("#date-picker").datepicker({
                dateFormat: 'd MM yy'
            });
        });
    </script>
<?php
}

add_action('save_post', 'odm_save_modified_date_metabox_value');

function odm_save_modified_date_metabox_value($post_id)
{
    $modifiedDateNounce = 'odm_modified_date_metabox_nounce';

    if (!isset($modifiedDateNounce) || !wp_verify_nonce($_POST[$modifiedDateNounce], basename(__FILE__))) {
        return;
    }

    if (!current_user_can('edit_posts')) {
        return;
    }

    if (array_key_exists('odm_modified_date_metabox', $_POST)) {
        update_post_meta($post_id, '_odm_modified_date_metabox', $_POST['odm_modified_date_metabox']);
    }
}
