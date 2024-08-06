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

		// Определяем секретный ключ
		$secretKey = 'mrsu-dns';

		// Создаем объект DateTime с Московским временем
		$now = new DateTime('now', new DateTimeZone('Europe/Moscow'));
		$issuedAt = $now->getTimestamp(); // Время создания токена
		$expiration = $now->modify('+1 hour')->getTimestamp(); // Время истечения срока действия токена

		// Заголовок токена
		$header = [
			'alg' => 'HS256', // Алгоритм подписи
			'typ' => 'JWT'
		];

		// Полезная нагрузка токена
		$payload = [
			'iss' => 'mrsu-issuer', // Issuer (может быть URL вашего сервера)
			'iat' => $issuedAt, // Время создания
			'exp' => $expiration, // Время истечения срока
			'userId' => $userId, // Пользовательский ID
		];

		// Кодируем заголовок и полезную нагрузку в Base64Url
		$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
		$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

		// Создаем строку для подписи
		$signatureInput = "$base64UrlHeader.$base64UrlPayload";

		// Создаем подпись с использованием HMAC SHA-256
		$signature = hash_hmac('sha256', $signatureInput, $secretKey, true);
		$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		// Создаем JWT
		$jwt = "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";

		// Сохраняем токен в базе данных
		$this->authRepository->saveToken($jwt, date('Y-m-d H:i:s', $expiration), $userId);

		return $jwt;
	}
}
