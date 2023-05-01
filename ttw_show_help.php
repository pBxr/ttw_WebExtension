<?php
include_once "ttw_includes_var_func.php";
include_once "ttw_includes_head.php";
?>
<h2>TagTool_WiZArD Web Extension - Help</h2>
<h3>Help documentation</h3>

<?php	
		
	$options[0]="--help" . " --tempID" . $nameTempDirectory;	
	
	$boxContent = call_ttw($options);
		
	echo "<textarea style=\"background-color: rgb(237,252,237)\" cols=\"160\" rows=\"30\" name=\"actionWindow\">";
	echo "$boxContent";
	echo "</textarea>";

?>
</body>
</html>