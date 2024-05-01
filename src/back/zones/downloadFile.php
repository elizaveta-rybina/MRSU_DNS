<?php

$inputValue = $_REQUEST['inputValue'];

$filename = $inputValue . ".hosts";

$dataFilePath = "/var/www/html/data/" . $filename;

if (file_exists($dataFilePath)) {
  // Отправка файла на скачивание
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . basename($dataFilePath) . '"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($dataFilePath));
  readfile($dataFilePath);
  exit;
} else {
    $already_exist['Message'] = "Error";
		die(print(json_encode($already_exist)));
}
?>
