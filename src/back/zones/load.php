<?php

require_once("../dataBase.php");

$filename = basename($_FILES["inpFile"]["name"]);
$targetPath = "/var/www/html/data/" . $filename;
move_uploaded_file($_FILES["inpFile"]["tmp_name"], $targetPath);

$domain = str_replace(".hosts", "", $filename);

$out = "";
exec("/usr/bin/named-checkzone $domain $targetPath", $out);
print_r($out);

$soa = "ns1.mrsu.ru";
$admin = "lysenkov.mrsu.ru";

$stmt = $connection->prepare("INSERT INTO `zone`(`name`, `ttl`, `timeout`, `soa`, `admin`, `serial`, `refresh`, `retry`, `expire`, `minimum`, `created_at`, `updated_at`) VALUES (?,14400,86400,?,?,1,3600,7200,1209600,86400,?,?)");
$stmt->bindParam(1, $domain, PDO::PARAM_STR);
$stmt->bindParam(2, $soa, PDO::PARAM_STR);
$stmt->bindParam(3, $admin, PDO::PARAM_STR);
$stmt->bindParam(4, time(), PDO::PARAM_INT);
$stmt->bindParam(5, time(), PDO::PARAM_INT);
$stmt->execute();

$zone_id = $connection->lastInsertId();

$data = file_get_contents($targetPath);
$lines = explode("\n", $data);
$found = false;

foreach ($lines as $line) {
  if (!$found) {
    $l = str_replace(" ", "", $line);
    $l = str_replace("\t", "", $l);
    if (str_contains($l, ')')) {
      $found = true;
    }
    continue;
  }
  if (strlen($line) == 0) continue;
  if (($line[0] == ';') || ($line[0] == '#') || ($line[0] == '$')) continue;
  $item = loadFromString($line);

  if (count($item) > 0) {

    $stmt = $connection->prepare("INSERT INTO `zone_item`(`zone_id`, `name`, `type`, `content`, `priority`, `ttl`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bindParam(1, $zone_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $item["name"], PDO::PARAM_STR);
    $stmt->bindParam(3, $item["type"], PDO::PARAM_STR);
    $stmt->bindParam(4, $item["content"], PDO::PARAM_STR);
    $stmt->bindParam(5, $item["priority"], PDO::PARAM_INT);
    $stmt->bindParam(6, $item["ttl"], PDO::PARAM_INT);
    $stmt->bindParam(7, time(), PDO::PARAM_INT);
    $stmt->bindParam(8, time(), PDO::PARAM_INT);
    $stmt->execute();
  }
}

function loadFromString($line)
{
  $item = [];
  $item["content"] = "";
  $str = str_replace("\t", " ", $line);
  $str = str_replace("  ", " ", $str);
  $parts = explode(" ", $str);
  $item["name"] = $parts[0];
  $index = 1;
  if (is_numeric($parts[$index])) {
    $item["ttl"] = $parts[$index];
    $index++;
  }
  if ($parts[$index] == "IN") $index++;
  $item["type"] = $parts[$index];
  switch ($item["type"]) {
    case "NS":
    case "A":
    case "CNAME":
      $item["content"] = $parts[$index + 1];
      break;
    case "MX":
      $item["priority"] = $parts[++$index];
      $item["content"] = $parts[++$index];
      while ($index < count($parts) - 1) {
        $item["content"] .= " " . $parts[$index++];
      }
      break;
    case "TXT":
    case "SRV":
      $item["content"] = $parts[++$index];
      while ($index < count($parts)) {
        $item["content"] .= " " . $parts[$index++];
      }
      break;
  }
  return $item;
}
