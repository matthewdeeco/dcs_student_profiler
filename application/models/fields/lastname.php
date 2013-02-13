<?php
require_once 'field.php';

class Lastname extends Field {
	public function parse() {
		$lastname = $this->values[0];
		if (empty($lastname))
			throw new Exception("Last name is empty");
		else if (preg_match('/\d/', $lastname))
			throw new Exception("Last name contains numeric characters");
		else if (preg_match('/[^\'\-a-zA-Z\. \x{00D1}\x{00F1}]/u', $lastname))
			throw new Exception("Last name contains non-alphabetic characters");
		return $lastname;
	}
	
	public function getName() {
		return "Last name";
	}
}
?>