<?php

function arrays_have_common_items($array1, $array2)
{
	foreach ($array1 as $value1) :
		foreach ($array2 as $value2) :
			if (strtolower($value1) == strtolower($value2)) :
				return true;
			endif;
		endforeach;
	endforeach;

	return false;
}
