<?
error_reporting(1);
include("#auth.php");
include("./mp3lib.php");
include("./config.php");




$partlenght = $_POST['partlenght'];
if (!$partlenght) $partlenght = 10;

$filename = $_POST['filename'];
//filename whitespace fix
$filename = str_replace(" ","-",$filename);


//check for files / dirs with same name
if (dir($archive_path."/".date("Y-m-d-H-i").$filename))
	{
	//if same named file / dir exists create new name with counting up the name in (1)
	$filenamecounter = 2;
	while (dir($archive_path."/".date("Y-m-d-H-i").$filename."(".$filenamecounter.")"))
		{
		$filenamecounter++;
		}
	//count ready
	$current_file_dir = date("Y-m-d-H-i").$filename."(".$filenamecounter.")";
	}
//name is right
else $current_file_dir = date("Y-m-d-H-i").$filename;

//create dir in archive for saving parts
mkdir($archive_path."/".$current_file_dir);



//test move uploadet file and move it
if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir."/".$current_file_dir.".mp3")) {
    //
} else{
    //error
    echo "There was an error uploading the file, please try again!<br>";
    //remove dir becouse it isn't needed anymore (becouse is no content if upload failed)
    rmdir($archive_path."/".$current_file_dir);
    
   	//echo Upload Error
   	if(!$_FILES['file'])
   	{
   		$upload_error = "No file was uploaded.";
   	}
   	else
   	{
	   	switch($_FILES['file']['error'])
	   	{
	   		case 0: $upload_error = "here is no error, the file uploaded with success.";
	   		case 1: $upload_error = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
	   		case 2: $upload_error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
	   		case 3: $upload_error = "The uploaded file was only partially uploaded.";
	   		case 4: $upload_error = "No file was uploaded.";
	   		case 5: $upload_error = "-";
	   		case 6: $upload_error = "Missing a temporary folder.";
	   		case 7: $upload_error = "Failed to write file to disk.";
	   		case 8: $upload_error = "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.";
	   		default: $upload_error = "unknown upload error";
	   	}
   	}
   	echo("Upload Error: ".$upload_error."<br>");
    //die
    die();
}









//get path for original mp3file
$path = $upload_dir.'/'.$current_file_dir.".mp3";
//read MP3 
$mp3 = new mp3($path); 



//create m3u8 header
$m3u8_file = "#EXTM3U 
#EXT-X-MEDIA-SEQUENCE:0 
#EXT-X-TARGETDURATION:".$partlenght."
";




//start on second 0
$position = 0;
//continue the loop while $continue_mp3split_loop = 1
$continue_mp3split_loop = 1;
while($continue_mp3split_loop==1)
{
	
		//split the part
		$mp3_1 = $mp3->extract($position,$partlenght); 
		//export and save the part as file
		$mp3_1->save($archive_path."/".$current_file_dir.'/'.'file'.$position.'.mp3'); 
		
		
		
		//check if title has ended
		if (filesize($archive_path."/".$current_file_dir.'/'.'file'.$position.'.mp3')<2)
		{
			//stop the loop
			$continue_mp3split_loop = 0;
			//delete last empty file
			unlink($archive_path."/".$current_file_dir.'/'.'file'.$position.'.mp3');
		}

		//file is OK
		else {
			//write file URL to m3u8-index
			$m3u8_file = $m3u8_file."#EXTINF:".$partlenght.", 
".$extern_archive_path."/".$current_file_dir.'/'.'file'.$position.'.mp3
';}
		
		//count up the secondcounter for next part
		$position = $position + $partlenght;
}


//finish m3u8-index-file
$m3u8_file = $m3u8_file."#EXT-X-ENDLIST";

//write m3u8-index-file
$dz = fopen($archive_path."/".$current_file_dir.'/'.'index.m3u8',w);
fwrite($dz,$m3u8_file);
fclose($dz);

//write empty index.html file
$dz = fopen($archive_path."/".$current_file_dir.'/'.'index.html',w);
fwrite($dz,'');
fclose($dz);

//echo / save stream index URL
$streanurlcontent = "Upload completet: <a href=\"".$extern_archive_path."/".$current_file_dir.'/'.'index.m3u8'."\">Stream URL</a><br><audio src='".$extern_archive_path."/".$current_file_dir.'/'.'index.m3u8'."' controls='controls'>
Your browser does not support the HTML5 audio element.
</audio>";
//delete original MP3-file
unlink($upload_dir."/".$current_file_dir.".mp3");

?>
<html>
	<head>
	<style type="text/css">

	#content {
		position: absolute;
		left: 50%;
		top: 50%;
		margin-top: -25px;
		padding: 10px;
		border-color: #00b7c5;
		background: -webkit-linear-gradient(top, #fff, #00b7c5);
		background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#00b7c5));
		background: -moz-linear-gradient(top, #fff, #00b7c5);
		background: -o-linear-gradient(top, #fff, #00b7c5);
		background: -ms-linear-gradient(top, #fff, #00b7c5);
		background: linear-gradient(top, #fff, #00b7c5);
		border-style: solid;
		border-width: 1px;
		background-color: #ddfff2;
		margin-left: -150px;
		width: 300px;
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
		<title>HTTP-Stream - URL</title>
	</head>
	<body>
		<div id="content">
			<center><?=$streanurlcontent;?></center>
		</div>
	</body>
</html>

