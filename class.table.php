<?php

require_once 'class.odt.php';

/**
 * A Class representing a table.
 *
 * @author Issam RACHDI
 */
class Table {

	private $contentDocument;
	private $tableElement;
	private $columns;
	private $columnsStyles;
	private $rows;
	private $rowsStyles;
	private $cells;
	private $cellsStyles;
	private $tableName;

	/**
	 *
	 * @param DOMDocument $contentDoc The DOMDocument instance of content.xml
	 * @param TableStyle $tableStyle A TableStyle object representing table style properties
	 */
	public function __construct($tableName, $tableStyle = null) {

		$this->contentDocument = ODT::getInstance()->getDocumentContent();
		$this->tableName = $tableName;
		$this->tableElement = $this->contentDocument->createElement('table:table');
		$this->tableElement->setAttribute('table:name', $tableName);
		if ($tableStyle != null) {
			if ($tableStyle->getStyleName() != $tableName) {
				$tableStyle->setStyleName($tableName);
			}
			$this->tableElement->setAttribute('table:style-name', $tableStyle->getStyleName());
		}
		$this->columns = array();
		$this->columnsStyles = array();
		$this->rows = array();
		$this->rowsStyles = array();
		$this->cells = array();
		$this->cellsStyles = array();

		$this->contentDocument->getElementsByTagName('office:text')->item(0)->appendChild($this->tableElement);
	}

	/**
	 * Create the number of columns specified. If the DOMDocument representing the styles is passed as the 
	 * second argument, a ColumnStyle is created for each column, and can be retrieved by 
	 * the method {@link #getColumnStyle getColumnStyle()}
	 * 
	 * @param integer $nbCols The number of columns
	 * @param DOMDocument $styleDoc 
	 */
	public function createColumns($nbCols, $createStyles = true) {
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
				'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$columnsElem = $this->contentDocument->createElement('table:table-columns');
		$letterIndex = 0;
		for ($i = 0; $i < $nbCols; $i++) {
			$column = $this->contentDocument->createElement('table:table-column');
			$styleName = $this->tableName . '.' . ($i < 26 ? $letters[$i] : $letters[0] . $letters[$i - 26]);
//			$styleName = 'Col'.($i+1);
			$column->setAttribute('table:style-name', $styleName);
			$columnsElem->appendChild($column);
			$this->columns[] = $column;
			if ($createStyles) {
				$this->columnsStyles[] = new ColumnStyle($styleName);
			}
		}
		$this->tableElement->appendChild($columnsElem);
	}

	/**
	 * Creates a header for each column using the elments of the array passed.
	 * 
	 * @param array $headers 
	 */
	public function addHeader($headers) {
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
				'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$rowHeader = $this->contentDocument->createElement('table:table-row');
		$j = 0;
		foreach ($headers as $header) {
			$cell = $this->contentDocument->createElement('table:table-cell');
//			$styleName = $this->tableName.'.'.($j < 26 ? $letters[$j] : $letters[0].$letters[$j - 26]).'1';
//			$cell->setAttribute('table:style-name', $styleName);
			$p = $this->contentDocument->createElement('text:p', $header);
			$cell->appendChild($p);
			$rowHeader->appendChild($cell);
			$j++;
		}
		$headersElement = $this->contentDocument->createElement('table:table-header-rows');
		$headersElement->appendChild($rowHeader);
		$this->tableElement->appendChild($headersElement);
	}

	/**
	 * Add rows to the table. 
	 * 
	 * @param array $rows A two dimension array, representing the rows, and the cells inside each row
	 * @param DOMDocument $styleDoc The DOMDocument representing the styles
	 */
	public function addRows($rows, $createStyles = true) {
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 
						 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$rowsElement = $this->contentDocument->createElement('table:table-rows');
		$i = 0;
		foreach ($rows as $row) {
			$j = 0;
			$rowElement = $this->contentDocument->createElement('table:table-row');
			$rowElement->setAttribute('office:value-type', 'string');
			$this->rows[] = $rowElement;
			foreach ($row as $cell) {
				$cellElement = $this->contentDocument->createElement('table:table-cell');
				if ($createStyles) {
					$styleName = $this->tableName . '.' . ($j < 26 ? $letters[$j] : $letters[0] . $letters[$j - 26]) . ($i + 1);
					$this->cellsStyles[$i][$j] = new CellStyle($styleName);
					$cellElement->setAttribute('office:value-type', 'string');
					$cellElement->setAttribute('table:style-name', $styleName);
				}
				if ($cell instanceof Paragraph) {
          $p = $cell->getDOMElement();
        } else {
          $p = $this->contentDocument->createElement('text:p', $cell);
        }
				$cellElement->appendChild($p);
				$this->cells[$i][$j] = $cellElement;
				$rowElement->appendChild($cellElement);
				$j++;
			}
			$rowsElement->appendChild($rowElement);
			$i++;
		}
		$this->tableElement->appendChild($rowsElement);
	}

	/**
	 * Affect a RowStyle to the row at the position $rowIndex
	 *
	 * @param integer $rowIndex
	 * @param RowStyle $rowStyle 
	 */
	public function setRowStyle($rowIndex, $rowStyle) {
		$this->rows[$rowIndex]->setAttribute('table:style-name', $rowStyle->getStyleName());
	}

	/**
	 * Affect a CellStyle to the cell at the position ($colIndex, $rowIndex)
	 * @param integer $colIndex
	 * @param integer $rowIndex
	 * @param CellStyle $cellStyle 
	 */
	public function setCellStyle($colIndex, $rowIndex, $cellStyle) {
		$this->cells[$rowIndex][$colIndex]->setAttribute('table:style-name', $cellStyle->getStyleName());
	}

	/**
	 * Return the ColumnStyle of the given index
	 * 
	 * @param integer $index
	 * @return ColumnStyle 
	 */
	public function getColumnStyle($index) {
		return $this->columnsStyles[$index];
	}

	/**
	 * Return the CellStyle of the given position
	 * 
	 * @param integer $col
	 * @param integer $row
	 * @return CellStyle 
	 */
	public function getCellStyle($col, $row) {
		return $this->cellsStyles[$row][$col];
	}

	function setStyle($tableStyle) {
		if ($tableStyle->getStyleName() != $this->tableName) {
			$tableStyle->setStyleName($this->tableName);
		}
		$this->tableElement->setAttribute('table:style-name', $tableStyle->getStyleName());
	}
  
  function getTableName() {
    return $this->tableName;
  }

}

?>
