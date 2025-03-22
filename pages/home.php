<?php 
  $chat = False;
?>

<!DOCTYPE html>

  <html lang="en">

<head>


    {{extend('head.php')}}

     {{cssLink('_set.css')}}

     {{cssLink('home.css')}}


</head>

<body>
     <div class="container">



          <div class="left-side {{$chat ? 'hidden-on-mini' : ''}}">



          <div class="head">

               <div class="user">

                    <div class="info">

                    <div class="user-image">

                         <img src="{{Img(user()->photo())}}" alt="">

                    </div>

                    <div class="user-name"><h2 class="name-cont">{{user()->username()}}</h2></div>



                    </div>

                    <div class="icons">

                         <i class="ph ph-user icon toggle-profile" ></i>



                         <i class="ph ph-bell icon" id="requests"></i> 

                         <i class="ph ph-sign-out icon" data-page='/logout'></i> 



                    </div>



                  <div class="profile-mini hidden">

                    <div class="content">

                      <div class="info">

                          <div class="image">

                            <label for="chimage">

                            <img src="{{Img(user()->photo())}}" alt="">

                            </label>

                          </div>

                        <h2 class="username" contenteditable>{{user()->username()}}</h2>

                      </div>

                            <input type="file" id="chimage" class="hidden">

                          <div class="btn"><button class="editprof">Update</button></div>

                    </div>

                  </div>

               </div>

               <!-- End User -->

          <!-- See Requests -->

               <div class="requests hidden">

                    <div class="ovl"></div>

                    <div class="content">

                         <div class="head">

                              <h2 class="title">Requests</h2>

                         </div>

                         <div class="body">

                             {{displayRequests($requests)}}

                         </div>

                    </div>

               </div>

          <!-- End See Requests -->

          <!-- Start Search Friends -->

               {{extend('search.php')}}


          <!-- End Search Friends -->


          </div>

          <!-- end Head -->

     <div class="body">

               <div class="friends">

                    <!-- friend -->

                    {{extend('public.php')}}
                    {{extend('gpt.php')}}


                   {{ displayFriends() }}

                    <!-- end friend -->           

               </div>

                    <div class="load hidden"></div>

          </div>



 
     </div>
 
     <!-- End Left Side  -->
     <!-- Start Chat Side -->
     
          <div class="chat {{$chat ? '' : 'hidden-on-mini'}}">
               
               <?php 

               
               if ($chat || isset($isChat)){
               
               ?>


               
                    <div class="head">
               
                    <div class="user">
    
                         <div class="image">
    
                              <img src="<?=isset($ai) ? asset('images' , $user->photo()) : Img($user->photo())?> " alt="">
                         </div>



                         <div class="name">

                              <h2 class="name">{{$user->username()}}</h2>

                              <input type="hidden" id='to_user_id' value="<?=$user->id()?>">

                              <input type="hidden" id='myId' value="<?=user()->id()?>">

                         </div>



                    </div>



                    <div class="options icons">

                    
                    <i class="icon ph ph-corners-out" onclick="toggleFullScreen() " ></i>
                    <i class="icon ph ph-arrow-left" data-page="/"></i>
                    </div>

                    </div>

                    <!-- End Head -->

                    <div class="msgs-box">



                    <div class="messages">

                  
                    <?php 
                      
                      if (count($messages) > 0){
                      
                        foreach ($messages as $msg) {
                      
                          ?>

                          <div class="message message-<?=$msg['sent_from'] === Auth() ? 'right' : 'left'?>" data-msg="<?=$msg['id']?>">

                        <?php 
                      
                      if (!isset($ai)):
                      
                      ?>
                      
                         <div class="user-image"><img src="<?= $msg['sent_to'] == Auth() ? Img($user->photo())  : Img(user()->photo())   ?>" alt=""></div>

                        <?php endif;?>

                        <div class="msg-cont  <?=$msg['state'] === 'deleted' ? 'deleted' : '' ?>">
                              <?php 
                                if ($msg['sent_from'] === Auth() && $msg['state'] != 'deleted'):
                              ?>
                              <?php 
                                if (!isset($ai)):
                              ?>
                              
                              <div class="options">
                          
                                   <i class="msg-icon ph ph-trash" onclick = "deleteMsg(<?=$msg['id']?>)"></i>
                          
                              </div>
                          
                                  <?php
                              ?>

                              <?php 
                              endif;  
                          
                            endif;?>

                                    <?php 
                         
                         if ($msg['state'] !=='deleted'):
                         
                          if($msg['type'] === 'txt' ):
                                    ?>
                         
                                <p class="cont" style="min-width:100px;padding:2px 30px"><?=htmlspecialchars( $msg['content'] ?? 'No Content' ) ?></p>


                                <?php elseif ($msg['type'] === 'img'):?>

                            <div class="img-msg cont">

                              <img loading="lazy" src="<?=getImage( $msg['content'] )?>" alt="">

                              <div class="download">

                                <i class="ph ph-download" onclick='loadImage("<?=getImage( $msg['content'] )?>")' ></i>

                              </div>

                            </div>

                              <?php

                              endif;?>

                             <?php 

                              else:

                                echo('<i>Deleted Message</i>');

                              endif;?>
 
                         </div>
                    </div>


                          <?php
                        }
                      }
                    ?>
                   
                   </div>


                    <div class="create-msg">

                      <div class="show-image hidden">

                        <img src="/assets/images/logo.png" alt="">
                     
                      </div>

                    <div class="input">


            <?=(!isset($ai) ? '<label for="image_message" class="ph ph-image abs-icon-right"></label>':'' ); ?>
                      <input type="file" accept="image/png" name="image" id="image_message" class="hidden">

                    <input type="text" autofocus placeholder="Type a message" name="content" class="message-content-inp" id="message">

                

                  </div>

                    <div class="send">

                    <i class="ph ph-paper-plane-tilt"></i>

                    </div>

                    </div>

                    </div>

                    <?php

                    }else{

                        ?>

                    <div class="app-box">

                         <div class="icon-img">

                              <img src="{{asset('images' , 'logo.png')}}" alt="">

                         </div>

                         <div class="txt">

                              <h2>ChatSync</h2>

                              <span class="enc">End-To-End Encrypt</span>

                         </div>

                    </div>

                    <?php

                    }

?>

     <!-- End Msgs Box -->

          </div>



     <!-- End Chat Side -->



     </div>



     <script src="/assets/scripts/home.js"></script>

               <?php 

              if($chat || isset($isChat) && !isset($ai) && $user->id() != -10) :

              ?>

            <script src="/assets/scripts/socket.js"></script>

                  <?php

      endif

?>



               <?php 

            if(($chat || isset($isChat)) &&   $user->id() == -10) :

            ?>

            <script src="/assets/scripts/public.js"></script>

                <?php

            endif

            ?>




                  <?php if (isset($ai) ) :

    ?> 

                <script src="/assets/scripts/<?=$ai?>.js"></script>

               <?php endif;?>

</body>

</html>

