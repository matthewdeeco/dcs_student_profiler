<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {
	var $tablenames;
	
	public function __construct() {
		parent::__construct();
		$this->initializeTableNames();
	}
	
	private function displayUploadFileView($data = null)  {
		if ($data == null)
			$data = array();
		$data['message'] = 'Select the xls file with grades to be uploaded!';
		$data['dest'] = site_url('update_statistics/upload');
		$this->displayview('upload_file', $data);
	}
	
	public function index() {
		$this->displayUploadFileView();
	}
	
	public function edit($tablename = null) {
		$db['default']['db_debug'] = FALSE;
		$data['tables'] = $this->getTablesForDisplay($tablename);
		$errormessage = $this->db->_error_message();
		if (!empty($errormessage))
			$data['errormessage'] = "Table ".$tablename." does not exist!";
		$this->displayview('edit_database', $data);
	}
	
	public function update() {
		echo '<script type="text/javascript"> alert("In the controller"); </script>';
		$tablename = $_POST['tablename'];
		$primarykeyname = $_POST['primarykeyname'];
		$primarykeyvalue = $_POST['primarykeyvalue'];
		$changedkeyname = $_POST['changedkeyname'];
		$changedkeyvalue = $_POST['changedkeyvalue'];
		$query = 'UPDATE '.$tablename.' SET '.$changedkeyname.'=\''.$changedkeyvalue.'\' WHERE '.$primarykeyname.'='.$primarykeyvalue.'';
		$this->db->query($query);
	}
	
	public function view($tablename = null) {
		$this->edit($tablename);
	}
	
	public function upload() {
		$data = array('success' => false);
		// maintain a table to store uploaded gradessheets?
		try {
			$file = $this->getUploadedFile();
			$data['excel_dump'] = $this->dumpExcelTable($file);
			$data['success'] = true;
			$this->parse($file, $data);
		} catch (Exception $e) {
			$data['errormessage'] = $e->getMessage();
		}
		$this->displayUploadFileView($data);
	}
	
	private function getUploadedFile() {
		$filename = $_FILES['upload_file']['name'];
		$filetype = $_FILES['upload_file']['type'];
		$filesize = $_FILES['upload_file']['size'];

		// customize filename for ease of access?
		// check for filetypes that are allowed?
		$target = "./assets/uploads/".$filename;
		if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $target)) {
			return $target;
		} else
			throw new Exception("Error: $filename could not be uploaded.");
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
	
	private function parse($file, &$data) {
		// start parsing
		$this->load->model('excel_parser', 'parser');
		$this->parser->initialize($file);
		$data['parse_output'] = $this->parser->parse();
		$success_rows = $this->parser->getSuccessCount();
		$error_rows = $this->parser->getErrorCount();
		$data['success_message'] = "<span class='success'>".$success_rows."</span> rows added, <span class='error'>".$error_rows."</span> errors encountered";
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