<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once 'repositories/domain/DomainRepository.php';
require_once 'repositories/records/RecordRepository.php';
require_once 'requestHandlers/GlobalRequestHandler.php';
require_once 'middlewares/GlobalErrorHandler.php';

set_error_handler("GlobalErrorHandler::handleError");
set_exception_handler("GlobalErrorHandler::handleException");

// Извлечение пути и строки запроса
$requestUri = trim($_SERVER["REQUEST_URI"], "/");
$parsedUrl = parse_url($requestUri);

$pathParts = explode("/", $parsedUrl['path']);

$type = $pathParts[0] ?? null; // 'domain'
$id = $pathParts[1] ?? null;   // '1' (domain_id)
$resource = $pathParts[2] ?? null; // 'records'

$queryParams = [];
parse_str($parsedUrl['query'] ?? '', $queryParams);

$recordType = $queryParams['type'] ?? null; // 'A'
$recordId = $queryParams['id'] ?? null; // '1'

// Дальнейшая обработка
$domainRepository = new DomainRepository();

if ($type === "domain" && $resource === "records") {
	$domainId = (int)$id; // Приведение $id к int
	$recordRepository = new RecordRepository($domainId); // Передаем domain_id
	$handler = new GlobalRequestHandler($domainRepository, $recordRepository);
	$handler->processRequest($_SERVER["REQUEST_METHOD"], $domainId, $resource, $recordType, $recordId !== null ? (int)$recordId : null);
} else {
	$handler = new GlobalRequestHandler($domainRepository, new RecordRepository(0)); // Пустой domain_id для обработки доменов
	$handler->processRequest($_SERVER["REQUEST_METHOD"], $id !== null ? (int)$id : null);
}
