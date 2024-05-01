'use strict'

import Admin from '../class/admin.js'
const adminClass = new Admin();

$(document).ready(function() {
	adminClass.getUsers(
		function(response)
		{
			$('#users').prepend(adminClass.usersHelper(response));
	  }
  );
});
