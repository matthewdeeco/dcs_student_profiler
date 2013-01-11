	<?php

/** Defines constants related to Semester and functions to
	convert integer to text interpretations of Semester. */
final class Semester {
	const Invalid = 0;
	const First  = 1;
	const Second = 2;
	const Summer = 3;
	
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
			$semester = preg_replace('/ /', '', $semester);
			$semester = $semester[0];
			if ($semester >= Semester::First && $semester <= Semester::Summer)
				return $semester;
			else
				return Semester::Invalid;
		}
	}
	
	/** Returns the text representation of $semester.
		$semester - the semester code
	*/
	public static function toString($semester) {
		if ($semester == Semester::First)
			return "1st Semester";
		else if ($semester == Semester::Second)
			return "2nd Semester";
		else if ($semester == Semester::Summer)
			return "Summer";
		else
			throw new Exception("toString: Invalid semester");
	}
}

?>
