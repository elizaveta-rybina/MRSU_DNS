<?php
require_once "../dataBase.php";

$domainid = $_REQUEST['domainid'];
$ip = $_REQUEST['ip'];

$stmt = $connection->prepare("INSERT INTO `arecord` (`domainid`, `ip`) VALUES (?, ?)");
$stmt->execute(array($domainid, $ip));