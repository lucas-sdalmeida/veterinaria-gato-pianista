<?php
    namespace domain\entities\animal;

    use DateTime;
    use DomainException;

    class Animal {
        private ?int $id = null;
        private readonly string $name;
        private readonly string $specie;
        private ?string $race;
        private readonly DateTime $dateOfBirth;
        private readonly DateTime $registryDate;

        public function __construct(string $name, string $specie, DateTime $dateOfBirth, 
                                    ?DateTime $registryDate=null, ?string $race=null) {
            $this->name = $name;
            $this->specie = $specie;
            $this->dateOfBirth = $dateOfBirth;
            $this->race = $race;
            $this->registryDate = $registryDate != null ? $registryDate : new DateTime();
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function setId(int $id) {
            if ($this->id != null)
                throw new DomainException(
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
            $currentYear = intval((new DateTime())->format("yyyy"));
            $yearOfBirth = intval($this->dateOfBirth->format("yyyy"));

            return $currentYear - $yearOfBirth;
        }

        public final function getDateOfBirth() {
            return $this->dateOfBirth;
        }

        public final function getRegistryDate() {
            return $this->registryDate;
        }
    }
?>
