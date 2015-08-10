<?php

 

class dbConn{

 

protected static $db;

 

private function __construct() {

 

try {

self::$db = new PDO( 'mysql:host=localhost;dbname=phpconf;charset=utf8', 'root', '' );

self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

}

catch (PDOException $e) {

echo "Connection Error, not connect to db "; //. $e->getMessage();

}

 

}

 

public static function getConnection() {

 

if (!self::$db) {

new dbConn();

}

 

return self::$db;

}

 

}

?>