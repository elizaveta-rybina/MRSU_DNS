'use strict'

import Users from '../class/users.js';
const usersClass = new Users();

$(document).ready(function()
{
  $('#login-form').on('submit', function(e)
  {
    e.preventDefault();
    var formData = $('#login-form').serializeArray();

    var login = formData[0]['value'];
    var password = formData[1]['value'];

    usersClass.Authorization(login, password);
  });
});
