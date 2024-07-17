<?php
require_once 'data/DbContext.php';
require_once 'entities/Domain.php';
require_once 'DomainRepositoryInterface.php';
require_once 'entities/SOA.php'; // Подключаем класс SOA

class DomainRepository implements DomainRepositoryInterface
{
  // Method for getting all domains
  public function getAll(string $orderby = "id"): array
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM domains ORDER BY $orderby");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $domains = [];
    foreach ($data as $row) {
      $soa = new SOA(
        $row['primary_ns'],
        $row['admin_email'],
        $row['serial'],
        $row['refresh'],
        $row['retry'],
        $row['expire'],
        $row['ttl']
      );

      $domain = new Domain(
        $row['id'],
        $row['name'],
        $soa,
        $row['created'],
        $row['updated'],
        $row['expires']
      );

      $domains[] = $domain;
    }

    return $domains;
  }

  // Method for getting domain by id
  public function get(int $id): ?Domain
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM domains WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
      return null;
    }

    $soa = new SOA(
      $data['primary_ns'],
      $data['admin_email'],
      $data['serial'],
      $data['refresh'],
      $data['retry'],
      $data['expire'],
      $data['ttl']
    );

    return new Domain(
      $data['id'],
      $data['name'],
      $soa,
      $data['created'],
      $data['updated'],
      $data['expires']
    );
  }

  // Method for delete domain by id
  public function delete(int $id): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("DELETE FROM domains WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
  }

  // Method for update domain by name and id
  public function update(Domain $current, Domain $new): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("UPDATE domains SET name = :name WHERE id = :id");
    $stmt->bindValue(":name", $new->name, PDO::PARAM_STR);
    $stmt->bindValue(':id', $current->id, PDO::PARAM_INT);
    $stmt->execute();
  }

  // Method for add domain
  public function add(Domain $data): void
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("INSERT INTO domains (name, primary_ns, admin_email, serial, refresh, retry, expire, ttl, created, updated, expires) VALUES (:name, :primary_ns, :admin_email, :serial, :refresh, :retry, :expire, :ttl, :created, :updated, :expires)");
    $stmt->bindValue(":name", $data->name, PDO::PARAM_STR);
    $stmt->bindValue(":primary_ns", $data->soa->primary_ns, PDO::PARAM_STR);
    $stmt->bindValue(":admin_email", $data->soa->admin_email, PDO::PARAM_STR);
    $stmt->bindValue(":serial", $data->soa->serial, PDO::PARAM_INT);
    $stmt->bindValue(":refresh", $data->soa->refresh, PDO::PARAM_INT);
    $stmt->bindValue(":retry", $data->soa->retry, PDO::PARAM_INT);
    $stmt->bindValue(":expire", $data->soa->expire, PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $data->soa->ttl, PDO::PARAM_INT);
    $stmt->bindValue(":created", $data->created, PDO::PARAM_STR);
    $stmt->bindValue(":updated", $data->updated, PDO::PARAM_STR);
    $stmt->bindValue(":expires", $data->expires, PDO::PARAM_STR);
    $stmt->execute();
  }
}
