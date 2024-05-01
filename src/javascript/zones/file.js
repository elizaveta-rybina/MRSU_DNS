"use strict";

import Zona from "../class/zona.js";
const zona = new Zona();

$(document).ready(function() {
  const myForm = document.getElementById('addFileZone');
  const inpFile = document.getElementById('inpFile');

  myForm.addEventListener("submit", e => {
    e.preventDefault();
    const endpoint = "../php/zones/upload.php";
    const formData = new FormData();

    formData.append("inpFile", inpFile.files[0]);

    fetch(endpoint, {
      method: "post",
      body: formData
    })
    .then(function(response)
    {
      if (!response.ok) {
          return Promise.reject(new Error(
              'Response failed: ' + response.status + ' (' + response.statusText + ')'
          ));
    }
    return response.json();
    }).then(function(data) {
      console.log(data);
      $('.error').append(zona.zonaErrorHelper(data));
      const errorDiv = document.querySelector('.error-title');
      errorDiv.style.display = 'block';
    }).catch(error => {console.error(error)});
    });
});
