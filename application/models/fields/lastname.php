<?php
require_once 'field.php';

class Lastname extends Field {
	public function parse($lastname, $a = null, $b = null) {
		if (empty($lastname))
			throw new Exception("Last name is empty");
		else if (preg_match('/[^\-a-zA-Z\. ]/u', $lastname))
			throw new Exception("Last name contains non-alphabetic characters");
		$this->values['lastname'] = $lastname;
	}
}
?>