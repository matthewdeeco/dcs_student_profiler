<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {
	var $tablenames;
	
	public function __construct() {
		parent::__construct();
		$this->initializeTableNames();
	}
	
	public function index() {
		$this->load->view('include/header');
		$this->load->view('include/header-teamc', $this->tablenames);
		$this->displayUploadFileView(); 
		$this->load->view('include/footer-teamc', $this->tablenames);
		$this->load->view('include/footer');
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
		$tablename = $_POST['tablename'];
		$primarykeyname = $_POST['primarykeyname'];
		$primarykeyvalue = $_POST['primarykeyvalue'];
		$changedkeyname = $_POST['changedkeyname'];
		$changedkeyvalue = $_POST['changedkeyvalue'];
		
		/*$query = "UPDATE $tablename SET $changedkeyname='$changedkeyvalue' WHERE $primarykeyname='$primarykeyvalue'";
		$this->db->query($query);
		$error = $this->db->_error_message();*/
		
		if (empty($error))
			echo "$changedkeyvalue";
		else
			echo "false";
		try {
			$this->load->model('edit_database_model', 'editor');
			$this->editor->validateRowUpdate($tablename, $primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue);	
			echo "true";
		}catch (Exception $e) {
			$error_msg = $e->getMessage();
			echo "error_msg";
		}
		
	}
	
	public function delete() {
		$tablename = $_POST['tablename'];
		$primarykeyname = $_POST['primarykeyname'];
		$primarykeyvalue = $_POST['primarykeyvalue'];
		
		$query = "DELETE * FROM $tablename WHERE $primarykeyname='$primarykeyvalue'";
		$this->db->query($query);
		$error = $this->db->_error_message();
		
		if (empty($error))
			echo "true";
		else
			echo "false";
	}	
	
	public function view($tablename = null) {
		$this->edit($tablename);
	}
	
	public function upload() {
		$this->displayUploadFileView();
	}
	
	// Called when an excel file is uploaded
	public function performUpload() {
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
	
	public function backup() {
		$pg_dump_location = $this->input->cookie('pg_dump_location', TRUE);
		if (!empty($pg_dump_location))
			$this->performBackup($pg_dump_location);
		else if (substr(php_uname(), 0, 7) == "Windows")
			$this->displayview('backup_view');
		else
			$this->performBackup('/usr/bin/pg_dump');
	}
	
	public function performBackup($pg_dump_location = null) {
		if (is_null($pg_dump_location)) {
			$pg_dump_location = $_POST['pg_dump_location'];
			if (!preg_match("/pg_dump.exe$/", $pg_dump_location))
				$pg_dump_location .= "/pg_dump.exe";
		}
		$backup_dir = $this->getAbsoluteBasePath().'dumps/';
		if (!file_exists($backup_dir))
			mkdir($backup_dir, 0755);
		$backup_name = $backup_dir.$this->db->database.date("m-d-Y").".sql";
		$cmd = escapeshellarg($pg_dump_location)." -U postgres ".$this->db->database." > $backup_name 2>&1";
		exec($cmd, $output, $status);
		if ($status == 0) { // save cookie
			$cookie = array('name'=>'pg_dump_location', 'value'=>$pg_dump_location, 'expire'=>'5000000');
			$this->input->set_cookie($cookie);
		}
		$data['backup_location'] = $backup_name;
		$data['output'] = $output;
		$data['status'] = $status;
		$this->displayView('backup_response', $data);
	}
	
	public function sql() {
		$data['message'] = 'Select the sql file to run';
		$data['dest'] = site_url('update_statistics/performSqlQuery');
		$this->displayview('upload_file', $data);
	}
	
	public function performSqlQuery() {
		$sql_file = $this->getUploadedFile();
		$sql_text = $this->load->file($sql_file, true);
		$this->db->query($sql_text);
		$data['success'] = true;
		$this->displayView('sql_response', $data);
	}
	
	public function restore() {
		$data['message'] = 'Select the database backup to restore';
		$data['dest'] = site_url('update_statistics/performRestore');
		$this->displayview('upload_file', $data);
	}
	
	public function performRestore() {
		$backup_filename = $this->getAbsoluteBasePath().$this->getUploadedFile();
		if (substr(php_uname(), 0, 7) == "Windows"){
			// append path to our files
			$abs_basepath = $this->getAbsoluteBasePath();
			$psql_location = $abs_basepath."assets/postgres/psql.exe";
		} 
		else { 
			$psql_location = "/usr/bin/psql";
		}
		$cmd = $psql_location." -U postgres ".$this->db->database." < $backup_filename 2>&1";
		exec($cmd, $output, $status);
		$data['output'] = $output;
		$data['status'] = $status;
		$this->displayView('restore_response', $data);
	}
	
	private function getUploadsFolder() {
		$upload_dir = "./assets/uploads";
		if (!file_exists($upload_dir))
			mkdir($upload_dir, 0755);
		return $upload_dir;
	}
	
	private function getAbsoluteBasePath() {
		return $_SERVER['DOCUMENT_ROOT'].'/'.explode('/', base_url(), 4)[3];
	}
	
	private function saveAsDialog($saveas_filename) {
		header ("Content-Type: application/download");
		header ("Content-Disposition: attachment; filename=$saveas_filename");
		header ("Content-Length: " . filesize("$saveas_filename"));
		$fp = fopen("$saveas_filename", "r");
		fpassthru($fp);
	}
	
	private function displayUploadFileView($data = null)  {
		$data['message'] = 'Select the xls file with grades to be uploaded';
		$data['dest'] = site_url('update_statistics/performUpload');
		$this->displayview('upload_file', $data);
	}
	
	private function getUploadedFile() {
		$filename = $_FILES['upload_file']['name'];
		$filetype = $_FILES['upload_file']['type'];
		$filesize = $_FILES['upload_file']['size'];

		// customize filename for ease of access?
		// check for filetypes that are allowed?
		$target = $this->getUploadsFolder().'/'.$filename;
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
		//$excel_dump = @$printer->dump(true,true);
		//return $excel_dump;
	}
	
	private function parse($file, &$data) {
		// start parsing
		$this->load->model('excel_parser', 'parser');
		$this->parser->initialize($file);
		$data['parse_output'] = $this->parser->parse();
		$success_rows = $this->parser->getSuccessCount();
		$error_rows = $this->parser->getErrorCount();
		$data['success_rows'] = $success_rows;
		$data['error_rows'] = $error_rows;
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
		$this->load->view($viewname, $data);
	}
}

/* Location: ./application/controllers/update_statistics.php */
