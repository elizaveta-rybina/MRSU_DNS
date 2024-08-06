<?php

require_once 'data/DbContext.php';
require_once 'entities/AuthToken.php';
require_once 'AuthRepositoryInterface.php';

/**
 * Класс AuthRepository управляет операциями, связанными с аутентификацией и токенами пользователей.
 */
class AuthRepository implements AuthRepositoryInterface
{
	/**
	 * @var PDO Подключение к базе данных.
	 */
	private PDO $connection;

	/**
	 * Конструктор класса.
	 * Устанавливает соединение с базой данных.
	 */
	public function __construct()
	{
		$this->connection = DbContext::getConnection();
	}

	/**
	 * Сохраняет токен аутентификации в базе данных.
	 *
	 * @param string $token Токен аутентификации.
	 * @param string $expiry Время истечения срока действия токена.
	 * @param int $userId Идентификатор пользователя, которому принадлежит токен.
	 */
	public function saveToken(string $token, string $expiry, int $userId): void
	{
		$stmt = $this->connection->prepare("
            INSERT INTO auth (token, expired, user_id) VALUES (:token, :expired, :user_id)
        ");
		$stmt->bindValue(':token', $token, PDO::PARAM_STR);
		$stmt->bindValue(':expired', $expiry, PDO::PARAM_STR);
		$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Проверяет валидность токена.
	 * Если токен действителен, возвращает идентификатор пользователя.
	 *
	 * @param string $token Токен аутентификации.
	 * @return int|null Идентификатор пользователя, если токен действителен, иначе null.
	 */
	public function validateToken(string $token): ?int
	{
		$stmt = $this->connection->prepare("
            SELECT user_id FROM auth WHERE token = :token AND expired > NOW()
        ");
		$stmt->bindValue(':token', $token, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		return $result ? (int)$result['user_id'] : null;
	}

	/**
	 * Удаляет истекшие токены из базы данных.
	 */
	public function deleteExpiredTokens(): void
	{
		// Создаем объект DateTime с Московским временем
		$now = new DateTime('now', new DateTimeZone('Europe/Moscow'));
		// Форматируем текущую дату и время
		$currentDateTime = $now->format('Y-m-d H:i:s');

		// Запрос на удаление истекших токенов
		$sql = "DELETE FROM auth WHERE expired < :currentDateTime";
		$stmt = $this->connection->prepare($sql);
		$stmt->bindParam(':currentDateTime', $currentDateTime);
		$stmt->execute();
	}
}
