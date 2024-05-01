'use strict'

import Domains from '../class/domains.js';
const domain = new Domains();

import Users from '../class/users.js'
const userClass = new Users();

$(document).ready(function() {
	domain.getDomain(
		function(response)
		{
			$('#domains').prepend(userClass.domainHelper(response));
	  }
  );
});
