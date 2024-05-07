<?php

require_once 'data/DbContext.php';
require_once 'ARecordRepositoryInterface.php';

/*
  TODO:
  1. Можно ли будет поменять id домена у записи?
  2. Сделать нормальный ttl
*/

class ARecordRepository implements ARecordRepositoryInterface
{

  private $domainId;

  public function __construct(int $domainId)
  {
    $this->domainId = $domainId;
  }

  //Method for getting all records type A
  public function getAll(string $orderby = "id DESC"): array
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `arecords` WHERE `domainId` = :domainId ORDER BY $orderby");
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
    $data = [];
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $data;
  }

  //Method for one getting record by id
  public function get(int $id): ?ARecord
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `arecord` WHERE `id` = :id AND `domainId` = :domainId");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data ? new ARecord($data['id'], $data['ip'], $data['domainId'], 3600) : null;
  }

  //Method for delete record by id
  public function delete(int $id): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("DELETE FROM `arecord` WHERE `id` = :id AND `domainId` = :domainId");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  //Method for update record by ip and id
  public function update(ARecord $current, ARecord $new): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("UPDATE `arecord` SET `ip` = :ip WHERE `id` = :id AND `domainId` = :domainId");
    $stmt->bindValue(":ip", $new["ip"] ?? $current["ip"], PDO::PARAM_STR);
    $stmt->bindParam(':id', $current["id"], PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  //Method for add domain by name
  public function add(ARecord $data): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("INSERT INTO `arecord` (`ip`, `domainId`) VALUES (:ip, :domainId, 3600)");
    $stmt->bindValue(":ip", $data["name"], PDO::PARAM_STR);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }
}
