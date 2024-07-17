<?php

require_once 'repositories/domain/DomainRepositoryInterface.php';
require_once 'entities/Domain.php';

class GlobalRequestHandler
{
  private $domainRepository;

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
          // Создаем новый объект Domain на основе данных
          $newDomain = new Domain(
            $domain->id, // передаем текущий id домена
            $data['name'] ?? $domain->name, // обновляем имя домена, если передано новое имя
            new SOA(
              $data['soa']['primary_ns'] ?? $domain->soa->primary_ns,
              $data['soa']['admin_email'] ?? $domain->soa->admin_email,
              $data['soa']['serial'] ?? $domain->soa->serial,
              $data['soa']['refresh'] ?? $domain->soa->refresh,
              $data['soa']['retry'] ?? $domain->soa->retry,
              $data['soa']['expire'] ?? $domain->soa->expire,
              $data['soa']['ttl'] ?? $domain->soa->ttl
            ),
            $data['created'] ?? $domain->created,
            $data['updated'] ?? $domain->updated,
            $data['expires'] ?? $domain->expires
          );

          // Вызываем метод update() репозитория
          $this->domainRepository->update($domain, $newDomain);

          echo json_encode([
            "message" => "Domain $id updated",
          ]);
        }
        break;

      case "DELETE":
        $this->domainRepository->delete($id);
        echo json_encode([
          "message" => "Domain $id deleted",
        ]);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PUT, DELETE");
        break;
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
          // Создаем объект Domain на основе данных
          $domain = new Domain(
            null, // или $data['id'], если у вас есть идентификатор
            $data['name'],
            new SOA(
              $data['soa']['primary_ns'],
              $data['soa']['admin_email'],
              $data['soa']['serial'],
              $data['soa']['refresh'],
              $data['soa']['retry'],
              $data['soa']['expire'],
              $data['soa']['ttl']
            ),
            $data['created'],
            $data['updated'],
            $data['expires']
          );

          // Добавляем созданный объект Domain
          $this->domainRepository->add($domain);

          echo json_encode([
            "message" => "Succes",
          ]);
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
