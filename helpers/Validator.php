<?php


require_once('models/User.php');

class Validator{

     static public function register($data){
               
          if (isset($data['username'] , $data['password']  , $data['cpassword']) &&
          !empty($data['username']) && !empty($data['password'] && $data['password'] === $data['cpassword'])&&
          strlen($data['password']) > 8 && strlen($data['cpassword']) > 8 && User::exists($data['username']) === 0){
               return 'valid';
          }

          return 'invalid';
     }

     static public function login($data){
               
          if (isset($data['username'] , $data['password'] ) && 
          !empty($data['username']) && !empty($data['password'])
            && User::exists($data['username']) === 1){
               return 'valid';
          }

          return 'invalid';
     }


}