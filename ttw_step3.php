<?php
session_start();
include_once "ttw_includes_var_func.php";
include_once "ttw_includes_head.php";
show_intro();
?>
<h3>Step 3: Download files or check the result first</h3>

<p><b>Tip:</b> After checking the result, you can re-do step 1 and 2 including the upload of files using the back button of your browser.</p>

<form method="post" action="ttw_show_result.php" target="_blank">
<div class="infobox">
	<table class="menue">
		<tr>
			<th>Create download package as .zip folder</th>
			<th><input type="radio" name="toShow" value="download" checked></th>
		</tr>
	</table>
</div>
<br />
<div class="infobox">
	<table class="menue">
		<tr>
			<th>... or show the converted files in a new browser window: </th>
		</tr>
		<?php
		if($_SESSION["outputFormat"] == "html"){ ?>
		<tr>
			<td>the <b>final .html</b> version of the article, converted by TagTool_WiZArD</td>
			<td><input type="radio" name="toShow" value="TagToolHtml"></td>
		</tr>
		<?php
		}
		if($_SESSION["outputFormat"] == "xml"){ ?>
		<tr>
			<td>the <b>final .xml</b> version of the article, converted by TagTool_WiZArD</td>
			<td><input type="radio" name="toShow" value="TagToolXml"></td>
		</tr>
		<?php 
		} 
		?>
		<tr>
			<td>(the intermediate .html version of the article, converted by pandoc)</td>
			<td><input type="radio" name="toShow" value="pandoc"></td>
		</tr>
		<tr>
	</table>
</div>
<br />
<div class="infobox">
	<table class="menue">
			<th>... or show the value lists for a second run, i. e.</th>
		</tr>
			<td>the <b>Metadata Value List</b> (see documentation above for preparing the .csv files)</td>
			<td><input type="radio" name="toShow" value="Metadata"></td>
		</tr>
		<tr>
			<td>the <b>Author Year List</b></td>
			<td><input type="radio" name="toShow" value="AuthorYear"></td>
		</tr>
		<tr>
			<td>the <b>Illustration Credit List</b></td>
			<td><input type="radio" name="toShow" value="Illustration"></td>
		</tr>
		<tr>
			<td>the additional <b>Search And Replace List</b></td>
			<td><input type="radio" name="toShow" value="SandR"></td>
		</tr>
	</table>
</div>
<h3>Submit and continue</h3>

<input type="submit" name="submit" value="Submit">

</form>
</body>
</html>