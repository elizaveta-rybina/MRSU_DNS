<?php

class MXRecord
{
	public $id;
	public $domainId;
	public $value;
	public $ttl;

	public function __construct($id, $domainId, $value, $ttl)
	{
		$this->id = $id;
		$this->domainId = $domainId;
		$this->value = $value;
		$this->ttl = $ttl;
	}
}
