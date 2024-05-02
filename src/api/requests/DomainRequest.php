<?php

require_once 'gateways/DomainGateway.php';

class domainRequest
{
  public function processRequest(string $method, ?string $id): void
  {
    if ($id) {
      $this->processResourceRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  //TODO: сделать delete + patch через Post
  private function processResourceRequest(string $method, string $id): void
  {
    $gateway = new DomainController();
    $domain = $gateway->get($id);
    switch ($method) {
      case "GET":
        echo json_encode($domain);
        break;

      case "PATCH":
        $data = (array) json_decode(file_get_contents("php://input"), true);
        $errors = $this->getValidationErrors($data, false);
        if (!empty($errors)) {
          http_response_code(422);
          echo json_encode(["errors" => $errors]);
          break;
        } else {
          $rows = $gateway->update($domain, $data);

          echo json_encode([
            "message" => "domain $id updated",
            "rows" => $rows
          ]);
          break;
        }

      case "DELETE":
        $rows = $gateway->delete($id);

        echo json_encode([
          "message" => "domain $id deleted",
          "rows" => $rows
        ]);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PATCH, DELETE");
    }
  }

  private function processCollectionRequest(string $method): void
  {
    $gateway = new DomainController();
    switch ($method) {
      case "GET":
        echo json_encode($gateway->getAll());
        break;

      case "POST":
        $data = (array) json_decode(file_get_contents("php://input"), true);

        var_dump($data);

        $errors = $this->getValidationErrors($data);

        if (!empty($errors)) {
          http_response_code(422);
          echo json_encode(["errors" => $errors]);
          break;
        } else {
          $id = $gateway->add($data);
          echo json_encode([
            "message" => "domain created",
            "id" => $id
          ]);
          break;
        }

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
