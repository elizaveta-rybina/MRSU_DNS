<?php

require_once 'helpers/DbConnection.php';

class DomainController
{
  // Array of database queries
  private $queries = [
    'getAllDomains' => "SELECT * FROM `domains` ORDER BY ?",
    'getDomainById' => "SELECT * FROM `domains` WHERE `id` = ?",
    //'deleteDomain' => "DELETE FROM `domains` WHERE `id` = ?"
  ];

  // General method for performing database queries
  private function executeQuery($queryKey, $params = []): array
  {
    try {
      $connection = DbConnection::getConnection();
      $stmt = $connection->prepare($this->queries[$queryKey]);
      $stmt->execute($params);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($result) {
        return $result;
      } else {
        http_response_code(404);
        $error = [
          'status' => false,
          'message' => 'Not found'
        ];
        return $error;
      }
    } catch (PDOException $e) {
      return ['error' => 'Database connection error'];
    }
  }

  //Method for getting all domains
  public function getAll($orderby = "id DESC"): void
  {
    $records = $this->executeQuery("getAllDomains", [$orderby]);
    echo json_encode($records);
  }

  //Method for getting domain by id
  public function get(int $id): void
  {
    $records = $this->executeQuery("getDomainById", [$id]);
    echo json_encode($records);
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
  public function update(int $id, string $name): int
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("UPDATE `domains` SET `name` = :domainName WHERE `id` = :domainId");
    $stmt->bindParam(':domainName', $name, PDO::PARAM_STR);
    $stmt->bindParam(':domainId', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  //Method for add domain by name
  public function add(string $name): int
  {
    $connection = DbConnection::getConnection();
    $stmt = $connection->prepare("INSERT INTO `domains` (`name`) VALUES ?");
    $data = array($name);
    $stmt->execute($data);
    return $this->$connection->lastInsertId();
  }
}
