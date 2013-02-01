<?php

if ($success) {
	echo "File successfully uploaded";
	echo $excel_dump;
	echo "<br>";
	echo $parse_output;
}
else
	echo "<span class='error'>Error: $errormessage</span>";

?> 