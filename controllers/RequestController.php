<?php 

require_once('helpers/db.php');
require_once('models/User.php');

function manageRequest(){
     header("Content-Type: application/json");

     if (isAuth()){
          
          $sender = +$_POST['sender']; 
          
          $action = $_POST['action'];

          $id = Auth(); 

               if ($action === 'accept'){

                    $query = "INSERT INTO `friends`(`friend_id` , `ur_id`) VALUES (:fid , :urid);
                    INSERT INTO `friends`(`ur_id` , `friend_id`) VALUES (:fid , :urid);
                    DELETE FROM `requests` WHERE `sent_from` = :fid AND `sent_to` = :urid;
                      ";
                    $params = [
                         'fid' => $sender,
                         'urid' => $id
                    ];

                        DB::execute($query , $params);
                   
                    Json([
                         'status' => 'accepted',
                    ]);
               }else{

                    $query = "DELETE FROM `requests` WHERE `sent_from` = :fid AND `sent_to` = :urid;";
                    $params = [
                         'fid' => $sender,
                         'urid' => $id
                    ];

                    DB::execute($query , $params);

                    Json([
                         'status' => 'deleted'
                    ]);

               }

          return ;
     }
}

function searchUser(){
  $searchValue = $_POST['query'];

  $query = "SELECT `id` ,  `username` , `profile_image`  FROM `users` WHERE `username` LIKE :username AND `id` != :id LIMIT 3";

  $params  = ['username' => "$searchValue%" , 'id' => Auth()];

    $result = DB::execute($query , $params);

      $result = $result->fetchAll(PDO::FETCH_ASSOC);

  $result = array_filter($result , function($user) {
    return !user()->isFriend($user['id']) && !User::isRequested($user['id']);
  });



  Json([
    'data' => $result
  ]);
}


function makeRequest(){

  $query = "DELETE FROM  `requests` WHERE `sent_from` = :sf AND `sent_to` = :st ;
    INSERT INTO `requests` (`sent_from`  , `sent_to`) VALUES (:sf , :st)";
  $toId = $_POST['to'];
  $params = ['sf' => Auth() , 'st' => $toId];
  
    DB::execute($query , $params);
  json([
    'status' => 'sent'
  ]);
}

