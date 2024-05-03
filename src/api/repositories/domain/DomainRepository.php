<?php

require_once 'data/DbContext.php';
require_once 'entities/Domain.php';
require_once 'DomainRepositoryInterface.php';

/*
TODO: 
getAll() -> array of domains
*/


class DomainRepository implements DomainRepositoryInterface
{
  //Method for getting all domains
  public function getAll(string $orderby = "id DESC"): array
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `domains` ORDER BY $orderby");
    $stmt->execute();
    $data = [];
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $data;
  }

  //Method for getting domain by id
  public function get(int $id): ?Domain
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `domains` WHERE `id` = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data ? new Domain($data['id'], $data['name']) : null;
  }

  //Method for delete domain by id
  public function delete(int $id): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("DELETE FROM `domains` WHERE `id` = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
  }

  //Method for update domain by name and id
  public function update(Domain $current, Domain $new): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("UPDATE `domains` SET `name` = :name WHERE `id` = :id");
    $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
    $stmt->bindParam(':id', $current["id"], PDO::PARAM_INT);
    $stmt->execute();
  }

  //Method for add domain by name
  public function add(Domain $data): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("INSERT INTO `domains` (`name`) VALUES (:name)");
    $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
    $stmt->execute();
  }
}
