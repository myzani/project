<?php
require_once('./core/ExternalFile.php');
/*** ExternalFile ***/
$file = new ExternalFile("./textCSV.txt", "r");
$results = $file->fileHandler("denolan");

echo "<pre>";
print_r($results);
echo "</pre>";
// foreach ($results as $key => $value) {
// 	echo $value . "<br/>";
// }
//
//$file = fopen("./alexis.txt", "w+");
//fwrite($file, "hello");
//fclose($file);
/** **/
?>
