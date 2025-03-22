<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/../helpers/db.php';
require_once __DIR__.'/../Models/User.php';
require_once __DIR__.'/../Models/Message.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;





class ChatSync implements \Ratchet\MessageComponentInterface {
    protected $clients;
    protected $users;
    protected $bad_words;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = []; 
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function sendAction($id  , $data){

        try{
            
            if (isset($this->users[$id])){
                $this->users[$id]->send(json_encode($data));            
            }
            
        }catch(\Exception $e){
            echo('Send Message Fail');
        }
    
    }



    public function sendToAll($msg , $from){
        try{
            

                foreach ($this->users as $user) {

                    if ($from === '*' || $this->users[$from] !== $user ){
                      
                        $user->send(json_encode($msg));
                
                    }
                }

            
        }catch(\Exception $e){
            echo('Send Message Fail');
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $data = json_decode($msg, true);

        if (isset($data['action']) && $data['action'] == 'register' && isset($data['id'])) {
            $id = $data['id'];
            $this->users[$id] = $from;
            echo "User $id Registerd \n";

        }

        elseif (isset($data['to']) && isset($data['message']) ) {
           
            if($data['type'] === 'txt'){

                $toId = $data['to'];

                $message = $data['message'];
                $from = $data['from'];

                    Message::create($message , $toId ,  $from , 'txt');

                        $type = "txt";

                        $action = "send";

                        $msg = Message::getMessage($from , $toId  , 'txt');
                        
                        $fromData = new User($from);
                        
                        $toData = new User($toId);

                    $main_data = [
                        'message' => $msg,
                        'type' =>  $type,
                        'from' =>  $fromData,
                        'action' => $action,

                    ];


                        $this->sendAction( $from , array_merge($main_data , 
                        [
                            'you' => True,
                            'action' => $action,
                            'chat' => $toId,
                         ]
                    ));

                      
                            if ($toId == -10 ){
                                $this->sendToAll(array_merge([
                                    'to' => $toData,
                                    'chat' => $from
                                ],$main_data) , $from);
                            }else{
                                $this->sendAction($toId, array_merge([
                                    'to' => $toData,
                                    'chat' => $from
                                 ] , $main_data));
    
                            }

            }
            
            elseif($data['type'] === 'img'){
                

                    $toId = $data['to'];

                    $img = $data['message'];
                
                    $newName = "chatsync_msg_image-" . uniqid() . '.txt' ;

                    $from = $data['from'];
                 
                    $file  = $this->uploadImage($newName ,  $img);
                    if ($file){
                        
                   Message::create($file['html_url'].'/raw' , $toId , $from , 'img');
                
                   $cmsg = Message::getMessage($from , $toId , 'img');

                $cmsg->content = $this->getImage($cmsg->content);
                       
                         $this->sendAction($from , [
                            'message' => $cmsg,
                            'type' =>  'img',
                            'from' =>  new User($from),
                            'you' => True,
                            'action' => 'send',
                            'chat' => $toId
                         ]);

                         
                           

                                if ($toId == -10){
                                    $this->sendToAll([
                                        'message' => $cmsg,
                                        'type' =>  'img',
                                        'from' =>  new User($from),
                                        'to' => new User($toId),
                                        'action' => 'send',
                                        'chat' => $from
        
                                     ] , $from);
                                }else{
                                    $this->sendAction($toId , [
                                        'message' => $cmsg,
                                        'type' =>  'img',
                                        'from' =>  new User($from),
                                        'to' => new User($toId),
                                        'action' => 'send',
                                        'chat' => $from
        
                                     ]);
                                }

                    }
            }

        }
        elseif (isset($data['action']) && $data['action'] === 'deletemsg'){
            
            $data = json_decode($msg, true);
            
            $deleteFrom = $data['userId'];
            
            $onChatOf = $data['toUserId'];
            
            $msgId = $data['msgId'];
            
            Message::delete($msgId);

            $this->sendAction($deleteFrom , [
                'msgId' =>  $msgId ,
                'action' => 'delete',
                'chat' => $onChatOf
            ]);

          
    
                if ($onChatOf == -10){
                        $this->sendToAll([
                        'msgId' =>  $msgId ,
                        'action' => 'delete',
                        'chat' => $deleteFrom
                        ] , '*');
                }else{
                    $this->sendAction($onChatOf , [
                        'msgId' =>  $msgId ,
                        'action' => 'delete',
                        'chat' => $deleteFrom
                    ]);
                }
    } 



}


    public function getImage($url)
    {
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ]);
    
        $response = file_get_contents($url, false, $context);
    
        return $response;
    }
    

    public function uploadImage($fileName, $fileContent, $desc = 'New Chatsync Image') {
        $githubToken = "TOKEN";
        $apiUrl = "https://api.github.com/gists";
    
        $postData = json_encode([
            "description" => $desc,
            "public" => true,
            "files" => [
                $fileName => ["content" => $fileContent]
            ]
        ]);
    
        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,  
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "Authorization: token $githubToken",
                "User-Agent: PHP-Gist-Client",
                "Content-Type: application/json"
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 2,
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true); 
    }
    

    public function onClose(ConnectionInterface $conn) {
        foreach ($this->users as $id => $client) {
            if ($client === $conn) {
                unset($this->users[$id]);
                break;
            }
        }
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatSync()
        )
    ),
    8080
);
echo("started \n");
$server->run();
