<?php

/**
 * Класс для обработки HTTP-запросов, связанных с записями.
 * Этот класс отвечает за выполнение CRUD-операций (создание, чтение, обновление и удаление) над записями.
 */
class RecordRequestHandler
{
	private RecordRepositoryInterface $recordRepository;

	/**
	 * Конструктор класса.
	 *
	 * @param RecordRepositoryInterface $recordRepository Репозиторий для работы с записями.
	 */
	public function __construct(RecordRepositoryInterface $recordRepository)
	{
		$this->recordRepository = $recordRepository;
	}

	/**
	 * Обрабатывает HTTP-запрос в зависимости от метода.
	 *
	 * @param string $method HTTP-метод (GET, POST, PUT, DELETE).
	 * @param int $domainId ID домена, к которому относится запись.
	 * @param string|null $type Тип записи (может быть null для получения записей всех типов).
	 * @param int|null $recordId ID записи (может быть null для создания новой записи или получения списка записей).
	 */
	public function processRequest(string $method, int $domainId, ?string $type, ?int $recordId): void
	{
		switch ($method) {
			case 'GET':
				$this->handleGet($domainId, $type, $recordId);
				break;

			case 'POST':
				$this->handlePost($domainId);
				break;

			case 'PUT':
				$this->handlePut($recordId);
				break;

			case 'DELETE':
				$this->handleDelete($recordId);
				break;

			default:
				http_response_code(405);
				header('Allow: GET, POST, PUT, DELETE');
				break;
		}
	}

	/**
	 * Обрабатывает GET-запрос для получения записей.
	 *
	 * Если ID записи передан, возвращает информацию о конкретной записи.
	 * Если ID записи не передан, возвращает список всех записей по типу (если указан) для заданного домена.
	 *
	 * @param int $domainId ID домена, к которому относится запись.
	 * @param string|null $type Тип записи (может быть null для получения записей всех типов).
	 * @param int|null $recordId ID записи (может быть null для получения списка записей).
	 */
	private function handleGet(int $domainId, ?string $type, ?int $recordId): void
	{
		if ($recordId !== null) {
			$record = $this->recordRepository->get($recordId);
			if ($record && $record->getDomainId() === $domainId && ($type === null || $record->getType() === $type)) {
				echo json_encode($record);  // Использует метод jsonSerialize() из интерфейса JsonSerializable
			} else {
				http_response_code(404);
				echo json_encode(['message' => 'Record not found']);
			}
		} else {
			$records = $this->recordRepository->getAll($type); // Использует только $type
			echo json_encode($records); // Использует метод jsonSerialize() из интерфейса JsonSerializable
		}
	}


	/**
	 * Обрабатывает POST-запрос для создания новой записи.
	 *
	 * Требуется передача данных в формате JSON. Создает новую запись и возвращает сообщение о результате.
	 *
	 * @param int $domainId ID домена, к которому относится новая запись.
	 */
	private function handlePost(int $domainId): void
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$errors = $this->getRecordValidationErrors($data);

		if (!empty($errors)) {
			http_response_code(403);
			echo json_encode(['errors' => $errors]);
		} else {
			$record = new Record(
				null,
				$domainId,
				$data['name'],
				$data['content'],
				$data['priority'],
				$data['ttl'],
				$data['type'],
				date('Y-m-d H:i:s'),
				date('Y-m-d H:i:s')
			);

			$this->recordRepository->add($record);

			echo json_encode(['message' => 'Record created']);
		}
	}

	/**
	 * Обрабатывает PUT-запрос для обновления записи.
	 *
	 * Обновляет данные записи по ее ID. Требуется передача данных в формате JSON.
	 *
	 * @param int|null $recordId ID записи, которую необходимо обновить.
	 */
	private function handlePut(?int $recordId): void
	{
		if ($recordId) {
			$data = json_decode(file_get_contents('php://input'), true);
			$errors = $this->getRecordValidationErrors($data, false);

			if (!empty($errors)) {
				http_response_code(403);
				echo json_encode(['errors' => $errors]);
			} else {
				$current = $this->recordRepository->get($recordId);
				if ($current) {
					$newRecord = new Record(
						$current->getId(),
						$current->getDomainId(),
						$data['name'] ?? $current->getName(),
						$data['content'] ?? $current->getContent(),
						$data['priority'] ?? $current->getPriority(),
						$data['ttl'] ?? $current->getTTL(),
						$data['type'] ?? $current->getType(),
						$current->getCreatedAt(), // Use the existing creation date
						date('Y-m-d H:i:s') // Set the updated date to the current time
					);

					$this->recordRepository->update($current, $newRecord);
					echo json_encode(['message' => 'Record updated']);
				} else {
					http_response_code(404);
					echo json_encode(['message' => 'Record not found']);
				}
			}
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'Record ID is required']);
		}
	}

	/**
	 * Обрабатывает DELETE-запрос для удаления записи.
	 *
	 * Удаляет запись по ее ID.
	 *
	 * @param int|null $recordId ID записи, которую необходимо удалить.
	 */
	private function handleDelete(?int $recordId): void
	{
		if ($recordId) {
			$this->recordRepository->delete($recordId);
			echo json_encode(['message' => 'Record deleted']);
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'Record ID is required']);
		}
	}

	/**
	 * Проверяет корректность входных данных записи.
	 *
	 * @param array $data Данные для проверки.
	 * @param bool $is_new Определяет, является ли запись новой (по умолчанию true).
	 * @return array Массив ошибок валидации.
	 */
	private function getRecordValidationErrors(array $data, bool $is_new = true): array
	{
		$errors = [];

		if ($is_new && empty($data['name'])) {
			$errors[] = 'Name is required';
		}
		if ($is_new && empty($data['content'])) {
			$errors[] = 'Content is required';
		}
		if ($is_new && empty($data['type'])) {
			$errors[] = 'Type is required';
		}

		return $errors;
	}
}
