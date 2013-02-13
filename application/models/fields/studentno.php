<?php
require_once 'field.php';

class Studentno extends Field {
	public function parse() {
		$studentno = $this->values[0];
		$studentno = preg_replace('/[ \-]/', '', $studentno); // remove spaces and hyphens
		if (empty($studentno))
			throw new Exception("Student # is empty");
		else if (preg_match('/[^\d]/', $studentno))
			throw new Exception("Student # contains non-numeric characters");
		else if (strlen($studentno) != 9)
			throw new Exception("Student # must be exactly 9 digits long");
		return $studentno;
	}
}
?>