<?php

class Record
{
	public int $id;
	public int $domainId;
	public string $name;
	public ?string $content;
	public ?int $priority;
	public ?int $ttl;
	public string $type;
	public string $createdAt;
	public string $updatedAt;

	public function __construct(
		?int $id,  // Сделайте этот параметр nullable
		int $domainId,
		string $name,
		?string $content,
		?int $priority,
		?int $ttl,
		string $type,
		string $createdAt,
		string $updatedAt
	) {
		$this->id = $id ?? 0;  // Установите значение по умолчанию, если null
		$this->domainId = $domainId;
		$this->name = $name;
		$this->content = $content;
		$this->priority = $priority;
		$this->ttl = $ttl;
		$this->type = $type;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
	}
}
