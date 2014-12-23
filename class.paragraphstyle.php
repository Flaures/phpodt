<?php

//require 'class.style.php';
//require 'exceptions/class.styleexception.php';

include_once 'phpodt.php';

/**
 * A Class representing style properties for paragraphs.
 *
 * @author Issam RACHDI
 */

class ParagraphStyle extends Style {

	public function __construct($name) {
    if (empty($name)) {
      $name = 'paragraphstyle'.rand(100, 9999999);
    }
		parent::__construct($name);
		$this->styleElement->setAttribute('style:family', 'paragraph');
	}

	/**
	 * Specifies a fixed line height either as a length or a percentage that 
	 * relates to the highest character in a line. The value StyleConstants::NORMAL 
	 * activates the default line height calculation
	 *
	 * @param length|percentage|StyleConstants::NORMAL $lineHeight
	 */
	function setLineHeight($lineHeight) {
		if (!isNumeric($lineHeight) && !isPercentage($lineHeight) && isLengthValue($lineHeight)) {
			if ($lineHeight == StyleConstants::NORMAL) {
				$lineHeight = 'normal';
			} else {
				throw new StyleException('Invalid line-height value.');
			}
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:line-height', $lineHeight);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies a fixed distance between two lines.
	 *
	 * @param length $distance
	 */
	function setLineDistance($distance) {
		if (!isNumeric($distance) && isLengthValue($distance)) {
			throw new StyleException('Invalid line-spacing value.');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('style:line-spacing', $distance);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies how to align text in paragraphs
	 *
	 * @param integer $textAlign Valid values are StyleConstants::(START, END, LEFT, RIGHT, CENTER, JUSTIFY)
	 */
	function setTextAlign($textAlign) {
		switch ($textAlign) {
			case StyleConstants::START:
				$textAlign = 'start';break;
			case StyleConstants::END:
				$textAlign = 'end';break;
			case StyleConstants::LEFT:
				$textAlign = 'left';break;
			case StyleConstants::RIGHT:
				$textAlign = 'right';break;
			case StyleConstants::CENTER:
				$textAlign = 'center';break;
			case StyleConstants::JUSTIFY:
				$textAlign = 'justify';break;
			default:
				throw new StyleException('Invalid text-align value.');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:text-align', $textAlign);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies whether the lines of a paragraph should be kept together on
	 * the same page or column (if the value is ALWAYS),
	 * or whether breaks are allowed within the paragraph (if the value is AUTO)
	 *
	 * @param integer $keepTogether Valid values are StyleConstants::(AUTO, ALWAYS)
	 */
	function setKeepTogether($keepTogether = StyleConstants::ALWAYS) {
		switch ($keepTogether) {
			case StyleConstants::ALWAYS:
				$keepTogether = 'always';break;
			case StyleConstants::AUTO:
				$keepTogether = 'auto';break;
			default:
				throw new StyleException('Invalid keep-together value.');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:keep-together', $keepTogether);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the minimum number of lines allowed at the top of a page to avoid paragraph widows.
	 *
	 * @param integer $widows Minimum number of lines
	 */
	function setWidows($widows) {
		if (!isNumeric($widows)) {
			throw new StyleException('Invalid widows value.');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:widows', $widows);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the minimum number of lines required at the bottom of a page to avoid paragraph orphans.
	 *
	 * @param integer $orphans Minimum number of lines
	 */
	function setOrphans($orphans) {
		if (!isNumeric($orphans)) {
			throw new StyleException('Invalid orphans value.');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:orphans', $orphans);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the left & right margin for a paragraph
	 *
	 * @param integer|string $leftMargin
	 * @param integer|string $rightMargin
	 */
	function setHorizontalMargins($leftMargin = 0, $rightMargin = 0) {
		if (!isNumeric($leftMargin) && !isLengthValue($leftMargin)) {
			throw new StyleException('Invalid left-margin value');
		}
		if (!isNumeric($rightMargin) && !isLengthValue($rightMargin)) {
			throw new StyleException('Invalid right-margin value');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:margin-left', $leftMargin);
		$element->setAttribute('fo:margin-right', $rightMargin);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the top & bottom margin for a paragraph
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
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:margin-top', $topMargin);
		$element->setAttribute('fo:margin-bottom', $bottomMargin);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Insert a page or column break before a paragraph
	 *
	 * @param integer $breakBefore Valid values are StyleConstants::(PAGE|COLUMN)
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
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:break-before', $breakBefore);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Insert a page or column break after a paragraph
	 *
	 * @param integer $breakAfter Valid values are StyleConstants::(PAGE|COLUMN)
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
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:break-after', $breakAfter);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the background color for the paragraph. The color format
	 * must be #XXYYZZ where XX is the red, YY the green and ZZ the blue, in hexadecimal.
	 *
	 * @param color $color
	 */
	function setBackgroundColor($color) {
		if ($color != StyleConstants::TRANSPARENT && !isColor($color)) {
			throw new StyleException('Invalid paragraph background color');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:background-color', $color);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies a background image for a paragraph. Note that if you specify the position, the image
	 * will not be repeated
	 *
	 * @param string $image The image's path.
	 * @param integer $position Specifies where to position a background image in a paragraph.
	 * Valid values are StyleConstants::(LEFT|RIGHT|CENTER|TOP|BOTTOM)
	 */
	function setBackgroundImage($image, $repeat = StyleConstants::REPEAT,
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
		$pPropElement = $this->styleDocument->createElement('style:paragraph-properties');
		$binaryElement = $this->styleDocument->createElement('office:binary-data', $dateImgB64);
		$imageElement = $this->styleDocument->createElement('style:background-image');
		$imageElement->setAttribute('style:repeat', $repeat);
		if ($position != -1) {
			$imageElement->setAttribute('style:position', $position);
		}
		$imageElement->appendChild($binaryElement);
		$pPropElement->appendChild($imageElement);
		$this->styleElement->appendChild($pPropElement);
	}

	/**
	 * Specifies a positive or negative indent for the first line of a paragraph
	 *
	 * @param length $textIndent
	 */
	function setTextIndent($textIndent) {
		if (!isNumeric($textIndent) && !isLengthValue($textIndent)) {
			throw new StyleException('Invalid text-indent value');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:text-indent', $textIndent);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the border properties for paragraphs.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 * @param string $position Do not use this, it's for internal use only.
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
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:border'.$position, "$borderWidth $borderStyle $borderColor");
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the top border properties for paragraphs.
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
	 * Specifies the bottom border properties for paragraphs.
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
	 * Specifies the left border properties for paragraphs.
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
	 * Specifies the right border properties for paragraphs.
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
	 * Specifies the spacing around a paragraph.
	 *
	 * @param length $padding
	 * @param string $position Do not use this, it's for internal use only.
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
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:padding'.$position, $padding);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the spacing on top of a paragraph.
	 *
	 * @param length $padding
	 */
	function setTopPadding($padding) {
		$this->setPadding($padding, 'top');
	}

	/**
	 * Specifies the spacing in the bottom of a paragraph.
	 *
	 * @param length $padding
	 */
	function setBottomPadding($padding) {
		$this->setPadding($padding, 'bottom');
	}

	/**
	 * Specifies the spacing in the left side of a paragraph.
	 *
	 * @param length $padding
	 */
	function setLeftPadding($padding) {
		$this->setPadding($padding, 'left');
	}

	/**
	 * Specifies the spacing in the right side of a paragraph.
	 *
	 * @param length $padding
	 */
	function setRightPadding($padding) {
		$this->setPadding($padding, 'right');
	}

	/**
	 * Keeps the current paragraph and the next paragraph together on a page or in a column after a break is inserted.
	 */
	function setKeepWithNext() {
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('fo:keep-with-next', 'always');
		$this->styleElement->appendChild($element);
	}

//	function setLineNumebring() {
//		$element = $this->styleDocument->createElement('style:paragraph-properties');
//		$element->setAttribute('text:number-lines', 'true');
//		$this->styleElement->appendChild($element);
//	}

	/**
	 * Specifies the vertical position of a character. By default characters are aligned according to their baseline.
	 *
	 * @param integer $align Valid values: StyleConstants::(TOP|BOTTOM|MIDDLE|BASELINE|AUTO)
	 */
	function setVerticalAlignment($align) {
		switch ($align) {
			case StyleConstants::TOP:
				$align = 'top';break;
			case StyleConstants::BOTTOM:
				$align = 'bottom';break;
			case StyleConstants::MIDDLE:
				$align = 'middle';break;
			case StyleConstants::BASELINE:
				$align = 'baseline';break;
			case StyleConstants::AUTO:
				$align = 'auto';break;
			default:
				throw new StyleException('Invalid vertical-align value');
		}
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('style:vertical-align', $align);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies the writing mode of a paragraph.
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
		$element = $this->styleDocument->createElement('style:paragraph-properties');
		$element->setAttribute('style:writing-mode', $writingMode);
		$this->styleElement->appendChild($element);
	}
}
?>
