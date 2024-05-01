'use strict';

$(document).ready(function() {
	$.ajax({
		type: 'GET',
		url: '../php/get/domainName.php',
		data: { id: new URL(window.location.href).searchParams.get("domainid") },
		success: function (domainName) {
			$('#domain-title span').text(JSON.parse(domainName).name);
		}
	});
});