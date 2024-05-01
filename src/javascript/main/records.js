'use strict'

import Domains from '../class/domains.js'
import Records from '../class/records.js';
const domain = new Domains();
const records = new Records();

var domain_id = domain.getDomainId();

$(document).ready(function() {
  //ARecord
	records.getRecord(
		function(response)
		{
			$('#records').append(records.aHelper(response));
		},
  'arecord');

  //MXRecord
  records.getRecord(
		function(response)
		{
			$('#records').append(records.mxHelper(response));
		},
  'mxrecord');

  //SRVRecord
  records.getRecord(
		function(response)
		{
			$('#records').append(records.srvHelper(response));
		},
  'srvrecord');

  //TXTRecord
  records.getRecord(
		function(response)
		{
			$('#records').append(records.txtHelper(response));
		},
  'txtrecord');

  //A4Record
  records.getRecord(
		function(response)
		{
			$('#records').append(records.a4Helper(response));
		},
  'a4record');

  //CNAMERecord
  records.getRecord(
		function(response)
		{
			$('#records').append(records.cnameHelper(response));
		},
  'cnamerecord');

  /*
    Следующие события необходимы для добавления разных типов записей
  */

  $('#exampleModalARecord').submit(function(event) {
		event.preventDefault();
    var input = $('#ARecordInput').val();
    records.addARecord(domain_id, input)
    location. reload()
  })

  $('#exampleModalMXRecord').submit(function(event) {
		event.preventDefault();
    var input = $('#MXRecordInput').val();
    var input_pr = $('#MXRecordInputPr').val();
    records.addMXRecord(domain_id, input, input_pr)
    location. reload()
  })

  $('#exampleModalCNAMERecord').submit(function(event) {
		event.preventDefault();
    var input = $('#CNAMERecordInput').val();
		records.addCNAMERecord(domain_id, input)
    location. reload()
  })

  $('#exampleModalTXTRecord').submit(function(event) {
		event.preventDefault();
    var input = $('#TXTRecordInput').val();
    records.addTXTRecord(domain_id, input)
    location. reload()
  })

  $('#exampleModalSRVRecord').submit(function(event) {
		event.preventDefault();
    var SRVname = $('#SRVname').val();
    var SRVPriority = $('#SRVPriority').val();
    var SRVWeight = $('#SRVWeight').val();
    var SRVPort = $('#SRVPort').val();
    var SRVHost = $('#SRVHost').val();
    var SRVTTL = $('#SRVTTL').val();
    records.addSRVRecord(domain_id, SRVname, SRVPriority, SRVWeight, SRVPort, SRVHost, SRVTTL)
    location. reload()
  })

  // //Удаление DNS-записи
  // $('#records').on('submit', '#openDeleteModal', function(e){
  //   e.preventDefault();
  //   var values = $('#openDeleteModal').serializeArray();

  //   $('#deleteRecordFormA').submit(function() {
  //     var value = values[0].value;
  //     var name = values[0].name;
  //     deleteRecords(
  //       function(){
  //         $('#records').remove(aHelper([{name: name}]));
  //       },
  //       value, 'arecord'
  //     )
  //     $('#openDeleteModal').modal('hide');
  //     location. reload()
  //   });
  // })
});
