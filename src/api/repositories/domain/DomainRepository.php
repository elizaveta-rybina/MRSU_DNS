<?php

require_once 'data/DbContext.php';
require_once 'entities/Domain.php';
require_once 'DomainRepositoryInterface.php';
require_once 'entities/SOA.php'; // Подключаем класс SOA
require_once 'repositories/userDomain/UserDomainRepository.php'; // Подключаем репозиторий для userDomains
require_once 'entities/User.php'; // Подключаем класс User

class DomainRepository implements DomainRepositoryInterface
{
  private $userDomainRepo;

  public function __construct()
  {
    $this->userDomainRepo = new UserDomainRepository();
  }

  /**
   * Проверяет, имеет ли пользователь указанную роль для домена.
   *
   * @param int $userId ID пользователя.
   * @param int $domainId ID домена.
   * @param UserRole $requiredRole Роль, необходимая для выполнения действия.
   * @return bool Возвращает true, если пользователь имеет указанную роль, иначе false.
   */
  private function hasRoleForDomain(int $userId, ?int $domainId, UserRole $requiredRole): bool
  {
    // Если domainId не предоставлен, проверяем роль пользователя глобально
    if ($domainId === null) {
      $users = $this->userDomainRepo->getAllUsers(); // Метод для получения всех пользователей
      foreach ($users as $user) {
        if ($user->getId() === $userId) {
          return $user->getRole() === $requiredRole;
        }
      }
      return false;
    }

    // Если domainId предоставлен, проверяем роль пользователя для конкретного домена
    $users = $this->userDomainRepo->getUsersForDomain($domainId);
    foreach ($users as $user) {
      if ($user->getId() === $userId) {
        return $user->getRole() === $requiredRole;
      }
    }
    return false;
  }

  /**
   * Получает все домены из базы данных, отсортированные по указанному столбцу.
   *
   * @param string $orderby Столбец для сортировки (по умолчанию "id").
   * @return Domain[] Массив объектов Domain.
   */
  public function getAll(string $orderby = "id"): array
  {
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM domains ORDER BY $orderby");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $domains = [];
    foreach ($data as $row) {
      $soa = new SOA(
        $row['primary_ns'],
        $row['admin_email'],
        $row['serial'],
        $row['refresh'],
        $row['retry'],
        $row['expire'],
        $row['ttl']
      );

      $domain = new Domain(
        $row['id'],
        $row['name'],
        $soa,
        $row['created'],
        $row['updated'],
        $row['expires']
      );

      $domains[] = $domain;
    }

    return $domains;
  }

  /**
   * Получает домен по его ID с учетом прав доступа пользователя.
   *
   * @param int $id ID домена.
   * @param int $userId ID пользователя.
   * @return Domain|null Объект Domain, если пользователь имеет доступ, иначе null.
   */
  public function get(int $id, int $userId): ?Domain
  {
    // Получаем домен по ID
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("SELECT * FROM domains WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
      return null; // Домен не найден
    }

    $soa = new SOA(
      $data['primary_ns'],
      $data['admin_email'],
      $data['serial'],
      $data['refresh'],
      $data['retry'],
      $data['expire'],
      $data['ttl']
    );

    $domain = new Domain(
      $data['id'],
      $data['name'],
      $soa,
      $data['created'],
      $data['updated'],
      $data['expires']
    );

    // Проверяем, есть ли у пользователя доступ к этому домену
    $users = $this->userDomainRepo->getUsersForDomain($id);
    $hasAccess = false;

    foreach ($users as $user) {
      if ($user->getId() === $userId) {
        $hasAccess = true;
        break; // Пользователь имеет доступ
      }
    }

    return $hasAccess ? $domain : null; // Возвращаем домен, если доступ есть, иначе null
  }

  /**
   * Удаляет домен по его ID из базы данных и удаляет все связи с пользователями.
   * Проверяет, есть ли у пользователя доступ к домену перед удалением.
   *
   * @param int $id ID домена.
   * @param int $userId ID пользователя.
   * @return bool Возвращает true, если удаление прошло успешно, иначе false.
   */
  public function delete(int $id, int $userId): bool
  {
    // Проверяем, имеет ли пользователь доступ к удалению домена
    if (!$this->hasRoleForDomain($userId, $id, UserRole::ADMIN) && !$this->hasRoleForDomain($userId, $id, UserRole::SUPER)) {
      return false; // Пользователь не имеет права на удаление
    }

    // Удаляем домен по ID
    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("DELETE FROM domains WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $result = $stmt->execute();

    if ($result) {
      // Удаляем связи с пользователями
      $this->userDomainRepo->removeAllUsersFromDomain($id);
    }

    return $result; // Возвращаем результат выполнения операции
  }

  /**
   * Обновляет данные существующего домена.
   * Проверяет, есть ли у пользователя доступ к домену перед обновлением.
   *
   * @param Domain $current Текущий объект Domain.
   * @param Domain $new Новый объект Domain с обновленными данными.
   * @param int $userId ID пользователя.
   * @return bool Возвращает true, если обновление прошло успешно, иначе false.
   */
  public function update(Domain $current, Domain $new, int $userId): bool
  {
    // Проверяем доступ пользователя к домену
    if (!$this->hasRoleForDomain($userId, $current->getId(), UserRole::EDITOR) && !$this->hasRoleForDomain($userId, $current->getId(), UserRole::SUPER) && !$this->hasRoleForDomain($userId, $current->getId(), UserRole::ADMIN)) {
      return false; // Пользователь не имеет права на обновление
    }

    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("UPDATE domains SET name = :name, primary_ns = :primary_ns, admin_email = :admin_email, serial = :serial, refresh = :refresh, retry = :retry, expire = :expire, ttl = :ttl, created = :created, updated = :updated, expires = :expires WHERE id = :id");
    $stmt->bindValue(":name", $new->getName(), PDO::PARAM_STR);
    $stmt->bindValue(":primary_ns", $new->getSOA()->getPrimaryNS(), PDO::PARAM_STR);
    $stmt->bindValue(":admin_email", $new->getSOA()->getAdminEmail(), PDO::PARAM_STR);
    $stmt->bindValue(":serial", $new->getSOA()->getSerial(), PDO::PARAM_INT);
    $stmt->bindValue(":refresh", $new->getSOA()->getRefresh(), PDO::PARAM_INT);
    $stmt->bindValue(":retry", $new->getSOA()->getRetry(), PDO::PARAM_INT);
    $stmt->bindValue(":expire", $new->getSOA()->getExpire(), PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $new->getSOA()->getTTL(), PDO::PARAM_INT);
    $stmt->bindValue(":created", $new->getCreated(), PDO::PARAM_STR);
    $stmt->bindValue(":updated", $new->getUpdated(), PDO::PARAM_STR);
    $stmt->bindValue(":expires", $new->getExpires(), PDO::PARAM_STR);
    $stmt->bindValue(":id", $current->getId(), PDO::PARAM_INT);
    $result = $stmt->execute();

    return $result; // Возвращаем результат выполнения операции
  }

  /**
   * Добавляет новый домен в базу данных.
   * Проверяет, есть ли у пользователя доступ к добавлению домена.
   *
   * @param Domain $data Объект Domain с данными для добавления.
   * @param int $userId ID пользователя.
   * @return bool Возвращает true, если добавление прошло успешно, иначе false.
   */
  public function add(Domain $data, int $userId): bool
  {
    // Проверяем, имеет ли пользователь доступ к добавлению домена
    if (!$this->hasRoleForDomain($userId, null, UserRole::ADMIN) && !$this->hasRoleForDomain($userId, null, UserRole::SUPER)) {
      return false; // Пользователь не имеет права на добавление
    }

    $connection = DbContext::getConnection();
    $stmt = $connection->prepare("INSERT INTO domains (name, primary_ns, admin_email, serial, refresh, retry, expire, ttl, created, updated, expires) VALUES (:name, :primary_ns, :admin_email, :serial, :refresh, :retry, :expire, :ttl, :created, :updated, :expires)");
    $stmt->bindValue(":name", $data->getName(), PDO::PARAM_STR);
    $stmt->bindValue(":primary_ns", $data->getSOA()->getPrimaryNS(), PDO::PARAM_STR);
    $stmt->bindValue(":admin_email", $data->getSOA()->getAdminEmail(), PDO::PARAM_STR);
    $stmt->bindValue(":serial", $data->getSOA()->getSerial(), PDO::PARAM_INT);
    $stmt->bindValue(":refresh", $data->getSOA()->getRefresh(), PDO::PARAM_INT);
    $stmt->bindValue(":retry", $data->getSOA()->getRetry(), PDO::PARAM_INT);
    $stmt->bindValue(":expire", $data->getSOA()->getExpire(), PDO::PARAM_INT);
    $stmt->bindValue(":ttl", $data->getSOA()->getTTL(), PDO::PARAM_INT);
    $stmt->bindValue(":created", $data->getCreated(), PDO::PARAM_STR);
    $stmt->bindValue(":updated", $data->getUpdated(), PDO::PARAM_STR);
    $stmt->bindValue(":expires", $data->getExpires(), PDO::PARAM_STR);
    $result = $stmt->execute();

    $domainId = $connection->lastInsertId();
    $this->addUserToDomain($userId, $domainId);

    return $result; // Возвращаем результат выполнения операции
  }

  /**
   * Добавляет пользователя к домену.
   *
   * @param int $userId ID пользователя.
   * @param int $domainId ID домена.
   */
  public function addUserToDomain(int $userId, int $domainId): void
  {
    $this->userDomainRepo->addUserToDomain($userId, $domainId);
  }

  /**
   * Удаляет пользователя из домена.
   *
   * @param int $userId ID пользователя.
   * @param int $domainId ID домена.
   */
  public function removeUserFromDomain(int $userId, int $domainId): void
  {
    $this->userDomainRepo->removeUserFromDomain($userId, $domainId);
  }

  /**
   * Получает всех пользователей для данного домена.
   *
   * @param int $domainId ID домена.
   * @return User[] Массив объектов User.
   */
  public function getUsersForDomain(int $domainId): array
  {
    return $this->userDomainRepo->getUsersForDomain($domainId);
  }

  /**
   * Получает все домены, доступные указанному пользователю.
   *
   * @param int $userId ID пользователя.
   * @return Domain[] Массив объектов Domain, доступных пользователю.
   */
  public function getDomainsAccessibleByUser(int $userId): array
  {
    $allDomains = $this->getAll();
    $accessibleDomains = [];

    foreach ($allDomains as $domain) {
      $users = $this->userDomainRepo->getUsersForDomain($domain->getId());

      foreach ($users as $user) {
        if ($user->getId() === $userId) {
          $accessibleDomains[] = $domain;
          break; // Пользователь имеет доступ, не нужно проверять остальных
        }
      }
    }
    return $accessibleDomains;
  }
}
