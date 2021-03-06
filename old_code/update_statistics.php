<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {
	private $tablenames;
	private $headers_included = false;
	
	public function __construct() {
		parent::__construct();
		$this->initializeTableNames();
	}
	
	public function index() {
		$this->headers_included = true;
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
		$this->displayView('edit_database', $data);
	}
	
	public function viewGrades($personid = null) {
		$db['default']['db_debug'] = FALSE;
		$data['tables'] = $this->getTable('studentgrades', $personid);

		$this->displayViewWithHeaders('edit_database', $data);	
	}
	
	public function update() {
		$tablename = $_POST['tablename'];
		$primarykeyname = $_POST['primarykeyname'];
		$primarykeyvalue = $_POST['primarykeyvalue'];
		$changedkeyname = $_POST['changedkeyname'];
		$changedkeyvalue = $_POST['changedkeyvalue'];
		try {
			$this->load->model('Field_factory', 'field_factory');
			$field = $this->field_factory->createFieldByName($changedkeyname);
			$field->parse($changedkeyvalue);
			
			$this->load->model('edit_database_model', 'editor', true);
			$this->editor->UpdateRow($primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue);
			
			//commented out to test the new code
			//$query = "UPDATE $tablename SET $changedkeyname='$changedkeyvalue' WHERE $primarykeyname='$primarykeyvalue'";
			//$this->db->query($query);
			
			
			// $this->editor->validateRowUpdate($tablename, $primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue);
			echo "true";
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function updateGrade() {
		$studentclassid = $_POST['studentclassid'];
		$grade = $_POST['grade'];
		
		try {
			$this->load->model('Field_factory', 'field_factory');
			$field = $this->field_factory->createFieldByName('Grade');
			$field->parse($grade); //will throw an exception if grade format is wrong
			
			$this->load->model('edit_database_model', 'editor', true);
			$this->editor->changeGrade($grade, $studentclassid);
			echo "true";
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}//end update grade
	
	public function updateStudentInfo(){
		$changedfield_name = $_POST['changedfield_name'];
		$changedfield_value = $_POST['changedfield_value'];
		$personid = $_POST['personid'];
		
		try {
			$this->load->model('Field_factory', 'field_factory');
			$field = $this->field_factory->createFieldByName($changedfield_name);
			$field->parse($changedfield_value); //will throw an exception if grade format is wrong
			
			$this->load->model('edit_database_model', 'editor', true);
			$this->editor->changeStudentInfo($changedfield_name, $changedfield_value, $personid);
			echo "true";
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}//end update student info
	
	public function view($tablename = null) {
		$this->edit($tablename);
	}
	
	public function upload() {
		$this->displayUploadFileView();
	}
	
	// Called when an excel file is uploaded
	public function performUpload() {
		$data = array('success' => false);
		if(!isset($_POST['reset']))
			$data['reset'] = "No";
		else
			$data['reset'] = $_POST['reset'];
			
		if($data['reset'] == "Yes"){
			$reset_success = $this->resetDatabase();
			if($reset_success)
				$data['reset_success'] = true;
		}
		else
			$data['reset_success'] = false;
		
		// maintain a table to store uploaded gradessheets?
		try {
			$file = $this->getUploadedFile();
			$data['excel_dump'] = $this->dumpExcelTable($file);
			$data['success'] = true;
			$this->parse($file, $data);
		} catch (Exception $e) {
			$data['errormessage'] = $e->getMessage();
		}
		$this->displayViewWithHeaders('upload_response', $data);
	}
	
	public function resetDatabase(){
		return $this->performResetDatabase();
	}
	
	public function performResetDatabase(){
		$reset_sql = $this->getResetSql();
		if(file_exists($reset_sql)){
			$query = $this->load->file($reset_sql, true);
			$this->db->query($query);
			$response = true;
			return $response;
		}
		else
			throw new Exception("Base file not found. Database cannot be reset.");
	}
	
	public function backup() {
		$cookie = $this->input->cookie('pg_bin_dir', TRUE);
		if (isset($_POST['pg_bin_dir'])) {
			$pg_bin_dir = $_POST['pg_bin_dir'];
			if (!preg_match("/bin$/", $pg_bin_dir))
				$pg_bin_dir .= "/bin";
			$this->performBackup($pg_bin_dir);
		}
		else if (!empty($cookie))
			$this->performBackup($cookie);
		else if (substr(php_uname(), 0, 7) == "Windows") {
			$data['dest'] = 'update_statistics/backup';
			$this->displayView('postgres_bin', $data);
		}
		else
			$this->performBackup('/usr/bin');
	}
	
	private function performBackup($pg_bin_dir) {
		$pg_dump = $pg_bin_dir."/pg_dump";
		if (substr(php_uname(), 0, 7) == "Windows")
			$pg_dump .= ".exe";
		$backup_dir = $this->getAbsoluteBasePath().'dumps/';
		if (!file_exists($backup_dir))
			mkdir($backup_dir, 0755);
		$backup_name = $backup_dir.$this->db->database.date("m-d-Y_h-i-s").".sql";
		$cmd = escapeshellarg($pg_dump)." -U postgres ".$this->db->database." > $backup_name 2>&1";
		exec($cmd, $output, $status);
		$success = ($status == 0);
		if ($success) { // save cookie
			$cookie = array('name'=>'pg_bin_dir', 'value'=>$pg_bin_dir, 'expire'=>'1000000');
			$this->input->set_cookie($cookie);
		}
		$data['backup_location'] = $backup_name;
		$data['output'] = $output;
		$data['success'] = $success;
		$this->displayView('backup_response', $data);
	}
	
	public function restore() {
		$data['message'] = 'Select the database backup to restore';
		$data['dest'] = site_url('update_statistics/performRestore');
		$this->displayView('upload_file', $data);
	}
	
	private function getPostgresBinLocation() {
		$cookie = $this->input->cookie('pg_bin_dir', TRUE);
		if (isset($_POST['pg_bin_dir'])) {
			$pg_bin_dir = $_POST['pg_bin_dir'];
			if (!preg_match("/bin$/", $pg_bin_dir))
				$pg_bin_dir .= "/bin";
		}
		else if (!empty($cookie))
			$pg_bin_dir = $cookie;
		else if (substr(php_uname(), 0, 7) == "Windows") {
			$data['dest'] = 'update_statistics/restore';
			$this->displayView('postgres_bin', $data);
		}
		else
			$pg_bin_dir = '/usr/bin';
		return $pg_bin_dir;
	}
	
	public function performRestore() {
		$pg_bin_dir = $this->getPostgresBinLocation();
		$backup_filename = $this->getAbsoluteBasePath().$this->getUploadedFile();
		if (substr(php_uname(), 0, 7) == "Windows"){
			$abs_basepath = $this->getAbsoluteBasePath();
			$psql_location = $pg_bin_dir."/psql.exe";
		} 
		else { 
			$psql_location = $pg_bin_dir."/psql";
		}
		$cmd = escapeshellarg($psql_location)." -U postgres ".$this->db->database." < $backup_filename 2>&1";
		exec($cmd, $output, $status);
		$data['output'] = $output;
		$data['status'] = $status;
		$this->displayViewWithHeaders('restore_response', $data);
	}
	
	public function sql() {
		$data['message'] = 'Select the sql file to run';
		$data['dest'] = site_url('update_statistics/performSqlQuery');
		$this->displayView('upload_file', $data);
	}
	
	public function performSqlQuery() {
		$sql_file = $this->getUploadedFile();
		$sql_text = $this->load->file($sql_file, true);
		$this->db->query($sql_text);
		$data['success'] = true;
		$this->displayViewWithHeaders('sql_response', $data);
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
		$this->load->view('upload_file', $data);
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

	private function getResetSql(){
		$filename = "Create Tables.sql";
		$upload_dir = "./db files";
		if (!file_exists($upload_dir))
			mkdir($upload_dir, 0755);

		$target = $upload_dir.'/'.$filename;
		return $target;
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
		$this->load->model('excel_parser', 'parser');
		$this->parser->initialize($file);
		$data['parse_output'] = $this->parser->parse();
		$reset_success = $data['reset_success'];
		$success_rows = $this->parser->getSuccessCount();
		$error_rows = $this->parser->getErrorCount();
		$data['success_rows'] = $success_rows;
		$data['error_rows'] = $error_rows;
	}
	
	private function getTableRows($tablename, $personid = null) {
		$table = array();
		$table['table_name'] = $tablename;
		//$result = $this->db->query("SELECT * FROM $tablename;");
		if($tablename == 'students')
			$result = $this->db->query("SELECT personid, studentno, lastname, firstname, middlename, pedigree FROM students natural join persons;");
		else if($tablename == 'studentgrades'){
			$name = $this->db->query("SELECT persons.personid as personid, studentno as studentno, lastname as lastname, firstname as firstname, middlename as middlename, pedigree as pedigree FROM persons JOIN students ON students.personid = persons.personid WHERE persons.personid = '$personid'");
			$name_arr = $name->result_array();
			$tablename = $name_arr[0]['lastname'] . ", " . $name_arr[0]['firstname'] . " " . $name_arr[0]['middlename'] . " " . $name_arr[0]['pedigree'] . "<br>" . $name_arr[0]['studentno'] . "<br>";
			
			$result = $this->db->query("SELECT persons.personid, coursename, section, gradename FROM students JOIN persons ON students.personid = persons.personid JOIN studentterms ON students.studentid = studentterms.studentid JOIN studentclasses ON studentterms.studenttermid = studentclasses.studenttermid JOIN classes ON studentclasses.classid = classes.classid JOIN courses ON classes.courseid = courses.courseid JOIN grades ON studentclasses.gradeid = grades.gradeid WHERE persons.personid = '$personid'");
		}
		
		$rows = $result->result_array();
		$table['rows'] = $rows;
		$table['table_name'] = $tablename;
		return $table;
	}

	private function initializeTableNames() {
		//$result = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public';");
		//$result = $this->db->query("SELECT * FROM students;");
		$result = array('students');
		//$this->tablenames['table_names'] = $result->result_array();
		$this->tablenames['table_names'] = $result;
		//foreach ($this->tablenames['table_names'] as &$tablename)
			//$tablename = $tablename['table_name'];
			
	}
	
	private function getAllTables() {
		$tables = array();
		foreach ($this->tablenames['table_names'] as $tablename)
			$tables[] = $this->getTableRows($tablename);
		return $tables;
	}
	
	private function getTable($tablename, $personid = null) {
		$tables = array();
		$table = $this->getTableRows($tablename, $personid);
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
	
	private function displayViewWithHeaders($viewname, $data = null) {
		$this->headers_included = true;
		$this->load->view('include/header');
		$this->load->view('include/header-teamc', $this->tablenames);
		$this->load->view($viewname, $data);
		$this->load->view('include/footer-teamc', $this->tablenames);
		$this->load->view('include/footer');
	}
	
	private function displayView($viewname, $data = null) {
		// if ($this->headers_included)
			$this->load->view($viewname, $data);
		// else
			// $this->displayViewWithHeaders($viewname, $data);
	}
}

/* Location: ./application/controllers/update_statistics.php */
