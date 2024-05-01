<?php
require_once "../dataBase.php";
$table = $_REQUEST['table'];
$id = $_REQUEST['id'];

$stmt = $connection->prepare("DELETE FROM $table WHERE id = $id");
$stmt->execute();
