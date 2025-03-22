let icons_links = document.querySelectorAll('[data-page]');
const srchInput = document.querySelector('.search .input-data input');
const searchBtn = document.querySelector('.submit-search');
const toggleRequestsBtn = document.getElementById('requests')
const requests = document.querySelector('.requests')
const contentRequests = document.querySelector('.requests .content')
const overlay = document.querySelector('.ovl')
const usersPlace = document.querySelector('.left-side .body .friends')

const mini_profile = document.querySelector('.profile-mini'); 
const nameInput = document.querySelector('.profile-mini .info h2'); 
const imageInput = document.querySelector('.profile-mini input');
const toggleProfileIcon = document.querySelector('.toggle-profile');
const currentImage = document.querySelector('.profile-mini .image img');
const editBtn = document.querySelector('.profile-mini .btn button');


icons_links.forEach(e => {
     e.onclick = () => {
          location.href= e.dataset.page;
     }
})
function loadImage(data){
    const link =  data;

    const el = document.createElement('a');
    el.href = link;
    el.download = 'chatSync-Message-image';

    el.className = 'hidden';
     document.body.appendChild(el);
     el.click();
     el.remove();

}


function toggleRqsts() {

     contentRequests.style.opacity = '0';
     overlay.style.opacity = '0';
     
     requests.classList.toggle('hidden');

     
     
     setTimeout(() => {
          
     overlay.style.opacity = '1';
     
     setTimeout(() => {
     
          contentRequests.style.opacity = '1';
     }, 200);
     
     }, 300);
               
       


}


toggleRequestsBtn.onclick = toggleRqsts;
overlay.onclick = toggleRqsts;



const proBtns = document.querySelectorAll('[data-sender]');

proBtns.forEach((btn) => {

     btn.onclick = () => {
          const sender = btn.dataset.sender;
          const action =  btn.className.trim();
          
          btn.parentElement.parentElement.remove();
          manageRequest(sender , action);
          
     }

})




function manageRequest(sender , action){
     const manageUrl = '/request/manage';
     const data = new FormData();
     data.append('sender' , sender);
     data.append('action' , action);

     fetch (manageUrl , {
          body: data,
          method : 'post',
     }).finally(e => {
          location.reload();
     })

}

// Search Users

function toggleLoading(){
    document.querySelector('.load').classList.toggle('hidden');
}
function search(){

     if (srchInput.value.trim()){

          const value = validate(srchInput.value.trim());
          
          const searchURL = '/users/search';

          const data = new FormData()
          data.append('query' , value);
          toggleLoading();
          fetch(searchURL , {
               method: 'post',
               body : data
          }).then(e => e.json()).then(e => {
             
            toggleLoading()

               const users = e.data;


                    if (users.length > 0){

                         usersPlace.innerHTML = '';
                         users.map(user => {
                              let userEl = `<div class="friend" >
                              <div class="details">
                                   <div class="info">
                                   <div class="image"><img src="${user.profile_image ? '/storage/' + user.profile_image : '/assets/images/logo.png' }" alt=""></div>
                                   
                                   <div class="username">
                                   
                                        <h2 class="name">${user.username.slice( 0 , 20 )}</h2>              

                                   </div>
              
                                   </div>
              
                                   <div class="options btns">
                                        <button class="send-request btn" onclick="request(${user.id})"> Request </button>
                                   </div>
                              </div>
              
                         </div>`;

                         usersPlace.innerHTML +=(userEl);
                         })
                         
                    }else{
                         usersPlace.innerHTML = "<h2 class='alone-msg'>No Results</h2> ";
                    }

               srchInput.value = '';

            })

     }

}


searchBtn.onclick = search;


srchInput.onkeyup = () =>{

     srchInput.value =  validate(srchInput.value)

}


function validate(value){
     let usernameRegexp = /[^A-Za-z0-9]/ig;
     let ValidatedValue = value.replaceAll(usernameRegexp , '');

      return ValidatedValue; 
};



function request(id){
     
     const requestUrl = '/user/request';

     const data = new FormData();
     data.append('to' , id);


     fetch(requestUrl , {
          method: 'post',
          body: data
     }).then(e => e.json()).then(e => {
          if (e.status === 'sent'){
               location.reload();
          }

     })

}



function toggleProfile(){

     mini_profile.style.opacity = 0;
     mini_profile.classList.toggle('hidden')

     setTimeout(() => {
     mini_profile.style.opacity = 1
     }, 300);

}


toggleProfileIcon.onclick = toggleProfile;

     const profileData = new FormData();
     profileData.append('name' , validate(nameInput.innerHTML.trim()) )
     profileData.append('image' , 0)

function editProfileData(){
     const editUrl = '/user/profile/edit'
     if (profileData.get('name').trim() || profileData.get('image') != 0 ){

               fetch(editUrl , {
                    method:'post',
                    body : profileData
               }).then(e => e.json()).then(e => {

                    console.log(e)
               })
               .finally(() => {
                    location.reload();
               })

     }

}


function changeName(newName){
     if (newName){
          profileData.set('name' , validate(newName));
     }
}

function changeFile(newFile){
     if (newFile){
          profileData.set('image' , newFile);
     }
}

nameInput.onkeyup = () => {
     changeName(nameInput.innerHTML);     
}


imageInput.onchange = (event) => {

     const file = imageInput.files[0];
     if(file){
          changeFile(file)
     }
     const reader = new FileReader();
 
     reader.onload = function (e){
               currentImage.src = e.target.result;
     }
     reader.readAsDataURL(file);
     
}


editBtn.onclick = editProfileData;




function toggleFullScreen() {
      if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen()
          document.querySelector('.chat').style.width = '100%';
          document.querySelector('.chat').style.borderRight = 'none';
          
        } else {
          document.exitFullscreen();
          document.querySelector('.chat').style.width = '65%';
          document.querySelector('.chat').style.borderRight = '2px solid var(--bg-dark)';

            
        }
      document.querySelector('.left-side').classList.toggle('hidden')
  }
  