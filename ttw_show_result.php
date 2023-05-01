<?php
session_start();
include_once "ttw_includes_var_func.php";
$ok=true;

switch($_POST["toShow"]){
	case "download" :
	$fileName = "ttw_result.zip";
	break;
	case "pandoc" :
	$fileName = "articleFile.html";
	break;
	case "TagToolHtml" :
	$fileName = "articleFile_edited_1_.html";
	break;
	case "TagToolXml" :
	$fileName = "articleFile_edited_1_.xml";
	break;
	case "Metadata" :
	$fileName = "01_MetadataValueList.csv";
	break;
	case "AuthorYear" :
	$fileName = "02_AuthorYearList.csv";
	break;
	case "Illustration" :
	$fileName = "03_IllustrationCreditList.csv";
	break;
	case "SandR" :
	$fileName = "04_ToSearchAndReplaceList.csv";
	break;
	default:
	$ok=false;
}

if($ok){
	if(file_exists($sessionTempPath . $fileName)){
		
		provide_result($fileName, $_SESSION["outputFormat"]);
		
		} else {
		echo "This file does not exist, try again.";
		}	
		
	} else {
		echo "This filename does not exist, try again.";
	}
?>