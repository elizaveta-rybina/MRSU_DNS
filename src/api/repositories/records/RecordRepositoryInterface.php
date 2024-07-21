<?php

interface RecordRepositoryInterface
{
	public function getAll(string $orderby = "id DESC"): array;
	public function getAllByType(int $domainId, ?string $type = null): array;
	public function get(int $id): ?Record;
	public function delete(int $id): void;
	public function update(Record $current, Record $new): void;
	public function add(Record $data): void;
}
