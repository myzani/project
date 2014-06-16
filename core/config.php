<?php
// Database Config
defined('ENGINE')   ? null : define('ENGINE', 'mysql');
defined('HOST')     ? null : define('HOST', 'elluamvc.dev');
defined('DBNAME')   ? null : define('DBNAME', 'testDB');
defined('DBUSER')   ? null : define('DBUSER', 'root');
defined('DBPWD')    ? null : define('DBPWD', '1234');

// Hash
defined('SALT_PWD') ? null : define('SALT_PWD', 'rounds=5000$sEcReTp@sHar87!x');

// PHP Mail
defined('HOST_EMAIL')  ? null : define('HOST_EMAIL', 'myzani_creed@hotmail.com');
defined('SMTP_AUTH')   ? null : define('SMTP_AUTH', true);
defined('USERNAME')    ? null : define('USERNAME', '');
defined('PASSWORD')    ? null : define('PASSWORD', '');
defined('SMTP_SECURE') ? null : define('SMTP_SECURE', 'tls');

?>
