<?php

/**
 * Интерфейс для работы с токенами аутентификации.
 */
interface AuthRepositoryInterface
{
	/**
	 * Сохраняет токен аутентификации в базе данных.
	 *
	 * @param string $token Токен аутентификации.
	 * @param string $expiry Время истечения срока действия токена в формате 'Y-m-d H:i:s'.
	 * @param int $userId Идентификатор пользователя, которому принадлежит токен.
	 * @return void
	 */
	public function saveToken(string $token, string $expiry, int $userId): void;

	/**
	 * Проверяет валидность токена.
	 * Если токен действителен, возвращает идентификатор пользователя.
	 *
	 * @param string $token Токен аутентификации.
	 * @return int|null Идентификатор пользователя, если токен действителен, иначе null.
	 */
	public function validateToken(string $token): ?int;

	/**
	 * Удаляет истекшие токены из базы данных.
	 *
	 * @return void
	 */
	public function deleteExpiredTokens(): void;
}
