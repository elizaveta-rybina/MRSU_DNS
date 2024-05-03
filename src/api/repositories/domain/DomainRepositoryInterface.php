<?php

interface DomainRepositoryInterface
{
	public function getAll(string $orderby = "id DESC"): array;
	public function get(int $id): ?Domain;
	public function delete(int $id): void;
	public function update(Domain $current, Domain $new): void;
	public function add(Domain $data): void;
}
