<?php

//require_once 'functions.php';
//require_once 'class.odt.php';

include_once 'phpodt.php';

/**
 * Base class for paragraph & text styles.
 * 
 * @author Issam RACHDI
 */
class ContentAutoStyle {

	/**
	 * The DOMDocument representing the styles xml file
	 * @access private
	 * @var DOMDocument
	 */
	protected $contentDocument;
	
	/**
	 * The name of the style
	 * @access private
	 * @var string
	 */
	protected $name;
	
	/**
	 * The DOMElement representing this style
	 * @access private
	 * @var DOMElement
	 */
	protected $styleElement;

	/**
	 * The constructor initializes the properties, then creates a <style:style> element , or an other element 
	 * if $elementNodeName is specified, representing this specific style, and add it to <office:automatic-styles> element
	 * 
	 * @param DOMDocument $contentDoc The content Document returned by the method {@link ODT.html#initContent initContent()}
	 * @param string $name The style name
	 */
	function __construct($name, $elementNodeName = NULL) {
		$this->contentDocument = ODT::getInstance()->getDocumentContent();
		$this->name = $name;
		if ($elementNodeName == NULL) {
			$this->styleElement = $this->contentDocument->createElement('style:style');
		} else {
			$this->styleElement = $this->contentDocument->createElement($elementNodeName);
		}
		$this->styleElement->setAttribute('style:name', $name);
		$this->contentDocument->getElementsByTagName('office:automatic-styles')->item(0)->appendChild($this->styleElement);
	}

	/**
	 * return the name of this style
	 * @return string
	 */
	function getStyleName() {
		return $this->name;
	}
	
	public function setStyleName($name) {
		$this->name = $name;
	}


}

?>
