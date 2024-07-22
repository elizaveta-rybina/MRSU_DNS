<?php

class Record
{
	private $id;
	private $domainId;
	private $name;
	private $content;
	private $priority;
	private $ttl;
	private $type;
	private $createdAt;
	private $updatedAt;

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

	// Геттеры
	public function getId(): int
	{
		return $this->id;
	}

	public function getDomainId(): int
	{
		return $this->domainId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function getPriority(): ?int
	{
		return $this->priority;
	}

	public function getTTL(): ?int
	{
		return $this->ttl;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getCreatedAt(): string
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): string
	{
		return $this->updatedAt;
	}

	// Сеттеры
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function setDomainId(int $domainId): void
	{
		$this->domainId = $domainId;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setContent(?string $content): void
	{
		$this->content = $content;
	}

	public function setPriority(?int $priority): void
	{
		$this->priority = $priority;
	}

	public function setTTL(?int $ttl): void
	{
		$this->ttl = $ttl;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function setCreatedAt(string $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function setUpdatedAt(string $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}
}
