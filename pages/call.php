<!DOCTYPE html>
<html lang="en">
<head>
     <title>Call User {{$user->username()}}</title>
     {{cssLink('_set.css')}}
     {{cssLink('chat.css')}}
     {{extend('head.php')}}
</head>
<body>
     

     <div class="container">
     

                    <div class="user-data">
                  
                    
                    <div class="user">
                    <div class="user-image">
                   <img src="{{Img(user()->photo())}}" alt="">
               </div>
           
               <h2 class="username">{{user()->username()}}</h2>
                    <input type="hidden" name="myid" value="{{user()->id()}}" id="myId">
               </div>

               <div class="sep">
               <i class="ph ph-arrow-right"></i>
               </div>
               
               <div class="user">
                    <div class="user-image">
                   <img src="{{Img($user->photo())}}" alt="">
               </div>
           
               <h2 class="username">{{$user->username()}}</h2>
               <input type="hidden" name="user-id" value="{{$user->id()}}" id="userId">

               </div>





          </div>
               <div class="call-state">
                    Calling
               </div>
     

          <div class="end-call">
               <button class="ph ph-x"></button>
          </div>

     
     </div>

     <script src="{{asset('scripts', 'audioCall.js')}}"></script>

</body>
</html>