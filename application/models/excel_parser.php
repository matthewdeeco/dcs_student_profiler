<?php
require_once 'parser.php';
require_once 'excel_reader.php';
require_once 'query_data.php';

class Excel_Parser extends Parser {
	private $query_data;
	private $parsers;
	private $spreadsheet, $rows, $cols;
	
	/** 
		$excelfile - the filename of the input excel file to be parsed
	*/
	public function initialize($excelfile) {
		$this->spreadsheet = new Spreadsheet_Excel_Reader($excelfile);
		$this->load->model('query_data', 'querydata');
		$this->rows = $this->spreadsheet->rowcount();
		$this->cols = $this->spreadsheet->colcount();
		
		$this->load->model("Field_factory", "field_factory");
		$this->parsers = array();
		for ($col = 1; $col <= $this->cols - 2; $col++) // last 3 columns (grades) are parsed at the same time
			$this->parsers[$col] = $this->field_factory->createFieldByNum($col);
	}
	
	/** Start parsing $this->spreadsheet. */
	public function parse() {
		$output = "<table class='databasetable'>";
		// If 1st row is not a header, change to $i = 1
		$output .= "<tr><th>row</th>";
		for ($col = 1; $col <= $this->cols - 2; $col++) {
			$header = $this->spreadsheet->val(1, $col);
			$output .= "<th>$header</th>";
		}
		$output .= "<tr>";
		for ($row = 2; $row <= $this->rows; $row++) {
			$this->querydata = new Query_data;
			$output .= $this->parseRow($row);
			$this->querydata->execute();
		}
		$output .= "</table>";
		return $output;
	}
	
	private function parseRow($row) {
		$success = true; // no errors encountered
		$output = "<tr><th>".$row."</th>";
		for ($col = 1; $col <= $this->cols - 2; $col++) { // last 3 columns (grades) are parsed at the same time
			$value = $this->spreadsheet->val($row, $col);
			try {
				$field = $this->parsers[$col];
				if ($col == $this->cols - 2) { // grades, include comp and secondcomp
					$compgrade = $this->spreadsheet->val($row, $col + 1);
					$secondcompgrade = $this->spreadsheet->val($row, $col + 2);
					$field->parse($value, $compgrade, $secondcompgrade);
				}
				else
					$field->parse($value);
				$field->insertToQueryData($this->querydata);
				$output .= "<td class='databasecell'>$value</td>";
			} catch (Exception $e) {
				$this->querydata->doNotExecute();
				$message = $e->getMessage(); // store for tooltip message
				$output .= "<td title='$message'><div class='databasecell upload_error'>$value</div></td>";
				$success = false;
			}
		}
		$output .= "</tr>";
		if ($success) {
			$this->successcount++;
			return ''; // don't print the row
		}
		else {
			$this->errorcount++;
			return $output; // add row for printing;
		}
	}
	
	/** Parse a row in the excel file.
		$row - the row number
	*/
	private function parseRowOld($row) {
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
	}
	
}
?>
