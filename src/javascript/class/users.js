export default class Users{

  constructor(id){
    this.id = id;
  }

  // Метод для авторизации пользователей
  Authorization(login, password){
    $.ajax({
      method: "POST",
      data: { login: login, password: password },
      url: '../php/sign/signin.php',
      success: function(response)
      {

        if(JSON.parse(response)['Message'] == "Success")
        {
            window.location.href = "http://localhost/pages/main.html";
            window.location.replace("http://localhost/pages/main.html");
        }
        if(JSON.parse(response)['Message'] == "Admin")
        {
            window.location.href = "http://localhost/pages/admin_panel.html";
            window.location.replace("http://localhost/pages/admin_panel.html");
        }
        console.log(JSON.parse(response)['Message']);
      }
  });
  }

  //Метод для просмотра доменов (для пользователей)
  domainHelper(responce) {

    var wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((domain) => {
      wrapper.append(`
        <div class="form-body">
          <p>${domain.name}</p>
          <div class="buttons">
            <form action="settings.html">
              <input type="hidden" name="domainid" value="${domain.id}">
              <button type="submit">
                <i class="fa-solid fa-gear" style="color: #000000;"></i>
              </button>
            </form>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  /*
    Следующие функции необходимы для просмотра записей пользователями
  */

  //Метод для работы AAAA-записи с DOM (для пользователей)
  a4Helper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>AAAA</span>
          </div>
          <div class="value">
            <p>IP: ${row.ip}</p>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для работы A-записи с DOM (для пользователей)
  aHelper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>A</span>
          </div>
          <div class="value">
            <p>IP: ${row.ip}</p>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для работы CNAME-записи с DOM (для пользователей)
  cnameHelper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>CNAME</span>
          </div>
          <div class="value">
            <p>Текст: ${row.text}</p>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для работы MX-записи с DOM (для пользователей)
  mxHelper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>MX</span>
          </div>
          <div class="value">
            <p>Приоритет: ${row.priority}</p>
            <p>Значение: ${row.value}</p>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для работы SRV-записи с DOM (для пользователей)
  srvHelper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>SRV</span>
          </div>
          <div class="value">
            <p>Имя: ${row.name}</p>
            <p>Приоритет: ${row.priority}</p>
            <p>Вес: ${row.weight}</p>
            <p>Порт: ${row.port}</p>
            <p>Хост: ${row.host}</p>
            <p>Время жизни: ${row.TTL}</p>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для работы TXT-записи с DOM (для пользователей)
  txtHelper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>TXT</span>
          </div>
          <div class="value">
            <span>Текст: ${row.text}</span>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для поиска на сайте
  getJson(inputBox, callback)
  {
    $.ajax({
      type: 'GET',
      url: '../php/search/searchDomain.php',
      data: { inputBox: inputBox },
      success: function(json)
      {
        callback(JSON.parse(json));
      },
      error: function(json){
        console.log(json.responseText);
      }
    });
  }



}
