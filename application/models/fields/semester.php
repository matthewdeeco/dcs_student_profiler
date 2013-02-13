<?php
require_once 'field.php';

class Semester extends Field{
	const FIRST  = "1st";
	const SECOND = "2nd";
	const SUMMER = "Sum";
	
	public function parse() {
		$semester = $this->values[0];
		if (empty($semester))
			throw new Exception("Semester is blank");
		else if (is_numeric($semester)) {
			switch($semester) {
				case 1: case 2: case 3:
					return $semester;
			}
		}
		throw new Exception("Semester is invalid");
	}
	
	public function getName() {
		return "Semester";
	}
	
	public function toString() {
		$semester = $this->values[0];
		switch ($semester) {
			case 1:
				return "1st Semester";
			case 2:
				return "2nd Semester";
			case 3:
				return "Summer";
			default:
				throw new Exception("Invalid semester");
		}
	}
	
	/** Returns the termid representation of $semester.
		$semester - the semester code
	*/
	public static function semtermid($semester) {
		return $this->values[0];
	}
}
?>