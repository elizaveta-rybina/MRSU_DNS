<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once 'gateways/DomainGateway.php';

$pathParts = explode("/", $_SERVER["REQUEST_URI"]);

$domains = new DomainController();

$type = $pathParts[1];
$id = $pathParts[2];

switch ($type) {
    case 'domains':
        if (isset($id)) {
            $domains->getDomain($id);
        } else {
            $domains->getAllDomains();
        }
        break;
    case 'records':
        $domains->getAllDomains();
        break;
    default:
        break;
}

// $controllerName = ucfirst($pathParts[1]) . 'Controller';
// $actionName = isset($pathParts[2]) ? $pathParts[2] : 'index';

// if (class_exists($controllerName)) {
//     $controller = new $controllerName();
//     if ($controllerName === 'domains') {
//         $domains->getAllDomains();
//     } else {
//         http_response_code(404);
//         echo json_encode(['error' => 'Method not found']);
//     }
// } else {
//     http_response_code(404);
//     echo json_encode(['error' => 'Controller not found']);
// }
