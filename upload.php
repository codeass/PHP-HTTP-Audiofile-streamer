<?
include("#auth.php");
include("./config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<style type="text/css">

	#formbody {
		position: absolute;
		left: 50%;
		top: 50%;
		margin-top: -75px;
		padding: 10px;
		border-style: solid;
		border-width: 1px;
		/*background-color: #ddfff2;*/
		border-color: #00b7c5;
		background: -webkit-linear-gradient(top, #fff, #00b7c5);
		background:-webkit-gradient(linear, left top, left bottom, from(#fff), to(#00b7c5));
		background: -moz-linear-gradient(top, #fff, #00b7c5);
		background: -o-linear-gradient(top, #fff, #00b7c5);
		background: -ms-linear-gradient(top, #fff, #00b7c5);
		background: linear-gradient(top, #fff, #00b7c5);
		margin-left: -175px;
		width: 350px;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		-webkit-box-shadow: 0px 2px 8px #666666;
    	-moz-box-shadow: 0px 2px 8px #666666;
		box-shadow: 0px 2px 8px #666666;
	}

	body {
		background-color: #f6fff8;
	}

	</style>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Upload to HTTP-Stream</title>
	</head>
	<body>
		<form method="post" action="split.php" enctype="multipart/form-data" id="formbody">
			<table>
				<tr><td>Name:</td><td> <input type="text" size="45" name="filename"/></td></tr>
				<tr><td>Part-length: </td><td> <input type="text" name="partlenght" value="<?=$default_mp3_part_length;?>" size="3" /> seconds</td></tr>
				<tr><td>File: </td><td> <input type="file" name="file" accept="audio/x-mpeg" maxlength="16000000"></td></tr>
				<tr><td><input value="Upload" type="submit"></td><td></td></tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><td colspan="2"><center><a href="./setup.php">Settings</a> | <a href="./logout.php">Logout</a></center></td></tr>
			</table>
		</form>
	</body>
</html>
