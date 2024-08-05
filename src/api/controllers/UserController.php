<?php

/**
 * Класс UserController управляет действиями пользователей, такими как вход в систему.
 */
class UserController
{
	/**
	 * @var UserRepository Репозиторий для работы с пользователями.
	 */
	private UserRepository $userRepository;

	/**
	 * @var AuthRepository Репозиторий для работы с аутентификацией.
	 */
	private AuthRepository $authRepository;

	/**
	 * Конструктор класса.
	 *
	 * @param UserRepository $userRepository Репозиторий для работы с пользователями.
	 * @param AuthRepository $authRepository Репозиторий для работы с аутентификацией.
	 */
	public function __construct(UserRepository $userRepository, AuthRepository $authRepository)
	{
		$this->userRepository = $userRepository;
		$this->authRepository = $authRepository;
	}

	/**
	 * Выполняет вход пользователя в систему.
	 *
	 * Проверяет электронную почту и пароль пользователя, создает новый токен аутентификации при успешном входе.
	 *
	 * @param string $email Электронная почта пользователя.
	 * @param string $password Пароль пользователя.
	 * @return array Результат входа, включающий статус, сообщение, данные пользователя и токен аутентификации.
	 */
	public function login(string $email, string $password): array
	{
		$users = $this->userRepository->findByEmail($email);
		foreach ($users as $user) {
			$loginRepository = new LoginRepository();
			$login = $loginRepository->getByUserId($user->getId());

			if ($login && $login->checkPassword($password)) {
				// Создаем новый токен
				$token = $this->generateToken($user->getId());
				return [
					'status' => 'success',
					'message' => 'User logged in successfully',
					'user' => $user,
					'token' => $token // Возвращаем токен в ответе
				];
			}
		}
		return [
			'status' => 'error',
			'message' => 'Invalid email or password'
		];
	}

	/**
	 * Генерирует новый токен аутентификации для пользователя.
	 *
	 * Удаляет истекшие токены и сохраняет новый токен в базе данных.
	 *
	 * @param int $userId Идентификатор пользователя.
	 * @return string Сгенерированный токен аутентификации.
	 */
	private function generateToken(int $userId): string
	{
		// Удаляем истекшие токены перед созданием нового
		$this->authRepository->deleteExpiredTokens();

		$token = bin2hex(random_bytes(16));
		$expiry = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

		$this->authRepository->saveToken($token, $expiry, $userId);

		return $token;
	}
}
