<?php

declare(strict_types=1);

require_once 'repositories/domain/DomainRepositoryInterface.php';
require_once 'entities/Domain.php';
require_once 'entities/SOA.php';

/**
 * Класс для обработки запросов, связанных с доменами.
 * Этот класс отвечает за выполнение запросов к ресурсам доменов, таких как получение, обновление, удаление и создание доменов.
 */
class DomainRequestHandler
{
	private DomainRepositoryInterface $domainRepository;
	private int $userId;

	/**
	 * Конструктор класса.
	 *
	 * @param DomainRepositoryInterface $domainRepository Репозиторий для работы с доменами.
	 * @param int $userId ID пользователя, который выполняет запрос.
	 */
	public function __construct(DomainRepositoryInterface $domainRepository, int $userId)
	{
		$this->domainRepository = $domainRepository;
		$this->userId = $userId;
	}

	/**
	 * Обрабатывает HTTP-запрос в зависимости от метода.
	 *
	 * @param string $method HTTP-метод (GET, POST, PUT, DELETE).
	 * @param int|null $id ID домена (может быть null для получения списка всех доменов или создания нового домена).
	 */
	public function processRequest(string $method, ?int $id): void
	{
		switch ($method) {
			case 'GET':
				$this->handleGet($id);
				break;

			case 'POST':
				$this->handlePost();
				break;

			case 'PUT':
				$this->handlePut($id);
				break;

			case 'DELETE':
				$this->handleDelete($id);
				break;

			default:
				http_response_code(405);
				header('Allow: GET, POST, PUT, DELETE');
				break;
		}
	}

	/**
	 * Обрабатывает GET-запрос для получения доменов.
	 *
	 * Если ID домена передан, возвращает информацию о конкретном домене.
	 * Если ID домена не передан, возвращает список всех доменов.
	 *
	 * @param int|null $id ID домена (может быть null для получения списка всех доменов).
	 */
	private function handleGet(?int $id): void
	{
		if ($id) {
			$domain = $this->domainRepository->get($id, $this->userId);
			if ($domain) {
				echo json_encode($domain);
			} else {
				http_response_code(404);
				echo json_encode(['message' => 'Domain not found']);
			}
		} else {
			$domains = $this->domainRepository->getDomainsAccessibleByUser($this->userId);
			echo json_encode($domains);
		}
	}


	/**
	 * Обрабатывает POST-запрос для создания нового домена.
	 *
	 * Требуется передача данных в формате JSON. Создает новый домен и возвращает его ID.
	 */
	private function handlePost(): void
	{
		$data = json_decode(file_get_contents('php://input'), true);
		if ($data === null) {
			http_response_code(400);
			echo json_encode(['message' => 'Invalid input data']);
			return;
		}

		$errors = $this->getValidationErrors($data);
		if (!empty($errors)) {
			http_response_code(403);
			echo json_encode(['errors' => $errors]);
			return;
		}

		try {
			$newDomain = new Domain(
				0,
				$data['name'],
				new SOA(
					$data['soa']['primary_ns'],
					$data['soa']['admin_email'],
					$data['soa']['serial'],
					$data['soa']['refresh'],
					$data['soa']['retry'],
					$data['soa']['expire'],
					$data['soa']['ttl']
				),
				$data['created'],
				$data['updated'],
				$data['expires']
			);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		$success = $this->domainRepository->add($newDomain, $this->userId);
		if ($success) {
			http_response_code(201);
			echo json_encode(['message' => 'Domain created successfully']);
		} else {
			http_response_code(500);
			echo json_encode(['message' => 'Failed to create domain']);
		}
	}

	/**
	 * Обрабатывает PUT-запрос для обновления информации о домене.
	 *
	 * Обновляет данные домена по его ID. Требуется передача данных в формате JSON.
	 *
	 * @param int|null $id ID домена, который необходимо обновить.
	 */
	private function handlePut(?int $id): void
	{
		if ($id === null) {
			http_response_code(400);
			echo json_encode(['message' => 'Domain ID is required']);
			return;
		}

		$data = json_decode(file_get_contents('php://input'), true);
		if ($data === null) {
			http_response_code(400);
			echo json_encode(['message' => 'Invalid input data']);
			return;
		}

		$errors = $this->getValidationErrors($data, false);
		if (!empty($errors)) {
			http_response_code(403);
			echo json_encode(['errors' => $errors]);
			return;
		}

		$current = $this->domainRepository->get($id, $this->userId);
		if ($current) {
			$newDomain = new Domain(
				$current->getId(),
				$data['name'] ?? $current->getName(),
				new SOA(
					$data['soa']['primary_ns'] ?? $current->getSOA()->getPrimaryNS(),
					$data['soa']['admin_email'] ?? $current->getSOA()->getAdminEmail(),
					$data['soa']['serial'] ?? $current->getSOA()->getSerial(),
					$data['soa']['refresh'] ?? $current->getSOA()->getRefresh(),
					$data['soa']['retry'] ?? $current->getSOA()->getRetry(),
					$data['soa']['expire'] ?? $current->getSOA()->getExpire(),
					$data['soa']['ttl'] ?? $current->getSOA()->getTTL()
				),
				$data['created'] ?? $current->getCreated(),
				$data['updated'] ?? $current->getUpdated(),
				$data['expires'] ?? $current->getExpires()
			);

			$success = $this->domainRepository->update($current, $newDomain, $this->userId);
			if ($success) {
				echo json_encode(['message' => "Domain $id updated"]);
			} else {
				http_response_code(500);
				echo json_encode(['message' => 'Failed to update domain']);
			}
		} else {
			http_response_code(404);
			echo json_encode(['message' => 'Domain not found']);
		}
	}


	/**
	 * Обрабатывает DELETE-запрос для удаления домена.
	 *
	 * Удаляет домен по его ID.
	 *
	 * @param int $id ID домена, который необходимо удалить.
	 */
	private function handleDelete(int $id): void
	{
		$success = $this->domainRepository->delete($id, $this->userId);
		if ($success) {
			echo json_encode(['message' => "Domain $id deleted"]);
		} else {
			http_response_code(500);
			echo json_encode(['message' => 'Failed to delete domain']);
		}
	}

	/**
	 * Проверяет корректность входных данных.
	 *
	 * @param array $data Данные для проверки.
	 * @param bool $is_new Определяет, является ли домен новым (по умолчанию true).
	 * @return array Массив ошибок валидации.
	 */
	private function getValidationErrors(array $data, bool $is_new = true): array
	{
		$errors = [];
		if ($is_new) {
			if (empty($data['name'])) {
				$errors[] = 'Name is required';
			}
			if (empty($data['soa']['primary_ns'])) {
				$errors[] = 'Primary NS is required';
			}
			if (empty($data['soa']['admin_email'])) {
				$errors[] = 'Admin email is required';
			}
		}
		return $errors;
	}
}
