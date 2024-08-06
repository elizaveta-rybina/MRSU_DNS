<?php

require_once 'data/DbContext.php';
require_once 'entities/Login.php';
require_once 'LoginRepositoryInterface.php';

class LoginRepository implements LoginRepositoryInterface
{
	private PDO $connection;

	/**
	 * Конструктор класса.
	 * Инициализирует соединение с базой данных.
	 */
	public function __construct()
	{
		$this->connection = DbContext::getConnection();
	}

	/**
	 * Получить запись логина по ID пользователя.
	 *
	 * @param int $userId ID пользователя.
	 * @return Login|null Объект Login, если найден, иначе null.
	 */
	public function getByUserId(int $userId): ?Login
	{
		$stmt = $this->connection->prepare("SELECT * FROM login WHERE user_id = :user_id");
		$stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($data) {
			return new Login($data['user_id'], $data['email'], $data['password']);
		}

		return null;
	}

	/**
	 * Обновить запись логина.
	 *
	 * @param Login $login Объект Login для обновления.
	 */
	public function update(Login $login): void
	{
		$stmt = $this->connection->prepare("UPDATE login SET password = :password WHERE user_id = :user_id");
		$stmt->bindValue(":password", $login->getPasswordHash(), PDO::PARAM_STR);
		$stmt->bindValue(":user_id", $login->getUserId(), PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Создать новую запись логина.
	 *
	 * @param Login $login Объект Login для создания.
	 */
	public function create(Login $login): void
	{
		$stmt = $this->connection->prepare(
			"INSERT INTO login (user_id, email, password) VALUES (:user_id, :email, :password)"
		);

		$stmt->bindValue(":user_id", $login->getUserId(), PDO::PARAM_INT);
		$stmt->bindValue(":email", $login->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":password", $login->getPasswordHash(), PDO::PARAM_STR);

		$stmt->execute();
	}


	/**
	 * Удалить запись логина по ID пользователя.
	 *
	 * @param int $userId ID пользователя.
	 */
	public function deleteByUserId(int $userId): void
	{
		$stmt = $this->connection->prepare("DELETE FROM login WHERE user_id = :user_id");
		$stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$stmt->execute();
	}
}
