<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\persistence\util\DAO;
    use pw2s3\clinicaveterinaria\domain\entities\tutor\Tutor;
    use pw2s3\clinicaveterinaria\model\repository\mysql\SingletonMySQLConnectionFactory;
    use pw2s3\clinicaveterinaria\util\CPF;
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use pw2s3\clinicaveterinaria\domain\entities\account\UserAccount;
    use InvalidArgumentException;
    use PDOException;
    use Exception;
    use DateTimeImmutable;

    final class MySQLTutorDAO implements DAO {
        public function insert(mixed $entity) : void {
            if (!static::isATutor($entity))
                throw new InvalidArgumentException("The entity must be a Tutor!");

            $sql = "INSERT INTO tutor(name, cpf, phone_number, date_of_birth, registration_date, status) VALUES " . 
                    "(:name, :cpf, :phone_number, :date_of_birth, :registration_date, :status)";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([
                    "name" => $entity->getName(),
                    "cpf" => $entity->getCPF()->getCPFNumber(),
                    "phone_number" => $entity->getPhoneNumber(),
                    "date_of_birth" => $entity->getDateOfBirth()->format("Y-m-d"),
                    "registration_date" => $entity->getRegistrationDate()->format("Y-m-d"),
                    "status" => $entity->getStatus()
                ]);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        private static function entryToEntity(array $entry) : mixed {
            $tutor = new Tutor($entry["name"], $entry["cpf"], $entry["phone_number"], 
                                DateTimeImmutable::createFromFormat('Y-m-d' , $entry["date_of_birth"]), 
                                DateTimeImmutable::createFromFormat("Y-m-d", $entry["registration_date"]),
                                RegistrationStatus::from($entry["status"]), $entry["id"]
                            );
            return $tutor;
        }

        public function findOneByKey(mixed $key) : mixed {
            $sql = "SELECT id, name, cpf, phone_number, date_of_birth, registration_date, status FROM tutor WHERE " . 
                    "id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);

                $tutorEntry = $statement->fetch();

                if (!$tutorEntry)
                    return null;

                return static::entryToEntity($tutorEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByCPF(CPF $cpf) : mixed {
            $sql = "SELECT id, name, cpf, phone_number, date_of_birth, registration_date, status FROM tutor WHERE " . 
                    "cpf = :cpf";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "cpf" => $cpf->getCPFNumber() ]);

                $tutorEntry = $statement->fetch();

                if (!$tutorEntry)
                    return null;

                return static::entryToEntity($tutorEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByKeyAndUserAccount(mixed $key, UserAccount $account) : mixed {
            $sql = "SELECT id, name, cpf, phone_number, date_of_birth, registration_date, status FROM tutor " . 
                    "WHERE id = :id AND account_id = :account_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key, "account_id" => $account->getId() ]);

                $tutorEntry = $statement->fetch();

                if (!$tutorEntry)
                    return null;

                return static::entryToEntity($tutorEntry);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByUserAccount(UserAccount $account) : mixed {
            $sql = "SELECT id, name, cpf, phone_number, date_of_birth, registration_date, status FROM tutor " . 
                    "WHERE account_id = :account_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "account_id" => $account->getId() ]);

                $tutorEntry = $statement->fetch();

                if (!$tutorEntry)
                    return null;

                return static::entryToEntity($tutorEntry);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findAll() : array {
            $sql = "SELECT id, name, cpf, phone_number, date_of_birth, registration_date, status FROM tutor";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([]);

                $tutorEntries = $statement->fetchAll();

                if (!$tutorEntries)
                    return [];

                $tutors = [];
                
                foreach($tutorEntries as $tutorEntry) 
                    $tutors[] = static::entryToEntity($tutorEntry);

                return $tutors;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function update(mixed $entity) : void {
            if (!static::isATutor($entity))
                throw new InvalidArgumentException("Entity must be a Tutor");

            $sql = "UPDATE tutor SET name = :name, phone_number = :phone_number, date_of_birth = :date_of birth, " . 
                    "status = :status WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ 
                    "name"  => $entity->getName(),
                    "phone_number" => $entity->getPhoneNumber(),
                    "date_of_birth" => $entity->getDateOfBirth()->format("Y-m-d"),
                    "status" => $entity->getStatus(),
                    "id" => $entity->getId()
                ]);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function deleteByKey(mixed $key) : void {
            $sql = "DELETE FROM tutor WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public static function isATutor(mixed $tutor) : bool {
            return $tutor instanceof Tutor;
        }
    }
?>
