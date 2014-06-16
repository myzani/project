<?php
require('../core/ExternalFile.php');
require('../core/csvfile.php');

$obj = new FileCSV('alexis.txt', 'r');
/** Reading CSV **/
$contents = $obj->csvRead();
//
foreach ($contents as $key => $value) {
    if($value != null) {
        echo "Item: " . $key . " ";
        foreach ($value as $item) {
            echo $item;
        }
        echo "<br/>";
    }
}
/** Creating CSV **/
//$data = ["denolan alexis", "john doe"];
//$obj->csvCreate($data, " ");
