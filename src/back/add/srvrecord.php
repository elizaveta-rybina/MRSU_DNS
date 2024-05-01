<?php
require_once "../dataBase.php";

$domainid = $_REQUEST['domainid'];
$name = $_REQUEST['name'];
$priority = $_REQUEST['priority'];
$weight = $_REQUEST['weight'];
$port = $_REQUEST['port'];
$host = $_REQUEST['host'];
$TTL = $_REQUEST['TTL'];

$stmt = $connection->prepare("INSERT INTO `srvrecord`(`domainid`, `name`, `priority`, `weight`, `port`, `host`, `TTL`) VALUES(?, ?, ?, ?, ?, ?, ?)");
$stmt->execute(array($domainid, $name, $priority, $weight, $port, $host, $TTL));
