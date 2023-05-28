<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\appointment;

    use pw2s3\clinicaveterinaria\domain\entities\animal\Animal;
    use pw2s3\clinicaveterinaria\domain\entities\doctor\Doctor;
    use pw2s3\clinicaveterinaria\domain\entities\appointment\AppointmentType;
    use pw2s3\clinicaveterinaria\domain\util\IllegalOperationException;
    use DateTimeImmutable;
    use InvalidArgumentException;

    class VeterinarianAppointment {
        private ?int $id = null;
        private readonly Animal $animal;
        private readonly Doctor $doctor;
        private readonly AppointmentType $type;
        private readonly string $reason;
        private ?string $action;
        private readonly DateTimeImmutable $startDateTime;
        private ?DateTimeImmutable $endDateTime;

        public function __construct(Animal $animal, Doctor $doctor, AppointmentType $type,
                                    string $reason, ?DateTimeImmutable $startDateTime=null,
                                    ?DateTimeImmutable $endDateTime=null,
                                    ?string $action=null) {
            $this->animal = $animal;
            $this->doctor = $doctor;
            $this->type = $type;
            $this->reason = $reason;
            $this->startDateTime = $startDateTime ?? new DateTimeImmutable();
            $this->endDateTime = null;
            
            if ($endDateTime !== null)
                $this->setEndDateTime($endDateTime);

            $this->action = $action;
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function setId(int $id) : void {
            if ($this->id != null)
                throw new IllegalOperationException("Unable to change id once set!");
            $this->id = $id;
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

        public final function getReason() : string {
            return $this->reason;
        }

        public final function getAction() : ?string {
            return $this->action;
        }

        public final function setAction(string $action) : void {
            $this->action = $action;
        }

        public final function getStartDateTime() : DateTimeImmutable {
            return $this->startDateTime;
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
    }
?>
