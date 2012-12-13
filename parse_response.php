<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--CSS for the Index Page. Use this for the layout of the other pages.-->
<link href="assets/CSS3 Menu_files/css3menu1/style.css" rel="stylesheet" type="text/css" />
<link href="assets/css/index.css" rel="stylesheet" type="text/css" />
<title>UPD DCS Student Profiling System</title>
</head>

<!--This is where the banner is placed.-->
<div id = "header">
  <div id = "banner">	  
   	  <img src="assets/img/logo.png"/>
 	</div> 
</div>
<body>
<div id = "menu">
<!--This is the Menu Bar. If you want to add more options, use this format:
 	<li><a href="page.php" style="height:14px;line-height:14px;">Option</a></li>-->
<ul id="css3menu1" class="topmenu">
    <li class="topfirst"><a href="#"  onclick= "return false" 
    					style="height:14px;line-height:14px;">Student Rankings</a></li>
    <li class="topfirst"><a href="#" onclick= "return false"  
    					style="height:14px;line-height:14px;">Course Statistics</a></li>
    <li class="topfirst"><a href="#" onclick= "return false"
    					style="height:14px;line-height:14px;">Eligibility Checking</a></li>
	<li class="topfirst"><a href="index.php"
    					style="height:14px;line-height:14px;">Update Statistics</a></li>
	<li class="topfirst"><a href="#" onclick= "return false"
    					style="height:14px;line-height:14px;">About</a></li>
</ul> 
</div> <!--menu-->


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
