<?php

function isSiteAdmin(){
  return in_array('administrator',  wp_get_current_user()->roles);
}

$local_lang = odm_language_manager()->get_the_language_code_by_site();

if(!is_user_logged_in() && !isSiteAdmin()):

  echo('You do not have access to this functionality');

elseif(odm_country_manager()->get_current_country() == "mekong"):

	echo('This operation cannot be executed on ODM site');

else:

	$qtranslate_term_name = get_option('qtranslate_term_name');

	$localizations = odm_taxonomy_manager()->get_taxonomy_translations_for_lang($local_lang);

	echo "Importing translations for taxonomy in: " . $local_lang . nl2br("\n");

	$localized = $qtranslate_term_name;

	echo "Current status of qtranslate_term_name option: " . nl2br("\n");

  print_r($localized);

	foreach ($localizations as $term_en => $term_local_lang):
		if (in_array($term_en,array_keys($localized)) && !$localized[$term_en][$local_lang]):
			$localized[$term_en][$local_lang] = $localizations[$term_en];
		elseif(!in_array($term_en,array_keys($localized))):
			$localized[$term_en] = array(
				"en" => $term_en,
				$local_lang => $localizations[$term_en]
 			);
		endif;
	endforeach;

	echo nl2br("\n") . "-------------------------" . nl2br("\n");

	print_r($localized);

	update_option('qtranslate_term_name',$localized);

	echo nl2br("\n") . "Success!" . nl2br("\n");

endif;

?>
