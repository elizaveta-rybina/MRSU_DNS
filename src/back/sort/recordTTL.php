<?php
require_once "../dataBase.php";

$domainid = $_REQUEST['domainid'];

$stmt = $connection->prepare("SELECT * FROM `srvrecord` WHERE domainid = ? ORDER BY TTL");
$stmt->execute(array($domainid));
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($records);
