<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\animal;

    use lucassdalmeida\gatopianista\veterinaria\domain\entities\tutor\Tutor;
    use lucassdalmeida\gatopianista\veterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;

    class Animal {
        private int $id;
        private readonly string $name;
        private readonly string $specie;
        private readonly ?string $race;
        private float $weight;
        private string $healthCondition;
        private readonly DateTimeImmutable $dateOfBirth;
        private readonly Tutor $tutor;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(int $id, string $name, string $specie, Tutor $tutor, DateTimeImmutable $dateOfBirth, 
                                    ?DateTimeImmutable $registrationDate=null, ?RegistrationStatus $status=null,
                                    ?string $race=null) {
            $this->id = $id;
            $this->name = $name;
            $this->specie = $specie;
            $this->dateOfBirth = $dateOfBirth;
            $this->race = $race;
            $this->tutor = $tutor;
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
        }

        public final function getAge() : int {
            $today = new DateTimeImmutable();
            $intervalBetweenDateOfBirthAndToday = $today->diff($this->dateOfBirth);

            return $intervalBetweenDateOfBirthAndToday->y;
        }

        public final function getId() : int {
            return $this->id;
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

        public final function getWeight() : float {
            return $this->weight;
        }

        public final function getHealthCondition() : string {
            return $this->healthCondition;
        }

        public final function getTutor() : Tutor {
            return $this->tutor;
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
            return "Animal( " . $this->name . ", " . $this->specie . ", " .
                    $this->race . ", age: " . $this->getAge() . ")";
        }
    }
?>
