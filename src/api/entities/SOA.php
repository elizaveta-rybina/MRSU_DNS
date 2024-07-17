<?php
class SOA
{
  public $primary_ns;
  public $admin_email;
  public $serial;
  public $refresh;
  public $retry;
  public $expire;
  public $ttl;

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
}
