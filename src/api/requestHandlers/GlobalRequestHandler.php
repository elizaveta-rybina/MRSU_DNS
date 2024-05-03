<?php

require_once 'repositories/domain/DomainRepositoryInterface.php';
require_once 'entities/Domain.php';

class GlobalRequestHandler
{
  private DomainRepositoryInterface $domainRepository;

  public function __construct(DomainRepositoryInterface $domainRepository)
  {
    $this->domainRepository = $domainRepository;
  }

  public function processRequest(string $method, ?string $id): void
  {
    if ($id) {
      $this->processResourceRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  private function processResourceRequest(string $method, string $id): void
  {
    $domain = $this->domainRepository->get($id);
    switch ($method) {
      case "GET":
        echo json_encode($domain);
        break;

      case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $errors = $this->getValidationErrors($data, false);
        if (!empty($errors)) {
          http_response_code(403);
          echo json_encode(["errors" => $errors]);
        } else {
          $rows = $this->domainRepository->update($domain, $data);
          
          echo json_encode([
            "message" => "domain $id updated",
            "rows" => $rows
          ]);
        }
        break;

      case "DELETE":
        $this->domainRepository->delete($id);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PATCH, DELETE");
    }
  }

  private function processCollectionRequest(string $method): void
  {
    switch ($method) {
      case "GET":
        echo json_encode($this->domainRepository->getAll());
        break;

      case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $errors = $this->getValidationErrors($data);

        if (!empty($errors)) {
          http_response_code(403);
          echo json_encode(["errors" => $errors]);
        } else {
          $this->domainRepository->add($data);
          http_response_code(201);
        }
        break;

      default:
        http_response_code(405);
        header("Allow: GET, POST");
    }
  }

  private function getValidationErrors(array $data, bool $is_new = true): array
  {
    $errors = [];

    if ($is_new && empty($data["name"])) {
      $errors[] = "name is required";
    }
    return $errors;
  }
}
