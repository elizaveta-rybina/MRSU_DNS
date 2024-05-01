export default class Admin{
  //Метод для работы с пользователями DOM
  usersHelper(responce) {

    let wrapper = $('<div class="form-wrapper"></div>');

    responce.forEach((row) => {
      wrapper.append(`
        <div class="form-body">
          <div class="name">
            <span>${row.name}</span>
          </div>
          <div class="lastName">
            <span>${row.lastName}</span>
          </div>
          <div class="FatherName">
            <span>${row.FatherName}</span>
          </div>
          <div class="email">
            <span>${row.email}</span>
          </div>
          <div class="login">
            <span>${row.login}</span>
          </div>
        </div>
      `);
    });

    return wrapper;
  }

  //Метод для отображения существующих пользователей
  getUsers(callback) {
    $.ajax({
      type: "GET",
      url: "../php/users/user.php",
      dataType: "html",
      success: function (response) {
        callback(JSON.parse(response));
      },
    });
  };

  //Метод для добавления нового пользователя
  AddUsers(name, lastName, FatherName, email, login, password){
  $.ajax({
    type: 'POST',
    data: { name: name, lastName: lastName, FatherName: FatherName, email: email, login: login, password: password},
    url: '../php/sign/signup.php',
    success: function(response)
    {
      location. reload()
    }
  });
  }
}

