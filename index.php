<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Sjark's Boxee tools</title>
	<script type="text/javascript" src="jquery-1.6.2-min.js"></script>
	</head>
	<body>
	<div id="content">
		<h1>The Boxee NFO Generator</h1><br />
		<h5 style="margin-top: -30px;">Made by Sjark aka Lasse</h5>
		<?php
			$error = $_GET['error'];
			if ($error == "1") {
				echo "<p style=\"color: red; font-weight:bold\">Du har ikke skrevet inn en ID!!!</p>";
			}
		?>
		<form enctype="multipart/form-data" action="gennfo.php" method="post">
			<table>
				<tr>
					<td>Skriv inn imdb id:</td><td><input type="text" name="id" /></td>
				</tr>
				<tr>
					<td>Tittel:</td><td><input type="text" name="Title" /></td>
				</tr>
				<tr>
					<td>Utgivelsesår:</td><td><input type="text" name="Year" /></td>
				</tr>
				<tr>
					<td>Plot:</td><td><textarea rows="5" cols="20" name="Plot" wrap="physical"></textarea></td>
				</tr>
				<tr>
					<td>Last opp thumbnail:</td><td><input name="thumb" type="file" /></td>
				</tr>
				<tr>
					<td><input type="submit" value="Generate nfo" /></td>
				</tr>
			</table>
		</form>
		<h5>Versjon 0.2 Beta</h5>
	</div>
	</body>
</html>