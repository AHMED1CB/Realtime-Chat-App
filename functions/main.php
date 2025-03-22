<?php

function extend($file){
     include('components/' . $file );
}

function isAuth(){
     return isset($_SESSION['user_login_id']);
}

function Auth(){
     return $_SESSION['user_login_id'] ;
}

function navigate($path){
     header('location: ' . $path);
}

function user(){
    if (isAuth()):
        return new User(Auth());
    endif;
}

function Password($data){
     return sha1($data);
}

function setAuth($token){
      $_SESSION['user_login_id'] = $token;
     return $_SESSION['user_login_id'] = $token;
}

function upload($file , $name){
    return move_uploaded_file($file , ('storage/' . $name));
}
function render($filename, $vars = [], $cond = 'defaultFunc', $onFalse = 'defaultFunc') {
    if ($cond()) {
        extract($vars);

        ob_start();
        include('pages/' . $filename);
        $content = ob_get_clean();

        $content = preg_replace_callback('/\{\{(.*?)\}\}/', function ($matches) use ($vars) {
            $code = trim($matches[1]);
            
                extract($vars); 
                ob_start();
                eval('$result = ' . $code . ';');
                $output = ob_get_clean();
                return isset($result) ? $result : $output;
            
        }, $content);

        print_r($content);
    } else {
        $onFalse();
    }
}



$errors = ['userRegisterError' => 'Invalid Data Or User Already Registerd' , 'userLoginError' => 'Invalid Data Or User Not Registerd',  'UnknownErorr' => 'Something Went Wrong' ];
function ErrorMessage() {
    global $errors;
    if (isset($errors[getCurrentError()])){
        $currentError = getCurrentError();
        $_SESSION['ERROR'] = '';
        return  $errors[$currentError] ;
    }else{
        return '';
    }
} 


function getCurrentError(){
    return isset($_SESSION['ERROR']) ? $_SESSION['ERROR'] : '' ;
}


function setError($name){
    global $errors;
    if(in_array($name , array_keys($errors))){
        $_SESSION['ERROR'] = $name;
        return 'sets';
    }

    return 'Unknown Error Name';
}



function cssLink($name){
    return ("<link rel=\"stylesheet\" href=\"/assets/css/$name\"> ");
}



function asset($type , $name){
    return("/assets/$type/$name");
}


function storage($name){
    return ("/storage/$name");
}

function Img($name){
    if ($name){
        return storage($name);
    }else{
        return asset('images' , 'logo.png');
    }

}


function getLastMessage($msg){
    if ($msg){
        return '- ' . substr($msg , 0  , 20) . '...';
    }
}

function displayFriends(){
    
    $friends = user()->getFriends();

    
        if (gettype($friends) === 'array' && count($friends) > 0 ){
            
            $str_friends = '';
            foreach ($friends as $friend) {
                $str_friends .= '<div class="friend" >
                <div class="details">
                     <div class="info" data-page="/c/'.$friend['id'].'">
                     <div class="image"><img src="'.Img($friend['profile_image']).'" alt=""></div>
                     
                     <div class="username">
                     
                          <h2 class="name">'.substr($friend['username'] , 0 , 20).'</h2>
                          <span class="last-message">'.getLastMessage($friend['last_message']).'</span>


                     </div>

                     </div>

                    
                </div>

           </div>';
            }
            
            return $str_friends;
        }
        echo gettype($friends) === 'string' || (gettype($friends) === 'array' && count($friends) === 0) ?  "<h2 class='alone-msg'>You Are Alone!</h2>" : '';

}


function displayRequests($requets){
    $rsts = '';
    if ($requets !== 0):
    foreach ($requets as $requet) {
        $sender = new User($requet['sender']);

        $rsts .= '<div class="request">
                <div class="info">
                    <img src="'.Img($sender->photo()).'" alt="">
                    <h2 class="name">'.$sender->username().'</h2>
                </div>
                <div class="btns">
                    <button class="accept" data-sender="'.$sender->id().'">Accept</button>
                    <button class="reject" data-sender="'.$sender->id().'">Reject</button>
                </div>
                
        </div>';
    }
    else:
        $rsts = '<h2 class="alone-msg">No Requets</h2>';    
    endif;

    return $rsts;

}



function JSON($data){
    echo(json_encode($data));
}


function getImage($url){
    $context = stream_context_create([
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ]);

    $response = file_get_contents($url, false, $context);

    return $response;
}