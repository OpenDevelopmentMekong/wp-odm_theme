<?php

	function arrays_have_common_items($array1, $array2) {

		foreach ($array1 as $key => $value):
			if (in_array($value,$array2)):
				return true;
			endif;
		endforeach;

		return false;
	}

	?>
