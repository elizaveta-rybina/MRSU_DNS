<?php

enum UserRole: string
{
	case READER = 'reader';
	case EDITOR = 'editor';
	case ADMIN = 'admin';
	case SUPER = 'super';
}

class User implements JsonSerializable
{
	private int $id;
	private string $firstName;
	private string $lastName;
	private string $email;
	private UserRole $role;
	private string $createdAt;
	private string $lastLogin;
	private bool $status;

	/**
	 * Конструктор класса User.
	 *
	 * @param int $id ID пользователя.
	 * @param string $firstName Имя пользователя.
	 * @param string $lastName Фамилия пользователя.
	 * @param string $email Электронная почта пользователя.
	 * @param UserRole $role Роль пользователя.
	 * @param string $createdAt Дата и время создания пользователя.
	 * @param string $lastLogin Дата и время последнего входа пользователя.
	 * @param bool $status Статус пользователя (активен или нет).
	 */
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


	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'email' => $this->email,
			'role' => $this->role->value, // Преобразуем enum в строку
			'createdAt' => $this->createdAt,
			'lastLogin' => $this->lastLogin,
			'status' => $this->status,
		];
	}

	/**
	 * Получить ID пользователя.
	 *
	 * @return int ID пользователя.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Получить имя пользователя.
	 *
	 * @return string Имя пользователя.
	 */
	public function getFirstName(): string
	{
		return $this->firstName;
	}

	/**
	 * Установить имя пользователя.
	 *
	 * @param string $firstName Имя пользователя.
	 */
	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	/**
	 * Получить фамилию пользователя.
	 *
	 * @return string Фамилия пользователя.
	 */
	public function getLastName(): string
	{
		return $this->lastName;
	}

	/**
	 * Установить фамилию пользователя.
	 *
	 * @param string $lastName Фамилия пользователя.
	 */
	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	/**
	 * Получить электронную почту пользователя.
	 *
	 * @return string Электронная почта пользователя.
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * Установить электронную почту пользователя.
	 *
	 * @param string $email Электронная почта пользователя.
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/**
	 * Получить роль пользователя.
	 *
	 * @return UserRole Роль пользователя.
	 */
	public function getRole(): UserRole
	{
		return $this->role;
	}

	/**
	 * Установить роль пользователя.
	 *
	 * @param UserRole $role Роль пользователя.
	 */
	public function setRole(UserRole $role): void
	{
		$this->role = $role;
	}

	/**
	 * Получить дату и время создания пользователя.
	 *
	 * @return string Дата и время создания пользователя.
	 */
	public function getCreatedAt(): string
	{
		return $this->createdAt;
	}

	/**
	 * Установить дату и время создания пользователя.
	 *
	 * @param string $createdAt Дата и время создания пользователя.
	 */
	public function setCreatedAt(string $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	/**
	 * Получить дату и время последнего входа пользователя.
	 *
	 * @return string Дата и время последнего входа пользователя.
	 */
	public function getLastLogin(): string
	{
		return $this->lastLogin;
	}

	/**
	 * Установить дату и время последнего входа пользователя.
	 *
	 * @param string $lastLogin Дата и время последнего входа пользователя.
	 */
	public function setLastLogin(string $lastLogin): void
	{
		$this->lastLogin = $lastLogin;
	}

	/**
	 * Проверить, активен ли пользователь.
	 *
	 * @return bool Статус пользователя (активен или нет).
	 */
	public function isStatus(): bool
	{
		return $this->status;
	}

	/**
	 * Установить статус пользователя.
	 *
	 * @param bool $status Статус пользователя (активен или нет).
	 */
	public function setStatus(bool $status): void
	{
		$this->status = $status;
	}

	/**
	 * Получить домены, к которым привязан пользователь.
	 *
	 * @return array Список доменов.
	 */
	public function getDomains(): array
	{
		$userDomainRepo = new UserDomainRepository();
		return $userDomainRepo->getDomainsForUser($this->id);
	}

	/**
	 * Добавить пользователя в домен.
	 *
	 * @param int $domainId ID домена.
	 */
	public function addDomain(int $domainId): void
	{
		$userDomainRepo = new UserDomainRepository();
		$userDomainRepo->addUserToDomain($this->id, $domainId);
	}

	/**
	 * Удалить пользователя из домена.
	 *
	 * @param int $domainId ID домена.
	 */
	public function removeDomain(int $domainId): void
	{
		$userDomainRepo = new UserDomainRepository();
		$userDomainRepo->removeUserFromDomain($this->id, $domainId);
	}
}
