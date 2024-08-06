<?php

/**
 * Класс для обработки HTTP-запросов, связанных с пользователями.
 * Этот класс отвечает за выполнение CRUD-операций (создание, чтение, обновление и удаление) над пользователями.
 */
class UserRequestHandler
{
	private UserRepository $userRepository;
	private LoginRepository $loginRepository;

	/**
	 * Конструктор класса.
	 *
	 * @param UserRepository $userRepository Репозиторий для работы с пользователями.
	 * @param LoginRepository $loginRepository Репозиторий для работы с логинами.
	 */
	public function __construct(UserRepository $userRepository, LoginRepository $loginRepository)
	{
		$this->userRepository = $userRepository;
		$this->loginRepository = $loginRepository;
	}

	/**
	 * Обрабатывает HTTP-запрос в зависимости от метода.
	 *
	 * @param string $method HTTP-метод (GET, POST, PUT, DELETE).
	 * @param int|null $userId ID пользователя (может быть null для получения списка всех пользователей).
	 */
	public function processRequest(string $method, ?int $userId): void
	{
		switch ($method) {
			case 'GET':
				$this->handleGet($userId);
				break;

			case 'POST':
				$this->handlePost();
				break;

			case 'PUT':
				$this->handlePut($userId);
				break;

			case 'DELETE':
				$this->handleDelete($userId);
				break;

			default:
				http_response_code(405);
				header('Allow: GET, POST, PUT, DELETE');
				break;
		}
	}

	/**
	 * Обрабатывает GET-запрос для получения информации о пользователе.
	 *
	 * Если ID пользователя передан, возвращает информацию о конкретном пользователе.
	 * Если ID пользователя не передан, возвращает список всех пользователей.
	 *
	 * @param int|null $userId ID пользователя (может быть null для получения списка всех пользователей).
	 */
	private function handleGet(?int $userId): void
	{
		if ($userId) {
			$user = $this->userRepository->get($userId);
			if ($user) {
				echo json_encode($user);
			} else {
				http_response_code(404);
				echo json_encode(['message' => 'User not found']);
			}
		} else {
			$users = $this->userRepository->getAll();
			echo json_encode($users);
		}
	}

	/**
	 * Обрабатывает POST-запрос для создания нового пользователя.
	 *
	 * Требуется передача данных в формате JSON. Создает нового пользователя и возвращает сообщение о результате.
	 *
	 * @throws \InvalidArgumentException Если входные данные невалидны.
	 */
	public function handlePost(): void
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$errors = $this->getUserValidationErrors($data);

		if (!empty($errors)) {
			http_response_code(400);
			echo json_encode(['errors' => $errors]);
			return;
		}

		$user = new User(
			0,
			$data['first_name'],
			$data['last_name'],
			$data['email'],
			UserRole::from($data['role']),
			date('Y-m-d H:i:s'),
			date('Y-m-d H:i:s'),
			true
		);
		$userId = $this->userRepository->add($user);


		$login = new Login($userId, $user->getEmail(), $data['password']);
		$login->setPasswordHash($data['password']);

		try {
			$this->loginRepository->create($login);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['message' => 'Failed to create login', 'error' => $e->getMessage()]);
			return;
		}

		echo json_encode(['message' => 'User created']);
	}



	/**
	 * Обрабатывает PUT-запрос для обновления информации о пользователе.
	 *
	 * Обновляет данные пользователя по его ID. Требуется передача данных в формате JSON.
	 *
	 * @param int|null $userId ID пользователя, которого необходимо обновить.
	 */
	private function handlePut(?int $userId): void
	{
		if ($userId) {
			$data = json_decode(file_get_contents('php://input'), true);
			$errors = $this->getUserValidationErrors($data, false);

			if (!empty($errors)) {
				http_response_code(400); // Исправлено с 403 на 400 для ошибки валидации
				echo json_encode(['errors' => $errors]);
			} else {
				$user = $this->userRepository->get($userId);
				if ($user) {
					$user->setFirstName($data['first_name'] ?? $user->getFirstName());
					$user->setLastName($data['last_name'] ?? $user->getLastName());
					$user->setEmail($data['email'] ?? $user->getEmail());
					$user->setRole(UserRole::from($data['role'] ?? $user->getRole()->value));

					if (isset($data['password'])) {
						$login = $this->loginRepository->getByUserId($userId);
						if ($login) {
							$login->setPasswordHash($data['password']);
							$this->loginRepository->update($login);
						}
					}

					$this->userRepository->update($user);
					echo json_encode(['message' => 'User updated']);
				} else {
					http_response_code(404);
					echo json_encode(['message' => 'User not found']);
				}
			}
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'User ID is required']);
		}
	}

	/**
	 * Обрабатывает DELETE-запрос для удаления пользователя.
	 *
	 * Удаляет пользователя по его ID.
	 *
	 * @param int|null $userId ID пользователя, которого необходимо удалить.
	 */
	private function handleDelete(?int $userId): void
	{
		if ($userId) {
			$this->userRepository->delete($userId);
			$this->loginRepository->deleteByUserId($userId); // Удаление записи логина, если необходимо
			echo json_encode(['message' => 'User deleted']);
		} else {
			http_response_code(400);
			echo json_encode(['message' => 'User ID is required']);
		}
	}

	/**
	 * Проверяет корректность входных данных для пользователя.
	 *
	 * @param array $data Данные для проверки.
	 * @param bool $is_new Определяет, является ли пользователь новым (по умолчанию true).
	 * @return array Массив ошибок валидации.
	 */
	private function getUserValidationErrors(array $data, bool $is_new = true): array
	{
		$errors = [];

		if ($is_new && empty($data['first_name'])) {
			$errors[] = 'First name is required';
		}
		if ($is_new && empty($data['last_name'])) {
			$errors[] = 'Last name is required';
		}
		if ($is_new && empty($data['email'])) {
			$errors[] = 'Email is required';
		}
		if ($is_new && empty($data['password'])) {
			$errors[] = 'Password is required';
		}

		if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Invalid email format';
		}

		return $errors;
	}
}
