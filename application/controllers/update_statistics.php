<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {

	public function index() {
		$this->load->view('include/header');
		$this->load->view('update_statistics_view');
		$this->load->view('include/footer');
	}
	
	public function upload() {
		$grades_spreadsheet = $_FILES['gradessheet']['name'];
		$spreadsheet_type = $_FILES['gradessheet']['type'];
		$spreadsheet_size = $_FILES['gradessheet']['size'];

		/*customize filename for ease of access?*/

		echo "$spreadsheet_type<br>";

		/*check for filetypes that are allowed*/

		$target = "./assets/gradesfiles/".$grades_spreadsheet;

		if (move_uploaded_file($_FILES['gradessheet']['tmp_name'], $target)) {
				  
			/*maintain a table to store uploaded gradessheets?*/
			
			echo "File successfully uploaded.<br>";
			
			$this->parse($target);
			$this->view();
		}
		else{
			$upload_error = "file upload";
			echo "There seems to be a problem with uploading the file.";
		}
	}

	public function parse($file) {
		echo "Parsing about to begin.<br><br>";
		$reader_file = './application/models/excel_reader.php';
		require_once $reader_file;
		
		// dump the input excel file
		$printer = new Spreadsheet_Excel_Reader($file);
		error_reporting(0);
		echo $printer->dump(true,true);
		error_reporting(E_ALL);
		echo "<br>";
		
		// start parsing
		$this->load->model('excel_parser', 'parser');
		$this->parser->initialize($file);
		//$this->parser = new ExcelParser($file);
		$this->parser->parse();
	}

	public function view() {
		$result = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public';");
		$tables = $result->result_array();
		foreach ($tables as &$table) {
			$tablename = $table['table_name'];
			$result = $this->db->query("SELECT * FROM $tablename;");
			$rows = $result->result_array();
			$table['rows'] = $rows;
		}
		$data['tables'] = $tables;
		$this->load->view('include/header');
		$this->load->view('edit_database', $data);
		$this->load->view('include/footer');
	}
	
	public function edit() {
		$this->view();
	}
	
}

/* Location: ./application/controllers/update_statistics.php */