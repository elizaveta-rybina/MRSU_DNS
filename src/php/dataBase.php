<?php
$host = 'mysql';
$dbname = 'dns';
$charset = 'utf8';
$dbuser = 'root';
$dbpass = 'root';
@$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $dbuser, $dbpass);