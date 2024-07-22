<?php

class UserDomain
{
	private int $id;
	private int $userId;
	private int $domainId;

	public function __construct(int $id, int $userId, int $domainId)
	{
		$this->id = $id;
		$this->userId = $userId;
		$this->domainId = $domainId;
	}

	// Getter and Setter for ID
	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	// Getter and Setter for User ID
	public function getUserId(): int
	{
		return $this->userId;
	}

	public function setUserId(int $userId): void
	{
		$this->userId = $userId;
	}

	// Getter and Setter for Domain ID
	public function getDomainId(): int
	{
		return $this->domainId;
	}

	public function setDomainId(int $domainId): void
	{
		$this->domainId = $domainId;
	}
}
