<?php
	 // attempt a connection
	$conn = pg_connect("host=localhost dbname=dcsstudentprofiler port=5432 user=postgres password=PASSWD");
	
	//if connection fails
	if (!$conn) {
		die("Error in connection: " . pg_last_error());
	}  

	/*sample query to database
	
	$query = "select * from user_table order by Username";
	
	$result = pg_query($conn,$query);
	
	if (!$result) {
		echo "Query cannot be processed by the database!");
	}  else {
		$numrows = pg_numrows($result);
		//process results here
	 }
	 
	*/

?>