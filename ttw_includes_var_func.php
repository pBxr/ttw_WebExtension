<?php

//Variables...
$path = basename(getcwd()) . "/";
$ttwPath = __DIR__ . "\\ttw\\";
$helpPath = $path . "ttw_show_help.php\" target=\"blank";
$indexPage = "href=\"ttw_index.php\"";

//Create temp ID for temp folder for all session files from Session ID
$currentDate = date('Ymd');
$tempID = substr(session_id(), 0, 4);
$nameTempDirectory = $currentDate . "_" . $tempID;
$sessionTempPath = $ttwPath . $nameTempDirectory . "\\"; 

//Get URL
$ttwURLcomplete = $_SERVER['REQUEST_URI'];
if($ttwURLcomplete != ""){
	$ttwURLsplit = explode("/", $ttwURLcomplete, -1);
	$ttwURL = implode("/", $ttwURLsplit);
	$ttwURL = $ttwURL . "/";
}

//Functions _______________________________________________________________
function call_ttw($options){
		
		global $ttwPath;
		global $nameTempDirectory; 
				
		$arguments = $ttwPath . "tagtool_v1-3-0.exe articleFile.html" . " --tempID" . $nameTempDirectory;
		
		foreach($options as $option){
			$arguments = $arguments . " " . $option;
		}
		
		//Switch on to check ttw call:
		//echo "ttw called with following arguments: " . $arguments;	
		
		$result = shell_exec($arguments);
		
		return $result;
}
		
function check_file_properties(){
		
		$ok = true;
		
		//Sizes...
		for ($i=0; $i<sizeof($_FILES["userfile"]["tmp_name"]); $i++) {
		
			if($_FILES["userfile"]["size"][$i] >= 1000000 || $_FILES["userfile"]["size"][$i] == 0){
				$ok=false;
			}
		}	
				
		//... now types for articleFile ...
		if(mime_content_type($_FILES["userfile"]["tmp_name"][0]) != "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
			$ok=false;
		} 
		
		$fileName = basename($_FILES["userfile"]["name"][0]);
		if(!str_contains($fileName, ".docx")){
			$ok=false;
		}
						
		//... and value lists ...		
		for ($i=1; $i<sizeof($_FILES["userfile"]["type"]); $i++) {
			
			$fileName = basename($_FILES["userfile"]["name"][$i]);
			if(!str_contains($fileName, ".csv")){
				$ok=false;
			}
			
			if(mime_content_type($_FILES["userfile"]["tmp_name"][$i]) != "text/plain" && mime_content_type($_FILES["userfile"]["tmp_name"][$i]) != "text/csv"){	
				$ok=false;
			}
		}	
				
		if(!$ok){
			return false;
		} else {
			return true;
		}
}	

function deleteFolder($folderName) { //function copied from https://paulund.co.uk/

         if (is_dir($folderName)){
           $folderHandle = opendir($folderName);
		 }
         
		 if (!$folderHandle){
              return false;
		 }
		 
         while($file = readdir($folderHandle)) {
               if ($file != "." && $file != "..") {
                    if (!is_dir($folderName."/".$file))
                         unlink($folderName."/".$file);
                    else
                         deleteFolder($folderName.'/'.$file);
               }
         }
		 
         closedir($folderHandle);
         rmdir($folderName);
         return true;
}

function open_proceed_box($stepNumber, $array){
	$file = "ttw_step" . $stepNumber . ".php";
	
	?>
	<br /><br />
	<table class="box">
		<tr>
			<?php 
			if($stepNumber == 2){
			echo "<td>Following files have been uploaded:<br />";	
			} 
				
			if($stepNumber == 3) {
			echo "<td>Following options have been set:<br />";
			}
			?>
						
			<span style="color: rgb(50, 205, 50);">
			<?php 
			foreach($array as $option){
			echo $option . "<br />";
			}
			?>
			</span></td>
			<td><b>Proceed <a href="../<?php global $path; echo $path. $file?>">here to step <?php echo $stepNumber;?></a></b> &check;</td>
		</tr>
	<table>
	<?php
}

function prepare_session() {
				
	global $sessionTempPath;
	global $ttwPath;
	global $currentDate;
		
	if(!file_exists($sessionTempPath)){
		mkdir($sessionTempPath);
	}
	
	//Now delete temp folders from previous sessions 
	$currentFiles = scandir($ttwPath);
		
	//Find garbage folders ..
	$garbageFolders = array();
	$extractedDate = array();
	
	foreach ($currentFiles as $currentFile){
		$pattern = '{(^20[0-9]{6}_)}';
		if (preg_match($pattern, $currentFile, $match)) {
			
			$extractedDate = explode("_", $currentFile);
			
			if((int)$extractedDate[0] < (int)$currentDate){ // = folders of the previous day and earlier
					array_push($garbageFolders, $currentFile);
			}
		}
	}

	//... and delete them
	foreach($garbageFolders as $garbageFolder){
		$toDelete = $ttwPath . $garbageFolder;
				
		if(!deleteFolder($toDelete)){
		echo "Warning: Garbage folder(s) could not be deleted!";
		}	
	}
	
	$_SESSION["sessionPrepared"] = true;
}

function provide_result($fileName, $outputFormat){
		
		global $tempID;
		global $currentDate;
		global $ttwURLcomplete;
		global $ttwPath;
		global $ttwURL;
		global $sessionTempPath; 
		global $nameTempDirectory;
		$fullPath = $sessionTempPath . $fileName;
				
		//Copy result to .zip archive and offer download...
		if(str_contains($fullPath, ".zip")){
						
			include_once "ttw_includes_head.php";

			$zipArchive = new ZipArchive();
			
			$zipArchive->open($fullPath, ZipArchive::OVERWRITE);
			
			if($outputFormat == "xml"){
				$zipArchive->addFile($sessionTempPath . "articleFile.html" , "articleFile_pandoc.html");
				$zipArchive->addFile($sessionTempPath . "articleFile_edited_1_.xml" , "articleFile_edited_1_.xml");
			}
						
			if($outputFormat == "html"){
				$zipArchive->addFile($sessionTempPath . "articleFile.html" , "articleFile_pandoc.html");			
				$zipArchive->addFile($sessionTempPath . "articleFile_edited_1_.html" , "articleFile_edited_1_.html");
								
				$zipArchive->addEmptyDir('articleFile_edited_1__ress');
				$zipArchive->addFile($sessionTempPath . "articleFile_edited_1__ress/colorschememapping.xml" , "articleFile_edited_1__ress/colorschememapping.xml");
				$zipArchive->addFile($sessionTempPath . "articleFile_edited_1__ress/filelist.xml" , "articleFile_edited_1__ress/filelist.xml");
				$zipArchive->addFile($sessionTempPath . "articleFile_edited_1__ress/header.html" , "articleFile_edited_1__ress/header.html");
				$zipArchive->addFile($sessionTempPath . "articleFile_edited_1__ress/item0001.xml" , "articleFile_edited_1__ress/item0001.xml");
			}
			
			$zipArchive->close();
		
			//URL is needed for the download attribute
			$targetURL = $ttwURL . "ttw/" . $nameTempDirectory . "/" . $fileName;
			
			?>
			<h3>Get your files <a href="<?php echo $targetURL?>" download="ttw_result.zip">here</a>.</h3>
			<div class="infobox">
			<p><b>Tip:</b> If you want to convert another article please close browser completely and start a new browser session.</p>
			</div>
			</body>
			</html>	
			<?php
		}
		
		//In case of .html show directly...
		if(mime_content_type($fullPath) == "text/html"){
			$articleContent = file_get_contents($fullPath);
			echo $articleContent;
		}
		
		//... in case of .xml show code...
		if(mime_content_type($fullPath) == "text/xml"){
			$articleContent = file_get_contents($fullPath);
			
			include_once "ttw_includes_head.php";	
			
			echo "\n<textarea style=\"background-color: rgb(237,252,237); font-family: calibri;\" cols=\"90\" rows=\"30\" name=\"actionWindow\">\n";
			echo $articleContent;
			echo "</textarea>\n</body>\n</html>";
		}
						
		//... in case of .csv prepare table and show
		if(mime_content_type($fullPath) == "text/plain"){
			include_once "ttw_includes_head.php";		
			
			$valueList = fopen($fullPath, 'r');
			
			echo "<table class=\"show_csv\">\n";
			while (!feof($valueList)) {
				$conentLine = fgets($valueList);
				$tableRow = "<tr><td>" . $conentLine;
				$tableRow = nl2br($tableRow);
				$tableRow = str_replace("<br />", "</td></tr>", $tableRow);
				$tableRow = str_replace("|", "</td><td>", $tableRow);
				$tableRow = str_replace("<td></td>", "<td><span class=\"soften\">-/-</span></td>", $tableRow);
				echo $tableRow;
				$tableRow="";
			}
		
		echo "\n</table>\n</div></body>\n</html>";
		
		fclose($valueList);	
		}
}

function show_intro(){
		?>
		<h2><a href="ttw_index.php" style="text-decoration: none";>TagTool_WiZArD - Web Extension</a></h2>
		<h3>How to use</h3>
		<p>You will find here <a href="../<?php global $helpPath; echo $helpPath?>">the application´s help documentation.</a> </p>
		<?php
		}	
?>