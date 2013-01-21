<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once 'parser.php';
require_once 'excel_reader.php';
require_once 'input_fields.php';
require_once 'semester.php';

class Excel_Parser extends Parser {

	/** 
		$excelfile - the filename of the input excel file to be parsed
	*/
	public function initialize($excelfile) {
		$this->spreadsheet = new Spreadsheet_Excel_Reader($excelfile);
		$this->load->model('query_data', 'querydata');
	}
	
	/** Start parsing $this->spreadsheet. */
	public function parse() {
		$rows = $this->spreadsheet->rowcount();
		
		echo "<table class='excel'>
		<thead><tr><th>Row</th>
		<th>Term</th>
		<th>Student #</th>
		<th>Name</th>
		<th>Class Code</th>
		<th>Course Name</th>
		<th>Section</th>
		<th>Grade</th>
		</tr></thead>";
		// If 1st row is not a header, change to $i = 1
		for ($i = 2; $i <= $rows; $i++) {
			echo "<tr><th>$i</th>";
			try { // if parsing the row failed, will immediately skip to catch
				$this->parseRow($i);
				$this->querydata->printInfo();
				// add $querydata to db
				$this->querydata->addToDatabase();
			} catch (Exception $e) {
				// print error message
				echo "<td colspan = 7 align=center><i>Error: ".$e->getMessage()."</i></td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		
		$result = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public';");
		$tables = $result->result_array();
		foreach ($tables as $table) {
			$tablename = $table['table_name'];
			echo "<b>$tablename</b><br>";
			echo "<table>";
			$result = $this->db->query("SELECT * FROM $tablename;");
			$rows = $result->result_array();
			if (!empty($rows)) {
				echo "<tr>";
				foreach ($rows[0] as $key => $value)
						echo "<th>$key</th>";
				echo "</tr>";
				foreach($rows as $row) {
					echo "<tr>";
					foreach ($row as $key => $value) {
						echo "<td>$value</td>";
					}
					echo "</tr>";
				}
			}
			echo "</table><br><br>";
		}
	}
	
	/** Parse a row in the excel file.
		$row - the row number
	*/
	private function parseRow($row) {
		// $querydata = new QueryData; // used to hold data for the query
		
		$acadyear = $this->spreadsheet->val($row, InputFields::AcadYear);
		$semester = $this->spreadsheet->val($row, InputFields::Semester);
		$this->parseTermName($acadyear, $semester);
		
		$studentno = $this->spreadsheet->val($row, InputFields::StudentNo);
		$this->parseStudentNo($studentno);
		
		$lastname = $this->spreadsheet->val($row, InputFields::LastName);
		$this->parseLastName($lastname);
		
		$firstname = $this->spreadsheet->val($row, InputFields::FirstName);
		$this->parseFirstName($firstname);
		
		$middlename = $this->spreadsheet->val($row, InputFields::MiddleName);
		$this->parseMiddleName($middlename);
		
		$pedigree = $this->spreadsheet->val($row, InputFields::Pedigree);
		$this->parsePedigree($pedigree);
		
		$classcode = $this->spreadsheet->val($row, InputFields::ClassCode);
		$this->parseClassCode($classcode);
		
		$classname = $this->spreadsheet->val($row, InputFields::ClassName);
		$this->parseClassName($classname);
		
		$grade = $this->spreadsheet->val($row, InputFields::Grade);
		$compgrade = $this->spreadsheet->val($row, InputFields::CompGrade);
		$secondcompgrade = $this->spreadsheet->val($row, InputFields::SecondCompGrade);
		$this->parseGrade($grade, $compgrade, $secondcompgrade);
		
		return $querydata;
	}
	
}
?>
