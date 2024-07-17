<?php

require_once 'SOA.php';

class Domain
{
  public $id;
  public $name;
  public $soa;
  public $created;
  public $updated;
  public $expires;

  public function __construct($id, $name, SOA $soa, $created, $updated, $expires)
  {
    $this->id = $id;
    $this->name = $name;
    $this->soa = $soa;
    $this->created = $created;
    $this->updated = $updated;
    $this->expires = $expires;
  }
}
