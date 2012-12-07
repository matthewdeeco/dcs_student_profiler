<?php

	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'parser.php';
	require_once 'excel_reader.php';
	require_once 'input_fields.php';
	require_once 'semester.php';
	require_once 'query_data.php';
	
	class ExcelParser extends Parser {
	
		public function ExcelParser($excelFile) {
			$this->spreadsheet = new Spreadsheet_Excel_Reader($excelFile);
		}
		
		public function parse() {
			$this->spreadsheet->dump(true, true);
			
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
			
		protected function parseRow($row) {
			$queryData = new QueryData;
			
			$acadyear = $this->spreadsheet->val($row, InputFields::AcadYear);
			$semester = $this->spreadsheet->val($row, InputFields::Semester);
			$this->parseTermName($acadyear, $semester, $queryData);
			
			$studentno = $this->spreadsheet->val($row, InputFields::StudentNo);
			$this->parseStudentNo($studentno, $queryData);
			
			$lastname = $this->spreadsheet->val($row, InputFields::LastName);
			$this->parseLastName($lastname, $queryData);
			
			$firstname = $this->spreadsheet->val($row, InputFields::FirstName);
			$this->parseFirstName($firstname, $queryData);
			
			$middlename = $this->spreadsheet->val($row, InputFields::MiddleName);
			$this->parseMiddleName($middlename, $queryData);
			
			$pedigree = $this->spreadsheet->val($row, InputFields::Pedigree);
			$this->parsePedigree($pedigree, $queryData);
			
			$classcode = $this->spreadsheet->val($row, InputFields::ClassCode);
			$this->parseClassCode($classcode, $queryData);
			
			$classname = $this->spreadsheet->val($row, InputFields::ClassName);
			$this->parseClassName($classname, $queryData);
			
			$grade = $this->spreadsheet->val($row, InputFields::Grade);
			$compgrade = $this->spreadsheet->val($row, InputFields::CompGrade);
			$secondcompgrade = $this->spreadsheet->val($row, InputFields::SecondCompGrade);
			$this->parseGrade($grade, $compgrade, $secondcompgrade, $queryData);
			
			return $queryData;
		}
		
	}
?>
