<?php
session_start();
include_once "ttw_includes_var_func.php";
include_once "ttw_includes_head.php";

if(!isset($_SESSION["sessionPrepared"])){
	prepare_session();
}

show_intro();

?>
<h3>Step 1: Upload files (maximum file size: 1 MB)</h3>

<?php
if(!isset($_POST["submit"])){
?>

<form method="post" enctype="multipart/form-data">
<div class="infobox">
	<table class="menue">
		<tr>
			<th>Required files</th>
			<th>Required type</th>
			<th>Choose files</th>
		</tr>
		<tr>
			<td>1. The <b>Article File</b></td>
			<td><b>.docx</b> file</td>
			<td><input type="file" name="userfile[]"></td>
		</tr>
		<tr>
			<td>2. The <b>Metadata Value List</b> (see documentation above for preparing the .csv files)</td>
			<td><b>.csv</b> file<br />(plain text with .csv ending)</td>
			<td><input type="file" name="userfile[]"></td>
		</tr>
		<tr>
			<td>3. The <b>Author Year List</b></td>
			<td><b>.csv</b> file<br />(see above)</td>
			<td><input type="file" name="userfile[]"></td>
		</tr>
		<tr>
			<td>4. The <b>Illustration Credit List</b></td>
			<td><b>.csv</b> file<br />(see above)</td>
			<td><input type="file" name="userfile[]"></td>
		</tr>
		<tr>
			<td>5. The <b>additional Search And Replace List</b></td>
			<td><b>.csv</b> file<br />(see above)</td>
			<td><input type="file" name="userfile[]"></td>
		</tr>
	</table>
</div>

<h3>Submit and continue</h3>
<input type="submit" name="submit" value="Submit">
<?php
}
	if (isset($_POST["submit"])){
	
		$complete = true;
		$correctProperties = true;
		
		for ($i=0; $i<sizeof($_FILES["userfile"]["tmp_name"]); $i++) {
			if($_FILES["userfile"]["tmp_name"][$i] == ""){
				$complete = false;
			}
		}	
		
		if($complete){
			$correctProperties = check_file_properties();
		}
		
		if($complete && $correctProperties){
			
			//Save Article File...
			$tempName = $_FILES["userfile"]["tmp_name"][0];
			$articleName = $sessionTempPath ."articleFile.docx";
			
			move_uploaded_file($tempName, $articleName);
			
			//Save MetadataValueList...
			$LoadedMVL = file_get_contents($_FILES["userfile"]["tmp_name"][1]);
			if(is_string("$LoadedMVL") && trim("$LoadedMVL") != "" ){
				$LoadedMVL = htmlspecialchars("$LoadedMVL");
				$MDVLcsv = fopen($sessionTempPath . "01_MetadataValueList.csv", "wb");
				fwrite($MDVLcsv, $LoadedMVL);
				fclose($MDVLcsv);
				} 
			
			//Save AuthorYearList...
			$LoadedAYL = file_get_contents($_FILES["userfile"]["tmp_name"][2]);
			if(is_string("$LoadedAYL") && trim("$LoadedAYL") != "" ){
				$LoadedAYL = htmlspecialchars("$LoadedAYL");
				$AYLcsv = fopen($sessionTempPath . "02_AuthorYearList.csv", "wb");
				fwrite($AYLcsv, $LoadedAYL);
				fclose($AYLcsv);
				}
			
			//Save IllustrationCreditList...
			$LoadedICL = file_get_contents($_FILES["userfile"]["tmp_name"][3]);
			if(is_string("$LoadedICL") && trim("$LoadedICL") != "" ){
				$LoadedICL = htmlspecialchars("$LoadedICL");
				$ICLcsv = fopen($sessionTempPath . "03_IllustrationCreditList.csv", "wb");
				fwrite($ICLcsv, $LoadedICL);
				fclose($ICLcsv);
				}
			
			//Save SearchAndReplaceList...
			$LoadedSRL = file_get_contents($_FILES["userfile"]["tmp_name"][4]);
			if(is_string("$LoadedSRL") && trim("$LoadedSRL") != "" ){
				$LoadedSRL = htmlspecialchars("$LoadedSRL");
				$SRLcsv = fopen($sessionTempPath . "04_ToSearchAndReplaceList.csv", "wb");
				fwrite($SRLcsv, $LoadedSRL);
				fclose($SRLcsv);
				}
		}
		
		if(!$complete || !$correctProperties){
			?>
			<script type="text/javascript" language="Javascript">
				alert("One or more of the required files are missing or have wrong type.\n\nCheck again and repeat upload.");
			</script>
			<p><b>Press "back" button of your browser to return to upload menue and restart upload.</b></p>
			
			<?php
			} 
			else {
				
				open_proceed_box("2", $_FILES["userfile"]["name"]);
			}
	}
?>
</form>
</body>
</html>