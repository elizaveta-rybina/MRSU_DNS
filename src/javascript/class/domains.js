/*
  Данный класс содержит методы для работы с доменами
*/

export default class Domains
{
  constructor(id, name){
    this.id = id;
    this.name = name;
  }

  //Проверка на содержание только английских букв
  isEnglishLetters(input) {
    return /^[a-zA-Z]+$/.test(input);
  }

  //Проверка на наличие домена в базе данных
  checkValueInDatabase(value) {
    return new Promise((resolve, reject) => {
      $.ajax({
        method: "GET",
        data: { inputValue: value},
        url: '../php/helpers/searchName.php',
        success: function(response) {
          if (JSON.parse(response)['Message'] === "Success") {
            resolve(true);
          } else if (JSON.parse(response)['Message'] === "Error") {
            resolve(false);
          } else {
            reject(new Error("Непредвиденный ответ от сервера"));
          }
        },
        error: function(xhr, status, error) {
          reject(new Error("AJAX запрос не удался: " + error));
        }
      });
    })
  }

  //Метод для извлечения id домена из адресной строки
  getDomainId(){
    let domainid = new URL(window.location.href).searchParams.get("domainid");
    return domainid;
  }

  //Метод для извлечения имени домена по его id
  getDomainName(){
    let domainid = this.getDomainId();
    let returnValue;
    $.ajax({
      type: 'GET',
      url: '../php/get/domainName.php',
      data: { id: domainid},
      async: false,
      success: function (domainName) {
        returnValue = domainName;
      }
    });
    return returnValue;
  }

  // Метод для взятия списка доменов из базы данных
  getDomain(callback) {
    $.ajax({
      type: "GET",
      url: "../php/get/domains.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      },
    });
  };

  // Метод для добавления доменов
  AddDomain(callback, input){
    $.ajax({
      type: 'GET',
      url: '../php/add/domain.php',
      data: { domain_name: input },
      cache: false,
      dataType: "html",
      success: function () {
        callback();
      },
      error: function(error) {
        alert("Ошибка добавления!");
      }
    });
  }

  // Метод для удаления доменов
  deleteDomain(callback, value){
    $.ajax({
      type: "GET",
      url: "../php/delete/domain.php",
      data: { id: value },
      success: function() {
        callback();
      }
    });
  }

  // Метод для изменения имени домена
  changeNameOfDomain(callback, domain_name, id){
    $.ajax({
      type: 'GET',
      url: '../php/changing/domain.php',
      data: { domain_name: domain_name, domain_id: id},
      cache: false,
      dataType: "html",
      success: function () {
        callback();
      }
    });
  }

  // Метод для работы доменов c DOM
  domainHelper(responce) {
    var wrapper = $('<div class="form-wrapper"></div>');
    responce.forEach((domain) => {
      wrapper.append(`
        <div class="form-body">
          <p>${domain.name}</p>
          <div class="buttons">
            <form id="changeNameOfDomainForm">
              <input type="hidden" name="${domain.id}" value="${domain.name}">
              <button class="changeNameBtn" type="submit" data-toggle="modal" data-target="#changeNameOfDomain">
                <i class="fa fa-pencil" style="color: #000000;"></i>
              </button>
            </form>
            <form action="setting.html">
              <input type="hidden" name="domainid" value="${domain.id}">
              <button type="submit">
                <i class="fa-solid fa-gear" style="color: #000000;"></i>
              </button>
            </form>
            <form id="openDeleteModal">
              <input type="hidden" name="${domain.name}" value="${domain.id}">
              <button type="submit" class="btnDelete" data-toggle="modal" data-target="#deleteDomainForm">
                <i class="fa-solid fa-trash" style="color: #000000;"></i>
              </button>
            </form>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

}
