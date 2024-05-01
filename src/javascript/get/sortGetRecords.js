"use strict";

import Admin from '../class/admin.js'

const adminClass = new Admin();

var domain_id = adminClass.getDomainId();
var domain_name = adminClass.getDomainName();

export function getDataSort(callback){
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

export function getDataSortDesc(callback){
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
