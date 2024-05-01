<?php
require_once "../dataBase.php";
$id = $_REQUEST['id'];

$stmt = $connection->prepare("DELETE FROM `domains` WHERE id = $id");
$stmt->execute();
