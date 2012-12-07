<?php

	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel_reader.php';
	require_once 'input_fields.php';
	require_once 'semester.php';
	require_once 'query_data.php';
	
	class ExcelParser {
	
		function ExcelParser($excelFile) {
			$this->spreadsheet = new Spreadsheet_Excel_Reader($excelFile);
		}
		
		function parse() {
			$rows = $this->spreadsheet->rowcount();
			
			// If 1st row is not a header, change to $i = 1
			for ($i = 2; $i <= $rows; $i++) {
				try {
					$queryData = $this->parseRow($i);
					$queryData->printInfo();
					// add $queryData to db
				} catch (Exception $e) {
					echo "Row $i has an error: ".$e->getMessage()."<br><br>";
				}
			}
		}
			
		private function parseRow($row) {
			$queryData = new QueryData;
			
			$acadyear = $this->spreadsheet->val($row, InputFields::AcadYear);
			$semester = $this->spreadsheet->val($row, InputFields::Semester);
			$this->parseTermName($acadyear, $semester, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::StudentNo);
			$this->parseStudentNo($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::LastName);
			$this->parseLastName($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::FirstName);
			$this->parseFirstName($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::MiddleName);
			$this->parseMiddleName($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::Pedigree);
			$this->parsePedigree($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::ClassCode);
			$this->parseClassCode($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::ClassName);
			$this->parseClassName($field, $queryData);
			
			$grade = $this->spreadsheet->val($row, InputFields::Grade);
			$compgrade = $this->spreadsheet->val($row, InputFields::CompGrade);
			$secondcompgrade = $this->spreadsheet->val($row, InputFields::SecondCompGrade);
			$this->parseGrade($grade, $compgrade, $secondcompgrade, $queryData);
			
			return $queryData;
		}
		
		private function parseTermName($acadyear, $semester, &$queryData) {
			$this->parseAcadYear($acadyear, $queryData);
			$this->parseSemester($semester, $queryData);
			$queryData->termname = Semester::toString($semester)." ".$acadyear;
		}
		
		private function parseAcadYear(&$acadyear, &$queryData) {
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
		
		private function parseSemester(&$semester, &$queryData) {
			if (empty($semester))
				throw new Exception("Semester cannot be blank");
			else if (($semester = Semester::getSemesterCode($semester)) == Semester::Invalid)
				throw new Exception("Semester is invalid");
			else
				$queryData->semester = $semester;
		}
	
		private function parseStudentNo(&$studentno, &$queryData) {
			if (empty($studentno))
				throw new Exception("Student no cannot be blank");
			$studentno = preg_replace('/[^\d]*/', '', $studentno); // strip non-numeric chars
			if (strlen($studentno) != 9)
				throw new Exception("Student no must be exactly 9 digits long");
			$queryData->studentno = $studentno;
		}

		private function parseLastName(&$lastname, &$queryData) {
			$queryData->lastname = $lastname;
		}
		
		private function parseFirstName(&$firstname, &$queryData) {
			$queryData->firstname = $firstname;
		}
		
		private function parseMiddleName(&$middlename, &$queryData) {
			$queryData->middlename = $middlename;
		}
		
		private function parsePedigree(&$pedigree, &$queryData) {
			$queryData->pedigree = $pedigree;
		}
		
		private function parseClassCode(&$classcode, &$queryData) {
			$classcode = preg_replace('/[^\d]*/', '', $classcode); // strip non-numeric chars
			if (empty($classcode))
				throw new Exception("Class code has no numeric characters");
			else if (strlen($classcode) != 5)
				throw new Exception("Class code must be exactly 5 digits long");
			$queryData->classcode = $classcode;
		}
		
		private function parseClassName($classname, &$queryData) {
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
	
		private function parseGrade(&$grade, $compgrade, $secondcompgrade, &$queryData) {
			$queryData->grade = $grade;
		}

	}
?>
