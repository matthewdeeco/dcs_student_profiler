<?php
require_once 'field.php';

class Grade extends Field {
	public function parse() {
		$grade = $this->values[0];
		$compgrade = $this->values[1];
		$secondcompgrade = $this->values[2];
		if (empty($grade))
			return "NG";
		else if (preg_match('/^([1-2](\.([27]5|[05]0))?)|([3-5](\.00)?)$/', trim($grade)))
			return number_format($grade, 2); // 2 decimal places					
		else if (preg_match('/^DRP$|^NG$/', trim($grade)))
			return $grade;
		else if (preg_match('/^(4(\.00)?)$|^INC$/', trim($grade))) {
			if (empty($compgrade))
				return $grade;
			else if (preg_match('/^([1-2](\.([27]5|[05]0))?)|([3-5](\.00)?)$/', trim($compgrade)))
				return number_format($compgrade, 2); // 2 decimal places
		}
		else
			throw new Exception("Invalid input in grade");
	}
}
?>