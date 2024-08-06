<?php

require_once 'data/DbContext.php';
require_once 'entities/UserDomain.php'; // Ваш класс UserDomain
require_once 'UserDomainRepositoryInterface.php';

class UserDomainRepository implements UserDomainRepositoryInterface
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
	 * Добавляет пользователя к домену.
	 *
	 * @param int $userId ID пользователя.
	 * @param int $domainId ID домена.
	 */
	public function addUserToDomain(int $userId, int $domainId): void
	{
		$stmt = $this->connection->prepare("INSERT INTO userDomain (user_id, domain_id) VALUES (:user_id, :domain_id)");
		$stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$stmt->bindValue(":domain_id", $domainId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Удаляет пользователя из домена.
	 *
	 * @param int $userId ID пользователя.
	 * @param int $domainId ID домена.
	 */
	public function removeUserFromDomain(int $userId, int $domainId): void
	{
		$stmt = $this->connection->prepare("DELETE FROM userDomain WHERE user_id = :user_id AND domain_id = :domain_id");
		$stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$stmt->bindValue(":domain_id", $domainId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Удаляет всех пользователей из указанного домена.
	 *
	 * @param int $domainId ID домена.
	 */
	public function removeAllUsersFromDomain(int $domainId): void
	{
		$stmt = $this->connection->prepare("DELETE FROM userDomain WHERE domain_id = :domain_id");
		$stmt->bindValue(":domain_id", $domainId, PDO::PARAM_INT);
		$stmt->execute();
	}

	public function getAllUsers(): array
	{
		try {
			$stmt = $this->connection->prepare("SELECT * FROM users");
			$stmt->execute();

			$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$users = [];

			foreach ($usersData as $userData) {
				$users[] = new User(
					(int)$userData['id'],
					$userData['first_name'],
					$userData['last_name'],
					$userData['email'],
					UserRole::from($userData['role']),
					$userData['created_at'],
					$userData['last_login'],
					(bool)$userData['status']
				);
			}
			return $users;
		} catch (PDOException $e) {
			throw new RuntimeException('Error retrieving all users: ' . $e->getMessage());
		}
	}


	/**
	 * Получает всех пользователей для данного домена.
	 *
	 * @param int $domainId ID домена.
	 * @return User[] Массив объектов User.
	 */
	public function getUsersForDomain(int $domainId): array
	{
		try {
			$stmt = $this->connection->prepare("
            SELECT u.*
            FROM users u
            JOIN userDomain ud ON u.id = ud.user_id
            WHERE ud.domain_id = :domain_id
        ");
			$stmt->bindValue(":domain_id", $domainId, PDO::PARAM_INT);
			$stmt->execute();

			$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Проверка на пустой результат
			if ($usersData === false) {
				return []; // Или можно бросить исключение, если это критично
			}

			$users = [];
			foreach ($usersData as $userData) {
				try {
					$role = UserRole::from($userData['role']); // Попробуйте создать роль из строки

					$users[] = new User(
						(int)$userData['id'],
						$userData['first_name'],
						$userData['last_name'],
						$userData['email'],
						$role,
						$userData['created_at'],
						$userData['last_login'],
						(bool)$userData['status']
					);
				} catch (ValueError $e) {
					error_log('Invalid user role: ' . $userData['role']);
				}
			}
			return $users;
		} catch (PDOException $e) {
			throw new RuntimeException('Error retrieving users for domain: ' . $e->getMessage());
		}
	}


	/**
	 * Получает все домены, к которым привязан пользователь.
	 *
	 * @param int $userId ID пользователя.
	 * @return Domain[] Массив объектов Domain.
	 */
	public function getDomainsForUser(int $userId): array
	{
		$stmt = $this->connection->prepare("
            SELECT d.*
            FROM domains d
            JOIN userDomain ud ON d.id = ud.domain_id
            WHERE ud.user_id = :user_id
        ");
		$stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$stmt->execute();

		$domainsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$domains = [];

		foreach ($domainsData as $domainData) {
			$soa = new SOA(
				$domainData['primary_ns'],
				$domainData['admin_email'],
				$domainData['serial'],
				$domainData['refresh'],
				$domainData['retry'],
				$domainData['expire'],
				$domainData['ttl']
			);

			$domains[] = new Domain(
				$domainData['id'],
				$domainData['name'],
				$soa,
				$domainData['created'],
				$domainData['updated'],
				$domainData['expires']
			);
		}

		return $domains;
	}
}
