<?php

/**
 * Интерфейс для работы с репозиторием записей.
 *
 * Определяет методы для получения, добавления, обновления и удаления записей.
 */
interface RecordRepositoryInterface
{
	/**
	 * Получить все записи с возможностью сортировки.
	 *
	 * @param string $orderby Поле и порядок сортировки записей. По умолчанию - "id DESC".
	 * @return Record[] Массив объектов Record.
	 */
	public function getAll(string $orderby = "id DESC"): array;

	/**
	 * Получить все записи для указанного домена и типа (если указан).
	 *
	 * @param int $domainId Идентификатор домена.
	 * @param string|null $type Тип записи. Если не указан, будут возвращены записи всех типов.
	 * @return Record[] Массив объектов Record.
	 */
	public function getAllByType(int $domainId, ?string $type = null): array;

	/**
	 * Получить запись по идентификатору.
	 *
	 * @param int $id Идентификатор записи.
	 * @return Record|null Объект Record, если запись найдена; иначе null.
	 */
	public function get(int $id): ?Record;

	/**
	 * Удалить запись по идентификатору.
	 *
	 * @param int $id Идентификатор записи.
	 */
	public function delete(int $id): void;

	/**
	 * Обновить запись.
	 *
	 * @param Record $current Текущая запись, которую нужно обновить.
	 * @param Record $new Новая запись с обновленными данными.
	 */
	public function update(Record $current, Record $new): void;

	/**
	 * Добавить новую запись.
	 *
	 * @param Record $data Запись для добавления.
	 */
	public function add(Record $data): void;
}
