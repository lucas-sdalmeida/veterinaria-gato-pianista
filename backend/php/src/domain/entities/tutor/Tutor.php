<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\tutor;

    use lucassdalmeida\gatopianista\veterinaria\util\CPF;
    use lucassdalmeida\gatopianista\veterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;
    use DomainException;

    class Tutor {
        private int $id;
        private readonly string $name;
        private readonly CPF $cpf;
        private string $phoneNumber;
        private DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(int $id, string $name, string|CPF $cpf, string $phoneNumber, 
                                    DateTimeImmutable $dateOfBirth, ?DateTimeImmutable $registrationDate=null, 
                                    ?RegistrationStatus $status=null) {
            $this->id = $id;
            $this->name = $name;
            $this->cpf = is_string($cpf) ? CPF::of($cpf) : $cpf;
            $this->phoneNumber = $phoneNumber;
            $this->setDateOfBirth($dateOfBirth);
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
        }

        public final function activateTutor() : void {
            $this->status = RegistrationStatus::ACTIVE;
        }

        public final function inactivateTutor() : void {
            $this->status = RegistrationStatus::INACTIVE;
        }

        public final function getId() : int {
            return $this->id;
        }

        public final function getName() : string {
            return $this->name;
        }

        public final function getCPF() : CPF {
            return $this->cpf;
        }

        public final function getPhoneNumber() : string {
            return $this->phoneNumber;
        }

        public final function setPhoneNumber(string $phoneNumber) : void {
            $this->phoneNumber = $phoneNumber; 
        }

        public final function getAge() : int {
            $today = new DateTimeImmutable();
            $intervalBetweenBirthAndToday = $today->diff($this->dateOfBirth);
            
            return $intervalBetweenBirthAndToday->y;
        }

        public final function getDateOfBirth() : DateTimeImmutable {
            return $this->dateOfBirth;
        }

        public final function setDateOfBirth(DateTimeImmutable $dateOfBirth) : void {
            $today = new DateTimeImmutable();
            
            if ($dateOfBirth > $today)
                throw new DomainException("It is impossible for a person to be born after today!");
            
            $interval = $today->diff($dateOfBirth);

            if ($interval->y < 18)
                throw new DomainException("A tutor must be of legal age!");

            $this->dateOfBirth = $dateOfBirth;
        }

        public final function getRegistrationDate() : DateTimeImmutable {
            return $this->registrationDate;
        }

        public final function getStatus() : RegistrationStatus {
            return $this->status;
        }

        public function __toString() : string {
            return "Tutor(" . $this->name . ", age: " . $this->getAge() . ", " . $this->cpf . ")";
        }
    }
?>
