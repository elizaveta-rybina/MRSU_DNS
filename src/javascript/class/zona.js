export default class Zona{
  //Метод для вывода ошибки при загрузке файла зоны в систему
  zonaErrorHelper(responce) {

    let wrapper = $('<div class="error-text"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="error-body">
          <div class="value">
            <p>${row}</p>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  getDomainFromZona(callback) {
    $.ajax({
      type: "GET",
      url: "../php/zones/get.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      },
    });
  };

  //Метод для работы с файлами зоны DOM
  domainHelperZona(responce) {
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

  //Метод для сохранения файла зоны на компьютер пользователя
  saveFileZone(value){
    $.ajax({
      type: 'GET',
      url: '../php/zones/downloadFile.php',
      data: { inputValue: value},
      xhrFields: {
        'responseType': 'blob'
      },
      success: function(data, status, xhr, responce) {
        var blob = new Blob([data], {type: xhr.getResponseHeader('Content-Type')});
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = value + ".hosts";
        link.click();
        alert("Файл был успешно сохранен");
      },
      error: function(error) {
        alert("Ошибка сохранения!");
      }
    });
  }
}
