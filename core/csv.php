<?php
	$file = fopen("textCSV.txt", "r");

	if($file == false) {
		echo "Error opening file";
		exit();
	}

	$i = 0;
	while (!feof($file)) {
		$contents[$i++] = fgetcsv($file);
	}
	foreach ($contents as $key => $value) {
		if($value != null) {
			echo "Item: " . $key . " ";
			foreach ($value as $item) {
				echo $item;
			}
			echo "<br/>";
		}
	}

	echo "<pre>";
	print_r($contents);
	echo "</pre>";

?>
