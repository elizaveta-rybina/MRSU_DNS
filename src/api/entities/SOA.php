<?php

class SOA
{
  private $primary_ns;
  private $admin_email;
  private $serial;
  private $refresh;
  private $retry;
  private $expire;
  private $ttl;

  public function __construct($primary_ns, $admin_email, $serial, $refresh, $retry, $expire, $ttl)
  {
    $this->primary_ns = $primary_ns;
    $this->admin_email = $admin_email;
    $this->serial = $serial;
    $this->refresh = $refresh;
    $this->retry = $retry;
    $this->expire = $expire;
    $this->ttl = $ttl;
  }

  // Геттеры
  public function getPrimaryNS()
  {
    return $this->primary_ns;
  }
  public function getAdminEmail()
  {
    return $this->admin_email;
  }
  public function getSerial()
  {
    return $this->serial;
  }
  public function getRefresh()
  {
    return $this->refresh;
  }
  public function getRetry()
  {
    return $this->retry;
  }
  public function getExpire()
  {
    return $this->expire;
  }
  public function getTTL()
  {
    return $this->ttl;
  }
}
