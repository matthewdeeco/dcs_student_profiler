<?php
require_once 'field.php';

class Firstname extends Field {
	public function parse() {
		$firstname = $this->values[0];
		if (empty($firstname))
			throw new Exception("First name is empty");
		else if (preg_match('/\d/', $firstname))
			throw new Exception("First name contains numeric characters");
		else if (preg_match('/[^\'\-a-zA-Z\. \x{00D1}\x{00F1}]/u', $firstname))
			throw new Exception("First name contains non-alphabetic characters");
		return $firstname;
	}
	
	public function getName() {
		return "First name";
	}
}
?>