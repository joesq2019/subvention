<?php

define('DB_CONNECTION','mysql');

// define('DB_HOST','localhost');
// define('DB_DATABASE','futbolsc_admin');
// define('DB_USERNAME','futbolsc_admin');
// define('DB_PASSWORD','futboladmin2021');


define('DB_HOST','localhost');
define('DB_DATABASE','subvenciones');
define('DB_USERNAME','root');
// define('DB_PASSWORD','');
define('DB_PASSWORD','123456789');


$dbconn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($dbconn->connect_errno) {

    echo "Lo sentimos, este sitio web está experimentando problemas.<br>";
    echo "Error: Fallo al conectarse a MySQL debido a: <br>";
    echo "Errno: " . $dbconn->connect_errno . "\n";
    echo "Error: " . $dbconn->connect_error . "\n";

    exit;
}
