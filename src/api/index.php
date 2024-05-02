<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once 'repositories/domain/DomainRepository.php';
require_once 'requestHandlers/GlobalRequestHandler.php';
require_once 'middlewares/GlobalErrorHandler.php';

set_error_handler("GlobalErrorHandler::handleError");
set_exception_handler("GlobalErrorHandler::handleException");

$pathParts = explode("/", $_SERVER["REQUEST_URI"]);

$type = $pathParts[1];
$id = $pathParts[2] ?? null;

$repository = new DomainRepository();
$controller = new GlobalRequestHandler($repository);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
