<?php
	require_once 'excel_parser.php';
	$parser = new ExcelParser("students.xls");
	$parser->parse();
?>
