<?php

/** Defines a family of parser classes (excel parser, csv parser in the future) */
abstract class Parser extends CI_Model {
		
	/** Implementation depends on the type of parser. */
	public abstract function parse();
	
	protected function parseTermName($acadyear, $semester) {
		$this->parseAcadYear($acadyear, $this->querydata);
		$this->parseSemester($semester, $this->querydata);
		$this->querydata->termname = Semester::toString($semester)." ".$acadyear;
	}
	
	protected function parseAcadYear(&$acadyear) {
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
		$this->querydata->acadyear = $acadyear;
	}
	
	protected function parseSemester(&$semester) {
		if (empty($semester))
			throw new Exception("Semester is blank");
		else if (($semester = Semester::getSemesterCode($semester)) == Semester::Invalid)
			throw new Exception("Semester is invalid");
		else
			$this->querydata->semester = $semester;
	}

	protected function parseStudentNo(&$studentno) {
		$studentno = preg_replace('/[ \-]/', '', $studentno); // remove spaces and hyphens
		if (empty($studentno))
			throw new Exception("Student # is empty");
		else if (preg_match('/[^\d]/', $studentno))
			throw new Exception("Student # contains non-numeric characters");
		else if (strlen($studentno) != 9)
			throw new Exception("Student # must be exactly 9 digits long");
		$this->querydata->studentno = $studentno;
	}
	
	protected function parseLastName(&$lastname) {
		if (empty($lastname))
			throw new Exception("Last name is empty");
		else if (preg_match('/\d/', $lastname))
			throw new Exception("Last name contains numeric characters");
		else if (preg_match('/[^a-zA-Z\. \x{00D1}\x{00F1}]/u', $lastname))
			throw new Exception("Last name contains non-alphabetic characters");
		$this->querydata->lastname = $lastname;
	}
	
	protected function parseFirstName(&$firstname) {
		if (empty($firstname))
			throw new Exception("First name is empty");
		else if (preg_match('/\d/', $firstname))
			throw new Exception("First name contains numeric characters");
		else if (preg_match('/[^a-zA-Z\. \x{00D1}\x{00F1}]/u', $firstname))
			throw new Exception("First name contains non-alphabetic characters");
		$this->querydata->firstname = $firstname;
	}
	
	protected function parseMiddleName(&$middlename) {
		if (preg_match('/\d/', $middlename))
			throw new Exception("Middle name contains numeric characters");
		else if (preg_match('/[^a-zA-Z\. \x{00D1}\x{00F1}]/u', $middlename))
			throw new Exception("Middle name contains non-alphabetic characters!");
		$this->querydata->middlename = $middlename;
	}
	
	protected function parsePedigree(&$pedigree) {
		$this->querydata->pedigree = $pedigree;
	}
	
	protected function parseClassCode(&$classcode) {
		if (empty($classcode))
			throw new Exception("Class code is empty");
		else if (preg_match('/[^\d]/', $classcode))
			throw new Exception("Class code contains non-numeric characters");
		/* else if (strlen($classcode) != 5)
			throw new Exception("Class code must be exactly 5 digits long"); */
		$this->querydata->classcode = $classcode;
	}
	
	protected function parseClassName($classname) {
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
		$this->querydata->coursename = $coursename;
		$this->querydata->section = $section;
	}
	
	protected function parseGrade(&$grade, $compgrade, $secondcompgrade) {
		if (empty($compgrade)) {
			if (empty($secondcompgrade)) {
				if (empty($grade))
					throw new Exception("Grade is empty");
				else if (preg_match('/^([1-2]\.([27]5|[05]0))|([3-5]\.00)$/', $grade))
					$this->querydata->grade = $grade;
				else
					throw new Exception("Invalid input in grade");
			}
			else
				throw new Exception("Expected input in completion(g)");
		}
		else if (preg_match('/^(4\.00)$|^DRP$|^INC$/', $compgrade))
			if (empty($secondcompgrade))
				$this->querydata->grade = $compgrade;
			else if (preg_match('/^([1-2]\.([27]5|[05]0))|([3-5]\.00)$/', $secondcompgrade))
				if($grade != $secondcompgrade)
					throw new Exception("Grade and secondcompletion should have the same values");
				else	
					$this->querydata->grade = $secondcompgrade;
			else
				throw new Exception("Invalid input in secondcompletion");			
		else
			throw new Exception("Expected input in completion(g): '4.00', 'INC', 'DRP'");					
	}
}

?>
