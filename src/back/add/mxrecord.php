<?php
require_once "../dataBase.php";

$domainid = $_REQUEST['domainid'];
$priority = $_REQUEST['priority'];
$value = $_REQUEST['value'];

$stmt = $connection->prepare("INSERT INTO `MXRecord` (`domainid`, `priority`,`value`) VALUES (?, ?, ?)");
$data = array($domainid, $priority, $value);
$stmt->execute($data);
