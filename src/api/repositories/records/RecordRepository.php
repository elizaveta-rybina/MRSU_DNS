<?php

require_once 'data/DbContext.php';
require_once 'RecordRepositoryInterface.php';
require_once 'entities/Record.php';

class RecordRepository implements RecordRepositoryInterface
{
  private $connection;
  private $domainId;

  public function __construct(int $domainId)
  {
    $this->connection = DbContext::getConnection();
    $this->domainId = $domainId;
  }

  public function getAll(?string $type = null): array
  {
    $query = "SELECT * FROM `records` WHERE `domainId` = :domainId";
    if ($type) {
      $query .= " AND `type` = :type";
    }
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    if ($type) {
      $stmt->bindValue(":type", $type, PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getAllByType(int $domainId, ?string $type = null): array
  {
    $query = "SELECT * FROM records WHERE domainId = :domainId";

    if ($type !== null) {
      $query .= " AND type = :type";
    }

    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":domainId", $domainId, PDO::PARAM_INT);

    if ($type !== null) {
      $stmt->bindValue(":type", $type, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }



  public function get(int $id): ?Record
  {
    $stmt = $this->connection->prepare("SELECT * FROM records WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $recordData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recordData) {
      return new Record(
        (int)$recordData['id'],
        (int)$recordData['domainId'],
        $recordData['name'],
        $recordData['content'],
        $recordData['priority'] !== null ? (int)$recordData['priority'] : null,
        $recordData['ttl'] !== null ? (int)$recordData['ttl'] : null,
        $recordData['type'],
        $recordData['createdAt'],
        $recordData['updatedAt']
      );
    }

    return null;
  }


  public function delete(int $id): void
  {
    $stmt = $this->connection->prepare("DELETE FROM `records` WHERE `id` = :id AND `domainId` = :domainId");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  public function update(Record $current, Record $new): void
  {
    $stmt = $this->connection->prepare("UPDATE `records` SET `name` = :name, `content` = :content, `priority` = :priority, `ttl` = :ttl, `type` = :type, `updatedAt` = :updatedAt WHERE `id` = :id AND `domainId` = :domainId");
    $stmt->bindValue(":name", $new->name, PDO::PARAM_STR);
    $stmt->bindValue(":content", $new->content, PDO::PARAM_STR);
    $stmt->bindValue(":priority", $new->priority, PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $new->ttl, PDO::PARAM_INT);
    $stmt->bindValue(":type", $new->type, PDO::PARAM_STR);
    $stmt->bindValue(":updatedAt", $new->updatedAt, PDO::PARAM_STR);
    $stmt->bindValue(":id", $new->id, PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  public function add(Record $record): void
  {
    $stmt = $this->connection->prepare("INSERT INTO `records` (`domainId`, `name`, `content`, `priority`, `ttl`, `type`, `createdAt`, `updatedAt`) VALUES (:domainId, :name, :content, :priority, :ttl, :type, :createdAt, :updatedAt)");
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->bindValue(":name", $record->name, PDO::PARAM_STR);
    $stmt->bindValue(":content", $record->content, PDO::PARAM_STR);
    $stmt->bindValue(":priority", $record->priority, PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $record->ttl, PDO::PARAM_INT);
    $stmt->bindValue(":type", $record->type, PDO::PARAM_STR);
    $stmt->bindValue(":createdAt", $record->createdAt, PDO::PARAM_STR);
    $stmt->bindValue(":updatedAt", $record->updatedAt, PDO::PARAM_STR);
    $stmt->execute();
  }
}
