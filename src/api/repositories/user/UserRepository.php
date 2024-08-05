<?php

require_once 'data/DbContext.php';
require_once 'UserRepositoryInterface.php';
require_once 'entities/User.php';
require_once 'entities/UserRole.php';

class UserRepository implements UserRepositoryInterface
{
	private $connection;

	/**
	 * Конструктор класса.
	 * Инициализирует соединение с базой данных.
	 */
	public function __construct()
	{
		$this->connection = DbContext::getConnection();
	}

	/**
	 * Получает всех пользователей из базы данных.
	 *
	 * @return User[] Массив объектов User.
	 */
	public function getAll(): array
	{
		$stmt = $this->connection->prepare("SELECT * FROM users");
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$users = [];
		foreach ($data as $row) {
			$users[] = $this->mapRowToUser($row);
		}
		return $users;
	}

	/**
	 * Получает пользователя по его ID.
	 *
	 * @param int $id ID пользователя.
	 * @return User|null Объект User, если пользователь найден, иначе null.
	 */
	public function get(int $id): ?User
	{
		$stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();

		$userData = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($userData) {
			return $this->mapRowToUser($userData);
		}

		return null;
	}

	/**
	 * Удаляет пользователя по его ID.
	 *
	 * @param int $id ID пользователя.
	 */
	public function delete(int $id): void
	{
		$stmt = $this->connection->prepare("DELETE FROM users WHERE id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Обновляет данные пользователя.
	 *
	 * @param User $user Объект User с обновленными данными.
	 */
	public function update(User $user): void
	{
		$stmt = $this->connection->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role, created_at = :created_at, last_login = :last_login, status = :status, password_hash = :password_hash WHERE id = :id");
		$stmt->bindValue(":first_name", $user->getFirstName(), PDO::PARAM_STR);
		$stmt->bindValue(":last_name", $user->getLastName(), PDO::PARAM_STR);
		$stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":role", $user->getRole()->value, PDO::PARAM_STR); // Используем значение роли
		$stmt->bindValue(":created_at", $user->getCreatedAt(), PDO::PARAM_STR);
		$stmt->bindValue(":last_login", $user->getLastLogin(), PDO::PARAM_STR);
		$stmt->bindValue(":status", $user->isStatus(), PDO::PARAM_BOOL);
		$stmt->bindValue(":password_hash", $user->getPasswordHash(), PDO::PARAM_STR);
		$stmt->bindValue(":id", $user->getId(), PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Добавляет нового пользователя в базу данных.
	 *
	 * @param User $user Объект User с данными для добавления.
	 */
	public function add(User $user): void
	{
		$stmt = $this->connection->prepare("INSERT INTO users (first_name, last_name, email, role, created_at, last_login, status, password_hash) VALUES (:first_name, :last_name, :email, :role, :created_at, :last_login, :status, :password_hash)");
		$stmt->bindValue(":first_name", $user->getFirstName(), PDO::PARAM_STR);
		$stmt->bindValue(":last_name", $user->getLastName(), PDO::PARAM_STR);
		$stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":role", $user->getRole()->value, PDO::PARAM_STR); // Используем значение роли
		$stmt->bindValue(":created_at", $user->getCreatedAt(), PDO::PARAM_STR);
		$stmt->bindValue(":last_login", $user->getLastLogin(), PDO::PARAM_STR);
		$stmt->bindValue(":status", $user->isStatus(), PDO::PARAM_BOOL);
		$stmt->bindValue(":password_hash", $user->getPasswordHash(), PDO::PARAM_STR);
		$stmt->execute();
	}

	/**
	 * Ищет пользователей по адресу электронной почты.
	 *
	 * @param string $email Адрес электронной почты пользователя.
	 * @return User[] Массив объектов User, соответствующих заданному email.
	 */
	public function findByEmail(string $email): array
	{
		$stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
		$stmt->bindValue(":email", $email, PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$users = [];
		foreach ($data as $row) {
			$users[] = $this->mapRowToUser($row);
		}
		return $users;
	}

	/**
	 * Ищет пользователей по статусу.
	 *
	 * @param bool $status Статус пользователя (активен/неактивен).
	 * @return User[] Массив объектов User, соответствующих заданному статусу.
	 */
	public function findByStatus(bool $status): array
	{
		$stmt = $this->connection->prepare("SELECT * FROM users WHERE status = :status");
		$stmt->bindValue(":status", $status, PDO::PARAM_BOOL);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$users = [];
		foreach ($data as $row) {
			$users[] = $this->mapRowToUser($row);
		}
		return $users;
	}

	/**
	 * Активирует пользователя по его ID.
	 *
	 * @param int $id ID пользователя.
	 */
	public function activate(int $id): void
	{
		$stmt = $this->connection->prepare("UPDATE users SET status = 1 WHERE id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Деактивирует пользователя по его ID.
	 *
	 * @param int $id ID пользователя.
	 */
	public function deactivate(int $id): void
	{
		$stmt = $this->connection->prepare("UPDATE users SET status = 0 WHERE id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Преобразует строку из результата запроса базы данных в объект User.
	 *
	 * @param array $row Массив, содержащий данные пользователя из базы данных.
	 * @return User Объект User, созданный на основе данных.
	 */
	private function mapRowToUser(array $row): User
	{
		return new User(
			(int)$row['id'],
			$row['first_name'],
			$row['last_name'],
			$row['email'],
			UserRole::from($row['role']),
			$row['created_at'],
			$row['last_login'],
			(bool)$row['status'],
			$row['password_hash']
		);
	}
}
