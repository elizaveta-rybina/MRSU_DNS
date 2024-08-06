<?php

require_once 'data/DbContext.php';
require_once 'RecordRepositoryInterface.php';
require_once 'entities/Record.php';

/**
 * Репозиторий для работы с записями в базе данных.
 *
 * @implements RecordRepositoryInterface
 */
class RecordRepository implements RecordRepositoryInterface
{
  private $connection;
  private $domainId;

  /**
   * Конструктор класса.
   *
   * @param int $domainId Идентификатор домена, с которым будут работать записи.
   */
  public function __construct(int $domainId)
  {
    $this->connection = DbContext::getConnection();
    $this->domainId = $domainId;
  }

  /**
   * Получить все записи для данного домена и типа (если указан).
   *
   * @param string|null $type Тип записи. Если не указан, будут возвращены записи всех типов.
   * @return Record[] Массив объектов Record.
   */
  public function getAll(?string $type): array
  {
    $query = "SELECT * FROM `records` WHERE `domain_id` = :domainId";
    if ($type) {
      $query .= " AND `type` = :type";
    }
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    if ($type) {
      $stmt->bindValue(":type", $type, PDO::PARAM_STR);
    }
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $records = [];
    foreach ($data as $row) {
      $records[] = $this->mapRowToRecord($row);
    }
    return $records;
  }



  /**
   * Получить запись по идентификатору.
   *
   * @param int $id Идентификатор записи.
   * @return Record|null Объект Record, если запись найдена; иначе null.
   */
  public function get(int $id): ?Record
  {
    $stmt = $this->connection->prepare("SELECT * FROM `records` WHERE `id` = :id AND `domain_id` = :domainId");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();

    $recordData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recordData) {
      return $this->mapRowToRecord($recordData);
    }

    return null;
  }


  /**
   * Удалить запись по идентификатору.
   *
   * @param int $id Идентификатор записи.
   */
  public function delete(int $id): void
  {
    $stmt = $this->connection->prepare("DELETE FROM `records` WHERE `id` = :id AND `domain_id` = :domainId");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  /**
   * Обновить запись.
   *
   * @param Record $current Текущая запись, которую нужно обновить.
   * @param Record $new Новая запись с обновленными данными.
   */
  public function update(Record $current, Record $new): void
  {
    $stmt = $this->connection->prepare("UPDATE `records` SET `name` = :name, `content` = :content, `priority` = :priority, `ttl` = :ttl, `type` = :type, `updated_at` = :updatedAt WHERE `id` = :id AND `domain_id` = :domainId");
    $stmt->bindValue(":name", $new->getName(), PDO::PARAM_STR);
    $stmt->bindValue(":content", $new->getContent(), PDO::PARAM_STR);
    $stmt->bindValue(":priority", $new->getPriority(), PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $new->getTTL(), PDO::PARAM_INT);
    $stmt->bindValue(":type", $new->getType(), PDO::PARAM_STR);
    $stmt->bindValue(":updatedAt", $new->getUpdatedAt(), PDO::PARAM_STR);
    $stmt->bindValue(":id", $current->getId(), PDO::PARAM_INT);
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  /**
   * Добавить новую запись.
   *
   * @param Record $record Запись для добавления.
   */
  public function add(Record $record): void
  {
    $stmt = $this->connection->prepare("INSERT INTO `records` (`domain_id`, `name`, `content`, `priority`, `ttl`, `type`, `created_at`, `updated_at`) VALUES (:domainId, :name, :content, :priority, :ttl, :type, :createdAt, :updatedAt)");
    $stmt->bindValue(":domainId", $this->domainId, PDO::PARAM_INT);
    $stmt->bindValue(":name", $record->getName(), PDO::PARAM_STR);
    $stmt->bindValue(":content", $record->getContent(), PDO::PARAM_STR);
    $stmt->bindValue(":priority", $record->getPriority(), PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $record->getTTL(), PDO::PARAM_INT);
    $stmt->bindValue(":type", $record->getType(), PDO::PARAM_STR);
    $stmt->bindValue(":createdAt", $record->getCreatedAt(), PDO::PARAM_STR);
    $stmt->bindValue(":updatedAt", $record->getUpdatedAt(), PDO::PARAM_STR);
    $stmt->execute();
  }

  /**
   * Преобразовать строку из базы данных в объект Record.
   *
   * @param array $row Ассоциативный массив, содержащий данные записи из базы данных.
   * @return Record Объект Record, созданный на основе данных.
   */
  private function mapRowToRecord(array $row): Record
  {
    return new Record(
      isset($row['id']) ? (int)$row['id'] : null,
      (int)$row['domain_id'],
      $row['name'],
      $row['content'],
      $row['priority'] !== null ? (int)$row['priority'] : null,
      $row['ttl'] !== null ? (int)$row['ttl'] : null,
      $row['type'],
      $row['created_at'],
      $row['updated_at']
    );
  }
}
