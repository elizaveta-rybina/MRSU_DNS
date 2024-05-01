
/*
  Данный класс содержит все методы для сортировки
*/
export default class Sort{

  //Метод для сортировки ДОМЕНОВ в алфавитном порядке
  getDomainSort(callback) {
    $.ajax({
      type: "GET",
      url: "../php/sort/domains.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      }
    });
  }

  //Метод для сортировки ДОМЕНОВ в обратном порядке
  getDomainSortDesc(callback) {
    $.ajax({
      type: "GET",
      url: "../php/sort/domainsDesc.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      }
    });
  }

  //Метод для сортировки ФАЙЛОВ ЗОНЫ в алфавитном порядке
  getDataZones(callback) {
    $.ajax({
      type: "GET",
      url: "../php/sort/domainsZone.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      }
    });
  }

  //Метод для сортировки ФАЙЛОВ ЗОНЫ в обратном порядке
  getDataSortZonesDesc(callback) {
    $.ajax({
      type: "GET",
      url: "../php/sort/domainsZoneDesc.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      }
    });
  }

  //Метод для сортировки ЗАПИСЕЙ в алфавитном порядке по времени жизни
  getDataSortRecords(callback, domain_id, domain_name){
    $.ajax({
      type: "GET",
      url: "../php/sort/recordTTL.php",
      dataType: "html",
      data: { domainid: domain_id },
      success: function (response) {
        callback(JSON.parse(response), JSON.parse(domain_name).name);
      },
    });
  };

  //Метод для сортировки ЗАПИСЕЙ в обратном порядке по времени жизни
  getDataSortDescRecords(callback, domain_id, domain_name){
    $.ajax({
      type: "GET",
      url: "../php/sort/recordsDesc.php",
      dataType: "html",
      data: { domainid: domain_id },
      success: function (response) {
        callback(JSON.parse(response), JSON.parse(domain_name).name);
      },
    });
  };
}
