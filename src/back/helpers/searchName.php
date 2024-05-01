<?php

require_once "../dataBase.php";

try {
  $inputValue = $_REQUEST['inputValue'];

  $stmt = $connection->prepare("SELECT * FROM `zone` WHERE `name` = :inputValue");
  $stmt->bindParam(':inputValue', $inputValue);
  $stmt->execute();

  // Подсчет количества найденных строк
  $rowCount = $stmt->rowCount();

  // Проверка результата запроса
  if ($rowCount > 0) {
    $already_exist['Message'] = "Success";
		die(print(json_encode($already_exist)));
  } else {
    $already_exist['Message'] = "Error";
		die(print(json_encode($already_exist)));
  }
} catch(PDOException $e) {
  echo "Ошибка: " . $e->getMessage();
}
?>
