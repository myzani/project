<?php
/** Difference between normal function and generator function **/
echo "<h3>Difference between normal function and generator function</h3>";

function normalFunc($input) {
	foreach ($input as $value) {
		$result[] = $value + 100;
	}
	return $result;
}

function genFunc($input) {
	foreach ($input as $value) {
		yield $value + 100;
	}
}

function genFuncKV($input) {
	foreach ($input as $key => $value) {
		yield $key => $value;
	}

}

$data = ["1", "2", "3"];

$objNormal = normalFunc($data);

echo "Normal Function: <br/>";
foreach ($objNormal as $val) {
	echo $val . "<br/>";
}

$objGen = genFunc($data);
echo "<br/><br/>Generator Function: <br/>";
foreach ($objGen as $val) {
	echo $val . "<br/>";
}

echo "<br/><br/>Generator Function with key value pair: <br/>";

$dataKV = ["name"=>"John Doe", "age"=> "30", "address"=>"Cebu, Philippines"];
$objKeyVal = genFuncKV($dataKV);
foreach ($objKeyVal as $key => $value) {
	echo "Key: ". $key ." ". "Value: ". $value . "<br/>";
}

?>
