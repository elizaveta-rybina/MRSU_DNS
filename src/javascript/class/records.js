import Domains from "./domains.js";
const domain = new Domains();

var domain_id = domain.getDomainId();
var domain_name = domain.getDomainName();

/*
  Данный класс содержит методы для работы с DNS-записями
*/
export default class Records{

  //Метод для взятия списка записей из базы данных
  getRecord(callback, tableName){
    $.ajax({
      type: "GET",
      url: "../php/get/records.php",
      dataType: "html",
      data: { table: tableName, domainid: domain_id },
      success: function (response) {
        callback(JSON.parse(response), JSON.parse(domain_name).name);
      },
    });
  };

  // Метод для работы AAAA-записей c DOM
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
          <div class="buttons">
            <button class="setting">
              <i class="fa-solid fa-pencil" style="color: #000000;"></i>
            </button>
            <button class="delete">
              <i class="fa-solid fa-trash" style="color: #000000;"></i>
            </button>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  // Метод для работы A-записей c DOM
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
          <div class="buttons">
            <button class="setting">
              <i class="fa-solid fa-pencil" style="color: #000000;"></i>
            </button>
            <form id="openDeleteModal">
              <input type="hidden" name="${row.id}" value="${row.id}">
              <button type="submit" class="btnDelete" data-toggle="modal" data-target="#deleteRecordFormA">
                <i class="fa-solid fa-trash" style="color: #000000;"></i>
              </button>
            </form>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  // Метод для работы CNAME-записей c DOM
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
          <div class="buttons">
            <button class="setting">
              <i class="fa-solid fa-pencil" style="color: #000000;"></i>
            </button>
            <form id="openDeleteModal">
              <input type="hidden" name="${row.id}" value="${row.id}">
              <button type="submit" class="btnDelete" data-toggle="modal" data-target="#deleteRecordForm">
                <i class="fa-solid fa-trash" style="color: #000000;"></i>
              </button>
            </form>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  // Метод для работы MX-записей c DOM
  mxHelper(response) {

    let wrapper = $('<div class="form-wrapper"></div>');

    response.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="type">
            <span>MX</span>
          </div>
          <div class="value">
            <p>Приоритет: ${row.priority}</p>
            <p>Значение: ${row.value}</p>
          </div>
          <div class="buttons">
            <button class="setting">
              <i class="fa-solid fa-pencil" style="color: #000000;"></i>
            </button>
            <form id="openDeleteModal">
              <input type="hidden" name="${row.id}" value="${row.id}">
              <button type="submit" class="btnDelete" data-toggle="modal" data-target="#deleteRecordForm">
                <i class="fa-solid fa-trash" style="color: #000000;"></i>
              </button>
            </form>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  // Метод для работы SRV-записей c DOM
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
        <div class="buttons">
          <button class="setting">
            <i class="fa-solid fa-pencil" style="color: #000000;"></i>
          </button>
          <form id="openDeleteModal">
						<input type="hidden" name="${row.id}" value="${row.id}">
						<button type="submit" class="btnDelete" data-toggle="modal" data-target="#deleteRecordForm">
              <i class="fa-solid fa-trash" style="color: #000000;"></i>
						</button>
					</form>
        </div>
			</div>
		`);
	});

	return wrapper;
  }

  // Метод для работы TXT-записей c DOM
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
        <div class="buttons">
          <button class="setting">
            <i class="fa-solid fa-pencil" style="color: #000000;"></i>
          </button>
          <form id="openDeleteModal">
						<input type="hidden" name="${row.id}" value="${row.id}">
						<button type="submit" class="btnDelete" data-toggle="modal" data-target="#deleteRecordForm">
              <i class="fa-solid fa-trash" style="color: #000000;"></i>
						</button>
					</form>
        </div>
			</div>
		`);
	});

	return wrapper;
  }

  //Метод для добавления A-записи
  addARecord(domainid, input){
    $.ajax({
      type: 'GET',
      url: '../php/add/arecord.php',
      data: { domainid: domainid, ip: input },
      success: function () {
        $('#records').prepend(this.a4Helper([{ip: input}], JSON.parse(domainName).name));
      },
      error: function(error) {
        alert("Ошибка добавления!");
      }
    });
  }

  //Метод для добавления MX-записи
  addMXRecord(domainid, input, input_pr){
    $.ajax({
      type: 'GET',
      url: '../php/add/mxrecord.php',
      data: { domainid: domainid, value: input, priority: input_pr},
      success: function() {
          $("#records").prepend(this.mxHelper([{priority: input_pr, value: input}], JSON.parse(domainName).name));
      },
      error: function(error) {
        alert("Ошибка добавления!");
      }
    });
  }

  //Метод для добавления CNAME-записи
  addCNAMERecord(domainid, input){
    $.ajax({
      type: 'GET',
      url: '../php/add/cnamerecord.php',
      data: { domainid: domainid, text: input },
      success: function () {
        $('#records').prepend(this.cnameHelper([{text: input}], JSON.parse(domainName).name));
      },
      error: function(error) {
        alert("Ошибка добавления!");
      }
    });
  }

  //Метод для добавления TXT-записи
  addTXTRecord(domainid, input){
    $.ajax({
      type: 'GET',
      url: '../php/add/txtrecord.php',
      data: { domainid: domainid, text: input },
      success: function () {
        $('#records').prepend(this.txtHelper([{text: input}], JSON.parse(domainName).name));
      },
      error: function(error) {
        alert("Ошибка добавления!");
      }
    });
  }

  //Метод для добавления SRV-записи
  addSRVRecord(domainid, SRVname, SRVPriority, SRVWeight, SRVPort, SRVHost, SRVTTL){
    $.ajax({
      type: 'GET',
      url: '../php/add/srvrecord.php',
      data: { domainid: domainid, name: SRVname, priority: SRVPriority, weight: SRVWeight, port: SRVPort, host: SRVHost, TTL: SRVTTL},
      success: function () {
        $('#records').prepend(this.srvHelper([{name: SRVname, priority: SRVPriority, weight: SRVWeight, port: SRVPort, host: SRVHost, TTL: SRVTTL}], JSON.parse(domainName).name));
      },
      error: function(error) {
        alert("Ошибка добавления!");
      }
    });
  }
}
