<?php

class SOA implements JsonSerializable
{
  private $primary_ns;
  private $admin_email;
  private $serial;
  private $refresh;
  private $retry;
  private $expire;
  private $ttl;

  /**
   * Конструктор класса SOA.
   *
   * @param string $primary_ns Основной сервер имен (NS).
   * @param string $admin_email Адрес электронной почты администратора.
   * @param int $serial Серийный номер зоны.
   * @param int $refresh Интервал обновления (в секундах).
   * @param int $retry Интервал повторных попыток (в секундах).
   * @param int $expire Время истечения (в секундах).
   * @param int $ttl Время жизни (TTL) (в секундах).
   */
  public function __construct(
    string $primary_ns,
    string $admin_email,
    int $serial,
    int $refresh,
    int $retry,
    int $expire,
    int $ttl
  ) {
    $this->primary_ns = $primary_ns;
    $this->admin_email = $admin_email;
    $this->serial = $serial;
    $this->refresh = $refresh;
    $this->retry = $retry;
    $this->expire = $expire;
    $this->ttl = $ttl;
  }

  public function jsonSerialize(): array
  {
    return [
      'primary_ns' => $this->primary_ns,
      'admin_email' => $this->admin_email,
      'serial' => $this->serial,
      'refresh' => $this->refresh,
      'retry' => $this->retry,
      'expire' => $this->expire,
      'ttl' => $this->ttl,
    ];
  }

  /**
   * Получить основной сервер имен (NS).
   *
   * @return string Основной сервер имен.
   */
  public function getPrimaryNS(): string
  {
    return $this->primary_ns;
  }

  /**
   * Получить адрес электронной почты администратора.
   *
   * @return string Адрес электронной почты.
   */
  public function getAdminEmail(): string
  {
    return $this->admin_email;
  }

  /**
   * Получить серийный номер зоны.
   *
   * @return int Серийный номер зоны.
   */
  public function getSerial(): int
  {
    return $this->serial;
  }

  /**
   * Получить интервал обновления (в секундах).
   *
   * @return int Интервал обновления.
   */
  public function getRefresh(): int
  {
    return $this->refresh;
  }

  /**
   * Получить интервал повторных попыток (в секундах).
   *
   * @return int Интервал повторных попыток.
   */
  public function getRetry(): int
  {
    return $this->retry;
  }

  /**
   * Получить время истечения (в секундах).
   *
   * @return int Время истечения.
   */
  public function getExpire(): int
  {
    return $this->expire;
  }

  /**
   * Получить время жизни (TTL) (в секундах).
   *
   * @return int Время жизни (TTL).
   */
  public function getTTL(): int
  {
    return $this->ttl;
  }
}
