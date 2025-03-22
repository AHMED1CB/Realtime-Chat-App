<?php
require_once(__DIR__.'/../database/connect.php');

class DB{

     static public function execute($query , $params ){

          global $con;

          $result = $con->prepare($query);

          array_map( function ($key , $value) use($params , $result) {

               $result->bindParam($key , $value);    

          }, array_keys($params) , array_values($params));
          
          if($result->execute()){
               return $result;
          }

     }
}