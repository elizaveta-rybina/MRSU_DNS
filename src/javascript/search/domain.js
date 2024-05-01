'use strict'

import Users from '../class/users.js';
const userClass = new Users();

$(document).ready(function()
{
 	$('.search-box').submit(function(event)
	{
		event.preventDefault();

    var searchInput = $('#inputBox').val();
		userClass.getJson(searchInput, function(json)
		{
      $('#domains').html("");
			$('#domains').append(userClass.domainHelper(json));
		});
	});
});
