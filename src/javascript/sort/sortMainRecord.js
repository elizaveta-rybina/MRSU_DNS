"use strict";

import Sort from '../class/sort.js';
import Records from '../class/records.js';
const records = new Records();
const sort = new Sort();

const btn = document.getElementsByClassName("sortByTTL")[0];

$(document).ready(function(){
  $(".sortByTTL").click(function(){
    if (btn.classList == "sortByTTL"){
      sort.getDataSort(
        function(response)
        {
            $('#records').html(records.srvHelper(response));

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
            btn.classList.remove("sortByTTL");
            btn.classList.add("sortByTTLDesc");
        }
    );
    }
    else{
      sort.getDataSortDesc(
        function(response)
        {
            $('#records').html(records.srvHelper(response));

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
            btn.classList.remove("sortByTTLDesc");
            btn.classList.add("sortByTTL");
        }
    );
    }
  });
});

