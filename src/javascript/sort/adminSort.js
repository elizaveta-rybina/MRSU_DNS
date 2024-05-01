"use strict";

import Sort from '../class/sort.js';
import Domains from '../class/domains.js';
const domain = new Domains();
const sort = new Sort();

const btn = document.getElementsByClassName("sortByName")[0];

$(document).ready(function(){
  $(".sortByName").click(function(){
    if (btn.classList == "sortByName"){
      sort.getDomainSort(
        function(response)
        {
          $('#domains').html(domain.domainHelper(response));
          btn.classList.remove("sortByName");
          btn.classList.add("sortByNameDesc");
        }
    );
    }
    else{
      sort.getDomainSortDesc(
        function(response)
        {
          $('#domains').html(domain.domainHelper(response));
          btn.classList.remove("sortByNameDesc");
          btn.classList.add("sortByName");
        }
      );
    }
  });
});

