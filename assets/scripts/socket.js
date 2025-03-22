const txtMsgInput = document.querySelector('.create-msg .input input');

const sendMsgBtn = document.querySelector('.create-msg .send i');

const messagesContainer = document.querySelector('.messages');

messagesContainer.scrollTop = messagesContainer.scrollHeight ;

const userId = document.getElementById("myId").value;

const toUserId = document.getElementById("to_user_id").value;

const InputMsg = document.getElementById('image_message');

const showImageEl = document.querySelector('.show-image');


let currentMsgImage = null;

let socketURL = "wss://web-production-ac21.up.railway.app"; //wss://web-production-ac21.up.railway.app

const ws = new WebSocket(socketURL);


ws.onopen = () => {
     const userId = document.getElementById("myId").value;
     ws.send(JSON.stringify({ action: "register", id: userId }));
     console.log('connected')
};


sendMsgBtn.onclick = sendTxtMessage;


function sendTxtMessage() {
     const message = document.getElementById("message").value;

          if (message.trim()){

               let msg = {
                    from: userId,
                    to: toUserId,
                    message: message,
                    type : 'txt'
                }
              
           ws.send(JSON.stringify(msg));
          document.getElementById("message").value = "";
          messagesContainer.scrollTop = messagesContainer.scrollHeight ;
          document.getElementById("message").focus();            
        }

 }
 



ws.onmessage = (event) => {
     const msg = JSON.parse(event.data);
     let userata = msg.from ? msg.from.user : {username:''};
     if (msg.chat === toUserId && msg.action === 'send'){
     let messageData = msg.message;
      myData = msg.from.user   ;

     if( messageData.type === 'txt'){
          
     displayTxtMsg(msg , messageData , myData);

     }else if (messageData.type === 'img'){
          
     displayImageMsg(messageData ,myData , msg);


     } 
   


     messagesContainer.scrollTop = messagesContainer.scrollHeight ;

    }else if(msg.action === 'delete' && msg.chat === toUserId){
         let targetMsg = document.querySelector(`[data-msg="${msg.msgId}"]`); 
     
          if(targetMsg){
               targetMsg.querySelector('.msg-cont').classList.add('deleted');

               targetMsg.querySelector('.msg-cont').innerHTML = '<i>Deleted Message</i>';
           
                targetMsg.querySelector('.options')?targetMsg.querySelector('.options').remove() : '';
           
          }

     }if (msg.chat !== toUserId){
        notify(`You Have A new Message From ${userata.username}`);

     }
};


ws.onclose =() =>{
     alert('something Went Wrong');
     location.reload();
}



function displayTxtMsg(msg , messageData , myData){

     if (msg.you){
          messageEl = `
          <div class="message message-right" data-msg="${messageData.id}">
          <div class="user-image"><img src="${myData.profile_image ? `/storage/${myData.profile_image}` : '/assets/images/logo.png'}" alt=""></div>
          <div class="msg-cont">

               
               <div class="options">
                    <i class="msg-icon ph ph-trash" onclick = "deleteMsg(${messageData.id})"></i>
               </div>


               <p class="cont" style="min-width:100px;padding:2px 30px">${messageData.content}</p>
          </div>
     </div>`;
     }else{
           messageEl = `
           <div class="message message-left" data-msg="${messageData.id}">
           <div class="user-image"><img src="${myData.profile_image ?  `/storage/${myData.profile_image}` : '/assets/images/logo.png'  }" alt=""></div>
           <div class="msg-cont">
                <p class="cont" style="min-width:100px;padding:2px 30px">${messageData.content}</p>
           </div>
      </div>`;
      
      
     }

     messagesContainer.innerHTML += messageEl;

}




window.onkeyup = (e) =>{
    if (e.key === 'Enter'){
        sendMsgBtn.click()
    }
}

function deleteMsg(msgId){

     ws.send(JSON.stringify({action : 'deletemsg' , msgId , userId , toUserId}))
}

function toggleImage(){

    if (showImageEl.classList.contains('hidden')){
        showImageEl.style.opacity = 1;
    }else{
        showImageEl.style.opacity = 0;
    }

    setTimeout(() => {
        showImageEl.classList.toggle('hidden');     
    }, 400);

}


InputMsg.onchange = (eve) =>{
     let file = InputMsg.files[0];
     if (file){

          if (file.type.startsWith('image/')){
               let reader = new FileReader();
               
               reader.onload = (res) => {
               showImageEl.querySelector('img').src = res.target.result; 
                showImageEl.classList.remove('hidden')
                
               currentMsgImage = res.target.result;
          }

          reader.readAsDataURL(file);

          sendMsgBtn.onclick = sendImageMsg;
     }
     

}

}


function sendImageMsg() {
     
     if(currentMsgImage){
          let msg = {
               from: userId,
               to: toUserId,
               message: currentMsgImage ,
               type : 'img'
          }



          ws.send(JSON.stringify(msg));

          sendMsgBtn.onclick = sendTxtMessage;
            toggleImage()

     }
     

}



function displayImageMsg(messageData ,  myData ,  msg){


     if (msg.you){
          messageEl = `
          <div class="message message-right" data-msg="${messageData.id}">
          <div class="user-image"><img src="${myData.profile_image ? `/storage/${myData.profile_image}` : '/assets/images/logo.png'}" alt=""></div>
          <div class="msg-cont">

               
               <div class="options">
                    <i class="msg-icon ph ph-trash" onclick = "deleteMsg(${messageData.id})"></i>
               </div>


               <div class="img-msg cont">
               <img src="${messageData.content}" alt="">
               <div class="download">
               <i class="ph ph-download" onclick='loadImage("${messageData.content}")'></i>
             </div> 
               </div> 
          </div>
     </div>`;


     }else{
       
           messageEl = `
           <div class="message message-left" data-msg="${messageData.id}">
           <div class="user-image"><img src="${myData.profile_image ?  `/storage/${myData.profile_image}` : '/assets/images/logo.png'  }" alt=""></div>
           <div class="msg-cont">

           <div class="img-msg cont">
                            <img src="${messageData.content}" alt="">
                            <div class="download">
                            <i class="ph ph-download" onclick='loadImage("${messageData.content}")' ></i>
                          </div>
          </div> 
              
           </div>
      </div>`;
      

      
     }
     
const downloadLinks = document.querySelectorAll('[data-download]');


downloadLinks.forEach(e => {
   e.onclick = () => {
        const link =  e.dataset.download;


       const el = document.createElement('a');
       el.href = link;
       el.download = 'chatSync-Message-image';

       el.className = 'hidden';
        document.body.appendChild(el);
        el.click();
        el.remove();
   }
})

     

     messagesContainer.innerHTML += messageEl;



}

function notify(msg) {
     if ('Notification' in window) {
         if (Notification.permission === 'granted') {
             push(msg);
         } else if (Notification.permission === 'denied') {
             Notification.requestPermission().then((res) => {
                 if (res === 'granted') {
                     push(msg);
                 }
             });
         } else {

          Notification.requestPermission().then((res) => {
                 if (res === 'granted') {
                     push(msg);
                 }
             });
         }
     }
 }
 
 function push(msg) {
     new Notification('ChatSync Notification', {
         body: msg,
         icon: '/assets/images/logo.png',
     });
 }
 