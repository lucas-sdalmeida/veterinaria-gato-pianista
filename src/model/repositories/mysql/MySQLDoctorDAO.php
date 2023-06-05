<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\persistence\util\DAO;
    use pw2s3\clinicaveterinaria\domain\entities\doctor\Doctor;
    use pw2s3\clinicaveterinaria\util\CPF;
    use pw2s3\clinicaveterinaria\util\CRMV;
    use pw2s3\clinicaveterinaria\model\repository\mysql\SingletonMySQLConnectionFactory;
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use pw2s3\clinicaveterinaria\domain\entities\account\UserAccount;
    use DateTimeImmutable;
    use InvalidArgumentException;
    use PDOException;
    use Exception;

    final class MySQLDoctorDAO implements DAO {
        public function insert(mixed $entity) : void {
            if (!static::isADoctor($entity))
                throw new InvalidArgumentException("The entity must be a Doctor!");

            $sql = "INSERT INTO doctor(name, cpf, crmv, phone_number, date_of_birth, hiring_date, " . 
                    "registration_date) VALUES (:name, :cpf, :crmv, :phone_number, :date_of_birth, " . 
                    ":hiring_date, :registration_date)";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([
                    "name" => $entity->getName(),
                    "cpf" => $entity->getCPF()->getCPFNumber(),
                    "crmv" => $entity->getCRMV()->getInscriptionCode(),
                    "phone_number" => $entity->getTutor()->getId(),
                    "date_of_birth" => $entity->getDateOfBirth()->format("Y-m-d"),
                    "hiring_date" => $entity->getHiringDate()->format("Y-m-d"),
                    "registration_date" => $entity->getRegistrationDate()->format("Y-m-d")
                ]);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        private static function entryToEntity(array $entry) : mixed {
            $doctor = new Doctor($entry["name"], $entry["cpf"], $entry["crmv"], $entry["phone_number"], 
                                    DateTimeImmutable::createFromFormat("Y-m-d", $entry["date_of_birth"]), 
                                    DateTimeImmutable::createFromFormat("Y-m-d", $entry["hiring_date"]), 
                                    DateTimeImmutable::createFromFormat("Y-m-d", $entry["registration_date"]),
                                    RegistrationStatus::from($entry["status"]), $entry["id"]
                                );

            return $doctor;
        }

        public function findOneByKey(mixed $key) : mixed {
            $sql = "SELECT id, name, cpf, Crmv, phone_number, date_of_birth, hiring_date, registration_date, status " . 
                    "FROM doctor WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);

                $doctorEntry = $statement->fetch();

                if (!$doctorEntry)
                    return null;

                return static::entryToEntity($doctorEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByCPF(CPF $cpf) : mixed {
            $sql = "SELECT id, name, cpf, crmv, phone_number, date_of_birth, hiring_date, registration_date, status " .
                    "FROM doctor WHERE cpf = :cpf";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "cpf" => $cpf ]);

                $doctorEntry = $statement->fetch();

                if (!$doctorEntry)
                    return null;

                return static::entryToEntity($doctorEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByCRMV(CRMV $crmv) : mixed {
            $sql = "SELECT id, name, cpf, crmv, phone_number, date_of_birth, hiring_date, registration_date, status " . 
                    "FROM doctor WHERE cpf = :cpf";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "crmv" => $crmv ]);

                $doctorEntry = $statement->fetch();

                if (!$doctorEntry)
                    return null;

                return static::entryToEntity($doctorEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByUserAccount(UserAccount $account) : mixed {
            $sql = "SELECT id, name, cpf, crmv, phone_number, date_of_birth, hiring_date, registration_date, status " . 
                    "FROM doctor WHERE account_id = :account_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "account_id" => $account->getId() ]);

                $doctorEntry = $statement->fetch();

                if (!$doctorEntry)
                    return null;

                return static::entryToEntity($doctorEntry);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findAll() : array {
            $sql = "SELECT id, name, cpf, crmv, phone_number, date_of_birth, hiring_date, registration_date, status " . 
                    "FROM doctor";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([]);

                $doctorEntries = $statement->fetchAll();

                if (!$doctorEntries)
                    return [];

                $doctors = [];
                
                foreach($doctorEntries as $doctorEntry) 
                    $doctors[] = static::entryToEntity($doctorEntry);

                return $doctors;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function update(mixed $entity) : void {
            if (!static::isADoctor($entity))
                throw new InvalidArgumentException("Entity must be a Doctor!");

            $sql = "UPDATE doctor SET name = :name, phone_number = :phone_number, hiring_date = :hiring_date, " . 
                    "date_of_birth = :date_of birth, status = :status WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ 
                    "name"  => $entity->getName(),
                    "phone_number" => $entity->getPhoneNumber(),
                    "hiring_date" => $entity->getHiring()->format("Y-m-d"),
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
            $sql = "DELETE FROM doctor WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public static function isADoctor(mixed $animal) : bool {
            return $animal instanceof Doctor;
        }
    }
?>
