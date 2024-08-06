<?php

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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


set_error_handler("GlobalErrorHandler::handleError");
set_exception_handler("GlobalErrorHandler::handleException");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit(0);
}

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


$userId = isAuthenticated();


if ($userId !== null) {

	$domainId = 1;

	$userRepository = new UserRepository();
	$userDomainRepository = new UserDomainRepository();
	$authRepository = new AuthRepository();
	$loginRepository = new LoginRepository();


	$recordRepository = new RecordRepository($domainId);
	$domainRepository = new DomainRepository($userId);

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


	$apiRequestHandler->handleRequest();
} else {

	$method = $_SERVER['REQUEST_METHOD'];
	$path = $_SERVER['REQUEST_URI'];


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


	http_response_code(401);
	echo json_encode(['message' => 'Unauthorized']);
}
