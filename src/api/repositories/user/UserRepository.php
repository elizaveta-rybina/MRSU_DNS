<?php
require_once 'data/DbContext.php';
require_once 'UserRepositoryInterface.php';
require_once 'entities/User.php';

/**
 * Класс UserRepository предоставляет методы для работы с пользователями в базе данных.
 */
class UserRepository implements UserRepositoryInterface
{
	/**
	 * @var PDO Соединение с базой данных.
	 */
	private PDO $connection;

	/**
	 * Конструктор класса.
	 *
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
	 * Получает пользователя по его идентификатору.
	 *
	 * @param int $id Идентификатор пользователя.
	 * @return User|null Объект User или null, если пользователь не найден.
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
	 * Удаляет пользователя по его идентификатору.
	 *
	 * @param int $id Идентификатор пользователя.
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
		$stmt = $this->connection->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role, created_at = :created_at, last_login = :last_login, status = :status WHERE id = :id");
		$stmt->bindValue(":first_name", $user->getFirstName(), PDO::PARAM_STR);
		$stmt->bindValue(":last_name", $user->getLastName(), PDO::PARAM_STR);
		$stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":role", $user->getRole()->value, PDO::PARAM_STR);
		$stmt->bindValue(":created_at", $user->getCreatedAt(), PDO::PARAM_STR);
		$stmt->bindValue(":last_login", $user->getLastLogin(), PDO::PARAM_STR);
		$stmt->bindValue(":status", $user->isStatus(), PDO::PARAM_BOOL);
		$stmt->bindValue(":id", $user->getId(), PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Добавляет нового пользователя в базу данных.
	 *
	 * @param User $user Объект User с данными нового пользователя.
	 */
	public function add(User $user): int
	{
		$stmt = $this->connection->prepare("INSERT INTO users (first_name, last_name, email, role, created_at, last_login, status) VALUES (:first_name, :last_name, :email, :role, :created_at, :last_login, :status)");
		$stmt->bindValue(":first_name", $user->getFirstName(), PDO::PARAM_STR);
		$stmt->bindValue(":last_name", $user->getLastName(), PDO::PARAM_STR);
		$stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":role", $user->getRole()->value, PDO::PARAM_STR);
		$stmt->bindValue(":created_at", $user->getCreatedAt(), PDO::PARAM_STR);
		$stmt->bindValue(":last_login", $user->getLastLogin(), PDO::PARAM_STR);
		$stmt->bindValue(":status", $user->isStatus(), PDO::PARAM_BOOL);
		$stmt->execute();

		return (int) $this->connection->lastInsertId();
	}

	/**
	 * Ищет пользователей по их электронной почте.
	 *
	 * @param string $email Электронная почта пользователя.
	 * @return User[] Массив объектов User.
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
	 * Ищет пользователей по их статусу.
	 *
	 * @param bool $status Статус пользователя.
	 * @return User[] Массив объектов User.
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
	 * Активирует пользователя.
	 *
	 * @param int $id Идентификатор пользователя.
	 */
	public function activate(int $id): void
	{
		$stmt = $this->connection->prepare("UPDATE users SET status = 1 WHERE id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Деактивирует пользователя.
	 *
	 * @param int $id Идентификатор пользователя.
	 */
	public function deactivate(int $id): void
	{
		$stmt = $this->connection->prepare("UPDATE users SET status = 0 WHERE id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Преобразует строку из базы данных в объект User.
	 *
	 * @param array $row Данные пользователя из базы данных.
	 * @return User Объект User.
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
			(bool)$row['status']
		);
	}
}
