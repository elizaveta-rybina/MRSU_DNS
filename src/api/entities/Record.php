<?php

class Record
{
	public $id;
	public $domainId;
	public $name;
	public $content;
	public $priority;
	public $ttl;
	public $type;
	public $createdAt;
	public $updatedAt;

	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}
}

/*
CREATE TABLE `zone_item` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `ttl` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

*/