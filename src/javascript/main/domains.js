'use strict'

import Domains from '../class/domains.js';

const domain = new Domains();

$(document).ready(function() {
	domain.getDomain(
		function(response)
		{
			$('#domains').prepend(domain.domainHelper(response));
	  }
  );

  $('#domains').on('submit', '#openDeleteModal', function(e){
    e.preventDefault();
    var values = $('#openDeleteModal').serializeArray();
    $('#deleteDomainForm').submit(function() {
      var value = values[0].value;
      var name = values[0].name;
      domain.deleteDomain(
        function(){
          $('#domains').remove(domain.domainHelper([{name: name}]));
        },
        value
      )
      $('#openDeleteModal').modal('hide');
    });
  })

  $('#domains').on('submit', '#changeNameOfDomainForm', function(e){
    e.preventDefault();
    var values = $('#changeNameOfDomainForm').serializeArray();
    $('#changeNameOfDomain').submit(function() {
      var input = $('#changeNameInput').val();

      var id = values[0].name;
      if (domain.isEnglishLetters(input)) {
        alert("Форма заполнена верно!");
        domain.changeNameOfDomain(
          function()
          {
            $('#domain').html(domain.domainHelper([{name: input}]))
          },
          input, id
        );
        $('#changeNameOfDomainForm').modal('hide');
        location. reload();
        domain.getDomain(function(response)
        {
          $('#domains').prepend(domain.domainHelper(response));
        });
      } else {
        alert("Ошибка в имени домена. Повторите попытку позже.");
    }
    });

  })

  $('#AddDomain').submit(function(event) {
    event.preventDefault();

    var inputElement = document.getElementById('domainInput');
    var input = inputElement.value;

    if (domain.isEnglishLetters(input)) {
      alert("Форма заполнена верно!");
      domain.AddDomain(
        function()
        {
          $('#domain').html(domain.domainHelper([{name: input}]))
        },
        input
      );
      $('#AddDomain').modal('hide');
      location. reload();
      domain.getDomain(function(response)
      {
        $('#domains').prepend(domain.domainHelper(response));
      });
    } else {
        alert("Ошибка в имени домена. Повторите попытку позже.");
    }
  })
});
