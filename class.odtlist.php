<?php

//require_once 'class.paragraph.php';
//require_once 'class.odt.php';

include_once 'phpodt.php';

/**
 * A Class representing a list.
 *
 * @author Issam RACHDI
 */

class ODTList {

	/**
	 * The DOMDocument instance containing the content of the document
	 * @var DOMDocument
	 */
	private $contentDocument;

	/**
	 * The DOMElement representing the list
	 * @var DOMElement
	 */
	private $listElement;

	/**
	 *
	 * @param DOMDocument $contentDocument
	 * @param boolean $addToDocument true if you're adding the list to the document, false if you want to add it as an item to another list
	 * @param array $items The items of the list. You can also add items via the addItem method
	 */
	function __construct($items = null, $addToDocument = true) {
		$this->contentDocument = ODT::getInstance()->getDocumentContent();
		$this->listElement = $this->contentDocument->createElement('text:list');
		if ($addToDocument) {
			$this->contentDocument->getElementsByTagName('office:text')->item(0)->appendChild($this->listElement);
		}
		if ($items != null && is_array($items)) {
			foreach ($items as $item) {
				$this->addItem($item);
			}
		}
	}

	/**
	 * Specifies the styles to apply to this list
	 *
	 * @param ListStyle $listStyle
	 */
	function setStyle($listStyle) {
		$this->listElement->setAttribute('text:style-name', $listStyle->getStyleName());
	}

	/**
	 * Adds an item to the list
	 *
	 * @param string $item
	 */
	function addItem($item) {
		$element = $this->contentDocument->createElement('text:list-item');
		$p = new Paragraph(null, false);
		$p->addText($item);
		$element->appendChild($p->getDOMElement());
		$this->listElement->appendChild($element);
	}

	/**
	 * Adds a sublist to this list
	 *
	 * @param ODTList $sublist
	 */
	function addSubList($sublist) {
		$element = $this->contentDocument->createElement('text:list-item');
		$element->appendChild($sublist->getDOMElement());
		$this->listElement->appendChild($element);
	}

	/**
	 * Returns the DOMElement representing this list
	 *
	 * @return DOMElement
	 */
	public function getDOMElement() {
		return $this->listElement;
	}
}

?>
