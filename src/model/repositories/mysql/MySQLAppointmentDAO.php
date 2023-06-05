<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\persistence\util\DAO;
    use pw2s3\clinicaveterinaria\domain\entities\appointment\VeterinarianAppointment;
    use pw2s3\clinicaveterinaria\domain\entities\doctor\Doctor;
    use pw2s3\clinicaveterinaria\domain\entities\animal\Animal;
    use pw2s3\clinicaveterinaria\model\repository\mysql\SingletonMySQLConnectionFactory;
    use pw2s3\clinicaveterinaria\domain\entities\appointment\AppointmentType;
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use InvalidArgumentException;
    use PDOException;
    use Exception;
    use DateTimeImmutable;

    final class MySQLAppointmentDAO implements DAO {
        public function insert(mixed $entity) : void {
            if (!static::isAVeterinarianAppointment($entity))
                throw new InvalidArgumentException("The entity must be a Veterinarian Appointment!");

            $sql = "INSERT INTO appointment(animal_id, doctor_id, type, reason, start_datetime) VALUES " . 
                    "(:animal_id, :doctor_id, :type, :reason, :start_datetime)";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([
                    "animal_id" => $entity->getAnimal()->getId(),
                    "doctor_id" => $entity->getDoctor()->getId(),
                    "type" => $entity->getType()->value(),
                    "reason" => $entity->getReason(),
                    "start_datetime" => $entity->getStartDateTime()->format("Y-m-d H:i:s")
                ]);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        private static function entryToEntity(array $entry) : mixed {
            $animal = (new MySQLAnimalDAO())->findOneByKey($entry["animal_id"]);
            $doctor = (new MySQLDoctorDAO())->findOneByKey($entry["doctor_id"]);

            $appointment = new VeterinarianAppointment($animal, $doctor, AppointmentType::fromString($entry["type"]),
                                $entry["reason"], 
                                DateTimeImmutable::createFromFormat('Y-m-d H:i:s' , $entry["start_datetime"]), 
                                DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $entry["end_datetime"]),
                                $entry["action"], $entry["id"]);

            return $appointment;
        }

        public function findOneByKey(mixed $key) : mixed {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);

                $appointmentEntry = $statement->fetch();

                if (!$appointmentEntry)
                    return null;

                return static::entryToEntity($appointmentEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findOneByAnimalAndDoctorAndStartDateTime(Animal $animal, Doctor $doctor, 
                DateTimeImmutable $startDateTime) : mixed {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE animal_id = :animal_id and doctor_id = :doctor_id and " . 
                    "start_datetime = :start_datatime";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ 
                    "animal_id" => $animal->getId(),
                    "doctor_id" => $doctor->getId(),
                    "start_datetime" => $startDateTime->format("Y-m-d H:i:s")
                ]);

                $appointmentEntry = $statement->fetch();

                if (!$appointmentEntry)
                    return null;

                return static::entryToEntity($appointmentEntry);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findSomeByAnimal(Animal $animal) : array {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE animal_id = :animal_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "animal_id" => $animal->getId() ]);

                $appointmentEntries = $statement->fetchAll();
                
                if (!$appointmentEntries)
                    return [];

                $appointments = [];

                foreach($appointmentEntries as $appointmentEntry)
                    $appointments[] = static::entryToEntity($appointmentEntry);

                return $appointments;
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findSomeByDoctor(Doctor $doctor) : array {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE doctor_id = :doctor_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "doctor_id" => $doctor->getId() ]);

                $appointmentEntries = $statement->fetchAll();
                
                if (!$appointmentEntries)
                    return [];

                $appointments = [];

                foreach($appointmentEntries as $appointmentEntry)
                    $appointments[] = static::entryToEntity($appointmentEntry);

                return $appointments;
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function findAll() : array {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([]);

                $appointmentEntries = $statement->fetchAll();

                if (!$appointmentEntries)
                    return [];

                $appointments = [];
                
                foreach($appointmentEntries as $appointmentEntry) 
                    $appointments[] = static::entryToEntity($appointmentEntry);

                return $appointments;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function findAllOpenScheduleAppointments() : array {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE end_datetime is null";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([]);

                $appointmentEntries = $statement->fetchAll();

                if (!$appointmentEntries)
                    return [];

                $appointments = [];
                
                foreach($appointmentEntries as $appointmentEntry) 
                    $appointments[] = static::entryToEntity($appointmentEntry);

                return $appointments;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function findAllOpenScheduleAppointmentsByAnimal(Animal $animal) : array {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE end_datetime is null and animal_id = :animal_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "animal_id" => $animal->getId() ]);

                $appointmentEntries = $statement->fetchAll();

                if (!$appointmentEntries)
                    return [];

                $appointments = [];
                
                foreach($appointmentEntries as $appointmentEntry) 
                    $appointments[] = static::entryToEntity($appointmentEntry);

                return $appointments;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function findAllOpenScheduleAppointmentsByDoctor(Doctor $doctor) : array {
            $sql = "SELECT id, animal_id, doctor_id, type, reason, action, start_datetime, end_datetime FROM " .
                    "appointment WHERE end_datetime is null and doctor_id = :doctor_id";
            $connectionFactory = new SingletonMySQLConnectionFactory();
            
            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "doctor_id" => $doctor->getId() ]);

                $appointmentEntries = $statement->fetchAll();

                if (!$appointmentEntries)
                    return [];

                $appointments = [];
                
                foreach($appointmentEntries as $appointmentEntry) 
                    $appointments[] = static::entryToEntity($appointmentEntry);

                return $appointments;
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }

        public function update(mixed $entity) : void {
            if (!static::isAVeterinarianAppointment($entity))
                throw new InvalidArgumentException("Entity must be a Veterinarian Appointment");

            $sql = "UPDATE appointment SET reason = :reason, action = :action, start_datetime = :start_datetime, " . 
                    "end_datetime = :end_datetime WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ 
                    "reason"  => $entity->getReason(),
                    "action" => $entity->getAction(),
                    "start_datetime" => $entity->getStartDateTime()->format("Y-m-d H:i:s"),
                    "end_datetime" => $entity->getEndDateTime()->format("Y-m-d H:i:s"),
                    "id" => $entity->getId()
                ]);
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public function deleteByKey(mixed $key) : void {
            $sql = "DELETE FROM appointment WHERE id = :id";
            $connectionFactory = new SingletonMySQLConnectionFactory();

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute([ "id" => $key ]);
            }
            catch(PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }

        public static function isAVeterinarianAppointment(mixed $tutor) : bool {
            return $tutor instanceof VeterinarianAppointment;
        }
    }
?>
