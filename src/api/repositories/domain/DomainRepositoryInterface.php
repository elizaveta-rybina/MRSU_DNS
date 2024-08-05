<?php

require_once 'entities/Domain.php';
require_once 'entities/User.php';
require_once 'entities/UserRole.php';

interface DomainRepositoryInterface
{
	/**
	 * Получает все домены из базы данных, отсортированные по указанному столбцу.
	 *
	 * @param string $orderby Столбец для сортировки (по умолчанию "id").
	 * @return Domain[] Массив объектов Domain.
	 */
	public function getAll(string $orderby = "id"): array;

	/**
	 * Получает домен по его ID с учетом прав доступа пользователя.
	 *
	 * @param int $id ID домена.
	 * @param int $userId ID пользователя.
	 * @return Domain|null Объект Domain, если пользователь имеет доступ, иначе null.
	 */
	public function get(int $id, int $userId): ?Domain;

	/**
	 * Удаляет домен по его ID из базы данных и удаляет все связи с пользователями.
	 * Проверяет, есть ли у пользователя доступ к домену перед удалением.
	 *
	 * @param int $id ID домена.
	 * @param int $userId ID пользователя.
	 * @return bool Возвращает true, если удаление прошло успешно, иначе false.
	 */
	public function delete(int $id, int $userId): bool;

	/**
	 * Обновляет данные существующего домена.
	 * Проверяет, есть ли у пользователя доступ к домену перед обновлением.
	 *
	 * @param Domain $current Текущий объект Domain.
	 * @param Domain $new Новый объект Domain с обновленными данными.
	 * @param int $userId ID пользователя.
	 * @return bool Возвращает true, если обновление прошло успешно, иначе false.
	 */
	public function update(Domain $current, Domain $new, int $userId): bool;

	/**
	 * Добавляет новый домен в базу данных.
	 * Проверяет, есть ли у пользователя доступ к добавлению домена.
	 *
	 * @param Domain $data Объект Domain с данными для добавления.
	 * @param int $userId ID пользователя.
	 * @return bool Возвращает true, если добавление прошло успешно, иначе false.
	 */
	public function add(Domain $data, int $userId): bool;

	/**
	 * Добавляет пользователя к домену.
	 *
	 * @param int $userId ID пользователя.
	 * @param int $domainId ID домена.
	 */
	public function addUserToDomain(int $userId, int $domainId): void;

	/**
	 * Удаляет пользователя из домена.
	 *
	 * @param int $userId ID пользователя.
	 * @param int $domainId ID домена.
	 */
	public function removeUserFromDomain(int $userId, int $domainId): void;

	/**
	 * Получает всех пользователей для данного домена.
	 *
	 * @param int $domainId ID домена.
	 * @return User[] Массив объектов User.
	 */
	public function getUsersForDomain(int $domainId): array;

	/**
	 * Получает все домены, доступные указанному пользователю.
	 *
	 * @param int $userId ID пользователя.
	 * @return Domain[] Массив объектов Domain, доступных пользователю.
	 */
	public function getDomainsAccessibleByUser(int $userId): array;
}
