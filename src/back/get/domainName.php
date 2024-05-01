<?php
require_once "../dataBase.php";
$id = $_REQUEST['id'];

$stmt = $connection->prepare("SELECT `name` FROM `domains` WHERE `id` = ?");
$stmt->execute(array($id));
$records = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($records);
