<?php

class UserDomain
{
	private int $id;
	private int $userId;
	private int $domainId;

	/**
	 * Конструктор класса UserDomain.
	 *
	 * @param int $id ID записи связи.
	 * @param int $userId ID пользователя.
	 * @param int $domainId ID домена.
	 */
	public function __construct(int $id, int $userId, int $domainId)
	{
		$this->id = $id;
		$this->userId = $userId;
		$this->domainId = $domainId;
	}

	/**
	 * Получить ID записи связи.
	 *
	 * @return int ID записи связи.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Установить ID записи связи.
	 *
	 * @param int $id ID записи связи.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * Получить ID пользователя.
	 *
	 * @return int ID пользователя.
	 */
	public function getUserId(): int
	{
		return $this->userId;
	}

	/**
	 * Установить ID пользователя.
	 *
	 * @param int $userId ID пользователя.
	 */
	public function setUserId(int $userId): void
	{
		$this->userId = $userId;
	}

	/**
	 * Получить ID домена.
	 *
	 * @return int ID домена.
	 */
	public function getDomainId(): int
	{
		return $this->domainId;
	}

	/**
	 * Установить ID домена.
	 *
	 * @param int $domainId ID домена.
	 */
	public function setDomainId(int $domainId): void
	{
		$this->domainId = $domainId;
	}
}
