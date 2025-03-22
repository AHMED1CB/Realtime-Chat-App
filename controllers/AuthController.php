<?php 
require_once('models/User.php');

function registerUser(){
     if (Validator::register($_REQUEST) === 'valid'){
      
               $data = [
                    'username' => $_REQUEST['username'],
                    'password' => $_REQUEST['password']
               ];
               if (User::register($data) === 200){
          
                    navigate('/login');

               }else{
                    setError('userRegisterError');     
                    navigate('/register');          
               }

          return;
     }else{

         setError('userRegisterError');     
          navigate('/register');
     
     }


}



function loginUser(){

    if (Validator::login($_REQUEST) === 'valid'){

          if (User::login($_REQUEST) === 200){
              navigate('/'); 
              return;
          }
               setError('userLoginError');     
               navigate('/login');     
          

          return;
     } 
          setError('userLoginError');     
          navigate('/login');
         
}

function logout(){
    session_destroy();
    session_unset();
    navigate('/register');
}

function editProfile(){

    $username = $_REQUEST['name'];

      $q = "";

      $params = ['un' => $username , 'id' => Auth()];

      if (isset($_FILES['image'])){
      
        $image = $_FILES['image'];

        $name = $image['name'];

        $imgTmp = $image['tmp_name'];

        $ext = pathinfo($name , PATHINFO_EXTENSION);

        $newName = "cs_user_image-" . uniqid() . '.'. $ext ;

        upload($imgTmp , $newName);


          $q = "UPDATE `users` SET `username` = :un , `profile_image` = :phImage";

          $params['phImage'] = $newName;

    }

    if ($q == ''){
      $q = "UPDATE `users` SET `username` = :un ";  
    }

    $q .= " WHERE `id` = :id";


    DB::execute($q , $params);


  json([
     'msg' => 'Updated' 
  ]);
}