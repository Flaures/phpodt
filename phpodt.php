<?php

include 'functions.php';

function odt_autoload($classname) {
  $classname = strtolower($classname);
  $path = "class.$classname.php";
  if (strpos($classname, 'exception')) {
    $path = "exceptions/class.$classname.php";
  }
  include_once $path;
}

spl_autoload_extensions('.php');
spl_autoload_register('odt_autoload');


?>
