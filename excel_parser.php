<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once 'parser.php';
require_once 'excel_reader.php';
require_once 'input_fields.php';
require_once 'semester.php';
require_once 'query_data.php';

class ExcelParser extends Parser {

	/** 
		$excelfile - the filename of the input excel file to be parsed
	*/
	public function ExcelParser($excelfile) {
		$this->spreadsheet = new Spreadsheet_Excel_Reader($excelfile);
	}
	
	/** Start parsing $this->spreadsheet. */
	public function parse() {
		$rows = $this->spreadsheet->rowcount();
		
		echo "<table class='excel'>
		<thead><tr><th>Row</th>
		<th>Term</th>
		<th>Student #</th>
		<th>Name</th>
		<th>Course Name</th>
		<th>Section</th>
		<th>Class Code</th>
		<th>Grade</th>
		</tr></thead>";
		// If 1st row is not a header, change to $i = 1
		for ($i = 2; $i <= $rows; $i++) {
			echo "<tr><th>$i</th>";
			try { // if parsing the row failed, will immediately skip to catch
				$querydata = $this->parseRow($i);
				$querydata->printInfo();
				// add $querydata to db
			} catch (Exception $e) {
				// print error message
				echo "<td colspan = 7 align=center><i>Error: ".$e->getMessage()."</i></td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	
	/** Parse a row in the excel file.
		$row - the row number
	*/
	private function parseRow($row) {
		$querydata = new QueryData; // used to hold data for the query
		
		$acadyear = $this->spreadsheet->val($row, InputFields::AcadYear);
		$semester = $this->spreadsheet->val($row, InputFields::Semester);
		$this->parseTermName($acadyear, $semester, $querydata);
		
		$studentno = $this->spreadsheet->val($row, InputFields::StudentNo);
		$this->parseStudentNo($studentno, $querydata);
		
		$lastname = $this->spreadsheet->val($row, InputFields::LastName);
		$this->parseLastName($lastname, $querydata);
		
		$firstname = $this->spreadsheet->val($row, InputFields::FirstName);
		$this->parseFirstName($firstname, $querydata);
		
		$middlename = $this->spreadsheet->val($row, InputFields::MiddleName);
		$this->parseMiddleName($middlename, $querydata);
		
		$pedigree = $this->spreadsheet->val($row, InputFields::Pedigree);
		$this->parsePedigree($pedigree, $querydata);
		
		$classcode = $this->spreadsheet->val($row, InputFields::ClassCode);
		$this->parseClassCode($classcode, $querydata);
		
		$classname = $this->spreadsheet->val($row, InputFields::ClassName);
		$this->parseClassName($classname, $querydata);
		
		$grade = $this->spreadsheet->val($row, InputFields::Grade);
		$compgrade = $this->spreadsheet->val($row, InputFields::CompGrade);
		$secondcompgrade = $this->spreadsheet->val($row, InputFields::SecondCompGrade);
		$this->parseGrade($grade, $compgrade, $secondcompgrade, $querydata);
		
		return $querydata;
	}
	
}
?>
