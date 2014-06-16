<?php
require_once("../core/init.php");
echo "<pre>";
$user = new UserAccount();
echo $user->get_id('myzani_creed@hotmail.com')->user_id;
?>
