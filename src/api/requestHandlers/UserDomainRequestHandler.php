<?php

require_once 'repositories/userDomain/UserDomainRepository.php';
require_once 'entities/UserDomain.php';

class UserDomainRequestHandler
{
	private UserDomainRepository $userDomainRepository;

	/**
	 * Конструктор класса.
	 * 
	 * @param UserDomainRepository $userDomainRepository Репозиторий для управления связями пользователей и доменов.
	 */
	public function __construct(UserDomainRepository $userDomainRepository)
	{
		$this->userDomainRepository = $userDomainRepository;
	}

	/**
	 * Обрабатывает HTTP-запрос в зависимости от метода.
	 *
	 * @param string $method HTTP-метод (GET, POST, DELETE).
	 * @param int|null $userId ID пользователя (может быть null для запросов, не требующих ID пользователя).
	 * @param int|null $domainId ID домена (может быть null для запросов, не требующих ID домена).
	 */
	public function processRequest(string $method, ?int $userId, ?int $domainId): void
	{
		switch ($method) {
			case 'POST':
				$this->handlePost($userId, $domainId);
				break;

			case 'DELETE':
				$this->handleDelete($userId, $domainId);
				break;

			case 'GET':
				$this->handleGet($userId, $domainId);
				break;

			default:
				http_response_code(405);
				header('Allow: POST, DELETE, GET');
				break;
		}
	}

	/**
	 * Обрабатывает POST-запрос для добавления пользователя к домену.
	 *
	 * @param int|null $userId ID пользователя.
	 * @param int|null $domainId ID домена.
	 */
	private function handlePost(?int $userId, ?int $domainId): void
	{
		if ($userId && $domainId) {
			$this->userDomainRepository->addUserToDomain($userId, $domainId);
			echo json_encode(['message' => 'User added to domain']);
			http_response_code(201);
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'User ID and Domain ID are required']);
		}
	}

	/**
	 * Обрабатывает DELETE-запрос для удаления пользователя из домена.
	 *
	 * @param int|null $userId ID пользователя.
	 * @param int|null $domainId ID домена.
	 */
	private function handleDelete(?int $userId, ?int $domainId): void
	{
		if ($userId && $domainId) {
			$this->userDomainRepository->removeUserFromDomain($userId, $domainId);
			echo json_encode(['message' => 'User removed from domain']);
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'User ID and Domain ID are required']);
		}
	}

	/**
	 * Обрабатывает GET-запросы для получения данных.
	 *
	 * Если передан только $userId, возвращает все домены, к которым привязан пользователь.
	 * Если передан только $domainId, возвращает всех пользователей для данного домена.
	 * 
	 * @param int|null $userId ID пользователя (может быть null для получения всех пользователей для домена).
	 * @param int|null $domainId ID домена (может быть null для получения всех доменов для пользователя).
	 */
	private function handleGet(?int $userId, ?int $domainId): void
	{
		if ($userId) {
			$domains = $this->userDomainRepository->getDomainsForUser($userId);
			echo json_encode($domains);
		} elseif ($domainId) {
			$users = $this->userDomainRepository->getUsersForDomain($domainId);
			echo json_encode($users);
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'User ID or Domain ID is required']);
		}
	}
}
