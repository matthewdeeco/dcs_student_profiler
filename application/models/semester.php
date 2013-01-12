<?php

/** Defines constants related to Semester and functions to
	convert integer to text interpretations of Semester. */
final class Semester {
	const First  = "1st";
	const Second = "2nd";
	const Summer = "Sum";
	
	/** Private constructor to prevent instantiation. */
	private function Semesters(){}
	
	/** Returns the semester code of $semester.
		$semester - the text or number to be interpreted
	*/
	public static function getSemesterCode($semester) {
		if (strcasecmp($semester, 'First') == 0)
			return Semester::First;
		else if (strcasecmp($semester, 'Second') == 0)
			return Semester::Second;
		else if (strcasecmp($semester, 'Summer') == 0)
			return Semester::Summer;
		else {
			$semester = trim($semester);
			$semester = $semester[0];
			switch($semester) {
				case 1: return Semester::First;
				case 2: return Semester::Second;
				case 3: return Semester::Summer;
				default: throw new Exception("Semester is invalid");
			}
		}
	}
	
	/** Returns the text representation of $semester.
		$semester - the semester code
	*/
	public static function semname($semester) {
		if ($semester == Semester::First || $semester == Semester::Second)
			return $semester." Semester";
		else if ($semester == Semester::Summer)
			return "Summer";
		else
			throw new Exception("Invalid semester");
	}
	
	/** Returns the termid representation of $semester.
		$semester - the semester code
	*/
	public static function semtermid($semester) {
		if ($semester == Semester::First)
			return 1;
		else if ($semester == Semester::Second)
			return 2;
		else if ($semester == Semester::Summer)
			return 3;
		else
			throw new Exception("Invalid semester");
	}
}

?>
