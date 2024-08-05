<?php

class Domain implements JsonSerializable
{
  private int $id;
  private string $name;
  private SOA $soa;
  private string $created;
  private string $updated;
  private string $expires;

  /**
   * Конструктор класса Domain.
   *
   * @param int $id ID домена.
   * @param string $name Название домена.
   * @param SOA $soa Запись SOA для домена.
   * @param string $created Дата и время создания домена.
   * @param string $updated Дата и время последнего обновления домена.
   * @param string $expires Дата и время истечения домена.
   */
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

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'soa' => $this->soa,
      'created' => $this->created,
      'updated' => $this->updated,
      'expires' => $this->expires,
    ];
  }

  /**
   * Получить ID домена.
   *
   * @return int ID домена.
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Получить название домена.
   *
   * @return string Название домена.
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Получить запись SOA для домена.
   *
   * @return SOA Запись SOA.
   */
  public function getSOA(): SOA
  {
    return $this->soa;
  }

  /**
   * Получить дату и время создания домена.
   *
   * @return string Дата и время создания домена.
   */
  public function getCreated(): string
  {
    return $this->created;
  }

  /**
   * Получить дату и время последнего обновления домена.
   *
   * @return string Дата и время последнего обновления домена.
   */
  public function getUpdated(): string
  {
    return $this->updated;
  }

  /**
   * Получить дату и время истечения домена.
   *
   * @return string Дата и время истечения домена.
   */
  public function getExpires(): string
  {
    return $this->expires;
  }
}
