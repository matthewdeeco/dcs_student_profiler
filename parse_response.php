<html>
<head>
<!-- style is from http://code.google.com/p/php-excel-reader/ -->
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
    padding: 0 5px;
	border: 1px solid #EEEEEE;
}
</style>
</head>

<body>
<?php
	require_once 'excel_parser.php';

	class ParseResponse {
		public function ParseResponse($file) {
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
</body>
</html>