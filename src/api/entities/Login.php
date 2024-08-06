<?php

class Login
{
	private int $userId;
	private string $email;
	private string $passwordHash;

	/**
	 * Конструктор класса Login.
	 *
	 * @param int $userId ID пользователя.
	 * @param string $passwordHash Хэш пароля пользователя.
	 */
	public function __construct(int $userId, string $email, string $passwordHash)
	{
		$this->userId = $userId;
		$this->email = $email;
		$this->passwordHash = $passwordHash;
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
	 * Получить почту пользователя.
	 *
	 * @return string Почта.
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * Получить хэш пароля пользователя.
	 *
	 * @return string Хэш пароля.
	 */
	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}

	/**
	 * Установить хэш пароля пользователя.
	 *
	 * @param string $password Пароль пользователя.
	 */
	public function setPasswordHash(string $password): void
	{
		$this->passwordHash = password_hash($password, PASSWORD_BCRYPT);
	}

	/**
	 * Проверить пароль пользователя.
	 *
	 * @param string $password Пароль для проверки.
	 * @return bool Возвращает true, если пароль совпадает, иначе false.
	 */
	public function checkPassword(string $password): bool
	{
		return password_verify($password, $this->passwordHash);
	}
}
