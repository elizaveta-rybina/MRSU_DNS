<?php
require_once "../dataBase.php";

$domainid = $_REQUEST['domainid'];
$text = $_REQUEST['text'];

$stmt = $connection->prepare("INSERT INTO `txtrecord` (`domainid`, `text`) VALUES (?, ?)");
$stmt->execute(array($domainid, $text));
