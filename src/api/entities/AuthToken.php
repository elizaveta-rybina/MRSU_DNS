<?php

class AuthToken
{
	private int $id;
	private string $token;
	private DateTime $expired;
	private int $userId;

	public function __construct(int $id, string $token, DateTime $expired, int $userId)
	{
		$this->id = $id;
		$this->token = $token;
		$this->expired = $expired;
		$this->userId = $userId;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getToken(): string
	{
		return $this->token;
	}

	public function getExpired(): DateTime
	{
		return $this->expired;
	}

	public function getUserId(): int
	{
		return $this->userId;
	}
}
