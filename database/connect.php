<?php 

try{
     
$conf = require(__DIR__.'/../configs/conf.php');

$env = $conf['ENV'];

$db_conf = $conf['database'][$env];



$user = $db_conf['user'];
$pass = $db_conf['pass'];
$host = $db_conf['host'];
$db   = $db_conf['database'];

$options = [
     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_PERSISTENT         => true,
     PDO::ATTR_TIMEOUT            => 600,
     PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
 ];


$con = new PDO("mysql:host=$host;dbname=$db;" , $user , $pass , $options);


}catch(\Exception $e){
    die('Something went wrong');

}