<?php
function uploadThumb($moviename) {
	if (!file_exists("thumbs") || !is_dir("thumbs")) {
		mkdir("thumbs");
	}
	
	if ((($_FILES["thumb"]["type"] == "image/jpeg")
	|| ($_FILES["thumb"]["type"] == "image/pjpeg"))
	&& ($_FILES["thumb"]["size"] < 1000000)) {
		if ($_FILES["thumb"]["error"] > 0) {
			return "E2";
		}
		else {
			$filename = explode(".", $_FILES["thumb"]["name"]);
			$ext = end($filename);
			$i = 0;
			$moviename = str_replace(" ", "_", $moviename);
			$orgname = $moviename;
			while (file_exists("thumbs/" . $moviename . "." . $ext)) {
				$moviename = $orgname . $i;
				$i++;
			}
			move_uploaded_file($_FILES["thumb"]["tmp_name"], "thumbs/" . $moviename . "." . $ext);
			return "http://boxee.sjark.no/thumbs/" . $moviename . "." . $ext;
		}
	}
	else {
		return "E3";
	}
	
}

function getInfo($infotype, $post, $imdb) {
	$postfield = $post[$infotype];
	$imdbfield = $imdb[$infotype];
	if (!empty($postfield)) {
		return $postfield;
	}
	else {
		return $imdbfield;
	}
}

if(isset($_POST['id']) && !empty($_POST['id'])) {
	$id = $_POST['id'];
	$apilink = "http://www.imdbapi.com/?i=" . $id;
	$toDecode = file_get_contents($apilink);
	$imdbInfo = json_decode($toDecode, TRUE);
	if($_FILES["thumb"]["name"] !== "") {
		$upload = uploadThumb($imdbInfo["Title"]);
		if ($upload == "E2") {
			header("location: index.php?error=2");
		}
		elseif ($upload == "E3") {
			header("location: index.php?error=3");
		}
		else {
			$thumb = $upload;
		}
	}
	else {
		$thumb = $imdbInfo["Poster"];
	}
	if($imdbInfo['Response'] == "Parse Error") {
		header("location: index.php?error=4");
	}
	
	$filename = "movie.nfo";
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$title = getInfo("Title", $_POST, $imdbInfo);
	$year = getInfo("Year", $_POST, $imdbInfo);
	$plot = getInfo("Plot", $_POST, $imdbInfo);
	echo "<movie>" . "\r\n";
	echo "\t" . "<title>" . $title . "</title>" . "\r\n";
	echo "\t" . "<rating>" . $imdbInfo['Rating'] . "</rating>" . "\r\n";
	echo "\t" . "<year>" . $year . "</year>" . "\r\n";
	echo "\t" . "<outline>" . $plot . "</outline>" . "\r\n";
	echo "\t" . "<runtime>" . $imdbInfo['Runtime'] . "</runtime>" . "\r\n";
	echo "\t" . "<thumb>" . $thumb . "</thumb>" . "\r\n";
	echo "\t" . "<id>" . $imdbInfo['ID'] . "</id>" . "\r\n";
	echo "\t" . "<genre>" . $imdbInfo['Genre'] . "</genre>" . "\r\n";
	echo "\t" . "<director>" . $imdbInfo['Director'] . "</director>" . "\r\n";
	$actors = explode(",", $imdbInfo['Actors']);
	foreach ($actors as $actor) {
		echo "\t" . "<actor>" . "\r\n";
		echo "\t" . "\t" . "<name>" . $actor . "</name>" . "\r\n";
		echo "\t" . "\t" . "<role>Himself</role>" . "\r\n";
		echo "\t" . "</actor>" . "\r\n";
	}
	echo "</movie>"; 
	
}
else {
	header("location: index.php?error=1");
}