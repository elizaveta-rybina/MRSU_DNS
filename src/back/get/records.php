<?php
require_once "../dataBase.php";

$table = $_REQUEST['table'];
$domainid = $_REQUEST['domainid'];

$stmt = $connection->prepare("SELECT * FROM `{$table}` WHERE domainid = ? ORDER BY id DESC");
$stmt->execute(array($domainid));
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($records);