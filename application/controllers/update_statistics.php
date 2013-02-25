<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {
	private $headers_included = false;
	
	public function __construct() {
		parent::__construct();
		
		//load model
		$this->load->model('student_model', 'student_model', true);
	}
	
	public function index() {
		$this->headers_included = true;
		$this->load->view('include/header');
		$this->load->view('include/header-teamc');
		$this->displayUploadFileView();
		$this->load->view('include/footer-teamc');
		$this->load->view('include/footer');
	}
	
	/*-----------------------------------------------------start edit functions-----------------------------------------------------*/
	
	public function edit_students(){
		$db['default']['db_debug'] = FALSE;
		$data['students'] = $this->student_model->getStudents();
		$this->displayView('edit_students', $data);
	}
	
	public function viewGrades($personid = null) {
		$db['default']['db_debug'] = FALSE;
		$this->load->model('grades_model', 'grades_model', true);
		
		$data['student_info'] = $this->grades_model->getStudentInfo($personid);
		$data['term_grades'] = $this->grades_model->getGrades($personid);
		$this->displayView('view_grades', $data);
	}
	
	public function updateGrade() {
		/*$studentclassid = $_POST['studentclassid'];
		$grade = $_POST['grade'];*/
		
		try {
			/*$this->load->model('Field_factory', 'field_factory');
			$field = $this->field_factory->createFieldByName('Grade');
			$field->parse($grade); //will throw an exception if grade format is wrong
			
			$this->load->model('grades_model', 'grades_model', true);
			$this->grades_model->changeGrade($grade, $studentclassid);
			echo "true";*/
			echo "hello";
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
			
			$this->student_model->changeStudentInfo($changedfield_name, $changedfield_value, $personid);
			echo "true";
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}//end update student info
	
	/*-----------------------------------------------------end edit functions-----------------------------------------------------*/
	
	/*-----------------------------------------------------start upload functions-----------------------------------------------------*/
	
	public function upload() {
		$this->displayUploadFileView();
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
	
	/*-----------------------------------------------------end upload functions-----------------------------------------------------*/
	
	/*-----------------------------------------------------start reset functions-----------------------------------------------------*/
	
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
	
	/*-----------------------------------------------------end reset functions-----------------------------------------------------*/
	
	/*-----------------------------------------------------start backup functions-----------------------------------------------------*/
	
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
	
	/*-----------------------------------------------------end backup functions-----------------------------------------------------*/
	
	/*-----------------------------------------------------start restore functions-----------------------------------------------------*/
	
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
	
	/*-----------------------------------------------------end restore functions-----------------------------------------------------*/
	
	/*-----------------------------------------------------start sql functions-----------------------------------------------------*/
	
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
	
	/*-----------------------------------------------------end sql functions-----------------------------------------------------*/
	
	private function saveAsDialog($saveas_filename) {
		header ("Content-Type: application/download");
		header ("Content-Disposition: attachment; filename=$saveas_filename");
		header ("Content-Length: " . filesize("$saveas_filename"));
		$fp = fopen("$saveas_filename", "r");
		fpassthru($fp);
	}
	
	private function getResetSql(){
		$filename = "Create Tables.sql";
		$upload_dir = "./db files";
		if (!file_exists($upload_dir))
			mkdir($upload_dir, 0755);

		$target = $upload_dir.'/'.$filename;
		return $target;
	}
	
	/*-----------------------------------------------------start display functions-----------------------------------------------------*/
	
	private function displayViewWithHeaders($viewname, $data = null) {
		$this->headers_included = true;
		$this->load->view('include/header');
		$this->load->view('include/header-teamc');
		$this->load->view($viewname, $data);
		$this->load->view('include/footer-teamc');
		$this->load->view('include/footer');
	}
	
	private function displayView($viewname, $data = null) {
		// if ($this->headers_included)
			$this->load->view($viewname, $data);
		// else
			// $this->displayViewWithHeaders($viewname, $data);
	}
	
	/*-----------------------------------------------------end display functions-------------------------------------------------*/
}

/* Location: ./application/controllers/update_statistics.php */
