<?php
require_once 'field.php';

class Middlename extends Field {
	public function parse() {
		$middlename = $this->values[0];
		if (preg_match('/\d/', $middlename))
			throw new Exception("Middle name contains numeric characters");
		else if (preg_match('/[^\'\-a-zA-Z\. \x{00D1}\x{00F1}]/u', $middlename))
			throw new Exception("Middle name contains non-alphabetic characters");
		return $middlename;
	}
	
	public function getName() {
		return "Middle name";
	}
}
?>