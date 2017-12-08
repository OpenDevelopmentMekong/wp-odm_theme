<?php

	function arrays_have_common_items($array1, $array2) {

		foreach ($array1 as $key => $value):
			if (preg_grep( "/" . $value . "/i" , $array2 )):
				return true;
			endif;
		endforeach;

		return false;
	}

	?>
