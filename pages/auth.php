

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>ChatSync - Register</title>

     {{cssLink('auth.css')}}
     {{cssLink('_set.css')}}

     <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

</head>
<body>

     <div class="container">
          <div class="heading">
               
               <div class="info">
                    <div class="image"><img src="/assets/images/logo.png" alt=""></div>
                    <h2>ChatSync</h2>


                    <div class="pg">
                    
                    <p>end-to-end encrypted</p>
        </div>

               </div>


          </div>


          <div class="form {{$action}}">
               <div class="title"><h2>{{$action}}</h2></div>
               <p class="warn-msg">{{ErrorMessage()}}</p>
               <form action="/{{$action}}" method="POST">
                    <div class="col">
                         <label for="username">Username</label>
                         <input type="text" minlength='6'  required name="username" id='username' placeholder='username'>
                    </div>
     
                    <div class="col">
                    
                         <label for="password">Password</label>
                         
                         <input type="password"  required name="password"  minlength='8' id='password' placeholder='password'>
              
              </div>


                    <?php if ($action === 'register'  ){?>
                    <div class="col">
                    
                    <label for="cpassword">Confirm password</label>
                    
                    <input type="password" required minlength='8'  name="cpassword" id='cpassword' placeholder='Confirm password'>
                    </div>
                    
                    <?php }?>
                    <div class="col">
                    <div class="btn">
                  
                  {{$action === 'register' ? 'Have An Account ?' : 'Dont Have An Account ?' }}
                  
                    <a href="/{{$action === 'register' ? 'login ' : 'register' }}">{{$action === 'register' ? 'Login ' : 'Register' }}</a>
                  
                  </div> 

            


                    </div>


                         <button type="submit" style="text-transform:capitalize">{{$action}}</button>

               </form>
          </div>
     
     </div>

                      <script src="{{asset('scripts' , 'validate.js')}}"></script>

</body>
</html>