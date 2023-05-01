<?php
session_start();
include_once "ttw_includes_var_func.php";
include_once "ttw_includes_head.php";
show_intro();
?>

<h3>Step 2: Select functions and output format</h3>

<?php
if(!isset($_POST["submit"])){
?>

<p>We strongly recommend to choose the following pre-selected options:</p> 

<form method="post" action="">
<div class="infobox">
	<ul>
		<li><input type="checkbox" name="options[]" value="--bodyTags"  checked>Set customized journal body tags</li>
		<li><input type="checkbox" name="options[]" value="--figTags" checked>Set figure references tags</li>
		<li><input type="checkbox" name="options[]" value="--litTags" checked>Set author year tags. *CAUTION*: Author Year List (.csv) *REQUIRED*"</li>
		<li><input type="checkbox" name="options[]" value="--paragrNum" checked>Set paragraph numbers (recommended only if --bodyTags is chosen as well)</li>
		<li><input type="checkbox" name="options[]" value="--illCred" checked>Insert tagged illustration credits section. *CAUTION*: Illustration Credit List (.csv) *REQUIRED*</li>
		<li><input type="checkbox" name="options[]" value="--addSR" checked>Additional search and replace based on a value list. *CAUTION*: To Search And Replace List (.csv) *REQUIRED*.</li>
	</ul>
</div>
<p>Choose output format:</p> 

<select name="options[]">
	<option value ="--toXML" selected>.xml</option>
	<option value ="--toHTML">.html</option>
</select>

<h3>Submit and continue</h3>
<input type="submit" name="submit" value="Submit">

<?php
}
	if(isset($_POST["submit"])){
				
		$complete = true;
		$options = $_POST['options'];	
		
		if(sizeof($options) < 2){
			$complete = false;
			}	
		
		if(!$complete){
			?>
			<script type="text/javascript" language="Javascript">
				alert("Parameter(s) missing. \nCheck again and repeat submission.");
			</script>
			<?php
		} 
		else {
			
			//Extract output format
			$outputFormat = null;
			foreach($options as $option){
				if($option == "--toHTML"){
					$outputFormat = "html";
				}
				if($option == "--toXML"){
					$outputFormat = "xml";
				}
			}
			if($outputFormat!="") {
				$_SESSION["outputFormat"] = $outputFormat;
			}
			
			//Start conversion and convert .docx to .html with pandoc...			
			$call_pandoc = "pandoc -s -o " . $sessionTempPath . "articleFile.html" . " " . $sessionTempPath . "articleFile.docx";
			shell_exec($call_pandoc);
			
			//.. and create a .zip archive
			$fullPath = $sessionTempPath . "ttw_result.zip";
			$zipArchive = new ZipArchive();
			$zipArchive->open($fullPath, ZipArchive::CREATE);
			$zipArchive->addFromString("InitialFile.txt", "Initial File\n");
			$zipArchive->close();
			
			//... finally run TagTool
			call_ttw($options); 
								
			open_proceed_box("3", $_POST['options']);
			
		}
	}	
?>
</form>
</body>
</html>