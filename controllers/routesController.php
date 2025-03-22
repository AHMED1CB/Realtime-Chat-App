<?php

require_once('models/User.php');

require_once('models/Message.php');

function defaultFunc(){

  return True;

}

function mainPage(){ 

  if (isAuth()){

    $requests = user()->getRequests();

    return render ('home.php' , ['requests' => $requests ]);
 }

 navigate('/register');
}

function registerPage(){     
  
  if (!isAuth()){
    
    return render('auth.php' , ['action' => 'register']);     
    
  }
  
  navigate('/');

} 

function loginPage(){

  if (!isAuth()){
    
    return render('auth.php' , ['action' => 'login']);     

  }
  
  navigate('/');
}

function chatUser($id){
  
try{
  if (isAuth() && user()->isFriend($id)){
          
    $requests = user()->getRequests();

    render('home.php' , [
 
      'chat' => $id ,
 
      'isChat' => True ,
 
      'requests' => $requests ,
 
      'user' => new User($id),
 
      'messages' => Message::getByChat($id)
 
      ]);
}
else{

  render('errorPage.php' );

}

}catch(\Ex $e){

  header('location:/');

}

}

function getGPTChat(){

  if (isAuth() ){
    
    $requests = user()->getRequests();

    $aiData = new User(-1);
    
    render('home.php' , [
    
      'chat' => -1 ,
    
      'isChat' => True ,
    
      'requests' => $requests ,
    
      'user' => $aiData ,
    
      'messages' => Message::getByChat(-1),
    
      'ai' => 'gpt'
    
      ]);
  }else{
      render('errorPage.php' , );
  }
}

function publicChat(){
  
    if(isAuth()){
      render ('home.php' , [
        'isChat' => True,
        'chat' => -10,        
        'user' => new User(-10),
        'messages' => Message::Public(),
        
      ]);
    }

}