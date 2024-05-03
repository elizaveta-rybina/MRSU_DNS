<?php

require_once 'data/DbContext.php';
require_once 'RecordRepositoryInterface.php';

class RecordRepository implements RecordRepositoryInterface
{
  //Method for getting all domains
  public function getAll(string $orderby = "id DESC"): array
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `domains` ORDER BY $orderby");
    $stmt->execute();
    $data = [];
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //   $data[] = $row;
    // }
    return $data;
  }

  //Method for getting domain by id
  public function get(int $id): ?array
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `domains` WHERE `id` = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  //Method for delete domain by id
  public function delete(int $id): int
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("DELETE FROM `domains` WHERE `id` = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  //Method for update domain by name and id
  public function update(array $current, array $new): int
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("UPDATE `domains` SET `name` = :name WHERE `id` = :id");
    $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
    $stmt->bindParam(':id', $current["id"], PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  //Method for add domain by name
  public function add(array $data): int
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("INSERT INTO `domains` (`name`) VALUES (:name)");
    $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
    $stmt->execute();
    return $connection->lastInsertId();
  }
}
