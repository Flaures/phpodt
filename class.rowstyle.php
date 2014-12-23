<?php

//require_once 'class.contentautostyle.php';
//require_once 'exceptions/class.styleexception.php';

include_once 'phpodt.php';

/**
 * A Class representing style properties for table rows.
 *
 * @author Issam RACHDI
 */

class RowStyle extends ContentAutoStyle {

	private $rowProp;

	public function __construct($name) {
    if (empty($name)) {
      $name = 'rowstyle'.rand(100, 9999999);
    }
		parent::__construct($name);
		$this->styleElement->setAttribute('style:family', 'table-row');
		$this->rowProp = $this->contentDocument->createElement('style:table-row-properties');
		$this->styleElement->appendChild($this->rowProp);
	}
	
	
	public function setMinHeight($minHeight) {
		if(isLengthValue($minHeight, true)) {
			$this->rowProp->setAttribute('style:min-row-height', $minHeight);
		} else {
			throw new StyleException('Invalid min-height value');
		}
	}
	
	public function setHeight($height) {
		if(isLengthValue($height, true)) {
			$this->rowProp->setAttribute('style:row-height', $height);
		} else {
			throw new StyleException('Invalid height value');
		}
	}
	
	/**
	 * Specifies if the row height should be recalculated automatically 
	 * if some content in the row changes.
	 * 
	 * @param type $optimalHeight 
	 */
	public function setOptimalHeight($optimalHeight) {
		if(is_bool($optimalHeight)) {
			$this->rowProp->setAttribute('style:use-optimal-row-height', $optimalHeight);
		} else {
			throw new StyleException('Value is not boolean');
		}
	}

	/**
	 * Sets the background color of the row
	 * 
	 * @param color $color 
	 */
	public function setBgColor($color) {
		if (!isColor($color)) {
			throw new StyleException('Invalid color value');
		}
		$this->rowProp->setAttribute('fo:background-color', $color);
	}
	
	/**
	 * Specifies a background image for a row. Note that if you specify the position, the image
	 * will not be repeated
	 *
	 * @param string $image The image's path.
	 * @param integer $repeat Specifies whether the background image is repeated or stretched.
	 * @param integer $position Specifies where to position the background image.
	 * Valid values are StyleConstants::(LEFT|RIGHT|CENTER|TOP|BOTTOM)
	 */
  //vertical and horizontal can be merged it seems, à revoir
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
		$this->rowProp->appendChild($imageElement);
	}
	
	/**
	 * Insert a page or column break before a table column
	 *
	 */
//	function setBreakBefore($breakBefore) {
//		switch ($breakBefore) {
//			case StyleConstants::PAGE:
//				$breakBefore = 'page';break;
//			case StyleConstants::COLUMN:
//				$breakBefore = 'column';break;
//			default:
//				throw new StyleException('Invalid break-before value.');
//		}
//		$this->rowProp->setAttribute('fo:break-before', $breakBefore);
//	}

	/**
	 * Insert a page or column break after a table column
	 *
	 */
//	function setBreakAfter($breakAfter) {
//		switch ($breakAfter) {
//			case StyleConstants::PAGE:
//				$breakAfter = 'page';break;
//			case StyleConstants::COLUMN:
//				$breakAfter = 'column';break;
//			default:
//				throw new StyleException('Invalid break-after value.');
//		}
//		$this->rowProp->setAttribute('fo:break-after', $breakAfter);
//	}
}

?>
