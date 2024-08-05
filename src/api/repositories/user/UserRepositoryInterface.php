<?php

require_once 'entities/User.php';

interface UserRepositoryInterface
{
	/**
	 * Получает всех пользователей из базы данных.
	 *
	 * @return User[] Массив объектов User, представляющих всех пользователей.
	 */
	public function getAll(): array;

	/**
	 * Получает пользователя по его ID.
	 *
	 * @param int $id ID пользователя.
	 * @return User|null Возвращает объект User, если пользователь найден, иначе null.
	 */
	public function get(int $id): ?User;

	/**
	 * Удаляет пользователя по его ID.
	 *
	 * @param int $id ID пользователя.
	 */
	public function delete(int $id): void;

	/**
	 * Обновляет данные пользователя.
	 *
	 * @param User $user Объект User с обновленными данными.
	 */
	public function update(User $user): void;

	/**
	 * Добавляет нового пользователя.
	 *
	 * @param User $user Объект User с данными нового пользователя.
	 */
	public function add(User $user): void;
}
