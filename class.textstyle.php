<?php

//require_once 'class.style.php';
//require_once 'exceptions/class.styleexception.php';

include_once 'phpodt.php';

/**
 * A Class representing style properties for text content.
 * 
 * @author Issam RACHDI
 */

class TextStyle extends Style {


	public function __construct($name = '') {
    if (empty($name)) {
      $name = 'textstyle'.rand(100, 9999999);
    }
		parent::__construct($name);
		$this->styleElement->setAttribute('style:family', 'text');
	}

	/**
	 * Possible values are StyleConstants::NONE, StyleConstants::LOWER_CASE,
	 * StyleConstants::UPPER_CASE and StyleConstants::CAPITALIZE.
	 * In case the value passed is equal to none of these values, a StyleException is thrown
	 * @param integer $transform
	 */
	function setTextTransform($transform) {
		$element = $this->styleDocument->createElement('style:text-properties');
		switch($transform) {
			case StyleConstants::NONE:
				$attrTransform = 'none';break;
			case StyleConstants::LOWER_CASE:
				$attrTransform = 'lowercase';break;
			case StyleConstants::UPPER_CASE:
				$attrTransform = 'uppercase';break;
			case StyleConstants::CAPITALIZE:
				$attrTransform = 'capitalize';break;
			default: throw new StyleException($transform.' is not a valid "text-transform" value.');
		}
		$element->setAttribute('fo:text-transform', $attrTransform);
		$this->styleElement->appendChild($element);
	}
	
	/**
	 * Changes the color of the text. The value passed
	 * must be a 6 character hexadecimal value, for example #FF0000
	 * or three integer (0 to 255) representing red, green, blue, for example "rgb(255, 0, 0)"
	 * or a valid color name : red, blue ...
	 * @param color $color
	 */
	function setColor($color = '#000000') {
		if (isColor($color)) {
			$element = $this->styleDocument->createElement('style:text-properties');
			$element->setAttribute('fo:color', $color);
			$this->styleElement->appendChild($element);
		} else {
			throw new StyleException('Color value '.$color.' is not valid.');
		}
	}
	
	/**
	 * Outline the text, or not
	 * 
	 * @param boolean $outline
	 */
	function setTextOutline($outline = true) {
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-outline', ($outline) ? 'true':'false');
		$this->styleElement->appendChild($element);
	}
	
	/**
	 * Draw a line through text.
	 * lineType can have one of these
	 * lineWidth can have these 
	 * @param integer $lineType values: StyleConstants::NONE, StyleConstants::SINGLE, StyleConstants::DOUBLE
	 * @param integer $lineWidth Valid values are: StyleConstants::NORMAL, StyleConstants::BOLD
	 */
	function setLineThrough($lineType = StyleConstants::SINGLE, $lineWidth = StyleConstants::NORMAL) {
		switch ($lineType) {
			case StyleConstants::NONE:
				$lineType = 'none';break;
			case StyleConstants::SINGLE:
				$lineType = 'single';break;
			case StyleConstants::DOUBLE:
				$lineType = 'double';break;
			default:
				throw new StyleException('LineType value is not valid.');
		}

		switch ($lineWidth) {
			case StyleConstants::NORMAL:
				$lineWidth = 'normal';break;
			case StyleConstants::BOLD:
				$lineWidth = 'bold';break;
			default:
				throw new StyleException('LineStyle value is not valid.');
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-line-through-type', $lineType);
		$element->setAttribute('style:text-line-through-width', $lineWidth);
		
		$this->styleElement->appendChild($element);
	}

	/**
	 * Make the text bold
	 */
	function setBold() {
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('fo:font-weight', 'bold');
		$this->styleElement->appendChild($element);
	}

	/**
	 * Sets the text position according to the baseline
	 * @param integer $position
	 */
	function setTextPosition($position) {
		switch ($position) {
			case StyleConstants::SUPER:
				$position = 'super';
				break;
			case StyleConstants::SUB:
				$position = 'sub';
				break;
			default:
				throw new StyleException('Text position value is not valid.');
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-position', $position);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specify the font name for the text
	 * @param <type> $fontName
	 */
	function setFontName($fontName) {
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:font-name', $fontName);
		$this->styleElement->appendChild($element);
	}

	/**
	 * 
	 * @param numeric|percentage $fontSize The font size can be either a numeric value representing absolute length
	 * or a percentage
	 */
	function setFontSize($fontSize) {
		if (isNumeric($fontSize) || isPercentage($fontSize)) {
			$element = $this->styleDocument->createElement('style:text-properties');
			$element->setAttribute('fo:font-size', $fontSize);
			$this->styleElement->appendChild($element);
		} else {
			throw new StyleException($fontSize. ' is not a valid font-size value');
		}
	}

//	/**
//	 * Valid values for $style are: StyleConstants::NORMAL, StyleConstants::ITALIC, StyleConstants::OBLIQUE
//	 * @param integer $style
//	 */
//	function setFontStyle($fontStyle) {
//		switch ($fontStyle) {
//			case StyleConstants::NORMAL:
//				$fontStyle = 'normal';break;
//			case StyleConstants::ITALIC:
//				$fontStyle = 'italic';break;
//			case StyleConstants::OBLIQUE:
//				$fontStyle = 'oblique';break;
//			default:
//				throw new StyleException('Invalid font-style value');
//		}
//		$element = $this->styleDocument->createElement('style:text-properties');
//		$element->setAttribute('fo:font-style', $fontStyle);
//		$this->styleElement->appendChild($element);
//	}
  
  function setItalic() {
    $element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('fo:font-style', 'italic');
		$this->styleElement->appendChild($element);
  }

	/**
	 * 
	 * @param integer $fontRelief Valid values: StyleConstants::EMBOSSED, StyleConstants::ENGRAVED
	 */
	function setFontRelief($fontRelief) {
		switch ($fontRelief) {
			case StyleConstants::EMBOSSED:
				$fontRelief = 'embossed';break;
			case StyleConstants::ENGRAVED:
				$fontRelief = 'engraved';break;
			default:
				throw new StyleException('Invalid font-relief value');
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:font-relief', $fontRelief);
		$this->styleElement->appendChild($element);
	}

	/**
	 *
	 * @param color $shadowColor The color of the shadow
	 * @param numeric $xCoord	 The x coordinate of the shadow, relative to the text
	 * @param numeric $yCoord	 The x coordinate of the shadow, relative to the text
	 * @param numeric $blurRadius	The amount of blurriness of the shadow
	 */
	function setTextShadow($shadowColor = '#000000', $xCoord = 5, $yCoord = 5, $blurRadius = 0) {
		if (isColor($shadowColor) && isNumeric($xCoord) && isNumeric($yCoord) && isNumeric($blurRadius)) {
			$element = $this->styleDocument->createElement('style:text-properties');
			$element->setAttribute('fo:text-shadow', "$shadowColor ${xCoord}px ${yCoord}px $blurRadius");
			$this->styleElement->appendChild($element);
		}
	}

	/**
	 *
	 * @param integer $underlineType Valid values: StyleConstants::SINGLE, StyleConstants::DOUBLE
	 * @param integer $underlineStyle Valid values: StyleConstants::SOLID, StyleConstants::DOTTED, StyleConstants::DASH, StyleConstants::LONG_DASH, StyleConstants::DOT_DOT_DASH, StyleConstants::WAVE
	 * @param integer $underlineWidth Valid values: StyleConstants::AUTO, StyleConstants::NORMAL, StyleConstants::BOLD, StyleConstants::THIN, StyleConstants::DASH, StyleConstants::MEDIUM, StyleConstants::THICK
	 * @param color $underlineColor a valid color
	 */
	function setTextUnderline($underlineType = StyleConstants::SINGLE,
							  $underlineStyle = StyleConstants::SOLID,
							  $underlineWidth = StyleConstants::NORMAL,
							  $underlineColor = '#000000') {
		switch($underlineType) {
			case StyleConstants::SINGLE:
				$underlineType = 'single';break;
			case StyleConstants::DOUBLE:
				$underlineType = 'double';break;
			default:
				throw new StyleException('Invalid underline-type value.');
		}
		switch($underlineStyle) {
			case StyleConstants::NONE:
				$underlineStyle = 'none';break;
			case StyleConstants::SOLID:
				$underlineStyle = 'solid';break;
			case StyleConstants::DOTTED:
				$underlineStyle = 'dotted';break;
			case StyleConstants::DASH:
				$underlineStyle = 'dash';break;
			case StyleConstants::LONG_DASH:
				$underlineStyle = 'long-dash';break;
			case StyleConstants::DOT_DOT_DASH:
				$underlineStyle = 'dot-dot-dash';break;
			case StyleConstants::WAVE:
				$underlineStyle = 'wave';break;
			default:
				throw new StyleException('Invalid underline-style value.');
		}
		switch($underlineWidth) {
			case StyleConstants::AUTO:
				$underlineWidth = 'single';break;
			case StyleConstants::NORMAL:
				$underlineWidth = 'normal';break;
			case StyleConstants::BOLD:
				$underlineWidth = 'bold';break;
			case StyleConstants::THIN:
				$underlineWidth = 'thin';break;
//			case StyleConstants::DASH:
//				$underlineWidth = 'dash';break;
			case StyleConstants::MEDIUM:
				$underlineWidth = 'medium';break;
			case StyleConstants::THICK:
				$underlineWidth = 'thick';break;
			default:
//				throw new StyleException('Invalid underline-type value.');
		}
		if (!isColor($underlineColor)) {
			throw new StyleException('Invalid color value.');
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-underline-type', $underlineType);
		$element->setAttribute('style:text-underline-style', $underlineStyle);
		$element->setAttribute('style:text-underline-width', $underlineWidth);
		$element->setAttribute('style:text-underline-color', $underlineColor);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specify whether underline white spaces or not when underlining
	 * @param integer $mode Valid values are StyleConstants::CONTINUOUS, StyleConstants::SKIP_WHITE_SPACE
	 */
	function setUnderlineWordMode($mode) {
		switch($mode) {
			case StyleConstants::CONTINUOUS:
				$mode = 'continuous';break;
			case StyleConstants::SKIP_WHITE_SPACE:
				$mode = 'skip-white-space';break;
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-underline-mode', $mode);
		$this->styleElement->appendChild($element);
	}

	/**
	 * Same as setUnderlineWordMode but for line-through
	 * @param integer $mode Valid values are StyleConstants::CONTINUOUS, StyleConstants::SKIP_WHITE_SPACE
	 */
	function setLineThroughWordMode($mode) {
		switch($mode) {
			case StyleConstants::CONTINUOUS:
				$mode = 'continuous';break;
			case StyleConstants::SKIP_WHITE_SPACE:
				$mode = 'skip-white-space';break;
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-line-through-mode', $mode);
		$this->styleElement->appendChild($element);
	}

//	/**
//	 * Enable or disable kerning between characters
//	 * @param boolean $kerning
//	 */
//	function setLetterKerning($kerning) {
//		$element = $this->styleDocument->createElement('style:text-properties');
//		$element->setAttribute('style:letter-kerning', $kerning);
//		$this->styleElement->appendChild($element);
//	}

	/**
	 * Specify whether or not text blinks
	 * @param boolean $blinking
	 */
	function setTextBlinking() {
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-blinking', 'true');
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specify the background color to apply to characters.
	 * @param color $color Background color
	 */
	function setTextBackgroundColor($color) {
		if ($color == 'transparent' || isColor($color)) {
			$element = $this->styleDocument->createElement('style:text-properties');
			$element->setAttribute('fo:background-color', $color);
			$this->styleElement->appendChild($element);
		}
	}

	/**
	 * Combine characters so that they are displayed within two lines.
	 * The value of $textCombine can be StyleConstants::LETTERS or StyleConstants::LINES.
	 * If the value is StyleConstants::LINES, all characters with this attribute value that immediately follow each other are
	 * displayed within two lines of approximately the same length. There can be a line break between
	 * any two characters to meet this constraint. The text will be surrounded by $startChar & $endChar
	 * If the value of the attribute is StyleConstants::LETTERS, up to 5 characters are combined within two lines. Any
	 * additional character is displayed as normal text.
	 * @param integer $textCombine
	 */
	function setTextCombine($textCombine, $startChar = '', $endChar = '') {
		switch ($textCombine) {
			case StyleConstants::LETTERS:
				$textCombine = 'letters';break;
			case StyleConstants::LINES:
				$textCombine = 'lines';break;
			default:
				throw new StyleException('Invalid text-combine value.');
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-combine', $textCombine);
		if ($textCombine == 'lines') {
			$element->setAttribute('style:text-combine-start-char', $startChar);
			$element->setAttribute('style:text-combine-end-char', $endChar);
		}
		$this->styleElement->appendChild($element);
	}

	/**
	 * Specifies an angle to which text is rotated. The angle can be 0, 90 or 270.
	 * 
	 * @param integer $angle Angle of rotation
	 */
	function setRotationAngle($angle) {
		if ($angle != 0 && $angle != 90 && $angle != 270) {
			throw new StyleException('Rotation Angle must be equal to 0, 90 or 270');
		}
		$element = $this->styleDocument->createElement('style:text-properties');
		$element->setAttribute('style:text-rotation-angle', $angle);
		$this->styleElement->appendChild($element);
	}

}
?>
