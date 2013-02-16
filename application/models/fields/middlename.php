<?php
require_once 'field.php';

class Middlename extends Field {
	public function parse($middlename, $a = null, $b = null) {
		if (preg_match('/\d/', $middlename))
			throw new Exception("Middle name contains numeric characters");
		else if (preg_match('/[^\'\-a-zA-Z\. \x{00D1}\x{00F1}]/u', $middlename))
			throw new Exception("Middle name contains non-alphabetic characters");
		$this->values['middlename'] = $middlename;
	}
}
?>