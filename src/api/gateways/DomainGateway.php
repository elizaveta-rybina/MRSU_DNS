<?php

require_once 'helpers/DbConnection.php';

class DomainController
{
	// Array of database queries
	private $queries = [
		'getAllDomains' => "SELECT * FROM `domains` ORDER BY ?",
		'getDomainById' => "SELECT * FROM `domains` WHERE `id` = ?",
	];

	// General method for performing database queries
	private function executeQuery($queryKey, $params = []): array
	{
		try {
			$connection = DbConnection::getConnection();
			$stmt = $connection->prepare($this->queries[$queryKey]);
			$stmt->execute($params);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($result) {
				return $result;
			} else {
				http_response_code(404);
				$error = [
					'status' => false,
					'message' => 'Not found'
				];
				return $error;
			}
		} catch (PDOException $e) {
			return ['error' => 'Database connection error'];
		}
	}

	//Method for getting all domains
	public function getAll($orderby = "id DESC"): void
	{
		$records = $this->executeQuery("getAllDomains", [$orderby]);
		echo json_encode($records);
	}

	//Method for getting domain by id
	public function get($id): void
	{
		$records = $this->executeQuery("getDomainById", [$id]);
		echo json_encode($records);
	}
}
