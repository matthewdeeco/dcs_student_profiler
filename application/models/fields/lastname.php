<?php
require_once 'field.php';

class Lastname extends Field {
	public function parse($lastname, $a = null, $b = null) {
		if (empty($lastname))
			throw new Exception("Last name is empty");
		else if (preg_match('/\d/', $lastname))
			throw new Exception("Last name contains numeric characters");
		else if (preg_match('/[^\'\-a-zA-Z\. \x{00D1}\x{00F1}]/u', $lastname))
			throw new Exception("Last name contains non-alphabetic characters");
		$this->values['lastname'] = $lastname;
	}
}
?>