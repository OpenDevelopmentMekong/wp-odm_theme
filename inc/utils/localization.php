<?php

function get_localization_language_by_language_code($lang_code = 'en')
{
    $language['en'] = 'English';
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

    return isset($language[$site_name]) ? $language[$site_name] : '';
}

function getMultilingualValueOrFallback($field, $lang, $fallback)
{
    if (!isset($field[$lang]) || empty($field[$lang])):
      return $fallback;
    endif;

    return $field[$lang];
}

/****** Add function convert date, H-E/**/
function convert_date_to_kh_date($date_string, $splitted_by = '.')
{
    if (odm_language_manager()->get_current_language() == 'km') {
        $splitted_date = explode($splitted_by, $date_string);
        $joined_date = '';
        if (count($splitted_date) > 1) {
            if (strlen($date_string) == 7) {
                $month_year = $splitted_date;
                if ($month_year[0] != '00') {
                    $joined_date .= ' ខែ'.convert_to_kh_month($month_year[0]);
                }
                if ($month_year[1] != '0000') {
                    $joined_date .= ' ឆ្នាំ'.convert_to_kh_number($month_year[1]);
                }
            } else {
                $day_month_year = $splitted_date;
                if ($day_month_year[0] != '00') {
                    $joined_date .= 'ថ្ងៃទី '.convert_to_kh_number($day_month_year[0]);
                }
                if ($day_month_year[1] != '00') {
                    $joined_date .= ' ខែ'.convert_to_kh_month($day_month_year[1]);
                }
                if ($day_month_year[2] != '0000') {
                    $joined_date .= ' ឆ្នាំ'.convert_to_kh_number($day_month_year[2]);
                }
            }
        } else {
            if (strlen($date_string) == 4) {
                $joined_date = ' ឆ្នាំ'.convert_to_kh_number($date_string);
            }
        }

        return $joined_date;
    } else {
        $return_date = date('d F Y', strtotime($date_string));

        return  $my_date;
    }
}
function convert_to_kh_month($month = '')
{
    if (odm_language_manager()->get_current_language() == 'km') {
        if ($month == 'Jan') {
            $kh_month = 'មករា';
        } elseif ($month == 'Feb') {
            $kh_month = 'កុម្ភៈ';
        } elseif ($month == 'Mar') {
            $kh_month = 'មីនា';
        } elseif ($month == 'Apr') {
            $kh_month = 'មេសា';
        } elseif ($month == 'May') {
            $kh_month = 'ឧសភា';
        } elseif ($month == 'Jun') {
            $kh_month = 'មិថុនា';
        } elseif ($month == 'Jul') {
            $kh_month = 'កក្កដា';
        } elseif ($month == 'Aug') {
            $kh_month = 'សីហា';
        } elseif ($month == 'Sep') {
            $kh_month = 'កញ្ញា';
        } elseif ($month == 'Oct') {
            $kh_month = 'តុលា';
        } elseif ($month == 'Nov') {
            $kh_month = 'វិច្ឆិកា';
        } elseif ($month == 'Dec') {
            $kh_month = 'ធ្នូ';
        } elseif ($month == '01') {
            $kh_month = 'មករា';
        } elseif ($month == '02') {
            $kh_month = 'កុម្ភៈ';
        } elseif ($month == '03') {
            $kh_month = 'មីនា';
        } elseif ($month == '04') {
            $kh_month = 'មេសា';
        } elseif ($month == '05') {
            $kh_month = 'ឧសភា';
        } elseif ($month == '06') {
            $kh_month = 'មិថុនា';
        } elseif ($month == '07') {
            $kh_month = 'កក្កដា';
        } elseif ($month == '08') {
            $kh_month = 'សីហា';
        } elseif ($month == '09') {
            $kh_month = 'កញ្ញា';
        } elseif ($month == '10') {
            $kh_month = 'តុលា';
        } elseif ($month == '11') {
            $kh_month = 'វិច្ឆិកា';
        } elseif ($month == '12') {
            $kh_month = 'ធ្នូ';
        } elseif ($month == '០១') {
            $kh_month = 'មករា';
        } elseif ($month == '០២') {
            $kh_month = 'កុម្ភៈ';
        } elseif ($month == '០៣') {
            $kh_month = 'មីនា';
        } elseif ($month == '០៤') {
            $kh_month = 'មេសា';
        } elseif ($month == '០៥') {
            $kh_month = 'ឧសភា';
        } elseif ($month == '០៦') {
            $kh_month = 'មិថុនា';
        } elseif ($month == '០៧') {
            $kh_month = 'កក្កដា';
        } elseif ($month == '០៨') {
            $kh_month = 'សីហា';
        } elseif ($month == '០៩') {
            $kh_month = 'កញ្ញា';
        } elseif ($month == '១០') {
            $kh_month = 'តុលា';
        } elseif ($month == '១១') {
            $kh_month = 'វិច្ឆិកា';
        } elseif ($month == '១២') {
            $kh_month = 'ធ្នូ';
        }

        return $kh_month;
    } else {
        return $month;
    }
}

function convert_to_kh_number($number)
{
    if (odm_language_manager()->get_current_language() == 'km') {
        $conbine_num = '';
        $split_num = str_split($number);
        foreach ($split_num as $num) {
            if ($num == '0') {
                $kh_num = '០';
            } elseif ($num == '1') {
                $kh_num = '១';
            } elseif ($num == '2') {
                $kh_num = '២';
            } elseif ($num == '3') {
                $kh_num = '៣';
            } elseif ($num == '4') {
                $kh_num = '៤';
            } elseif ($num == '5') {
                $kh_num = '៥';
            } elseif ($num == '6') {
                $kh_num = '៦';
            } elseif ($num == '7') {
                $kh_num = '៧';
            } elseif ($num == '8') {
                $kh_num = '៨';
            } elseif ($num == '9') {
                $kh_num = '៩';
            } else {
                $kh_num = $num;
            }

            $conbine_num .= $kh_num;
        }

        return $conbine_num;
    } else {
        return $number;
    }
}

function remove_language_code_from_url($url){
  $lang_code = substr($url,0,3);
  if ($lang_code == "/km/" || $lang_code == "/th/" || $lang_code == "/vi/" || $lang_code == "/my/" || $lang_code == "/la/"):
    $url = substring($url,3);
  endif;
  return $url;
}
