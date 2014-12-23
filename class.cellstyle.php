<?php

//require_once 'class.contentautostyle.php';
//require_once 'exceptions/class.styleexception.php';

include_once 'phpodt.php';

/**
 * A Class representing style properties for table cells.
 *
 * @author Issam RACHDI
 */

class CellStyle extends ContentAutoStyle {

	private $cellProp;
	
	/**
	 *
	 * @param DOMDocument $contentDoc 
	 * @param string $name 
	 */
	public function __construct($name) {
		parent::__construct($name);
		$this->styleElement->setAttribute('style:family', 'table-cell');
		$this->cellProp = $this->contentDocument->createElement('style:table-cell-properties');
		$this->styleElement->appendChild($this->cellProp);
	}

	
	/**
	 * Specifies the vertical alignment of text in a table cell
	 * 
	 * @param type $vAlign Possible values are StyleConstants::(TOP|MIDDLE|BOTTOM|AUTO)
	 */
	public function setVerticalAlign($vAlign) {
		switch ($vAlign) {
			case StyleConstants::TOP:
				$vAlign = 'top';break;
			case StyleConstants::MIDDLE:
				$vAlign = 'middle';break;
			case StyleConstants::BOTTOM:
				$vAlign = 'bottom';break;
			case StyleConstants::AUTO:
				$vAlign = 'automatic';break;
			default:
				throw new StyleException('Invalid vertical align value');
		}
		$this->cellProp->setAttribute('style:vertical-align', $vAlign);
	}
	
//	/**
//	 * Specifies the source of the text-alignment. If the value of this attribute is StyleConstants::FIX, 
//	 * the value specified with setVerticalAlign is used. If the value is StyleConstants::VALUE_TYPE, 
//	 * the text alignment depends on the value-type of the cell.
//	 * 
//	 * @param type $src 
//	 */
//	public function setTextAlignSrc($src) {
//		switch ($src) {
//			case StyleConstants::FIX:
//				$src = 'fix';break;
//			case StyleConstants::VALUE_TYPE:
//				$src = 'value-type';break;
//			default:
//				throw new StyleException('Invalid text align source value');
//		}
//		$this->cellProp->setAttribute('style:text-align-source', $src);
//	}
	
	/**
	 * Specifies the direction of characters in a cell. The most common direction is left to right 
	 * (StyleConstants::LTR). The other direction is top to bottom (StyleConstants::TTB), where the 
	 * characters in the cell are stacked but not rotated.
	 * 
	 * @param integer $direction 
	 */
	public function setDirection($direction) {
		switch ($direction) {
			case StyleConstants::LTR:
				$direction = 'ltr';break;
			case StyleConstants::TTB:
				$direction = 'ttb';break;
			default:
				throw new StyleException('Invalid cell direction value');
		}
		$this->cellProp->setAttribute('style:direction', $direction);
	}
	
	/**
	 * Specifies the vertical glyph orientation. 
	 * The property specifies an angle or automatic mode. The only possible angle is 0, which disables 
	 * this feature.
	 * 
	 * @param integer $orientation 
	 */
	public function setVertGlyphOrient($orientation) {
		switch ($orientation) {
			case StyleConstants::AUTO:
				$orientation = 'auto';break;
			case 0:
				$orientation = '0';break;
			default:
				throw new StyleException('Invalid vertical glyph orientation value');
		}
		$this->cellProp->setAttribute('style:glyph-orientation-vertical', $orientation);
	}

	/**
	 * Sets the background color of the cell.
	 * 
	 * @param color $color 
	 */
	public function setBgColor($color) {
		if (!isColor($color)) {
			throw new StyleException('Invalid color value');
		}
		$this->cellProp->setAttribute('fo:background-color', $color);
	}
	
	/**
	 * Specifies a background image for a cell. Note that if you specify the position, the image
	 * will not be repeated
	 *
	 * @param string $image The image's path.
	 * @param integer $repeat Specifies whether the background image is repeated or stretched.
	 * @param integer $position Specifies where to position the background image.
	 * Valid values are StyleConstants::(LEFT|RIGHT|CENTER|TOP|BOTTOM)
	 */
	public function setBgImage($image, $repeat = StyleConstants::REPEAT, $position = -1) {
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
		$this->cellProp->appendChild($imageElement);
	}
	
	/**
	 * Specifies the border properties for cell.
	 *
	 * @param color $borderColor Border color
	 * @param integer $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param integer|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 * @param string $position
	 */
	function setBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN, $position = '') {
		if (!isColor($borderColor)) {
			throw new StyleException('Invalid border-color value');
		}

		switch ($borderStyle) {
			case StyleConstants::SOLID:
				$borderStyle = 'solid';break;
			case StyleConstants::DOUBLE:
				$borderStyle = 'double';break;
			default:
				throw new StyleException('Invalid border-style value');
		}
		switch ($borderWidth) {
			case StyleConstants::THIN:
				$borderWidth = 'thin';break;
			case StyleConstants::THICK:
				$borderWidth = 'thick';break;
			case StyleConstants::MEDIUM:
				$borderWidth = 'medium';break;
			default:
				if (!isLengthValue($borderWidth, true)) {
					throw new StyleException('Invalid border-width value');
				}
		}
		if (!empty($position)) {
			if (!in_array($position, array('top', 'bottom', 'left', 'right'))) {
				$position = '';
			} else {
				$position = '-'.$position;
			}
		}
		$this->cellProp->setAttribute('fo:border'.$position, "$borderWidth $borderStyle $borderColor");
	}

	/**
	 * Specifies the top border properties for a cell.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */
	function setTopBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setBorder($borderColor, $borderStyle, $borderWidth, 'top');
	}

	/**
	 * Specifies the bottom border properties for a cell.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */
	function setBottomBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setBorder($borderColor, $borderStyle, $borderWidth, 'bottom');
	}

	/**
	 * Specifies the left border properties for a cell.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */
	function setLeftBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setBorder($borderColor, $borderStyle, $borderWidth, 'left');
	}

	/**
	 * Specifies the right border properties for a cell.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */
	function setRightBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setBorder($borderColor, $borderStyle, $borderWidth, 'right');
	}
	
	/**
	 * Specifies the spacing around a table cell.
	 *
	 * @param length $padding
	 * @param string $position
	 */
	function setPadding($padding, $position = '') {
		if (!isLengthValue($padding, true) && !isNumeric($padding)) {
			throw new StyleException('Invalid padding value');
		}
		if (!empty($position)) {
			if (!in_array($position, array('top', 'bottom', 'left', 'right'))) {
				$position = '';
			} else {
				$position = '-'.$position;
			}
		}
		$this->cellProp->setAttribute('fo:padding'.$position, $padding);
	}

	/**
	 * Specifies the spacing on top of a table cell.
	 *
	 * @param length $padding
	 */
	function setTopPadding($padding) {
		$this->setPadding($padding, 'top');
	}

	/**
	 * Specifies the spacing in the bottom of a table cell.
	 *
	 * @param length $padding
	 */
	function setBottomPadding($padding) {
		$this->setPadding($padding, 'bottom');
	}

	/**
	 * Specifies the spacing in the left side of a table cell.
	 *
	 * @param length $padding
	 */
	function setLeftPadding($padding) {
		$this->setPadding($padding, 'left');
	}

	/**
	 * Specifies the spacing in the right side of a table cell.
	 *
	 * @param length $padding
	 */
	function setRightPadding($padding) {
		$this->setPadding($padding, 'right');
	}
	
//	/**
//	 * Specifies whether text wraps within a table cell. 
//	 * @param integer $wrapOption
//	 */
//	function setWrapOption($wrapOption) {
//		switch ($wrapOption) {
//			case StyleConstants::WRAP:
//				$wrapOption = 'wrap';break;
//			case StyleConstants::NO_WRAP:
//				$wrapOption = 'no-wrap';break;
//			default:
//				throw new StyleException('Invalid wrap option value');
//		}
//		$this->cellProp->setAttribute('fo:wrap-option', $wrapOption);
//	}		
	
//	/**
//	 * Specifies the rotation angle of the cell content in degrees.
//	 * @param positive integer $angle 
//	 */
//	function setRotationAngle($angle) {
//		if (!isNumeric($angle, true)) {
//			throw new StyleException('Invalid rotation angle value');
//		}
//		$this->cellProp->setAttribute('style:rotation-angle', $angle);
//	}
	
//	/**
//	 * Specifies how the edge of the text in a cell is aligned after a rotation. 
//	 * There are four alignment options: StyleConstants::(TOP|BOTTOM|CENTER|NONE)
//	 * @param integer $angle 
//	 */
//	function setRotationAlign($align) {
//		switch ($align) {
//			case StyleConstants::NONE:
//				$align = 'none';break;
//			case StyleConstants::BOTTOM:
//				$align = 'bottom';break;
//			case StyleConstants::TOP:
//				$align = 'top';break;
//			case StyleConstants::CENTER:
//				$align = 'center';break;
//			default:
//				throw new StyleException('Invalid rotation align value');
//		}
//		$this->cellProp->setAttribute('style:rotation-align', $align);
//	}
	
//	function setRepeatContent($repeat) {
//		if (!is_bool($repeat)) {
//			throw new StyleException('Invalid repeat content value');
//		}
//		$this->cellProp->setAttribute('style:repeat-content', $repeat);
//	}
//	function setShrinkToFit($shrink) {
//		if (!is_bool($shrink)) {
//			throw new StyleException('Invalid shrink to fit value');
//		}
//		$this->cellProp->setAttribute('style:shrink-to-fit', $shrink);
//	}
}

?>
