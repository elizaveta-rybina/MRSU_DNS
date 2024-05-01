'use strict';

import Domain from '../class/domains.js'
const domain = new Domain();

import Zona from '../class/zona.js'
const zona = new Zona();

$(document).ready(function(){

  $('#addFileZone').modal('hide');
	zona.getDomainFromZona(
		function(response)
		{
			$('#domains').prepend(domain.domainHelper(response));
	  }
  );

  //Сохранение файла зоны на компьютер пользователя
  $('#downloadFileZone').submit(function(event) {
    event.preventDefault();
    var inputValue = $('#downloadFile').val();
    console.log(domain.checkValueInDatabase(inputValue));
    domain.checkValueInDatabase(inputValue)
    .then(function(result) {
      if (result === true) {
        alert("Домен найден!");
        zona.saveFileZone(inputValue);
        $('#downloadFileZone').modal('hide');
      } else {
        alert("Ошибка! Такого домена не существует.");
      }
    })
    .catch(function(error) {
      console.error("Произошла ошибка: " + error);
    });
  })
})
