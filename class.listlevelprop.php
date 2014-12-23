<?php

//require_once 'class.styleconstants.php';

include_once 'phpodt.php';

class ListLevelProp {

	private $align = NULL;
	private $indent = NULL;
	private $minLabelWidth = NULL;
	private $minLabelDistance = NULL;
	private $vAlign = NULL;
	private $imageWidth = NULL;
	private $imageHeight = NULL;

	function __construct() {
		
	}

	public function getAlign() {
		return $this->align;
	}

	/**
	 * Specifies the horizontal alignment of a label (number) within the width 
	 * specified by the {@link #setMinLabelWidth setMinLabelWidth()} method
	 * 
	 * @param integer $align 
	 */
	public function setAlign($align) {
		switch($align) {
			case StyleConstants::START:
				$align = 'start';break;
			case StyleConstants::END:
				$align = 'end';break;
			case StyleConstants::LEFT:
				$align = 'left';break;
			case StyleConstants::RIGHT:
				$align = 'right';break;
			case StyleConstants::CENTER:
				$align = 'center';break;
			case StyleConstants::JUSTIFY:
				$align = 'justify';break;
			default:
				throw new StyleException('Invalid align value');
		}
		$this->align = $align;
	}

	public function getIndent() {
		return $this->indent;
	}

	/**
	 * Specifies the space to include before the number for all paragraphs at this level. 
	 * The value of this property is absolute. This means that when the position of a label is 
	 * calculated the indent value of the current level is only considered. The indent values for 
	 * lower levels do not affect the label position.
	 * 
	 * @param length $indent 
	 */
	public function setIndent($indent) {
		$this->indent = $indent;
	}

	public function getMinLabelWidth() {
		return $this->minLabelWidth;
	}

	/**
	 * Specifies the minimum width of a number.
	 * 
	 * @param length $minLabelWidth 
	 */
	public function setMinLabelWidth($minLabelWidth) {
		if (isLengthValue($minLabelWidth, true)) {
			$this->minLabelWidth = $minLabelWidth;
		} else {
			throw new StyleException('Invalid min-label-width value');
		}
	}

	public function getMinLabelDistance() {
		return $this->minLabelDistance;
	}

	/**
	 * Specifies the minimum distance between the number and the text of the list item.
	 * 
	 * @param length $minLabelDistance 
	 */
	public function setMinLabelDistance($minLabelDistance) {
		if (isLengthValue($minLabelDistance, true)) {
			$this->minLabelDistance = $minLabelDistance;
		} else {
			throw new StyleException('Invalid min-label-distance value');
		}
	}

	public function getVAlign() {
		return $this->vAlign;
	}

	/**
	 * Specifies the vertical alignment of the image.
	 * 
	 * @param integer $vAlign Valid values are StyleConstants::(TOP|MIDDLE|BOTTOM)
	 */
	public function setVAlign($vAlign) {
		switch ($vAlign) {
			case StyleConstants::TOP:
				$vAlign = 'top';break;
			case StyleConstants::MIDDLE:
				$vAlign = 'middle';break;
			case StyleConstants::BOTTOM:
				$vAlign = 'bottom';break;						
			default:
				throw new StyleException('Invalid vertical align value');
		}
		$this->vAlign = $vAlign;
	}

	public function getImageWidth() {
		return $this->imageWidth;
	}

	/**
	 * Specifies the images's width
	 * 
	 * @param length $imageWidth 
	 */
	public function setImageWidth($imageWidth) {
		if (isLengthValue($imageWidth, true)) {
			$this->imageWidth = $imageWidth;
		} else {
			throw new StyleException('Invalid image width value');
		}		
	}

	public function getImageHeight() {
		return $this->imageHeight;
	}

	/**
	 * Specifies the images's heiht
	 * 
	 * @param length $imageHeight 
	 */
	public function setImageHeight($imageHeight) {
		if (isLengthValue($imageHeight, true)) {
			$this->imageHeight = $imageHeight;
		} else {
			throw new StyleException('Invalid image height value');
		}
	}
}
?>