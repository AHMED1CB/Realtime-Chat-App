const userNameInput = document.getElementById('username')

userNameInput.onkeyup = () =>{
     validate()
}
userNameInput.onkeydown = () =>{
     validate()
}
userNameInput.onblur = () =>{
     validate()
}
function validate(){
     
     let usernameRegexp = /[^A-Za-z0-9]/ig;
     let ValidatedValue = userNameInput.value.replaceAll(usernameRegexp , '')

     userNameInput.value = ValidatedValue; 
}