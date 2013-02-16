<?php
require_once 'field.php';

class Firstname extends Field {
	public function parse($firstname, $a = null, $b = null) {
		if (empty($firstname))
			throw new Exception("First name is empty");
		else if (preg_match('/\d/', $firstname))
			throw new Exception("First name contains numeric characters");
		else if (preg_match('/[^\'\-a-zA-Z\. \x{00D1}\x{00F1}]/u', $firstname))
			throw new Exception("First name contains non-alphabetic characters");
		$this->values['firstname'] = $firstname;
	}
}
?>