<script>
$(document).ready(function(){
	$('#sr').removeClass('active');
	$('#cs').removeClass('active');
	$('#et').removeClass('active');
	$('#us').addClass('active');
	$('#ab').removeClass('active');	
	
	$(".inputcell").change(function() {
	
		var callback = "<?=site_url('update_statistics/updateStudentInfo')?>";
		var changed_cell = $(this);
		
		$.ajax({
			type: 'post',
			url: callback,
			data: {
				personid: $(this).attr('id'),
				changedfield_name: $(this).attr("data-changedfieldname"),
				changedfield_value: $(this).val()
			},
			dataType: 'html',
			success: function (retVal) {
				if (retVal == 'true') {
					$(changed_cell).css('background-color','#AAFFCC').css("color","#555555");
					setTimeout(function() { $(changed_cell).css("background-color","white"); }, 250);
				} else {
					alert(retVal);
					$(changed_cell).css("background-color","#CF0220").css("color","white");
				}
			},
			error: function(){
				alert("Call to database failed.");
				$(changed_cell).css("background-color","#CF0220").css("color","white");
			}
		});//endajax
	});//endchange
});//end ready ()
</script>

<script src="http://localhost/cs192dcs/assets/js/jquery.tablesorter.js"></script>

<style>
.students
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:500;
border-collapse:collapse;
color:#FFFFFF;
}
.students td, #students th 
{
font-size:1.2em;
color:#FFFFFF;
border:1px solid #006600;
padding:3px 7px 2px 7px;
background-color:green;
}
.students th 
{
font-size:1.4em;
text-align:left;
padding-top:3px;
padding-bottom:2px;
background-color:green;
}
tr:nth-child(even) {background: #F5F0EB}
tr:nth-child(odd) {background: #FFF}

</style>
<style type="text/css">
    table thead tr .header {
        background-image: url(http://localhost/cs192dcs/images/bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        background-color:#4D9900;
    }
    table thead tr .headerSortUp {
        background-image: url(http://localhost/cs192dcs/images/asc.gif);
        background-color:#336600;
    }
    table thead tr .headerSortDown {
        background-image: url(http://localhost/cs192dcs/images/desc.gif);
        background-color:#336600;
    }
</style>
 <div class="page-header">
    <h1>Edit Student Information</h1>
 </div>
<form action="" method="post"> 
<table id="students" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
            <th width="10%"><center>Student Number</center></th>
			<th width="20%"><center>First Name</center></th>
			<th width="20%" ><center>Middle Name</center></th>
			<th width="20%"><center>Last Name</center></th>			
			<th width="10%"><center>Pedigree</center></th>
			<th width="20%"><center></center></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($students as $student){
			
				$personid = $student['personid'];
				$studentno = $student['studentno'];
				$firstname = $student['firstname'];
				$middlename = $student['middlename'];
				$lastname = $student['lastname'];
				$pedigree = $student['pedigree'];

				echo "<tr>";
				printCell($personid, 'studentno', $studentno);
				printCell($personid, 'firstname', $firstname);				
				printCell($personid, 'middlename', $middlename);
				printCell($personid, 'lastname', $lastname);
				printCell($personid, 'pedigree', $pedigree);
				echo "<td></td>";
				echo "</tr>";
			}
			
			function printCell($personid, $fieldname, $fieldvalue) {
				$length = strlen($fieldvalue) + 1;
				$data = array('name'=>'databasecell', 'id'=>$personid, 'class'=>'inputcell',
				'data-changedfieldname'=>$fieldname, 'size'=>$length, 'value'=>$fieldvalue);
				echo "<td><center>".form_input($data)."</center></td>";
			}
		?>
	
	</body>
</table>
</form>