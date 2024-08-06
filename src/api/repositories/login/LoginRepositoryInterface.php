<?php

/**
 * Интерфейс для управления записями логинов в базе данных.
 */
interface LoginRepositoryInterface
{
	/**
	 * Получить запись логина по ID пользователя.
	 *
	 * @param int $userId ID пользователя.
	 * @return Login|null Объект Login, если найден, иначе null.
	 */
	public function getByUserId(int $userId): ?Login;

	/**
	 * Обновить запись логина.
	 *
	 * @param Login $login Объект Login для обновления.
	 * @return void
	 */
	public function update(Login $login): void;

	/**
	 * Создать новую запись логина.
	 *
	 * @param Login $login Объект Login для создания.
	 * @return void
	 */
	public function create(Login $login): void;

	/**
	 * Удалить запись логина по ID пользователя.
	 *
	 * @param int $userId ID пользователя.
	 * @return void
	 */
	public function deleteByUserId(int $userId): void;
}
