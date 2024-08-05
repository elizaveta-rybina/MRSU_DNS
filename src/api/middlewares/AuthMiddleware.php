<?php

/**
 * Класс для проверки аутентификации и авторизации пользователей.
 */
class AuthMiddleware
{
	/**
	 * Массив защищённых маршрутов, требующих аутентификации.
	 *
	 * @var array
	 */
	private $protectedRoutes = [
		'user/domains',
		'user/domains/edit',
		'user/domains/add',
		'user/profile',
		'user/settings'
	];

	/**
	 * Обрабатывает входящий запрос, проверяя необходимость аутентификации.
	 *
	 * Если маршрут защищённый и пользователь не аутентифицирован,
	 * отправляет ошибку 401 Unauthorized и завершает выполнение.
	 *
	 * @param string $requestUrl URL запрашиваемого ресурса.
	 * @param callable $next Функция для вызова следующего обработчика запроса.
	 *
	 * @return mixed Результат выполнения следующего обработчика запроса.
	 */
	public function handle($requestUrl, $next)
	{
		// Проверка, является ли маршрут защищённым
		if ($this->isProtectedRoute($requestUrl)) {
			// Проверка аутентификации
			if (!$this->isAuthenticated()) {
				header('HTTP/1.1 401 Unauthorized');
				echo json_encode(['error' => 'Unauthorized']);
				exit;
			}
		}

		// Вызов следующего обработчика
		return $next($requestUrl);
	}

	/**
	 * Проверяет, является ли маршрут защищённым.
	 *
	 * @param string $url URL запрашиваемого ресурса.
	 *
	 * @return bool Возвращает true, если маршрут защищённый, иначе false.
	 */
	private function isProtectedRoute($url)
	{
		return in_array($url, $this->protectedRoutes, true);
	}

	/**
	 * Проверяет, аутентифицирован ли пользователь.
	 *
	 * Проверяет наличие ID пользователя в сессии.
	 *
	 * @return bool Возвращает true, если пользователь аутентифицирован, иначе false.
	 */
	private function isAuthenticated()
	{
		return isset($_SESSION['user_id']);
	}
}
