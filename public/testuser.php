<?php
require_once("../core/init.php");
//require_once(CLASSAUTO);

//$objUser = new UserAccount();
//$obj = $objUser->getUserInfo(1);
//$results = $objUser->iniObj;
//echo $results->pwd;
//foreach($results as $item) {
//    echo $item;
//}

//echo "<pre>";
//print_r(UserAccount::$iniObj);

//foreach($obj as $item){
//    echo $item->email . "<br/>";
//}
echo "<pre>";
//print_r($obj);

if(isset($_FILES['file'])){
    $finfo = new finfo(FILEINFO_MIME);
    $type = $finfo->file(SITE_ROOT.DS.'public'.DS.'images'.DS.$_FILES['file']['name']);
    $ftype = array_shift(explode(';', $type));
    echo $ftype;
    print_r($_FILES['file']);
}

?>
