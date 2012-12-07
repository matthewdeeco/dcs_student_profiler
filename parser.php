<?php

abstract class Parser {
		
		public abstract function parse();
		
		protected function parseTermName($acadyear, $semester, &$queryData) {
			$this->parseAcadYear($acadyear, $queryData);
			$this->parseSemester($semester, $queryData);
			$queryData->termname = Semester::toString($semester)." ".$acadyear;
		}
		
		protected function parseAcadYear(&$acadyear, &$queryData) {
			$acadyear = preg_replace('/[^\d\-]*/', '', $acadyear); // remove non-numeric and non-hyphen chars
			if (empty($acadyear)) // nothing was left
				throw new Exception("Acad Year has no numeric characters");
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
			$queryData->acadyear = $acadyear;
		}
		
		protected function parseSemester(&$semester, &$queryData) {
			if (empty($semester))
				throw new Exception("Semester cannot be blank");
			else if (($semester = Semester::getSemesterCode($semester)) == Semester::Invalid)
				throw new Exception("Semester is invalid");
			else
				$queryData->semester = $semester;
		}
	
		protected function parseStudentNo(&$studentno, &$queryData) {
			if (empty($studentno))
				throw new Exception("Student no cannot be blank");
			$studentno = preg_replace('/[^\d]*/', '', $studentno); // strip non-numeric chars
			if (strlen($studentno) != 9)
				throw new Exception("Student no must be exactly 9 digits long");
			$queryData->studentno = $studentno;
		}
		
		protected function parseLastName(&$lastname, &$queryData) {
			if (empty($lastname))
				throw new Exception("Last name is empty");
			else if(preg_match('/[0-9]/', $lastname)){
				throw new Exception("Last name contains numeric characters!");
			}
			$queryData->lastname = $lastname;
		}
		
		protected function parseFirstName(&$firstname, &$queryData) {
			if(empty($firstname))
				throw new Exception("First name is empty");
			else if(preg_match('/[0-9]/', $firstname)){
				throw new Exception("First name contains numeric characters!");
			}
			$queryData->firstname = $firstname;
		}
		
		protected function parseMiddleName(&$middlename, &$queryData) {
			if(empty($middlename))
				throw new Exception("Middle name is empty");
			else if(preg_match('/[0-9]/', $middlename)){
				throw new Exception("Middle name contains numeric characters!");
			}

			$queryData->middlename = $middlename;
		}
		
		protected function parsePedigree(&$pedigree, &$queryData) {
			$queryData->pedigree = $pedigree;
		}
		
		protected function parseClassCode(&$classcode, &$queryData) {
			$classcode = preg_replace('/[^\d]*/', '', $classcode); // strip non-numeric chars
			if (empty($classcode))
				throw new Exception("Class code has no numeric characters");
			else if (strlen($classcode) != 5)
				throw new Exception("Class code must be exactly 5 digits long");
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
	
		protected function parseGrade(&$grade, $compgrade, $secondcompgrade, &$queryData) {
			if (empty($grade))
				throw new Exception("Grade is empty");
			$grade = preg_replace('/[^\d.]*/', '', $grade); // strip non-numeric and non-dot chars
			// what about inc/drp?
			$grade = floatval($grade);
			$queryData->grade = $grade;
		}
}

?>