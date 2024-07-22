<?php

enum UserRole: string
{
	case READER = 'reader';
	case EDITOR = 'editor';
	case ADMIN = 'admin';
	case SUPER = 'super';
}

class User
{
	private int $id;
	private string $firstName;
	private string $lastName;
	private string $email;
	private UserRole $role;
	private string $createdAt;
	private string $lastLogin;
	private bool $status;

	public function __construct(
		int $id,
		string $firstName,
		string $lastName,
		string $email,
		UserRole $role,
		string $createdAt,
		string $lastLogin,
		bool $status
	) {
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->role = $role;
		$this->createdAt = $createdAt;
		$this->lastLogin = $lastLogin;
		$this->status = $status;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getRole(): UserRole
	{
		return $this->role;
	}

	public function setRole(UserRole $role): void
	{
		$this->role = $role;
	}

	public function getCreatedAt(): string
	{
		return $this->createdAt;
	}

	public function setCreatedAt(string $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getLastLogin(): string
	{
		return $this->lastLogin;
	}

	public function setLastLogin(string $lastLogin): void
	{
		$this->lastLogin = $lastLogin;
	}

	public function isStatus(): bool
	{
		return $this->status;
	}

	public function setStatus(bool $status): void
	{
		$this->status = $status;
	}

	public function getDomains(): array
	{
		$userDomainRepo = new UserDomainRepository();
		return $userDomainRepo->getDomainsForUser($this->id);
	}

	public function addDomain(int $domainId): void
	{
		$userDomainRepo = new UserDomainRepository();
		$userDomainRepo->addUserToDomain($this->id, $domainId);
	}

	public function removeDomain(int $domainId): void
	{
		$userDomainRepo = new UserDomainRepository();
		$userDomainRepo->removeUserFromDomain($this->id, $domainId);
	}
}
