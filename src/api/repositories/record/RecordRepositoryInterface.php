<?php

interface RecordRepositoryInterface
{
	//TODO: продумать что будет, если записей будет миллион
	public function getAll(string $orderby, int $domainId): array;
		
	public function get(int $id): ?array;
	public function delete(int $id): int;
	public function update(array $current, array $new): int;
	public function add(array $data): int;
}
