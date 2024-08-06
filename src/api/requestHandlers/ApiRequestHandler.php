<?php

class ApiRequestHandler
{
  private UserRequestHandler $userRequestHandler;
  private UserDomainRequestHandler $userDomainRequestHandler;
  private RecordRequestHandler $recordRequestHandler;
  private DomainRequestHandler $domainRequestHandler;
  private UserController $userController;

  public function __construct(
    UserRequestHandler $userRequestHandler,
    UserDomainRequestHandler $userDomainRequestHandler,
    RecordRequestHandler $recordRequestHandler,
    DomainRequestHandler $domainRequestHandler,
    UserController $userController
  ) {
    $this->userRequestHandler = $userRequestHandler;
    $this->userDomainRequestHandler = $userDomainRequestHandler;
    $this->recordRequestHandler = $recordRequestHandler;
    $this->domainRequestHandler = $domainRequestHandler;
    $this->userController = $userController;
  }

  public function handleRequest(): void
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = $_SERVER['REQUEST_URI'];

    // Извлечение сегментов пути
    $urlParts = parse_url($path);
    $pathParts = explode('/', trim($urlParts['path'], '/'));

    $resource = $pathParts[0] ?? '';
    $id = isset($pathParts[1]) ? (int)$pathParts[1] : null;
    $subResource = isset($pathParts[2]) ? $pathParts[2] : null;
    $subResourceId = $pathParts[3] ?? null;

    // Извлечение параметров запроса
    $query = $urlParts['query'] ?? '';
    parse_str($query, $queryParams);
    $type = $queryParams['type'] ?? null;

    // Обработка запроса на логин
    if ($resource === 'login' && $method === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      if (isset($data['email']) && isset($data['password'])) {
        $response = $this->userController->login($data['email'], $data['password']);
        echo json_encode($response);
        return;
      }
    }

    // Проверка авторизации
    $userId = $this->isAuthenticated();
    if ($userId === null) {
      // Если нет токена, перенаправляем на страницу авторизации
      http_response_code(302);
      header('Location: /login');
      exit;
    }

    // Обработка защищенных ресурсов
    switch ($resource) {
      case 'users':
        $this->userRequestHandler->processRequest($method, $id);
        break;

      case 'domains':
        if ($subResource === 'records') {
          $recordId = isset($subResourceId) ? (int)$subResourceId : null;
          $this->recordRequestHandler->processRequest($method, $id, $type, $recordId);
        } else {
          $this->domainRequestHandler->processRequest($method, $id);
        }
        break;

      default:
        http_response_code(404);
        echo json_encode(['message' => 'Resource not found']);
        break;
    }
  }




  // public function handleRequest(): void
  // {
  //   $method = $_SERVER['REQUEST_METHOD'];
  //   $path = $_SERVER['REQUEST_URI'];

  //   $pathParts = explode('/', trim($path, '/'));
  //   $resource = $pathParts[0] ?? '';
  //   $id = isset($pathParts[1]) ? (int)$pathParts[1] : null;
  //   $domainId = isset($pathParts[2]) ? (int)$pathParts[2] : null;

  //   // Обработка запроса на логин
  //   if ($resource === 'login' && $method === 'POST') {
  //     $data = json_decode(file_get_contents('php://input'), true);
  //     if (isset($data['email']) && isset($data['password'])) {
  //       $response = $this->userController->login($data['email'], $data['password']);
  //       echo json_encode($response);
  //       return;
  //     }
  //   }

  //   // Проверка авторизации
  //   $userId = $this->isAuthenticated();
  //   if ($userId === null) {
  //     // Если нет токена, перенаправляем на страницу авторизации
  //     http_response_code(302);
  //     header('Location: /login');
  //     exit;
  //   }

  //   // Обработка защищенных ресурсов
  //   switch ($resource) {
  //     case 'users':
  //       $this->userRequestHandler->processRequest($method, $id);
  //       break;

  //     case 'userDomains':
  //       $this->userDomainRequestHandler->processRequest($method, $id, $domainId);
  //       break;

  //     case 'records':
  //       $this->recordRequestHandler->processRequest($method, $domainId, $resource, $id);
  //       break;

  //     case 'domains':
  //       $this->domainRequestHandler->processRequest($method, $id);
  //       break;

  //     default:
  //       http_response_code(404);
  //       echo json_encode(['message' => 'Resource not found']);
  //       break;
  //   }
  // }

  /**
   * Проверяет аутентификацию пользователя по токену.
   *
   * @return int|null Возвращает идентификатор пользователя, если токен действителен, иначе null.
   */
  private function isAuthenticated(): ?int
  {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
      return null;
    }

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    $matches = [];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
      $token = $matches[1];
      $authRepository = new AuthRepository();
      return $authRepository->validateToken($token);
    }

    return null;
  }
}
