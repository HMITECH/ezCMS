<?php
// mySQL configuration
// used for accessing database
include ($_SERVER["DOCUMENT_ROOT"] . '/config.php');

global  $config_table, $server, $user, $password, $base;
$server = $databaseServer;   // replace by you mySQL server, could be something like 'localhost:3306' or 'localhost', or 'mysql.myhost.com'
$user = $databaseUser;     // replace by your login to mySQL server
$password = $databasePasswd; // replace by your password
$base = $databaseName;     // replace by the database where you want to create tables
// Table with phpTrafficA configuration, you can change it if you wish
$config_table = "phpTrafficA_conf";
?>
