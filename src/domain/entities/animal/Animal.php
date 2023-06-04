<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\animal;

    use pw2s3\clinicaveterinaria\domain\entities\tutor\Tutor;
    use pw2s3\clinicaveterinaria\domain\util\IllegalOperationException;
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;

    class Animal {
        private ?int $id = null;
        private readonly string $name;
        private readonly string $specie;
        private ?string $race;
        private readonly Tutor $tutor;
        private readonly DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(string $name, string $specie, Tutor $tutor, DateTimeImmutable $dateOfBirth, 
                                    ?DateTimeImmutable $registrationDate=null, ?RegistrationStatus $status=null,
                                    ?string $race=null) {
            $this->name = $name;
            $this->specie = $specie;
            $this->dateOfBirth = $dateOfBirth;
            $this->race = $race;
            $this->tutor = $tutor;
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function setId(int $id) : void {
            if ($this->id != null)
                throw new IllegalOperationException(
                    "This animal already has an id, which is <" . $this->id . ">"
                );
            
            $this->id = $id;
        }

        public final function getName() : string {
            return $this->name;
        }

        public final function getSpecie() : string {
            return $this->specie;
        }

        public final function getRace() : ?string {
            return $this->race;
        }

        public final function setRace(string $race) : void {
            $this->race = $race;
        }

        public final function getTutor() : tutor {
            return $this->tutor;
        }

        public final function getAge() : int {
            $currentYear = intval((new DateTimeImmutable())->format("Y"));
            $yearOfBirth = intval($this->dateOfBirth->format("Y"));

            return $currentYear - $yearOfBirth;
        }

        public final function getDateOfBirth() : DateTimeImmutable{
            return $this->dateOfBirth;
        }

        public final function getRegistryDate() : DateTimeImmutable {
            return $this->registrationDate;
        }

        public final function getStatus() : RegistrationStatus {
            return $this->status;
        }

        public final function setStatus(RegistrationStatus $status) : void {
            $this->status = $status;
        }

        public function __toString() : string {
            return "Animal: " . $this->name . ", " . $this->specie . ", " .
                    $this->race . ", age: " . $this->getAge();
        }
    }
?>
