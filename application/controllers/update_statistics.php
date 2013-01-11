<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_Statistics extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
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
		}
		else{
			$upload_error = "file upload";
			echo "There seems to be a problem with uploading the file.";
		}
	}

	public function parse($file) {
		echo "Parsing $file about to begin.<br><br>";
		$reader_file = './application/models/excel_reader.php';
		require_once $reader_file;
		// dump the input excel file
		$printer = new Spreadsheet_Excel_Reader($file);
		echo $printer->dump(true,true);
		echo "<br>";
		// start parsing
		$this->load->model('excel_parser', 'parser');
		$this->parser->initialize($file);
		//$this->parser = new ExcelParser($file);
		$this->parser->parse();
	}
}

/* Location: ./application/controllers/update_statistics.php */