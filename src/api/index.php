<?php

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once 'repositories/user/UserRepository.php';
require_once 'repositories/userDomain/UserDomainRepository.php';
require_once 'repositories/records/RecordRepository.php';
require_once 'repositories/domain/DomainRepository.php';
require_once 'repositories/auth/AuthRepository.php'; // Для аутентификации и токенов
require_once 'repositories/login/LoginRepository.php'; // Для управления логинами
require_once 'requestHandlers/UserRequestHandler.php';
require_once 'requestHandlers/UserDomainRequestHandler.php';
require_once 'requestHandlers/RecordRequestHandler.php';
require_once 'requestHandlers/DomainRequestHandler.php';
require_once 'requestHandlers/ApiRequestHandler.php';
require_once 'controllers/UserController.php';
require_once 'middlewares/GlobalErrorHandler.php';

// Установим обработчики ошибок
set_error_handler("GlobalErrorHandler::handleError");
set_exception_handler("GlobalErrorHandler::handleException");

// Функция для проверки аутентификации
function isAuthenticated(): ?int
{
	if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
		$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
		if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$token = $matches[1];
			$authRepo = new AuthRepository();
			return $authRepo->validateToken($token);
		}
	}
	return null;
}

// Определяем, аутентифицирован ли пользователь
$userId = isAuthenticated();

// Создаем репозитории и обработчики запросов, только если пользователь аутентифицирован
if ($userId !== null) {
	// Значение `domainId` нужно получить из запроса или контекста
	$domainId = 1; // Убедитесь, что это значение корректно

	$userRepository = new UserRepository();
	$userDomainRepository = new UserDomainRepository();
	$authRepository = new AuthRepository();
	$loginRepository = new LoginRepository(); // Для управления логинами

	// Используйте логин и аутентификацию в зависимости от потребностей
	$recordRepository = new RecordRepository($domainId); // Передача domainId
	$domainRepository = new DomainRepository($userId); // Передача userId

	$userController = new UserController($userRepository, $authRepository);
	$userRequestHandler = new UserRequestHandler($userRepository, $loginRepository);
	$userDomainRequestHandler = new UserDomainRequestHandler($userDomainRepository);
	$recordRequestHandler = new RecordRequestHandler($recordRepository);
	$domainRequestHandler = new DomainRequestHandler($domainRepository, $userId);

	$apiRequestHandler = new ApiRequestHandler(
		$userRequestHandler,
		$userDomainRequestHandler,
		$recordRequestHandler,
		$domainRequestHandler,
		$userController
	);

	// Обработка запроса
	$apiRequestHandler->handleRequest();
} else {
	// Если не аутентифицирован, проверяем запрос
	$method = $_SERVER['REQUEST_METHOD'];
	$path = $_SERVER['REQUEST_URI'];

	// Обработка маршрута логина отдельно
	if ($path === '/login' && $method === 'POST') {
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($data['email']) && isset($data['password'])) {
			$authRepo = new AuthRepository();
			$userController = new UserController(new UserRepository(), $authRepo);
			$response = $userController->login($data['email'], $data['password']);
			echo json_encode($response);
			return;
		}
	}

	// Если не аутентифицирован и запрос не на логин, вернуть 401 Unauthorized
	http_response_code(401);
	echo json_encode(['message' => 'Unauthorized']);
}
