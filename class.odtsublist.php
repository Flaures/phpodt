<?php

include_once 'phpodt.php';

class ODTSubList extends ODTList {
  
  function __construct($items = null) {
    parent::__construct($items, false);
  }
}
?>
