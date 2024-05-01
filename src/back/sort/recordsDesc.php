<?php
require_once "../dataBase.php";

$domainid = $_REQUEST['domainid'];

$stmt = $connection->prepare("SELECT * FROM `srcvrecord` WHERE domainid = ? ORDER BY TTL DESC");
$stmt->execute(array($domainid));
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($records);
