<html>
<head>
<style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
</head>

<body>
<?php
	require_once 'excel_parser.php';

	class Grade_Parser{
		public function Grade_Parser($file){
			$this->target = $file
			$this->printer = new Spreadsheet_Excel_Reader($this->target);
			echo $this->printer->dump(true,true);
		}
	
		
		
		/*$parser = new ExcelParser($this->target);
		$parser->parse();*/
	}
?>
</body>
</html>