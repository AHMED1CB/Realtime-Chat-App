<?php
require_once(__DIR__.'/../helpers/db.php');
class Message{

     static public function create($content , $to , $from , $type = 'txt'){

          $query = "INSERT INTO `messages` (`sent_from` , `sent_to` , `content` , `type`) VALUES (:fromU , :toU , :cont , :typeM) ";
          $params = [
               'fromU' => $from,
               'toU' => $to,
               'cont' => $content,
               'typeM' => $type
          ];

          DB::execute($query , $params);

     }

     static public function getByChat($chatId){
          
          $query = "SELECT * FROM `messages` WHERE `content` IS NOT NULL  AND `sent_to` = :myid AND `sent_from` = :chatId OR `sent_to` = :chatId AND `sent_from` = :myid     " ;

          $params = ['myid' => Auth(), 'chatId' => $chatId];

          $messages = DB::execute($query , $params);

          $messages = $messages->fetchAll(PDO::FETCH_ASSOC);

          return $messages;

     }


     static public function getLast($from , $to , $content , $type){
          
          $query = "SELECT * FROM `messages` WHERE `sent_from` = :sf AND `sent_to` = :st AND `content` = :cont AND `type` = :type ";
         
          $params = [
               'sf' => $from,
               'st' => $to,
               'cont' => $content,
               'type' => $type
          ];

          $msg = DB::execute($query , $params);

          return $msg->fetchObject();
     }
     
     static public function delete($id){
          $query = "UPDATE `messages` SET `state` = :nstate WHERE id = :id   ";
          $params = ['nstate' => 'deleted' , 'id' => $id];
          
          DB::execute($query , $params);
     
     }

     static public function Public(){
          
          $query = "SELECT * FROM `messages` WHERE sent_to = :pchid";
          $params = ['pchid' => -10];
          
          $data = DB::execute($query , $params);
     
          return $data->fetchAll(PDO::FETCH_ASSOC);

     }


}