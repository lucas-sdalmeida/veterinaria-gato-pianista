<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\appointment;

    use pw2s3\clinicaveterinaria\domain\entities\animal\Animal;
    use pw2s3\clinicaveterinaria\domain\entities\doctor\Doctor;
    use pw2s3\clinicaveterinaria\domain\entities\appointment\AppointmentType;
    use DateTimeImmutable;
    use InvalidArgumentException;
    use DomainException;

    class VeterinarianAppointment {
        private ?int $id;
        private readonly Animal $animal;
        private readonly Doctor $doctor;
        private readonly AppointmentType $type;
        private ?string $reason;
        private ?string $action;
        private DateTimeImmutable $startDateTime;
        private ?DateTimeImmutable $endDateTime;

        public function __construct(Animal $animal, Doctor $doctor, AppointmentType $type,
                                    ?DateTimeImmutable $startDateTime=null, ?string $reason,
                                    ?DateTimeImmutable $endDateTime=null,
                                    ?string $action=null, ?int $id=null) {
            $this->animal = $animal;
            $this->doctor = $doctor;
            $this->type = $type;
            $this->reason = $reason;
            $this->startDateTime = $startDateTime ?? new DateTimeImmutable();
            $this->endDateTime = null;
            
            if ($endDateTime !== null)
                $this->setEndDateTime($endDateTime);

            $this->action = $action;
            $this->id = $id;
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

        public final function getReason() : string {
            return $this->reason;
        }

        public final function setReason(string $reason) : void {
            $this->reason = $reason;
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
    }
?>
