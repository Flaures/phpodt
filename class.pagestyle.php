<?php

//require_once 'functions.php';
require_once 'class.odt.php';

/**
 * A Class representing the properties of a page.
 *
 * @author Issam RACHDI
 */
class PageStyle {

	private $pageWidth = '21cm';

	private $pageHeight = '29.70cm';

	/**
	 * The DOMDocument representing the styles xml file
	 * @access private
	 * @var DOMDocument
	 */
	private $styleDocument;
	/**
	 * The name of the style
	 * @access private
	 * @var string
	 */
	private $name;

	/**
	 * The DOMElement representing the page layout properties
	 * @access private
	 * @var DOMElement
	 */
	private $pageLayoutProperties;

	private $masterStyleElement;

	/**
	 * The DOMElement representing the header properties
	 * @access private
	 * @var DOMElement
	 */
	private $headerProperties;

	/**
	 * The DOMElement representing the header properties
	 * @access private
	 * @var DOMElement
	 */
	private $footerProperties;

	/**
	 * The constructor initializes the properties, then creates a <style:style>
	 * element representing this specific style, and add it to <office:styles>
	 * element
	 * @param DOMDocument $styleDoc
	 * @param string $name
	 */
	function __construct($name) {
		$this->styleDocument = ODT::getInstance()->getStyleDocument();
		$this->name = $name;
		$pageLayoutStyleElement = $this->styleDocument->createElement('style:page-layout');
		$this->pageLayoutProperties = $this->styleDocument->createElement('style:page-layout-properties');
		$this->headerProperties = $this->styleDocument->createElement('style:header-footer-properties');
		$this->footerProperties = $this->styleDocument->createElement('style:header-footer-properties');
		$headerStyle = $this->styleDocument->createElement('style:header-style');
		$headerStyle->appendChild($this->headerProperties);
		$footerStyle = $this->styleDocument->createElement('style:footer-style');
		$footerStyle->appendChild($this->footerProperties);
		$pageLayoutStyleElement->setAttribute('style:name', $name);
		$pageLayoutStyleElement->appendChild($this->pageLayoutProperties);
		$pageLayoutStyleElement->appendChild($headerStyle);
		$pageLayoutStyleElement->appendChild($footerStyle);
		$this->styleDocument->getElementsByTagName('office:automatic-styles')->item(0)->appendChild($pageLayoutStyleElement);
		$this->masterStyleElement = $this->styleDocument->createElement('style:master-page');
		$this->masterStyleElement->setAttribute('style:name', 'Standard');
		$this->masterStyleElement->setAttribute('style:page-layout-name', $name);
		$this->styleDocument->getElementsByTagName('office:master-styles')->item(0)->appendChild($this->masterStyleElement);
		$this->setHorizontalMargin('2cm', '2cm');
	}

	/**
	 * return the name of this style
	 * @return string
	 */
	function getStyleName() {
		return $this->name;
	}

	/**
	 * Specify the physical size of the page.
	 *
	 * @param integer|length $width
	 * @param integer|string $height
	 */
	function setPageSize($width, $height) {
		if (!isLengthValue($width) && !isLengthValue($height)) {
			throw new StyleException('Invalid page-height value');
		}
		$this->pageWidth = $width;
		$this->pageHeight = $height;
		$this->pageLayoutProperties->setAttribute('fo:page-width', $width);
		$this->pageLayoutProperties->setAttribute('fo:page-height', $height);
	}

	/**
	 * Sepcifies default number format for page styles, which is used to display page numbers within headers and footers.
	 *
	 * @param string $prefix
	 * @param string $suffix
	 * @param string $format Valid values: "1", "a", "A", "i", or "I"
	 */
	function setPageNumberFormat($prefix, $suffix, $format) {
		$this->pageLayoutProperties->setAttribute('style:num-prefix', $prefix);
		$this->pageLayoutProperties->setAttribute('fo:page-suffix', $suffix);
		switch ($format) {
			case '1':
			case 'a':
			case 'A':
			case 'i':
			case 'I':
				$this->pageLayoutProperties->setAttribute('fo:page-suffix', $format);break;
			default:
				throw new StyleException('Invalid num-format value');
		}
	}

	/**
	 * Specifies the orientation of the printed page
	 *
	 * @param integer $orientation Valid values: StyleConstants::(LANDSCAPE|PORTRAIT)
	 */
	function setOrientation($orientation) {
		switch ($orientation) {
			case StyleConstants::LANDSCAPE:
				$orientation = 'landscape';
				if ($this->pageWidth < $this->pageHeight) {
					$this->setPageSize($this->pageHeight, $this->pageWidth);
				}
				break;
			case StyleConstants::PORTRAIT:
				$orientation = 'portrait';
				if ($this->pageWidth > $this->pageHeight) {
					$this->setPageSize($this->pageHeight, $this->pageWidth);
				}
				break;
			default:
				throw new StyleException('Invalid orientation value.');
		}
		$this->pageLayoutProperties->setAttribute('style:print-orientation', $orientation);
	}

	/**
	 * Specify the left & right margin for the page
	 *
	 * @param integer|string $leftMargin
	 * @param integer|string $rightMargin
	 */
	function setHorizontalMargin($leftMargin = 0, $rightMargin = 0) {
		if (!isNumeric($leftMargin) && !isLengthValue($leftMargin)) {
			throw new StyleException('Invalid left-margin value');
		}
		if (!isNumeric($rightMargin) && !isLengthValue($rightMargin)) {
			throw new StyleException('Invalid right-margin value');
		}
		
		$this->pageLayoutProperties->setAttribute('fo:margin-left', $leftMargin);
		$this->pageLayoutProperties->setAttribute('fo:margin-right', $rightMargin);
	}

	/**
	 * Specify the top & bottom margin for the page
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
		$this->pageLayoutProperties->setAttribute('fo:margin-top', $topMargin);
		$this->pageLayoutProperties->setAttribute('fo:margin-bottom', $bottomMargin);
	}

	/**
	 * Specifies the border properties for the page.
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
		$this->pageLayoutProperties->setAttribute('fo:border'.$position, "$borderWidth $borderStyle $borderColor");
	}

	/**
	 * Specifies the top border property for pages.
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
	 * Specifies the bottom border property for paragraphs.
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
	 * Specifies the left border property for pages.
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
	 * Specifies the right border property for pages.
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
	 * Specifies the spacing around the page.
	 * Note that you must first specify the border
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
		$this->pageLayoutProperties->setAttribute('fo:padding'.$position, $padding);
	}

	/**
	 * Specifies the spacing on top of the pages.
	 *
	 * @param length $padding
	 */
	function setTopPadding($padding) {
		$this->setPadding($padding, 'top');
	}

	/**
	 * Specifies the spacing in the bottom of the pages.
	 *
	 * @param length $padding
	 */
	function setBottomPadding($padding) {
		$this->setPadding($padding, 'bottom');
	}

	/**
	 * Specifies the spacing in the left side of the pages.
	 *
	 * @param length $padding
	 */
	function setLeftPadding($padding) {
		$this->setPadding($padding, 'left');
	}

	/**
	 * Specifies the spacing in the right side of the pages.
	 *
	 * @param length $padding
	 */
	function setRightPadding($padding) {
		$this->setPadding($padding, 'right');
	}

	/**
	 * Specifies the background color for the page
	 *
	 * @param color $color
	 */
	function setBackgroundColor($color) {
		if ($color != StyleConstants::TRANSPARENT && !isColor($color)) {
			throw new StyleException('Invalid page background color');
		}
		$this->pageLayoutProperties->setAttribute('fo:background-color', $color);
	}

	/**
	 * Specifies the background image for the pages. Note that if you specify the position, the image
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
		$binaryElement = $this->styleDocument->createElement('office:binary-data', $dateImgB64);
		$imageElement = $this->styleDocument->createElement('style:background-image');
		$imageElement->setAttribute('style:repeat', $repeat);
		if ($position != -1) {
			$imageElement->setAttribute('style:position', $position);
		}
		$imageElement->appendChild($binaryElement);
		$this->pageLayoutProperties->appendChild($imageElement);
	}

	//style:columns


	/**
	 * Specify the number of the first page
	 *
	 * @param integer $number
	 */
	function setFirstPageNumber($number) {
		if (!isNumeric($number)) {
			throw new StyleException('Invalid first page number value.');
		}
		$this->pageLayoutProperties->setAttribute('style:first-page-number', $number);
	}

	/**
	 * Specifies the maximum amount of space on the page that a footnote can occupy.
	 *
	 * @param length $height
	 */
	function setMaximumFootnoteHeight($height) {
		if (!isNumeric($height)) {
			throw new StyleException('Invalid maximum footnote height.');
		}
		$this->pageLayoutProperties->setAttribute('style:footnote-max-height', $height);
	}

	/**
	 * Specify the line that separates the footnote from the body text area on a page
	 *
	 * @param length $lineWidth
	 * @param color $color
	 * @param integer $adjustment How the line is aligned on the page. Valid values: StyleConstants::(LEFT|RIGHT|CENTER)
	 * @param length $distanceBefore
	 * @param length $distanceAfter
	 * @param integer $lineStyle
	 */

	function setFootnoteSeparator($lineWidth = '1mm', $color = '#000000', $adjustment = StyleConstants::CENTER,
								  $distanceBefore = '5mm', $distanceAfter = '5mm',
								  $lineStyle = StyleConstants::SOLID) {

		if (!isLengthValue($lineWidth)) {
			throw new StyleException('Invalid line-width value');
		}
		if (!isColor($color)) {
			throw new StyleException('Invalid color value');
		}
		switch ($adjustment) {
			case StyleConstants::LEFT:
				$adjustment = 'left';break;
			case StyleConstants::RIGHT:
				$adjustment = 'right';break;
			case StyleConstants::CENTER:
				$adjustment = 'center';break;
			default:
				throw new StyleException('Invalid adjustment value');
		}
		if (!isLengthValue($distanceBefore)) {
			throw new StyleException('Invalid distance-before value');
		}
		if (!isLengthValue($distanceAfter)) {
			throw new StyleException('Invalid distance-before value');
		}
		switch($lineStyle) {
			case StyleConstants::NONE:
				$lineStyle = 'none';break;
			case StyleConstants::SOLID:
				$lineStyle = 'solid';break;
			case StyleConstants::DOTTED:
				$lineStyle = 'dotted';break;
			case StyleConstants::DASH:
				$lineStyle = 'dash';break;
			case StyleConstants::LONG_DASH:
				$lineStyle = 'long-dash';break;
			case StyleConstants::DOT_DOT_DASH:
				$lineStyle = 'dot-dot-dash';break;
			case StyleConstants::WAVE:
				$lineStyle = 'wave';break;
			default:
				throw new StyleException('Invalid line-style value.');
		}
		$footNote = $this->styleDocument->createElement('style:footnote-sep');
		$footNote->setAttribute('style:width', $lineWidth);
		$footNote->setAttribute('style:color', $color);
		$footNote->setAttribute('style:adjustment', $adjustment);
		$footNote->setAttribute('style:distance-before-sep', $distanceBefore);
		$footNote->setAttribute('style:distance-after-sep', $distanceAfter);
		$footNote->setAttribute('style:line-style', $lineStyle);

	}

	/**
	 * Specifies the writing mode for the pages.
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
		$this->pageLayoutProperties->setAttribute('style:writing-mode', $writingMode);
	}

	/**
	 * Specifies the height of the headers & footers
	 *
	 * @param length $height
	 */
	function setHeadFootHeight($element, $height) {
		if (!isLengthValue($height)) {
			throw new StyleException('Invalid height value.');
		}
		if ($element == 'header') {
			$this->headerProperties->setAttribute('svg:height', $height);
		} else if ($element == 'footer') {
			$this->footerProperties->setAttribute('svg:height', $height);
		}
	}

	function setHeaderHeight($height) {
		$this->setHeadFootHeight('header', $height);
	}
	function setFooterHeight($height) {
		$this->setHeadFootHeight('footer', $height);
	}

	/**
	 * Specifies the minimum height of the headers & footers
	 *
	 * @param length $height
	 */
	function setHeadFootMinHeight($element, $minHeight) {
		if (!isLengthValue($minHeight)) {
			throw new StyleException('Invalid min-height value.');
		}
		if ($element == 'header') {
			$this->headerProperties->setAttribute('fo:min-height', $minHeight);
		} else if ($element == 'footer'){
			$this->footerProperties->setAttribute('fo:min-height', $minHeight);
		}
	}

	function setHeaderMinHeight($minHeight) {
		$this->setHeadFootMinHeight('header', $minHeight);
	}
	function setFooterMinHeight($minHeight) {
		$this->setHeadFootMinHeight('footer', $minHeight);
	}

	/**
	 * Specify the left & right margin for headers & footers
	 *
	 * @param integer|string $leftMargin
	 * @param integer|string $rightMargin
	 */
	function setHeadFootHMargins($element, $leftMargin = 0, $rightMargin = 0) {
		if (!isNumeric($leftMargin) && !isLengthValue($leftMargin)) {
			throw new StyleException('Invalid left-margin value');
		}
		if (!isNumeric($rightMargin) && !isLengthValue($rightMargin)) {
			throw new StyleException('Invalid right-margin value');
		}
		if ($element == 'header') {
			$this->headerProperties->setAttribute('fo:margin-left', $leftMargin);
			$this->headerProperties->setAttribute('fo:margin-right', $rightMargin);
		} else if ($element == 'footer') {
			$this->footerProperties->setAttribute('fo:margin-left', $leftMargin);
			$this->footerProperties->setAttribute('fo:margin-right', $rightMargin);
		}
	}

	function setHeaderHMargins($leftMargin = 0, $rightMargin = 0) {
		$this->setHeadFootHMargins('header', $leftMargin, $rightMargin);
	}

	function setFooterHMargins($leftMargin = 0, $rightMargin = 0) {
		$this->setHeadFootHMargins('footer', $leftMargin, $rightMargin);
	}

	/**
	 * Specify the top & bottom margin for headers & footers
	 *
	 * @param integer|string $topMargin
	 * @param integer|string $bottomMargin
	 */
	function setHeadFootVMargins($topMargin, $bottomMargin) {
		if (!isNumeric($topMargin, true) && !isLengthValue($topMargin, true)) {
			throw new StyleException('Invalid top-margin value');
		}
		if (!isNumeric($bottomMargin) && !isLengthValue($bottomMargin)) {
			throw new StyleException('Invalid bottom-margin value');
		}
		$this->headerFooterProperties->setAttribute('fo:margin-top', $topMargin);
		$this->headerFooterProperties->setAttribute('fo:margin-bottom', $bottomMargin);
	}

	function setHeaderVMargins($topMargin = 0, $bottomMargin = 0) {
		$this->setHeadFootHMargins('header', $topMargin, $bottomMargin);
	}

	function setFooterVMargins($topMargin = 0, $bottomMargin = 0) {
		$this->setHeadFootHMargins('footer', $topMargin, $bottomMargin);
	}

	/**
	 * Specifies the border properties for headers & footers.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 * @param string $position Do not use this, it's for internal use only.
	 */
	function setHeadFootBorder($element, $borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
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
		if ($element == 'header') {
			$this->headerProperties->setAttribute('fo:border'.$position, "$borderWidth $borderStyle $borderColor");
		} else if ($element == 'footer') {
			$this->footerProperties->setAttribute('fo:border'.$position, "$borderWidth $borderStyle $borderColor");
		}
	}

	function setHeaderBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
							 $borderWidth = StyleConstants::THIN) {
		$this->setHeadFootBorder('header', $borderColor, $borderStyle, $borderWidth);
	}
	function setFooterBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
							 $borderWidth = StyleConstants::THIN) {
		$this->setHeadFootBorder('footer', $borderColor, $borderStyle, $borderWidth);
	}

	/**
	 * Specifies the border properties for headers & footers.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */

	function setHeaderTopBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setHeaderBorder($borderColor, $borderStyle, $borderWidth, 'top');
	}
	function setFooterTopBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
								$borderWidth = StyleConstants::THIN) {
		$this->setFooterBorder($borderColor, $borderStyle, $borderWidth, 'top');
	}

	/**
	 * Specifies the border properties for headers & footers.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */

	function setHeaderBottomBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setHeaderBorder($borderColor, $borderStyle, $borderWidth, 'bottom');
	}
	function setFooterBottomBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
								$borderWidth = StyleConstants::THIN) {
		$this->setFooterBorder($borderColor, $borderStyle, $borderWidth, 'bottom');
	}

	/**
	 * Specifies the border properties for headers & footers.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */

	function setHeaderLeftBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setHeaderBorder($borderColor, $borderStyle, $borderWidth, 'left');
	}
	function setFooterLeftBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
								$borderWidth = StyleConstants::THIN) {
		$this->setFooterBorder($borderColor, $borderStyle, $borderWidth, 'left');
	}

	/**
	 * Specifies the border properties for headers & footers.
	 *
	 * @param color $borderColor Border color
	 * @param int $borderStyle Valid values: StyleConstants::(SOLID|DOUBLE)
	 * @param int|length $borderWidth Can be a length, or one of these values: StyleConstants::(THIN|THICK|MEDIUM)
	 */

	function setHeaderRightBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
					   $borderWidth = StyleConstants::THIN) {
		$this->setHeaderBorder($borderColor, $borderStyle, $borderWidth, 'right');
	}
	function setFooterRightBorder($borderColor = '#000000', $borderStyle = StyleConstants::SOLID,
								$borderWidth = StyleConstants::THIN) {
		$this->setFooterBorder($borderColor, $borderStyle, $borderWidth, 'right');
	}

	/**
	 * Specifies the spacing around the headers & footers..
	 * Note that you must first specify the border for the padding to work
	 *
	 * @param length $padding
	 * @param string $position Do not use this, it's for internal use only.
	 */
	function setHeadFootPadding($element, $padding, $position = '') {
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
		if ($element == 'header') {
			$this->headerProperties->setAttribute('fo:padding'.$position, $padding);
		} else if ($element == 'footer') {
			$this->footerProperties->setAttribute('fo:padding'.$position, $padding);
		}
	}

	function setHeaderPadding($padding, $position = '') {
		$this->setHeadFootPadding('header', $padding, $position);
	}
	function setFooterPadding($padding, $position = '') {
		$this->setHeadFootPadding('footer', $padding, $position);
	}

	function setHeaderTopPadding($padding) {
		$this->setHeaderPadding($padding, 'top');
	}
	function setFooterTopPadding($padding) {
		$this->setFooterPadding($padding, 'top');
	}

	function setHeaderBottomPadding($padding) {
		$this->setHeaderPadding($padding, 'bottom');
	}
	function setFooterBottomPadding($padding) {
		$this->setFooterPadding($padding, 'bottom');
	}

	function setHeaderLeftPadding($padding) {
		$this->setHeaderPadding($padding, 'left');
	}
	function setFooterLeftPadding($padding) {
		$this->setFooterPadding($padding, 'left');
	}

	function setHeaderRightPadding($padding) {
		$this->setHeaderPadding($padding, 'right');
	}
	function setFooterRightPadding($padding) {
		$this->setFooterPadding($padding, 'right');
	}

	/**
	 * Specify the background color of the headers & footers.
	 *
	 * @param color $color
	 */
	function setHeadFootBackgroundColor($element, $color) {
		if ($color != StyleConstants::TRANSPARENT && !isColor($color)) {
			throw new StyleException('Invalid background color');
		}
		if ($element == 'header') {
			$this->headerProperties->setAttribute('fo:background-color', $color);
		} else if ($element == 'footer') {
			$this->footerProperties->setAttribute('fo:background-color', $color);
		}
	}

	function setHeaderBackground($color) {
		$this->setHeadFootBackgroundColor('header', $color);
	}
	function setFooterBackground($color) {
		$this->setHeadFootBackgroundColor('footer', $color);
	}

	/**
	 *
	 * @param string $image The image's path.
	 * @param integer $repeat Specifies whether a background image is repeated or stretched.
	 * Valid values are StyleConstants::(REPEAT|NO_REPEAT|STRETCH)
	 * @param integer $position Specifies where to position a background image in a paragraph.
	 * Valid values are StyleConstants::(LEFT|RIGHT|CENTER|TOP|BOTTOM)
	 */
	function setHeadFootBackgroundImage($element, $image, $repeat = StyleConstants::REPEAT,
								$position = StyleConstants::CENTER) {
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
		$binaryElement = $this->styleDocument->createElement('office:binary-data', $dateImgB64);
		$imageElement = $this->styleDocument->createElement('style:background-image');
		$imageElement->setAttribute('style:repeat', $repeat);
		$imageElement->setAttribute('style:position', $position);
		$imageElement->appendChild($binaryElement);
		if ($element == 'header') {
			$this->headerProperties->appendChild($imageElement);
		} else if ($element == 'footer') {
			$this->footerProperties->appendChild($imageElement);
		}
	}

	function setHeaderBackgroundImage($image, $repeat = StyleConstants::REPEAT, $position = StyleConstants::CENTER) {
		$this->setHeadFootBackgroundColor('header', $image, $repeat, $position);
	}
	function setFooterBackgroundImage($image, $repeat = StyleConstants::REPEAT, $position = StyleConstants::CENTER) {
		$this->setHeadFootBackgroundColor('footer', $image, $repeat, $position);
	}

	function setHeadFootContent($element, $content, $paragraphStyles = null) {
		$p = new Paragraph($paragraphStyles, false);
		if ($content == StyleConstants::PAGE_NUMBER) {
			$pageNumber = ODT::getInstance()->getDocumentContent()->createElement('text:page-number');
			$pageNumber->setAttribute('text:select-page', 'current');
			$p->getDOMElement()->appendChild($pageNumber);
		} else if($content == StyleConstants::CURRENT_DATE) {
			$date = ODT::getInstance()->getDocumentContent()->createElement('text:date');
			$p->getDOMElement()->appendChild($date);
		} else {
			$p->addText($content);
		}
		$headfoot = $this->styleDocument->createElement($element);
		$headfoot->appendChild($this->styleDocument->importNode($p->getDOMElement(), true));
		$this->masterStyleElement->appendChild($headfoot);
	}

	function setHeaderContent($content, $paragraphStyles = null) {
		$this->setHeadFootContent('style:header', $content, $paragraphStyles);
	}

	function setFooterContent($content, $paragraphStyles = null) {
		$this->setHeadFootContent('style:footer', $content, $paragraphStyles);
	}
}
?>
