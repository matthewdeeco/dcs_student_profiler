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
			
			$field = $this->spreadsheet->val($row, InputFields::AcadYear);
			$this->parseAcadYear($field, $queryData);
			
			$field = $this->spreadsheet->val($row, InputFields::Semester);
			$this->parseSemester($field, $queryData);
			
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
		
		private function parseAcadYear($field, &$queryData) {
			$field = preg_replace('/[^\d]*/', '', $field, 1); // skip to first numeric char
			$field = preg_replace('/[^\d\-]/', '', $field); // strip all other non-numeric and non-hyphen chars
			if (empty($field)) // nothing was left
				throw new Exception("Acad Year has no numeric characters");
			$field = explode("-", $field); // separate by hyphen (e.g. 2010-2011)
			$start = $field[0];
			if (count($field) == 1) // no end year was specified
				$queryData->acadyear = $start."-".($start + 1);
			else { // end year was specified
				$end = $field[1];
				if ($end - $start !== 1)
					throw new Exception("Start and end of Acad Year is not 1 year apart");
				$queryData->acadyear = $start."-".$end;
			}
		}
		
		private function parseSemester($field, &$queryData) {
			if (empty($field))
				throw new Exception("Semester cannot be blank");
			else if (($semester = Semester::getSemesterCode($field)) == Semester::Invalid)
				throw new Exception("Semester is invalid");
			else
				$queryData->semester = $semester;
		}
	
		private function parseStudentNo($field, &$queryData) {
			if (empty($field))
				throw new Exception("Student no cannot be blank");
			$field = preg_replace('/[^\d]/', '', $field); // strip non-numeric chars
			if (strlen($field) != 9)
				throw new Exception("Student no must be exactly 9 digits long");
			$queryData->studentno = $field;
		}

		private function parseLastName($field, &$queryData) {
			$queryData->lastname = $field;
		}
		
		private function parseFirstName($field, &$queryData) {
			$queryData->firstname = $field;
		}
		
		private function parseMiddleName($field, &$queryData) {
			$queryData->middlename = $field;
		}
		
		private function parsePedigree($field, &$queryData) {
			$queryData->pedigree = $field;
		}
		
		private function parseClassCode($field, &$queryData) {
			$field = preg_replace('/[^\d]/', '', $field); // strip non-numeric chars
			if (empty($field))
				throw new Exception("Class code has no numeric characters");
			else if (strlen($field) != 5)
				throw new Exception("Class code must be exactly 5 digits long");
			$queryData->classcode = $field;
		}
		
		private function parseClassName($field, &$queryData) {
			if (empty($field))
				throw new Exception("Class is empty");
			if (($lastspace = strrpos($field, " ")) === false)// no spaces
				throw new Exception("Course name and section cannot be distinguished");
			$coursename = substr($field, 0, $lastspace);
			$section = substr($field, $lastspace);
			// check if $coursename is in table?
			$queryData->coursename = $coursename;
			$queryData->section = $section;
		}
	
		private function parseGrade($grade, $compgrade, $secondcompgrade, &$queryData) {
			$queryData->grade = $grade;
		}

	}
?>
