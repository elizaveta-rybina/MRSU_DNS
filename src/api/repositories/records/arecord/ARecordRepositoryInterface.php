<?php

interface ARecordRepositoryInterface
{
	//TODO: продумать что будет, если записей будет миллион
	public function getAll(string $orderby): array;
	public function get(int $id): ?ARecord;
	public function delete(int $id): void;
	public function update(ARecord $current, ARecord $new): void;
	public function add(ARecord $data): void;
}
