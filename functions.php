<?php

function isColor($color) {
	//return preg_match('/(#[\dA-Fa-f]{6}|[A-Za-z]+|rgb\(\d{1,3},\d{1,3},\d{1,3}\))/', $color);
	return preg_match('/#[\dA-Fa-f]{6}/', $color);
}

function isNumeric($value, $nonNegative = false) {
	if ($nonNegative) {
		$pattern = '/\d+(.\d)*/';
	} else {
		$pattern = '/-?\d+(.\d)*/';
	}
	return preg_match($pattern, $value);
}

function isLengthValue($value, $nonNegative = false) {
	if ($nonNegative) {
		$pattern = '/\d*.?\d+(pt|cm|mm)/';
	} else {
		$pattern = '/-?\d*.?\d+(pt|cm|mm)/';
	}
	return preg_match($pattern, $value);
}

function isPercentage($value) {
	return preg_match('/\d+(.\d)*%/', $value);
}

?>
