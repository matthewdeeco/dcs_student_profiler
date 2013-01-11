<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
		$this->load->view('update_statistics');
		$this->load->view('include/footer');
	}
	
	public function upload()
	{
		$grades_spreadsheet = $_FILES['gradessheet']['name'];
		$spreadsheet_type = $_FILES['gradessheet']['type'];
		$spreadsheet_size = $_FILES['gradessheet']['size'];

		/*customize filename for ease of access?*/

		echo "$spreadsheet_type<br>";

		/*check for filetypes that are allowed*/

		$target = "./assets/gradesfiles/".$grades_spreadsheet;

		if (move_uploaded_file($_FILES['gradessheet']['tmp_name'], $target)) {
				  
			/*maintain a table to store uploaded gradessheets?*/
			
			echo "File successfully uploaded.\nParsing about to begin.<br><br>";
			
			require_once 'parse_response.php';
			$parser = new ParseResponse($target);
		}
		else{
			$upload_error = "file upload";
			echo "There seems to be a problem with uploading the file.";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/parse_response.php */


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--CSS for the Index Page. Use this for the layout of the other pages.-->
<link href="assets/CSS3 Menu_files/css3menu1/style.css" rel="stylesheet" type="text/css" />
<link href="assets/css/index.css" rel="stylesheet" type="text/css" />
<title>UPD DCS Student Profiling System</title>
</head>

<body>

<div id = "container" style="width:1150px">
     <?php
		require_once 'excel_parser.php';

		class ParseResponse {
			public function ParseResponse($file) {
				echo "File successfully uploaded.\nParsing about to begin.<br><br>";
	
				// dump the input excel file
				$this->printer = new Spreadsheet_Excel_Reader($file);
				echo $this->printer->dump(true,true);
				echo "<br>";
				// start parsing
				$this->parser = new ExcelParser($file);
				$this->parser->parse();
			}
		}
	?>

</div> <!--container-->

</body>

</html>
