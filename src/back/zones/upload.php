<?php

require_once("../dataBase.php");

error_reporting(E_ERROR | E_PARSE);

$filename = basename($_FILES["inpFile"]["name"]);
$targetPath = "/var/www/html/data/" . $filename;
move_uploaded_file($_FILES["inpFile"]["tmp_name"], $targetPath);

$domain = str_replace(".hosts", "", $filename);

$out = "";
exec("/usr/bin/named-checkzone $domain $targetPath", $out);

function checkForOK($out) {
  $arrayLength = count($out);
  for ($i = 0; $i < $arrayLength; $i++) {
    if($out[$i] == "OK"){
      return true;
    }
  }
  return false;
}

$output = checkForOK($out);

if ($output == true){
  //TODO: на будущее, сделать нормальное разделение SOA записи
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
}else{
  echo json_encode($out);
}

// function getSerialStr($item)
// {
//   $num = strval($item["serial"]);
//   if (strlen($num) < 2) $num = '0' . $num;
//   return date('dmY') . $num;
// }

// function checkZone($domain)
// {
//   // $out = "";
//   // exec("/usr/bin/named-checkzone $domain /var/www/html/data/2man.site.hosts", $out);
//   // return print_r($out, true);

//   global $connection;
//   $filename = '/tmp/' . $domain . '.hosts';
//   $stmt = $connection->prepare("SELECT id, ttl, timeout, soa, admin, serial, refresh, retry, expire, minimum FROM `zone` WHERE name=?");
//   $stmt->bindParam(1, $domain, PDO::PARAM_STR);
//   $stmt->execute();
//   if ($zone = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     $str = "\$TTL " . $zone["ttl"] . "\r\n";
//     $str .= $domain . ".\t" . $zone["timeout"] . "\tIN\tSOA\tns1.mrsu.ru.\t" . $zone["admin"] . "\t(\r\n";
//     $str .= "\t\t\t" . $zone["serial"] . "; serial\r\n";
//     $str .= "\t\t\t" . $zone["refresh"] . "; refresh\r\n";
//     $str .= "\t\t\t" . $zone["retry"] . "; retry\r\n";
//     $str .= "\t\t\t" . $zone["expire"] . "; expire\r\n";
//     $str .= "\t\t\t" . $zone["minimum"] . "; minimum\r\n)\r\n";
//   }
//   $stmt = $connection->prepare("SELECT name, type, content, priority, ttl FROM zone_item WHERE zone_id=?");
//   $stmt->bindParam(1, $zone["id"], PDO::PARAM_INT);
//   $stmt->execute();
//   $str .= "\r\n";
//   while($item = $stmt->fetch((PDO::FETCH_ASSOC))){
//     if ($item["name"] != "SOA"){
//       $str .= $item["name"] . "\t" . $item["type"] . "\t" . $item["content"] . "\t" . $item["priority"] . "\t" . $item["ttl"] . "\r\n";
//     }
//   }
//   return $str;
//   file_put_contents($filename, "\$TTL " . $item["ttl"] . "\r\n");
//   file_put_contents($filename, $item["name"] . ".\t" . $item["timeout"] . "\tIN\tSOA\tns1.mrsu.ru.\t" . $item["admin"] . "\t(\r\n", FILE_APPEND);
//   file_put_contents($filename, "\t\t\t" . $item->getSerialStr() . " ;serial\r\n", FILE_APPEND);
//   file_put_contents($filename, "\t\t\t" . $item["refresh"] . " ;refresh\r\n", FILE_APPEND);
//   file_put_contents($filename, "\t\t\t" . $item["retry"] . " ;retry\r\n", FILE_APPEND);
//   file_put_contents($filename, "\t\t\t" . $item["expire"] . " ;expire\r\n", FILE_APPEND);
//   file_put_contents($filename, "\t\t\t" . $item["minimum"] . " ;minimum\r\n)\r\n", FILE_APPEND);
//   return false;
// }

// function updateSerial($item)
// {
//   date('dmY', $item["updated_at"]) == date('dmY') ? $item["serial"]++ : $item["serial"] = 1;
//   if ($item->checkZone()) {
//     $item->save();
//   }
// }

// function zoneItem($item)
// {
//   if ($item->validate()) {
//     $item->save(false);
//     return true;
//   }

//   return false;
// }

// function assign($model)
// {
//   $item["name"] = $model->name;
//   $item["priority"] = $model->priority;
//   $item["ttl"] = $model->ttl;
//   $item["type"] = $model->type;
//   $item["content"] = $model->content;
// }

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
