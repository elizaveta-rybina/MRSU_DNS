'use strict';

export function gen_password(len){
  var password = "";
  var symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for (var i = 0; i < len; i++){
      password += symbols.charAt(Math.floor(Math.random() * symbols.length));
  }
  return password;
}

$(document).ready(function(){
  $("#addUser-btn").click(function(e){
    e.preventDefault();
    var password = gen_password(8);
    document.getElementById('password').value = password;
  });
});
