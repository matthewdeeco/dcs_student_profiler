<?php
require_once 'parser.php';
require_once 'excel_reader.php';
require_once 'query_data.php';

class Excel_Parser extends Parser {
	private $query_data;
	
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
		$cols = $this->spreadsheet->colcount();
		$output = "<table class='databasetable'>";
		$this->load->model("Field_factory", "field_factory");
		// If 1st row is not a header, change to $i = 1
		$output .= "<tr>";
		for ($col = 1; $col <= $cols - 2; $col++) {
			$header = $this->spreadsheet->val(1, $col);
			$output .= "<th>$header</th>";
		}
		$output .= "<tr>";
		for ($row = 2; $row <= $rows; $row++) {
			$this->querydata = new Query_data;
			$output .= $this->parseRow($row, $cols);
			$this->querydata->execute();
		}
		$output .= "</table>";
		return $output;
	}
	
	private function parseRow($row, $cols) {
		$success = true; // no errors encountered
		$output = "<tr>";
		for ($col = 1; $col <= $cols - 2; $col++) { // last 3 columns (grades) are parsed at the same time
			$values = array($this->spreadsheet->val($row, $col));
			if ($col == $cols - 2) { // grades, include comp and secondcomp
				$values[] = $this->spreadsheet->val($row, $col+1);
				$values[] = $this->spreadsheet->val($row, $col+2);
			}
			try {
				$field = $this->field_factory->createFieldByNum($col, $values);
				$fieldname = strtolower(get_class($field));
				$field->parse();
				$value = $field->getValue();
				$output .= "<td class='databasecell'>$value</td>";
				$this->querydata->$fieldname = $value;
			} catch (Exception $e) {
				$this->querydata->doNotExecute();
				$message = $e->getMessage(); // store for tooltip message
				$output .= "<td title='$message'><div class='databasecell'><input type='text' class='edit_failure' value='$values[0]'></div></td>";
				$success = false;
			}
		}
		$this->querydata->batch = 2009;
		$this->querydata->termid = 201012;
		$this->querydata->termname = "1st Semester 2010-2011";
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
