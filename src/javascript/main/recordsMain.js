'use strict'

import Users from '../class/users.js'
const userClass = new Users();
import Records from '../class/records.js'
const records = new Records();

$(document).ready(function() {
  //ARecord
	records.getRecord(
		function(response)
		{
			$('#records').append(userClass.aHelper(response));
		},
  'arecord');

  //MXRecord
  records.getRecord(
		function(response)
		{
			$('#records').append(userClass.mxHelper(response));
		},
  'mxrecord');

  //SRVRecord
  records.getRecord(
		function(response)
		{
			$('#records').append(userClass.srvHelper(response));
		},
  'srvrecord');

  //TXTRecord
  records.getRecord(
		function(response)
		{
			$('#records').append(userClass.txtHelper(response));
		},
  'txtrecord');

  //A4Record
  records.getRecord(
		function(response)
		{
			$('#records').append(userClass.a4Helper(response));
		},
  'a4record');

  //CNAMERecord
  records.getRecord(
		function(response)
		{
			$('#records').append(userClass.cnameHelper(response));
		},
  'cnamerecord');

});
