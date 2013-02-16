<?php
require_once 'field.php';

class Middlename extends Field {
	public function parse($middlename, $a = null, $b = null) {
		if (preg_match('/[^\-a-zA-Z\. ]/u', $middlename))
			throw new Exception("Middle name contains non-alphabetic characters");
		$this->values['middlename'] = $middlename;
	}
}
?>