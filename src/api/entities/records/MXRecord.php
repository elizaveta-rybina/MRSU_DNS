<?php

class MXRecord
{
	public $id;
	public $domainId;
	public $host;
	public $priority;
	public $ttl;

	public function __construct($id, $domainId, $host, $priority, $ttl)
	{
		$this->id = $id;
		$this->domainId = $domainId;
		$this->host = $host;
		$this->priority = $priority;
		$this->ttl = $ttl;
	}
}
