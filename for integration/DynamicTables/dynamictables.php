<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Dynamic Table</title>
<script type="text/javascript" src="jQuery-1.8.2.js"></script>
<script type="text/javascript">
function swapContent(num) {
		var url = "changeContent.php"
			$.post(url, {tableNum: num}, 
				function(data) {
					$("#editable").html(data).show(); 
				}
			);	
	}

</script> 
</head>

<body>
<div id ="tables">
	<ul class="tableList">
    	<li><a href="#" onclick="return false" onmousedown="javascript:swapContent('1');">Requirements</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('2');">Persons</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('3');">Instructors</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('4');">Curricula</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('5');">Students</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('6');">Terms</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('7');">Grades</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('8');">Courses</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('9');">Classes</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('10');">Instructor Classes</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('11');">Student Terms</a></li>
        <li><a href="#" onclick="return false" onmousedown="javascript:swapContent('12');">Student Classes</a></li>
    </ul>
</div>
<div id ="editable">
	CONTENT CHANGE HERE.
</div>

</body>
</html>