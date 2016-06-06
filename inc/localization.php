<?php

function get_localization_language_by_language_code($lang_code = 'en')
{
    $language['en'] = 'English';
    $language['kh'] = 'Khmer';
    $language['km'] = 'Khmer';
    $language['lo'] = 'Lao';
    $language['my'] = 'Burmese';
    $language['th'] = 'Thai';
    $language['vi'] = 'Vietnamese';

    return $language[$lang_code];
}

function get_the_localization_language_by_website($site = '')
{
    $site_name = str_replace('Open Development ', '', get_bloginfo('name'));
    $language['ODM'] = '';
    $language['Cambodia'] = 'Khmer';
    $language['Laos'] = 'Lao';
    $language['Myanmar'] = 'Burmese';
    $language['Thailand'] = 'Thai';
    $language['Vietnam'] = 'Vietnamese';

    return $language[$site_name];
}
