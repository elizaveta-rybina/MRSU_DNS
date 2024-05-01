<?php

require_once 'helpers/DbConnection.php';

class DomainController
{

	//Method for getting all domains
	public function getAllDomains($orderby = "id DESC")
	{
		try {
			$connection = DbConnection::getConnection();
			$stmt = $connection->prepare("SELECT * FROM `domains` ORDER BY $orderby");
			$stmt->execute();
			$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($records) {
				echo json_encode($records);
			} else {
				http_response_code(404);
				$result = [
					'status' => false,
					'message' => 'Domains not found'
				];
				echo json_encode($result);
			}
		} catch (PDOException $e) {
			echo json_encode(['error' => 'Database connection error']);
		}
	}

	//Method for getting domain by id
	public function getDomain($id)
	{
		try {
			$connection = DbConnection::getConnection();
			$stmt = $connection->prepare("SELECT * FROM `domains` WHERE `id` = ?");
			$stmt->execute(array($id));
			$records = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($records) {
				echo json_encode($records);
			} else {
				http_response_code(404);
				$result = [
					'status' => false,
					'message' => 'Domain not found'
				];
				echo json_encode($result);
			}
		} catch (PDOException $e) {
			echo json_encode(['error' => 'Database connection error']);
		}
	}
}
