<?php
//require_once('../init.php');
//require_once(CLASSAUTO);

$objUser = new UserAccount();
$obj = $objUser->getUserInfo(1);
$results = $objUser->iniObj;
echo $results->pwd;
//foreach($results as $item) {
//    echo $item;
//}

//echo "<pre>";
//print_r(UserAccount::$iniObj);

//foreach($obj as $item){
//    echo $item->email . "<br/>";
//}
//echo "<pre>";
//print_r($obj);
?>
