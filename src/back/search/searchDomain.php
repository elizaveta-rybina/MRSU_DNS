<?php
	require_once("../dataBase.php");

	if(!isset($_REQUEST['inputBox']))
	{
		die(print_r('No data fields.'));
	}

	if(isset($_REQUEST['offset']) && isset($_REQUEST['rowsCount']))
	{
		$fetchDomains = $connection->prepare("SELECT * FROM `domains` WHERE `name` LIKE :inputBox ORDER BY `name` LIMIT :offset, :rowsCount");

		$fetchDomains->bindValue(':inputBox', '%'.$_REQUEST['inputBox'].'%', PDO::PARAM_STR);
		$fetchDomains->bindValue(':offset', (int) trim($_REQUEST['offset']), PDO::PARAM_INT);
		$fetchDomains->bindValue(':rowsCount', (int) trim($_REQUEST['rowsCount']), PDO::PARAM_INT);
	} else
	{
		$fetchDomains = $connection->prepare("SELECT * FROM `domains` WHERE `name` LIKE :inputBox ORDER BY `name`");

		$fetchDomains->bindValue(':inputBox', '%'.$_REQUEST['inputBox'].'%', PDO::PARAM_STR);
	}
	$fetchDomains->execute() or die(print_r($fetchDomains->errorInfo()));

	$domains = $fetchDomains->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($domains);
