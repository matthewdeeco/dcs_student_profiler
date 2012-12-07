<?php

final class Semester {
	const Invalid = 0;
	const First  = 1;
	const Second = 2;
	const Summer = 3;
	
	// prevent instantiation
	private function Semesters(){}
	
	public static function getSemesterCode($field) {
		if (strcasecmp($field, 'First') == 0)
			return Semester::First;
		else if (strcasecmp($field, 'Second') == 0)
			return Semester::Second;
		else if (strcasecmp($field, 'Summer') == 0)
			return Semester::Summer;
		else {
			$field = preg_replace('/[^\d]/', '', $field); // strip non-numeric chars
			if ($field >= Semester::First && $field <= Semester::Summer)
				return $field;
			else
				return Semester::Invalid;
		}
	}
	
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
