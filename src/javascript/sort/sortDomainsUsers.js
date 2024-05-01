"use strict";

import Sort from '../class/sort.js';
import Users from '../class/users.js';
const users = new Users();
const sort = new Sort();

const btn = document.getElementsByClassName("sortByName")[0];

$(document).ready(function(){
  $(".sortByName").click(function(){
    if (btn.classList == "sortByName"){
      sort.getDataSort(
        function(response)
        {
          $('#domains').html(users.domainHelper(response));
          btn.classList.remove("sortByName");
          btn.classList.add("sortByNameDesc");
        }
    );
    }
    else{
      sort.getDataSortDesc(
        function(response)
        {
          $('#domains').html(users.domainHelper(response));
          btn.classList.remove("sortByNameDesc");
          btn.classList.add("sortByName");
        }
      );
    }
  });
});

