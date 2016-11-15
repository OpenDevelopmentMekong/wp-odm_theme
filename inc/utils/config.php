<?php

	function parse_mapping_pairs($column_list_raw) {
		$column_list = explode("\r\n", $column_list_raw);
		$column_list_array = array();
    foreach ($column_list as $value) {
        $array_value = explode('=>', trim($value));
				if (!isset($array_value[0]) || !isset($array_value[1])):
					return null;
				endif;
        $column_list_array[trim($array_value[0])] = trim($array_value[1]);
    }

		return $column_list_array;
	}

 ?>
