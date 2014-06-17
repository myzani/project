<?php
require_once("../core/init.php");
echo "<pre>";
$user = new UserProfile();
print_r($user->get_all_users());
?>
