<?php

class Domain
{
  private int $id;
  private string $name;
  private SOA $soa;
  private string $created;
  private string $updated;
  private string $expires;

  public function __construct(
    int $id,
    string $name,
    SOA $soa,
    string $created,
    string $updated,
    string $expires
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->soa = $soa;
    $this->created = $created;
    $this->updated = $updated;
    $this->expires = $expires;
  }

  // Геттеры
  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getSOA(): SOA
  {
    return $this->soa;
  }

  public function getCreated(): string
  {
    return $this->created;
  }

  public function getUpdated(): string
  {
    return $this->updated;
  }

  public function getExpires(): string
  {
    return $this->expires;
  }
}
