<?php

class SRVRecord
{
	public $id;
	public $domainId;
	public $name;
	public $ttl;
	public $priority;
	public $weight;
	public $port;

	public function __construct($id, $domainId, $name, $ttl, $priority, $weight, $port)
	{
		$this->id = $id;
		$this->domainId = $domainId;
		$this->name = $name;
		$this->ttl = $ttl;
		$this->priority = $priority;
		$this->weight = $weight;
		$this->port = $port;
	}
}
