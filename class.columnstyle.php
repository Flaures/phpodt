<?php

//require_once 'class.contentautostyle.php';
//require_once 'exceptions/class.styleexception.php';

include_once 'phpodt.php';

/**
 * A Class representing style properties for table columns.
 *
 * @author Issam RACHDI
 */

class ColumnStyle extends ContentAutoStyle {

	private $colProp;

	/**
	 *
	 * @param type $contentDoc
	 * @param type $name 
	 */
	public function __construct($name) {
    if (empty($name)) {
      $name = 'columnstyle'.rand(100, 9999999);
    }
		parent::__construct($name);
		$this->styleElement->setAttribute('style:family', 'table-column');
		$this->colProp = $this->contentDocument->createElement('style:table-column-properties');
		$this->styleElement->appendChild($this->colProp);
	}

	/**
	 * Sets the width of the table.
	 *
	 * @param length|percentage $width
	 */
	public function setWidth($width) {
		if (isLengthValue($width, true) || isPercentage($width)) {
			if (isLengthValue($width, true)) {
				$this->colProp->setAttribute('style:column-width', $width);
			} else if (isPercentage($width)){
				$this->colProp->setAttribute('style:rel-column-width', $width);
			}
		} else {
			throw new StyleException('Invalid table-width value');
		}
	}
	
	/**
	 * Specifies if the column width should be recalculated automatically if some content in the column changes.
	 * 
	 * @param boolean $optimal 
	 */
	public function setOptimalWidth($optimalWidth) {
		if (is_bool($optimalWidth)) {
			$this->colProp->setAttribute('style:use-optimal-column-width', $optimalWidth);
		} else {
			throw new StyleException('Value must be boolean');
		}
	}

	/**
	 * Insert a page or column break before a table column.
	 * 
	 * @param integer $breakBefore Possible values: StyleConstants::(PAGE|COLUMN)
	 */
	function setBreakBefore($breakBefore) {
		switch ($breakBefore) {
			case StyleConstants::PAGE:
				$breakBefore = 'page';break;
			case StyleConstants::COLUMN:
				$breakBefore = 'column';break;
			default:
				throw new StyleException('Invalid break-before value.');
		}
		$this->colProp->setAttribute('fo:break-before', $breakBefore);
	}

	/**
	 * Insert a page or column break after a table column
	 *
	 * @param integer $breakAfter Possible values: StyleConstants::(PAGE|COLUMN)
	 */
	function setBreakAfter($breakAfter) {
		switch ($breakAfter) {
			case StyleConstants::PAGE:
				$breakAfter = 'page';break;
			case StyleConstants::COLUMN:
				$breakAfter = 'column';break;
			default:
				throw new StyleException('Invalid break-after value.');
		}
		$this->colProp->setAttribute('fo:break-after', $breakAfter);
	}
}

?>
