<?php

interface DomainRepositoryInterface
{
	public function getAll(string $orderby): array;
	public function get(int $id): ?array;
	public function delete(int $id): int;
	public function update(array $current, array $new): int;
	public function add(array $data): int;
}
