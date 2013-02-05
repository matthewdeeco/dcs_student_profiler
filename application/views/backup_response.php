<?php
if ($status == 0) {
	echo "<h4><span class='success'>Backup Success!<br></span></h4>";
	echo "Backup was saved to <span class='backup-location'>$backup_location</span>.<br>";
}
else {
	echo "<span class='error'>Failed to backup the database</span>.<br>
	Check if pg_dump is in the assets/postgres folder and if you have write permissions on the dumps folder.";
}
foreach ($output as $output_line)
	echo $output_line."<br>";
?>