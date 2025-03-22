<?php

require_once(__DIR__.'/../helpers/db.php');

class User{
     public $user;
     public function __construct($id){

          $getUserQuery = "SELECT `id` ,  `username` , `profile_image` FROM `users`  WHERE `id` = :userId ";
          $params = ['userId' => $id];
          
          $res = DB::execute($getUserQuery , $params);
          $this->user = $res->fetchObject();

          return $this->user;

     }

     
     public function username(){
          return substr($this->user->username , 0 , 20);
     }

     
     public function photo() {
          return $this->user->profile_image;
     }

     public function id() {
          return +$this->user->id;
     }

     public function getFriends()  {
          $query = "SELECT 
          u.id, 
          u.username, 
          u.profile_image,
          (
        SELECT m.content 
        FROM messages m 
        WHERE m.sent_from = u.id 
        AND m.sent_to = :id
        AND m.state IS NULL
        AND m.type = 'txt'
        ORDER BY m.id DESC 
        LIMIT 1
      ) AS last_message 
      FROM friends f 
      JOIN users u ON u.id = f.friend_id
      WHERE f.ur_id = :id";
          
          $params = ['id' => $this->user->id];

          $res = DB::execute($query , $params);

          return $res->fetchAll(PDO::FETCH_ASSOC);
     }

     public function getRequests(){
      $myId = Auth();

      $q = 'SELECT `sent_from` AS `sender` FROM `requests` WHERE `sent_to` = :id  ';
      $params = ['id' => $myId];

      $rqts = DB::execute($q , $params);

      $rqts = $rqts->fetchAll(PDO::FETCH_ASSOC);

      return count($rqts) > 0 ? $rqts : 0;



    }

     public function isFriend($id){

               $query = "SELECT * FROM friends WHERE `friend_id` = :fid AND `ur_id` = :urid ";
               $myId = Auth();
               $params = ['fid' => $id , 'urid' => $myId];

               $res = DB::execute($query , $params);

               if ($res->execute() && $res->rowCount() > 0){
                    return True;
               }


          return False;

     }

     static public function exists($user_name){
          $data = DB::execute('SELECT * FROM `users` WHERE `username` = :un' , ['un' => $user_name]);
          return $data->rowCount();
     }

     

     static public function register($data){
          $username = $data['username'];

          $password = $data['password'];


          $params = ['un' => $username , 'pass' => Password($password)]; 
          $query = "INSERT INTO `users` (`username` , `password`) VALUES (:un , :pass)";

          if (DB::execute($query , $params)){
               return 200;
          }else{
               return 400;
          }
          
     }

     static public function login($data){
         $username = substr($data['username'] , 0 , 20);
      
       $params = [
               'un' => $username,
               'pass' => Password($data['password']),
               'unhpass' => $data['password']
          ];

          $q = "SELECT * FROM `users` WHERE `username` = :un AND (`password` = :pass OR `password` = :unhpass) ";
         
          $result = DB::execute($q , $params);

          $result = $result->fetchObject();
          if (!empty($result)){
               setAuth($result->id);
               return 200;
          }

          

     }

     static public function isRequested($userId){
       
      $query = "SELECT * FROM requests WHERE `sent_to` = :sentId AND `sent_from` = :id ";
      $params = ['sentId' => $userId , 'id' => Auth()];
      $res = DB::execute($query , $params);
      if ($res->rowCount() > 0){
        return True;
      }
      return False;

     }


}