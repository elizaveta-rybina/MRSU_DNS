async function getData() {
  return $.ajax({
    type: "GET",
    url: "../php/get/domains.php",
    dataType: "html",
    success: function (response) {

    },
  });
}

async function main(){
  const postsData = await getData();
  const arr = JSON.parse(postsData);

  var availabelKeywords = [];
  for (var i = 0; i < arr.length; i++) {
    availabelKeywords.push(arr[i].name);
  }

  const resultBox = document.querySelector(".result-box");
  const inputBox = document.getElementById("inputBox");

  inputBox.onkeyup = function(){
    let result = [];
    let input = inputBox.value;
    if(input.length){
      result = availabelKeywords.filter((keywords) => {
        return keywords.toLowerCase().includes(input.toLowerCase());
      });
    }
    display(result);

    if(!result.length){
      resultBox.innerHTML = '';
    }
  }

  function display(result) {
    const ul = document.createElement('ul');

    for (let i = 0; i < result.length; i++) {
        const li = document.createElement('li');
        li.textContent = result[i];
        li.onclick = () => selectInput(result[i]);
        ul.appendChild(li);
    }

    resultBox.innerHTML = '';
    resultBox.appendChild(ul);
  }

  function selectInput(list){
    inputBox.value = list;
    resultBox.innerHTML = '';
  }
}

main();
