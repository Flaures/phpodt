<?php

//require_once 'class.contentautostyle.php';
//require_once 'exceptions/class.styleexception.php';

include_once 'phpodt.php';

/**
 * A Class representing style properties for tables.
 *
 * @author Issam RACHDI
 */

class TableStyle extends ContentAutoStyle {

	private $tableProp;

	public function __construct($name) {
		parent::__construct($name);
		$this->styleElement->setAttribute('style:family', 'table');
		$this->tableProp = $this->contentDocument->createElement('style:table-properties');
		$this->styleElement->appendChild($this->tableProp);
	}

	/**
	 * Specify the width of the table. You must also set the widths of each column, 
	 * and set the alignment to a value different than StyleConstants::MARGINS
	 *
	 * @param positiveLength|percentage $width
	 */
	public function setWidth($width) {
		if (isLengthValue($width, true) || isPercentage($width)) {
			if (isLengthValue($width, true)) {
				$this->tableProp->setAttribute('style:width', $width);
			} else if (isPercentage($width)){
				$this->tableProp->setAttribute('style:rel-width', $width);
			}
		} else {
			throw new StyleException('Invalid table-width value');
		}
	}

	/**
	 * Specifies the horizontal alignment of a table.
	 * 
	 * @param integer $align. Valid values are: StyleConstants::(LEFT|RIGHT|CENTER|MARGINS)
	 * LEFT — The table aligns to the left.
   * RIGHT — The table aligns to the center.
   * CENTER — The table aligns to the right.
   * MARGINS — The table fills all the space between the left and right margins.
	 */
	public function setAlignment($align) {
		switch ($align) {
			case StyleConstants::LEFT:
				$align = 'left';
				break;
			case StyleConstants::RIGHT:
				$align = 'right';
				break;
			case StyleConstants::CENTER:
				$align = 'center';
				break;
			case StyleConstants::MARGINS:
				$align = 'margins';
				break;
			default:
				throw new StyleException('Invalid align value');
		}
		$this->tableProp->setAttribute('table:align', $align);
	}
	
	/**
	 * Specifies the left & right margin for a table. You must first specify an alignment 
	 * Doesn't work when alignment is set to StyleConstants::CENTER
	 *
	 * @param integer|string $leftMargin
	 * @param integer|string $rightMargin
	 */
	function setHorizontalMargin($leftMargin, $rightMargin) {
		if (!isNumeric($leftMargin) && !isLengthValue($leftMargin)) {
			throw new StyleException('Invalid left-margin value');
		}
		if (!isNumeric($rightMargin) && !isLengthValue($rightMargin)) {
			throw new StyleException('Invalid right-margin value');
		}
		$this->tableProp->setAttribute('fo:margin-left', $leftMargin);
		$this->tableProp->setAttribute('fo:margin-right', $rightMargin);
	}

	/**
	 * Specifies the top & bottom margin for a table
	 *
	 * @param integer|string $topMargin
	 * @param integer|string $bottomMargin
	 */
	function setVerticalMargin($topMargin, $bottomMargin) {
		if (!isNumeric($topMargin, true) && !isLengthValue($topMargin, true)) {
			throw new StyleException('Invalid top-margin value');
		}
		if (!isNumeric($bottomMargin) && !isLengthValue($bottomMargin)) {
			throw new StyleException('Invalid bottom-margin value');
		}
		$this->tableProp->setAttribute('fo:margin-top', $topMargin);
		$this->tableProp->setAttribute('fo:margin-bottom', $bottomMargin);
	}
	
	/**
	 * Insert a page or column break before a table
	 * 
	 * @param integer $breakBefore Valid values: StyleConstants::(PAGE|COLUMN)
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
		$this->tableProp->setAttribute('fo:break-before', $breakBefore);
	}

	/**
	 * Insert a page or column break after a table
	 * 
	 * @param integer $breakAfter Valid values: StyleConstants::(PAGE|COLUMN)
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
		$this->tableProp->setAttribute('fo:break-after', $breakAfter);
	}
	
	/**
	 * Sets the background color of the table
	 * 
	 * @param color $color 
	 */
	public function setBgColor($color) {
		if (!isColor($color)) {
			throw new StyleException('Invalid color value');
		}
		$this->tableProp->setAttribute('fo:background-color', $color);
	}
	
	/**
	 * Specifies a background image for a table. Note that if you specify the position, the image
	 * will not be repeated
	 *
	 * @param string $image The image's path.
	 * @param integer $repeat Specifies whether the background image is repeated or stretched.
	 * @param integer $position Specifies where to position the background image.
	 * Valid values are StyleConstants::(LEFT|RIGHT|CENTER|TOP|BOTTOM)
	 */
	public function setBgImage($image, $repeat = StyleConstants::REPEAT,
								$position = -1) {
		$file = fopen($image, 'r');
		if (!$file) {
			throw new StyleException('Cannot open image');
		}
		switch($repeat) {
			case StyleConstants::REPEAT:
				$repeat = 'repeat';break;
			case StyleConstants::NO_REPEAT:
				$repeat = 'no-repeat';break;
			case StyleConstants::STRETCH:
				$repeat = 'stretch';break;
			default:
				throw new StyleException('Invalid repeat value');
		}
		switch($position) {
			case -1:
				break;
			case StyleConstants::LEFT:
				$position = 'left';break;
			case StyleConstants::RIGHT:
				$position = 'right';break;
			case StyleConstants::CENTER:
				$position = 'center';break;
			case StyleConstants::TOP:
				$position = 'top';break;
			case StyleConstants::BOTTOM:
				$position = 'left';break;
			default:
				throw new StyleException('Invalid background-position value');
		}
		$dataImg = fread($file, filesize($image));
		$dateImgB64 = base64_encode($dataImg);
		fclose($file);
		$binaryElement = $this->contentDocument->createElement('office:binary-data', $dateImgB64);
		$imageElement = $this->contentDocument->createElement('style:background-image');
		$imageElement->setAttribute('style:repeat', $repeat);
		if ($position != -1) {
			$imageElement->setAttribute('style:position', $position);
		}
		$imageElement->appendChild($binaryElement);
		$this->tableProp->appendChild($imageElement);
	}
	
	/**
	 * Keeps the table and the next paragraph together on a page or in a column after a break is inserted.
	 */
	function setKeepWithNext() {
		$this->tableProp->setAttribute('fo:keep-with-next', 'always');
	}
	
	/**
	 * There are two types of border model:
	 * Collapsing border model:
	 * When two adjacent cells have different borders, the wider border appears as the border
	 * between the cells. Each cell receives half of the width of the border.
	 * Separating border model:
	 * Borders appear within the cell that specifies the border.
	 * 
	 * @param type $model StyleConstants::(COLLAPSING, SEPARATING)
	 */
	function setBorderModel($model) {
		switch ($model) {
			case StyleConstants::COLLAPSING:
				$model = 'collapsing';break;
			case StyleConstants::SEPARATING:
				$model = 'separating';break;
			default :
				throw new StyleException('Invalid border model value');
		}
		$this->tableProp->setAttribute('table:border-model', $model);
	}
	
	/**
	 * Specifies the writing mode.
	 * 
	 * @param integer $writingMode Valid values: StyleConstants::(LR_TB|RL_TB|TB_RL|TB_LR|RL|TB|PAGE)
	 */
	function setWritingMode($writingMode) {
		switch ($writingMode) {
			case StyleConstants::LR_TB:
				$writingMode = 'lr-tb';break;
			case StyleConstants::RL_TB:
				$writingMode = 'rl-tb';break;
			case StyleConstants::TB_RL:
				$writingMode = 'tb-rl';break;
			case StyleConstants::TB_LR:
				$writingMode = 'tb-lr';break;
			case StyleConstants::RL:
				$writingMode = 'rl';break;
			case StyleConstants::TB:
				$writingMode = 'tb';break;
			case StyleConstants::PAGE:
				$writingMode = 'page';break;
			default:
				throw new StyleException('Invalid writing-mode value');
		}
		$this->tableProp->setAttribute('style:writing-mode', $writingMode);
	}
}

?>
