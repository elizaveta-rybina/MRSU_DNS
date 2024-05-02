<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once 'gateways/DomainGateway.php';
require_once 'helpers/Request.php';
require_once 'helpers/ErrorHandle.php';

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$pathParts = explode("/", $_SERVER["REQUEST_URI"]);

$domains = new DomainController();

$type = $pathParts[1];
$id = $pathParts[2] ?? null;

$gateway = new DomainController();
$controller = new domainRequest();

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

// switch ($type) {
//     case 'domains':
//         if (isset($id)) {
//             $domains->get($id);
//         } else {
//             $domains->getAll();
//         }
//         break;
//     case 'records':
//         $domains->getAll();
//         break;
//     default:
//         break;
// }
