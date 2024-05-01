<?php
require_once "../dataBase.php";

$fetchDomains = $connection->prepare("SELECT * FROM `zone` ORDER BY name DESC");
$fetchDomains->execute() or die(print_r($fetchDomains->errorInfo()));

$response = $fetchDomains->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($response);
