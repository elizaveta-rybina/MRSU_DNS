<?php
require_once "../dataBase.php";

$stmt = $connection->prepare("SELECT * FROM `users` ORDER BY id");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($records);



//