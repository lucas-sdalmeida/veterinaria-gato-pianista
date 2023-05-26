<?php
    namespace domain\entities\animal;

    use domain\entities\tutor\Tutor;
    use domain\util\IllegalOperationException;
    use DateTimeImmutable;

    class Animal {
        private ?int $id = null;
        private readonly string $name;
        private readonly string $specie;
        private ?string $race;
        private readonly Tutor $tutor;
        private readonly DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $registrationDate;

        public function __construct(string $name, string $specie, Tutor $tutor,
                                    DateTimeImmutable $dateOfBirth, 
                                    ?DateTimeImmutable $registrationDate=null, 
                                    ?string $race=null) {
            $this->name = $name;
            $this->specie = $specie;
            $this->dateOfBirth = $dateOfBirth;
            $this->race = $race;
            $this->tutor = $tutor;
            $this->registrationDate = $registrationDate != null ? $registrationDate : 
                                        new DateTimeImmutable();
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function setId(int $id) {
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

        public final function setRace(string $race) {
            $this->race = $race;
        }

        public final function getAge() : int {
            $currentYear = intval((new DateTimeImmutable())->format("yyyy"));
            $yearOfBirth = intval($this->dateOfBirth->format("yyyy"));

            return $currentYear - $yearOfBirth;
        }

        public final function getDateOfBirth() : DateTimeImmutable{
            return $this->dateOfBirth;
        }

        public final function getRegistryDate() : DateTimeImmutable {
            return $this->registrationDate;
        }
    }
?>
