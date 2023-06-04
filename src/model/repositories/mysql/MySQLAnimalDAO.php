<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\persistence\util\DAO;
    use pw2s3\clinicaveterinaria\domain\entities\animal\Animal;
    use pw2s3\clinicaveterinaria\domain\entities\tutor\Tutor;
    use pw2s3\clinicaveterinaria\model\repository\mysql\SingletonMySQLConnectionFactory;
    use DateTimeImmutable;
    use InvalidArgumentException;
    use PDOException;
    use Exception;
use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;

    final class MySQLAnimalDAO implements DAO {
        public function insert(mixed $entity) : void {
            if (!static::isAnAnimal($entity))
                throw new InvalidArgumentException("The entity must be an Animal!");

            $sql = "INSERT INTO animal(name, specie, race, tutor_id, date_of_birth, registration_date) VALUES " . 
                    "(:name, :specie, :race, :tutor, :date_of_birth, :registration_date)";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([
                    "name" => $entity->getName(),
                    "specie" => $entity->getSpecie(),
                    "race" => $entity->getRace(),
                    "tutor" => $entity->getTutor()->getId(),
                    "date_of_birth" => $entity->getDateOfBirth()->format("Y-m-d"),
                    "registration_date" => $entity->getRegistrationDate()->format("Y-m-d")
                ]);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        private static function entryToEntity(array $entry) : mixed {
            $tutor = (new MySQLTutorDAO())->findOneByKey($entry["tutor_id"]);

            $animal = new Animal($entry["name"], $entry["specie"], $tutor, 
                                 DateTimeImmutable::createFromFormat("Y-m-d", $entry["date_of_birth"]), 
                                 DateTimeImmutable::createFromFormat("Y-m-d", $entry["registration_date"]), 
                                 RegistrationStatus::from($entry["status"]), $entry["race"], $entry["id"]);

            return $animal;
        }

        public function findOneByKey(mixed $key) : mixed {
            $sql = "SELECT id, name, specie, race, tutor_id, date_of_birth, registration_date, status FROM animal " . 
                    "WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);

                $animalEntry = $statement->fetch();

                if (!$animalEntry)
                    return null;

                return static::entryToEntity($animalEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByNameAndTutor(string $name, Tutor $tutor) : mixed {
            $sql = "SELECT id, name, specie, race, tutor_id, date_of_birth, registration_date, status FROM animal " . 
                    "WHERE name = :name AND tutor_id = :tutor_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "name" => $name, "tutor_id" => $tutor->getId() ]);

                $animalEntry = $statement->fetch();

                if (!$animalEntry)
                    return null;

                return static::entryToEntity($animalEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findAll() : array {
            $sql = "SELECT id, name, specie, race, tutor_id, date_of_birth, registration_date, status FROM animal";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([]);

                $animalEntries = $statement->fetchAll();

                if (!$animalEntries)
                    return [];

                $animals = [];
                
                foreach($animalEntries as $animalEntry) 
                    $animals[] = static::entryToEntity($animalEntry);

                return $animals;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function updade(mixed $entity) : void {
            if (!static::isAnAnimal($entity))
                throw new InvalidArgumentException("Entity must be a Animal!");

            $sql = "UPDATE animal SET name = :name, specie = :specie, race = :race, date_of_birth = :date_of birth, " . 
                    "status = :status WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ 
                    "name"  => $entity->getName(),
                    "specie" => $entity->getSpecie(),
                    "race" => $entity->getRace(),
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
            $sql = "DELETE FROM animal WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public static function isAnAnimal(mixed $animal) : bool {
            return $animal instanceof Animal;
        }
    }
?>
