<?php

require_once('models/Message.php');

function chatGPT(){
     header('Content-Type: application/json'); 

     $KeyToken = "YOUR_KEY";
     $query = $_POST['query'];
     $apiURL = "https://api.openai.com/v1/chat/completions";
     $data = [
          'model' => 'gpt-4o-mini',
          'messages' => [
              ['role' => 'user', 'content' => htmlspecialchars($query)]
          ]
      ];


     $ch = curl_init($apiURL);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, [
     'Content-Type: application/json',
     'Authorization: Bearer ' . $KeyToken  

     ]);
     $response = curl_exec($ch);

     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     
     curl_close($ch);

     $msgCont = json_decode($response , true)['choices'][0]['message']['content'];

     Message::create($query ,   -1 , user()->id() );

     Message::create($msgCont ,  user()->id() , -1);


     echo $response ;
}


