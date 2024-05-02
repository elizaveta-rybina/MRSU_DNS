<?php

require_once 'helpers/DbConnection.php';

class DomainController
{
  // Array of database queries
  private $queries = [
    'getAllDomains' => "SELECT * FROM `domains` ORDER BY ?",
    'getDomainById' => "SELECT * FROM `domains` WHERE `id` = ?",
  ];

  // General method for performing database queries
  // private function executeQuery($queryKey, $params = []): array
  // {
  //   try {
  //     $connection = DbConnection::getConnection();
  //     $stmt = $connection->prepare($this->queries[$queryKey]);
  //     $stmt->execute($params);
  //     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //     if ($result) {
  //       return $result;
  //     } else {
  //       http_response_code(404);
  //       $error = [
  //         'status' => false,
  //         'message' => 'Not found'
  //       ];
  //       return $error;
  //     }
  //   } catch (PDOException $e) {
  //     return ['error' => 'Database connection error'];
  //   }
  // }

  //Method for getting all domains
  public function getAll($orderby = "id DESC"): array
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `domains` ORDER BY $orderby");
    $stmt->execute(); // Выполнение запроса
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
    }
    return $data;
  }

  //Method for getting domain by id
  public function get(int $id): ?array
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("SELECT * FROM `domains` WHERE `id` = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  //Method for delete domain by id
  public function delete(int $id): int
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("DELETE FROM `domains` WHERE `id` = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  //Method for update domain by name and id
  public function update(array $current, array $new): int
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("UPDATE `domains` SET `name` = :name WHERE `id` = :id");
    $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
    $stmt->bindParam(':id', $current["id"], PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  //Method for add domain by name
  public function add(array $data): int
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("INSERT INTO `domains` (`name`) VALUES (:name)");
    $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
    $stmt->execute();
    return $connection->lastInsertId();
  }
}
