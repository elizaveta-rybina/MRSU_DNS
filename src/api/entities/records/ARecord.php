<?php

class ARecord
{
	public $id;
	public $domainId;
	public $ip;
	public $ttl;

	public function __construct($id, $domainId, $ip, $ttl)
	{
		$this->id = $id;
		$this->domainId = $domainId;
		$this->ip = $ip;
		$this->ttl = $ttl;
	}
}
