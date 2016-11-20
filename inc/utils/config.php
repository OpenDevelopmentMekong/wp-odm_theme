<?php

	function parse_mapping_pairs($column_list_raw) {
		$column_list = explode("\r\n", $column_list_raw);
		$column_list_array = array();
    foreach ($column_list as $value) {
        $array_value = explode('=>', trim($value));
				if (!isset($array_value[0]) || !isset($array_value[1])):
					return null;
				endif;
				$clean_key = preg_replace( '/[\x{200B}-\x{200D}]/u', '', trim($array_value[0]) );
        $clean_value = preg_replace( '/[\x{200B}-\x{200D}]/u', '', trim($array_value[1]) );
        $column_list_array[$clean_key] = $clean_value;
    }

		return $column_list_array;
	}

 ?>
