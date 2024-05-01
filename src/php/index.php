<?php

$page = $_GET['q'];
echo $page;

require_once "dataBase.php";

header('Content-type: json/application');

$stmt = $connection->prepare("SELECT * FROM `domains` ORDER BY id DESC");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($records);