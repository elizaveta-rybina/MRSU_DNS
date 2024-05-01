<?php
require_once "../dataBase.php";

$stmt = $connection->prepare("SELECT * FROM `zone` ORDER BY id DESC");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($records);
