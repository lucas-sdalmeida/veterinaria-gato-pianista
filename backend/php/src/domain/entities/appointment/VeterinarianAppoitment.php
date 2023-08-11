<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\appointment;

    use lucassdalmeida\gatopianista\veterinaria\domain\entities\animal\Animal;
    use lucassdalmeida\gatopianista\veterinaria\domain\entities\doctor\Doctor;
    use lucassdalmeida\gatopianista\veterinaria\domain\entities\appointment\AppointmentType;
    use DateTimeImmutable;
    use InvalidArgumentException;
    use DomainException;

    class VeterinarianAppointment {
        private readonly int $id;
        private readonly Animal $animal;
        private readonly Doctor $doctor;
        private readonly AppointmentType $type;
        private ?string $animalHealthCondition;
        private ?string $action;
        private readonly DateTimeImmutable $scheduleDateTime;
        private ?DateTimeImmutable $startDateTime;
        private ?DateTimeImmutable $endDateTime;

        public function __construct(int $id, Animal $animal, Doctor $doctor, AppointmentType $type, 
                                    DateTimeImmutable $scheduleDateTime , ?string $animalHealthCondition=null, 
                                    ?string $action=null, ?DateTimeImmutable $startDateTime=null, 
                                    ?DateTimeImmutable $endDateTime=null) {
            $this->id = $id;
            $this->animal = $animal;
            $this->doctor = $doctor;
            $this->type = $type;
            $this->scheduleDateTime = $scheduleDateTime;
            $this->animalHealthCondition;
            $this->action = $action;
            $this->startDateTime = $startDateTime;
            $this->endDateTime = $endDateTime;
        }

        public final function hasStarted() : bool {
            return $this->startDateTime != null;
        }

        public final function hasEnded() : bool {
            return $this->endDateTime != null;
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function getAnimal() : Animal {
            return $this->animal;
        }

        public final function getDoctor() : Doctor {
            return $this->doctor;
        }

        public final function getType() : AppointmentType {
            return $this->type;
        }

        public final function getAnimalHealthCondition() : string {
            return $this->animalHealthCondition;
        }

        public final function setAnimalHealthCondition(string $animalHealthCondition) : void {
            $this->animalHealthCondition = $animalHealthCondition;
        }

        public final function getAction() : ?string {
            return $this->action;
        }

        public final function setAction(string $action) : void {
            $this->action = $action;
        }

        public final function getScheduleDateTime() : DateTimeImmutable {
            return $this->scheduleDateTime;
        }

        public final function getStartDateTime() : DateTimeImmutable {
            return $this->startDateTime;
        }

        public final function setStartDateTime(DateTimeImmutable $startDateTime) : void {
            $today = new DateTimeImmutable();

            if ($today > $startDateTime)
                throw new DomainException("Cannot schedule an appointment before now!");

            $this->startDateTime = $startDateTime;
        }

        public final function getEndDateTime() : ?DateTimeImmutable {
            return $this->endDateTime;
        }

        public final function setEndDateTime(DateTimeImmutable $endDateTime) : void {
            if ($endDateTime->getTimestamp() < $this->startDateTime->getTimestamp())
                throw new InvalidArgumentException(
                    "The end date and time cannot be earlier than the start date and time!"
                );
            
            $this->endDateTime = $endDateTime;
        }

        public function __toString() {
            return "VeterianrianAppointment(" . $this->id . ", " . $this->animal . ", " . $this->doctor . ", "  .
                    $this->type->value . ", " . $this->scheduleDateTime . ", ". $this->animalHealthCondition . ", "  . 
                    $this->action . ", " . $this->startDateTime . $this->endDateTime . ")";
        }
    }
?>
