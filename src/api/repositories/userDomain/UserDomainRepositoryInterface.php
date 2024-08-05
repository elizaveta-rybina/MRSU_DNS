<?php

interface UserDomainRepositoryInterface
{
    /**
     * Добавляет связь между пользователем и доменом.
     *
     * @param int $userId Идентификатор пользователя.
     * @param int $domainId Идентификатор домена.
     */
    public function addUserToDomain(int $userId, int $domainId): void;

    /**
     * Удаляет связь между пользователем и доменом.
     *
     * @param int $userId Идентификатор пользователя.
     * @param int $domainId Идентификатор домена.
     */
    public function removeUserFromDomain(int $userId, int $domainId): void;

    /**
     * Удаляет все связи для указанного домена.
     *
     * @param int $domainId Идентификатор домена.
     */
    public function removeAllUsersFromDomain(int $domainId): void;

    /**
     * Получает список пользователей для указанного домена.
     *
     * @param int $domainId Идентификатор домена.
     * @return User[] Список пользователей.
     */
    public function getUsersForDomain(int $domainId): array;

    /**
     * Получает список доменов для указанного пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return Domain[] Список доменов.
     */
    public function getDomainsForUser(int $userId): array;
}
