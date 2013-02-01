<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {
	var $tablenames;
	
	public function __construct() {
		parent::__construct();
		$this->initializeTableNames();
	}
	
	public function index() {
		$this->displayview('upload_file');
	}
	
	public function edit($tablename = null) {
		$db['default']['db_debug'] = FALSE;
		$data['tables'] = $this->getTablesForDisplay($tablename);
		$errormessage = $this->db->_error_message();
		if (!empty($errormessage))
			$data['errormessage'] = "Table ".$tablename." does not exist!";
		$this->displayview('edit_database', $data);
	}
	
	public function view($tablename = null) {
		$this->edit($tablename);
	}
	
	public function upload() {
		$grades_spreadsheet = $_FILES['gradessheet']['name'];
		$spreadsheet_type = $_FILES['gradessheet']['type'];
		$spreadsheet_size = $_FILES['gradessheet']['size'];

		// customize filename for ease of access?
		// check for filetypes that are allowed?
		$target = "./assets/uploads/".$grades_spreadsheet;

		$data = array('success' => false);
		if (move_uploaded_file($_FILES['gradessheet']['tmp_name'], $target)) {
			// maintain a table to store uploaded gradessheets?
			try {
				$data['excel_dump'] = $this->dumpExcelTable($target);
				$data['parse_output'] = $this->parse($target);
				$data['success'] = true;
			} catch (Exception $e) {
				$data['errormessage'] = "$grades_spreadsheet cannot be parsed.";
			}
		} else
			$data['errormessage'] = "$grades_spreadsheet could not be uploaded.";
		$this->displayview('upload_file_response', $data);
	}

	private function dumpExcelTable($file) {
		$reader_file = './application/models/excel_reader.php';
		require_once $reader_file;
		
		// dump the input excel file
		$printer = new Spreadsheet_Excel_Reader($file);
		error_reporting(0);
		$excel_dump = $printer->dump(true,true);
		error_reporting(E_ALL);
		return $excel_dump;
	}
	
	private function parse($file) {
		// start parsing
		$this->load->model('excel_parser', 'parser');
		$this->parser->initialize($file);
		return $this->parser->parse();
	}
	
	private function getTableRows($tablename) {
		$table = array();
		$table['table_name'] = $tablename;
		$result = $this->db->query("SELECT * FROM $tablename;");
		$rows = $result->result_array();
		$table['rows'] = $rows;
		return $table;
	}

	private function initializeTableNames() {
		$result = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public';");
		$this->tablenames['table_names'] = $result->result_array();
		foreach ($this->tablenames['table_names'] as &$tablename)
			$tablename = $tablename['table_name'];
	}
	
	private function getAllTables() {
		$tables = array();
		foreach ($this->tablenames['table_names'] as $tablename)
			$tables[] = $this->getTableRows($tablename);
		return $tables;
	}
	
	private function getTable($tablename) {
		$tables = array();
		$table = $this->getTableRows($tablename);
		$tables[] = $table;
		return $tables;
	}
	
	private function getTablesForDisplay($tablename = null) {
		if (is_null($tablename))
			$tables = $this->getAllTables();
		else
			$tables = $this->getTable($tablename);
		return $tables;
	}
	
	private function displayView($viewname, $data = null) {
		$this->load->view('include/header');
		$this->load->view('include/header-teamc', $this->tablenames);
		$this->load->view($viewname, $data); 
		$this->load->view('include/footer-teamc', $this->tablenames);
		$this->load->view('include/footer');
	}
}

/* Location: ./application/controllers/update_statistics.php */