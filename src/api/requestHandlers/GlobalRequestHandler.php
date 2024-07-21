<?php

require_once 'repositories/domain/DomainRepositoryInterface.php';
require_once 'repositories/records/RecordRepositoryInterface.php';
require_once 'entities/Domain.php';
require_once 'entities/Record.php';

class GlobalRequestHandler
{
  private $domainRepository;
  private $recordRepository;

  public function __construct(DomainRepositoryInterface $domainRepository, RecordRepositoryInterface $recordRepository)
  {
    $this->domainRepository = $domainRepository;
    $this->recordRepository = $recordRepository;
  }

  public function processRequest(string $method, ?int $id, ?string $resource = null, ?string $recordType = null, ?int $recordId = null): void
  {
    if ($resource === "records") {
      $this->processRecordRequest($method, $id, $recordType, $recordId);
    } elseif ($id) {
      $this->processResourceRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  private function processRecordRequest(string $method, int $domainId, ?string $type, ?int $recordId): void
  {
    switch ($method) {
      case 'GET':
        if ($recordId) {
          $record = $this->recordRepository->get($recordId);
          if ($record && ($type === null || $record->type === $type)) {
            echo json_encode($record);
          } else {
            http_response_code(404);
            echo json_encode(['message' => 'Record not found']);
          }
        } else {
          $records = $this->recordRepository->getAllByType($domainId, $type);
          echo json_encode($records);
        }
        break;

      case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = $this->getRecordValidationErrors($data);

        if (!empty($errors)) {
          http_response_code(403);
          echo json_encode(['errors' => $errors]);
        } else {
          $record = new Record(
            null,
            $domainId,
            $data['name'],
            $data['content'],
            $data['priority'],
            $data['ttl'],
            $data['type'],
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
          );

          $this->recordRepository->add($record);

          echo json_encode(['message' => 'Record created']);
          http_response_code(201);
        }
        break;

      case 'PUT':
        if ($recordId) {
          $data = json_decode(file_get_contents('php://input'), true);
          $errors = $this->getRecordValidationErrors($data, false);

          if (!empty($errors)) {
            http_response_code(403);
            echo json_encode(['errors' => $errors]);
          } else {
            $current = $this->recordRepository->get($recordId);
            if ($current) {
              $newRecord = new Record(
                $current->id,
                $domainId,
                $data['name'] ?? $current->name,
                $data['content'] ?? $current->content,
                $data['priority'] ?? $current->priority,
                $data['ttl'] ?? $current->ttl,
                $data['type'] ?? $current->type,
                $current->createdAt,
                date('Y-m-d H:i:s')
              );

              $this->recordRepository->update($current, $newRecord);

              echo json_encode(['message' => 'Record updated']);
            } else {
              http_response_code(404);
              echo json_encode(['message' => 'Record not found']);
            }
          }
        } else {
          http_response_code(400);
          echo json_encode(['message' => 'Record ID is required']);
        }
        break;

      case 'DELETE':
        if ($recordId) {
          $this->recordRepository->delete($recordId);
          echo json_encode(['message' => 'Record deleted']);
        } else {
          http_response_code(400);
          echo json_encode(['message' => 'Record ID is required']);
        }
        break;

      default:
        http_response_code(405);
        header('Allow: GET, POST, PUT, DELETE');
        break;
    }
  }


  private function processResourceRequest(string $method, int $id): void
  {
    $domain = $this->domainRepository->get($id);

    switch ($method) {
      case 'GET':
        echo json_encode($domain);
        break;

      case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = $this->getValidationErrors($data, false);

        if (!empty($errors)) {
          http_response_code(403);
          echo json_encode(['errors' => $errors]);
        } else {
          $newDomain = new Domain(
            $domain->id,
            $data['name'] ?? $domain->name,
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

          $this->domainRepository->update($domain, $newDomain);

          echo json_encode(['message' => "Domain $id updated"]);
        }
        break;

      case 'DELETE':
        $this->domainRepository->delete($id);
        echo json_encode(['message' => "Domain $id deleted"]);
        break;

      default:
        http_response_code(405);
        header('Allow: GET, PUT, DELETE');
        break;
    }
  }

  private function processCollectionRequest(string $method): void
  {
    switch ($method) {
      case 'GET':
        echo json_encode($this->domainRepository->getAll());
        break;

      case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = $this->getValidationErrors($data);

        if (!empty($errors)) {
          http_response_code(403);
          echo json_encode(['errors' => $errors]);
        } else {
          $domain = new Domain(
            null,
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

          $this->domainRepository->add($domain);

          echo json_encode(['message' => 'Success']);
          http_response_code(201);
        }
        break;

      default:
        http_response_code(405);
        header('Allow: GET, POST');
    }
  }

  private function getValidationErrors(array $data, bool $is_new = true): array
  {
    $errors = [];

    if ($is_new && empty($data['name'])) {
      $errors[] = 'name is required';
    }
    return $errors;
  }

  private function getRecordValidationErrors(array $data, bool $is_new = true): array
  {
    $errors = [];

    if ($is_new && empty($data['name'])) {
      $errors[] = 'name is required';
    }
    if (empty($data['type'])) {
      $errors[] = 'type is required';
    }

    return $errors;
  }
}
