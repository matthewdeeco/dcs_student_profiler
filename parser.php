<?php

/** Defines a family of parser classes (excel parser, csv parser in the future) */
abstract class Parser {
		
	/** Implementation depends on the type of parser. */
	public abstract function parse();
	
	protected function parseTermName($acadyear, $semester, &$querydata) {
		$this->parseAcadYear($acadyear, $querydata);
		$this->parseSemester($semester, $querydata);
		$querydata->termname = Semester::toString($semester)." ".$acadyear;
	}
	
	protected function parseAcadYear(&$acadyear, &$querydata) {
		$acadyear = preg_replace('/ /', '', $acadyear); // remove spaces
		if (empty($acadyear)) // nothing was left
			throw new Exception("Acad Year is empty");
		else if (preg_match('/[^\d\-]/', $acadyear))
			throw new Exception("Acad Year contains non-numeric characters");
		$acadyear = explode("-", $acadyear); // separate by hyphen (e.g. 2010-2011)
		$acadyear = array_filter($acadyear); // remove empty elements
		$acadyear = array_values($acadyear); // rearrange elements to remove gaps in index
		$start = $acadyear[0];
		if (count($acadyear) == 1) // no end year was specified
			$acadyear = $start."-".($start + 1);
		else { // end year was specified
			$end = $acadyear[1];
			if ($end - $start !== 1)
				throw new Exception("Start and end of Acad Year is not 1 year apart");
			$acadyear = $start."-".$end;
		}
		$querydata->acadyear = $acadyear;
	}
	
	protected function parseSemester(&$semester, &$querydata) {
		if (empty($semester))
			throw new Exception("Semester is blank");
		else if (($semester = Semester::getSemesterCode($semester)) == Semester::Invalid)
			throw new Exception("Semester is invalid");
		else
			$querydata->semester = $semester;
	}

	protected function parseStudentNo(&$studentno, &$querydata) {
		$studentno = preg_replace('/[ \-]/', '', $studentno); // remove spaces and hyphens
		if (empty($studentno))
			throw new Exception("Student # is empty");
		else if (preg_match('/[^\d]/', $studentno))
			throw new Exception("Student # contains non-numeric characters");
		else if (strlen($studentno) != 9)
			throw new Exception("Student # must be exactly 9 digits long");
		$querydata->studentno = $studentno;
	}
	
	protected function parseLastName(&$lastname, &$queryData) {
		if (empty($lastname))
			throw new Exception("Last name is empty");
		else if (preg_match('/\d/', $lastname))
			throw new Exception("Last name contains numeric characters");
		else if (preg_match('/[^a-zA-Z\.]/', $lastname))
			throw new Exception("Last name contains non-alphabetic characters");
		$queryData->lastname = $lastname;
	}
	
	protected function parseFirstName(&$firstname, &$queryData) {
		if (empty($firstname))
			throw new Exception("First name is empty");
		else if (preg_match('/\d/', $firstname))
			throw new Exception("First name contains numeric characters");
		else if (preg_match('/[^a-zA-Z\.]/', $lastname))
			throw new Exception("First name contains non-alphabetic characters");
		$queryData->firstname = $firstname;
	}
	
	protected function parseMiddleName(&$middlename, &$queryData) {
		if (preg_match('/\d/', $middlename))
			throw new Exception("Middle name contains numeric characters");
		else if (preg_match('/[^a-zA-Z\.]/', $lastname))
			throw new Exception("Middle name contains non-alphabetic characters!");
		$queryData->middlename = $middlename;
	}
	
	protected function parsePedigree(&$pedigree, &$queryData) {
		$queryData->pedigree = $pedigree;
	}
	
	protected function parseClassCode(&$classcode, &$queryData) {
		if (empty($classcode))
			throw new Exception("Class code is empty");
		else if (preg_match('/[^\d]/', $classcode))
			throw new Exception("Class code contains non-numeric characters");
		/* else if (strlen($classcode) != 5)
			throw new Exception("Class code must be exactly 5 digits long"); */
		$queryData->classcode = $classcode;
	}
	
	protected function parseClassName($classname, &$queryData) {
		if (empty($classname))
			throw new Exception("Class is empty");
		if (($lastspace = strrpos($classname, " ")) === false)// no spaces
			throw new Exception("Course name and section cannot be distinguished");
		$coursename = substr($classname, 0, $lastspace);
		$section = substr($classname, $lastspace);
		// check if $coursename is in table?
		/*
		$sql = "SELECT * FROM courses WHERE coursename = '$coursename'; "
		//send sql
		$result = pg_query($conn, $sql); //yung $conn, connection sa database. i.e. $conn = pg_pconnect("dbname=publisher"); 
		if(!$result){
			//not sure what to do. :D
			throw new Exception("Class is invalid.");
			exit();
		}
		*/
		$queryData->coursename = $coursename;
		$queryData->section = $section;
	}
	
	protected function parseGrade(&$grade, $compgrade, $secondcompgrade, &$querydata) {
		if (empty($grade))
			throw new Exception("Grade is empty");
		$grade = preg_replace('/[^\d.]*/', '', $grade); // strip non-numeric and non-dot chars
		// what about inc/drp?
		$grade = floatval($grade);
		$querydata->grade = $grade;
	}
}

?>