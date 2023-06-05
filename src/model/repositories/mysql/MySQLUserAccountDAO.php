<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\domain\entities\account\UserAccount;
    use pw2s3\clinicaveterinaria\persistence\util\DAO;
    use pw2s3\clinicaveterinaria\domain\entities\account\UserRole;
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;
    use InvalidArgumentException;
    use PDOException;
    use Exception;

    final class MySQLUserAccountDAO implements DAO {
        public function insert(mixed $entity) : void {
            if (!static::isAUserAccount($entity))
                throw new InvalidArgumentException("Entity must be of type UserAccount!");
            
            $sql = "INSERT INTO user_account(username, password, role, registration_date) VALUES" .
                    "(:username, :password, :role, :registration_date)";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([
                    "username" => $entity->getUsername(),
                    "password" => $entity->getPassword(),
                    "role" => $entity->getRole()->value(),
                    "registration_date" => $entity->getRegistrationDate()
                ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public static function entryToEntity(array $entry) : mixed {
            return new UserAccount($entry["username"], $entry["password"], 
                UserRole::fromString($entry["role"]),
                DateTimeImmutable::createFromFormat("Y-m-d", $entry["registration_date"]),
                RegistrationStatus::from($entry["status"]), $entry["id"]
            );
        }

        public function findOneByKey(mixed $key): mixed {
            $sql = "SELECT id, username, password, role, registration_date, status FROM " . 
                    "user_account WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);

                $accountEntry = $statement->fetch();

                if (!$accountEntry)
                    return null;
                
                return static::entryToEntity($accountEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByUsername(string $username) : mixed {
            $sql = "SELECT id, username, password, role, registration_date, status FROM " .
                    "user_account WHERE username = :username";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "username" => $username ]);

                $accountEntry = $statement->fetch();

                if (!$accountEntry)
                    return null;

                return static::entryToEntity($accountEntry);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findAll() : array {
            $sql = "SELECT id, username, password, role, registration_date, status FROM " . 
                    "user_account ";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([]);

                $accountEntries = $statement->fetchAll();

                if (!$accountEntries)
                    return [];

                $accounts = [];

                foreach($accountEntries as $accountEntry)
                    $accounts[] = static::entryToEntity($accountEntry);

                return $accounts;
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function update(mixed $entity) : void {
            $sql = "UPDATE user_account SET role = :role WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $entity->getId() ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function deleteByKey(mixed $key): void {
            $sql = "DELETE FROM user_account WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public static function isAUserAccount(mixed $entity) : bool {
            return $entity instanceof UserAccount;
        }
    }
?>
