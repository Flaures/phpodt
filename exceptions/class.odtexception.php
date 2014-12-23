<?php
	class ODTException extends Exception {
		
		protected $message;
		
		function __construct($message) {
			$this->message = $message;
		}
		
		function __toString() {
			$class = new ReflectionClass($this);
			$trace = $this->getTrace();
			$errorMsg = 'Exception "'.$class->getName().'" in '.$trace[0]['file'].'('.$trace[0]['line'].')'.
						': <strong>'.$this->getMessage().'</strong>';
			return $errorMsg;
		}
	}
?>
