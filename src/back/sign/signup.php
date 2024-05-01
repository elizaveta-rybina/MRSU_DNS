<?php
  require_once "../dataBase.php";

	if(!isset($_REQUEST['login']) || !isset($_REQUEST['password']))
	{
		die(print_r('No data set.'));
	}

	if(!isset($_REQUEST['name']) || !isset($_REQUEST['lastName']) || !isset($_REQUEST['FatherName']) || !isset($_REQUEST['email']))
	{
		die(print_r('No data set.'));
	}

  $name = $_REQUEST['name'];
  $lastName = $_REQUEST['lastName'];
  $FatherName = $_REQUEST['FatherName'];
	$email = $_REQUEST['email'];
	$login = $_REQUEST['login'];
	$password = $_REQUEST['password'];

	$fetchUsers = $connection->prepare("SELECT `id` FROM `users` WHERE `login` = ?");
	$fetchUsers->execute(array($login)) or die(print_r($fetchUsers->errorInfo()));
	$existedUsers = $fetchUsers->fetchAll(PDO::FETCH_ASSOC);

	if(count($existedUsers) != 0)
	{
		$already_exist['Message'] = "Already exist";
		die(print(json_encode($already_exist)));
	}

	$fetchLastUserId = $connection->prepare("SELECT MAX(`id`) AS `id` FROM `users`");
	$fetchLastUserId->execute() or die(print_r($fetchLastUserId->errorInfo()));
	$lastUserId = $fetchLastUserId->fetch(PDO::FETCH_ASSOC);

	$newId = $lastUserId['id'] + 1;

	$createCustomer = $connection->prepare
	('
		INSERT INTO `users`
		(`name`, `lastName`, `FatherName`, `id`, `email`, `login`, `password`)
		VALUES (?, ?, ?, ?, ?, ?, ?)
	');

  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  $createCustomer->bindParam(1, $name, PDO::PARAM_STR);
  $createCustomer->bindParam(2, $lastName, PDO::PARAM_STR);
	$createCustomer->bindParam(3, $FatherName, PDO::PARAM_STR);
	$createCustomer->bindParam(4, $newId, PDO::PARAM_INT);
	$createCustomer->bindParam(5, $email, PDO::PARAM_STR);
	$createCustomer->bindParam(6, $login, PDO::PARAM_STR);
	$createCustomer->bindParam(7, $password_hash, PDO::PARAM_STR);
	$createCustomer->execute() or die(print_r($createCustomer->errorInfo()));

	$auto_expire = 12 * 60 * 60; // 12 hours
	$token = password_hash(uniqid(), PASSWORD_DEFAULT);
	$redis->hset('authentication:' . $login, "token", $token);
	$redis->expire('authentication', $auto_expire);
	setcookie('login', $login, time() + $auto_expire, '/');
	setcookie('token', $token, time() + $auto_expire, '/');

	$success['Message'] = "Success";
	print(json_encode($success));

?>
