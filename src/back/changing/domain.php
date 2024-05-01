<?php
require_once "../dataBase.php";
$domain_name = $_REQUEST['domain_name'];
$domain_id = $_REQUEST['domain_id'];

$stmt = $connection->prepare("UPDATE `domains` SET `name` = :domainName WHERE `id` = :domainId");
$stmt->bindParam(':domainName', $domain_name, PDO::PARAM_STR);
$stmt->bindParam(':domainId', $domain_id, PDO::PARAM_INT);
$stmt->execute();
