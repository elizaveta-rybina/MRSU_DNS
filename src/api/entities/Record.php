<?php

class Record implements JsonSerializable
{
	private ?int $id;
	private int $domainId;
	private string $name;
	private ?string $content;
	private ?int $priority;
	private ?int $ttl;
	private string $type;
	private string $createdAt;
	private string $updatedAt;

	/**
	 * Конструктор класса Record.
	 *
	 * @param int|null $id ID записи. Может быть null для новой записи.
	 * @param int $domainId ID домена, к которому принадлежит запись.
	 * @param string $name Имя записи.
	 * @param string|null $content Содержимое записи. Может быть null.
	 * @param int|null $priority Приоритет записи. Может быть null.
	 * @param int|null $ttl Время жизни записи в секундах. Может быть null.
	 * @param string $type Тип записи (например, 'A', 'MX', 'TXT').
	 * @param string $createdAt Дата и время создания записи.
	 * @param string $updatedAt Дата и время последнего обновления записи.
	 */
	public function __construct(
		?int $id,
		int $domainId,
		string $name,
		?string $content,
		?int $priority,
		?int $ttl,
		string $type,
		string $createdAt,
		string $updatedAt
	) {
		$this->id = $id ?? 0;  // Устанавливаем значение по умолчанию, если null
		$this->domainId = $domainId;
		$this->name = $name;
		$this->content = $content;
		$this->priority = $priority;
		$this->ttl = $ttl;
		$this->type = $type;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
	}

	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'domain_id' => $this->domainId,
			'name' => $this->name,
			'content' => $this->content,
			'priority' => $this->priority,
			'ttl' => $this->ttl,
			'type' => $this->type,
			'created_at' => $this->createdAt,
			'updated_at' => $this->updatedAt,
		];
	}


	// Геттеры
	/**
	 * Получить ID записи.
	 *
	 * @return int ID записи.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Получить ID домена, к которому принадлежит запись.
	 *
	 * @return int ID домена.
	 */
	public function getDomainId(): int
	{
		return $this->domainId;
	}

	/**
	 * Получить имя записи.
	 *
	 * @return string Имя записи.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Получить содержимое записи.
	 *
	 * @return string|null Содержимое записи или null, если не задано.
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}

	/**
	 * Получить приоритет записи.
	 *
	 * @return int|null Приоритет записи или null, если не задано.
	 */
	public function getPriority(): ?int
	{
		return $this->priority;
	}

	/**
	 * Получить время жизни записи.
	 *
	 * @return int|null Время жизни записи в секундах или null, если не задано.
	 */
	public function getTTL(): ?int
	{
		return $this->ttl;
	}

	/**
	 * Получить тип записи.
	 *
	 * @return string Тип записи (например, 'A', 'MX', 'TXT').
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * Получить дату и время создания записи.
	 *
	 * @return string Дата и время создания записи.
	 */
	public function getCreatedAt(): string
	{
		return $this->createdAt;
	}

	/**
	 * Получить дату и время последнего обновления записи.
	 *
	 * @return string Дата и время последнего обновления записи.
	 */
	public function getUpdatedAt(): string
	{
		return $this->updatedAt;
	}

	// Сеттеры
	/**
	 * Установить ID записи.
	 *
	 * @param int $id ID записи.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * Установить ID домена, к которому принадлежит запись.
	 *
	 * @param int $domainId ID домена.
	 */
	public function setDomainId(int $domainId): void
	{
		$this->domainId = $domainId;
	}

	/**
	 * Установить имя записи.
	 *
	 * @param string $name Имя записи.
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * Установить содержимое записи.
	 *
	 * @param string|null $content Содержимое записи или null.
	 */
	public function setContent(?string $content): void
	{
		$this->content = $content;
	}

	/**
	 * Установить приоритет записи.
	 *
	 * @param int|null $priority Приоритет записи или null.
	 */
	public function setPriority(?int $priority): void
	{
		$this->priority = $priority;
	}

	/**
	 * Установить время жизни записи.
	 *
	 * @param int|null $ttl Время жизни записи в секундах или null.
	 */
	public function setTTL(?int $ttl): void
	{
		$this->ttl = $ttl;
	}

	/**
	 * Установить тип записи.
	 *
	 * @param string $type Тип записи (например, 'A', 'MX', 'TXT').
	 */
	public function setType(string $type): void
	{
		$this->type = $type;
	}

	/**
	 * Установить дату и время создания записи.
	 *
	 * @param string $createdAt Дата и время создания записи.
	 */
	public function setCreatedAt(string $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	/**
	 * Установить дату и время последнего обновления записи.
	 *
	 * @param string $updatedAt Дата и время последнего обновления записи.
	 */
	public function setUpdatedAt(string $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}
}
