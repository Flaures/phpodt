<?php

class NumberFormat {
	
	private $prefix;
	/**
	 * What will be displayed before the number
	 * @var string
	 */
	private $suffix;
	 
	private $format;
	
	function __construct($prefix = NULL, $suffix = NULL, $format = NULL) {
		$this->prefix = $prefix;
		$this->suffix = $suffix;
		$this->format = $format;
	}
	
	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 * Specifies what will be displayed before the number
	 * 
	 * @param string $prefix 
	 */
	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}

	public function getSuffix() {
		return $this->suffix;
	}

	/**
	 * Specifies what will be displayed after the number
	 * 
	 * @param string $suffix 
	 */
	public function setSuffix($suffix) {
		$this->suffix = $suffix;
	}

	public function getFormat() {
		return $this->format;
	}

	/**
	 * The number formats supported are as follows:
	 * Numeric: 1, 2, 3, ...
	 * Alphabetic: a, b, c, ... or A, B, C, ...
	 * Roman: i, ii, iii, iv, ... or I, II, III, IV,...
	 * The argument can be "1", "a", "A", "i", or "I". If empty, no number is displayed.
	 * 
	 * @param string $format
	 */
	public function setFormat($format) {
		switch ($format) {
			case '1':
			case 'a':
			case 'A':
			case 'i':
			case 'I':
				$this->format = $format;
			default:
				throw new Exception('Invalid num-format value');
		}
		
	}


}
?>
