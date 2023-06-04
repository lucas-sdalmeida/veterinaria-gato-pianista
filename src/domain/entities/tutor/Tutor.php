<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\tutor;

    use pw2s3\clinicaveterinaria\util\CPF;
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;
    use DomainException;

    class Tutor {
        private int $id;
        private string $name;
        private readonly CPF $cpf;
        private string $phoneNumber;
        private DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(string $name, string|CPF $cpf, string $phoneNumber, DateTimeImmutable $dateOfBirth, 
                                    ?DateTimeImmutable $registrationDate=null, ?RegistrationStatus $status=null,
                                    ?int $id=null) {
            $this->name = $name;
            $this->cpf = is_string($cpf) ? CPF::of($cpf) : $cpf;
            $this->phoneNumber = $phoneNumber;
            $this->setDateOfBirth($dateOfBirth);
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
            $this->id = $id;
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function getName() : string {
            return $this->name;
        }

        public final function setName(string $name) : void {
            $this->name = $name;
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
            $currentYear = intval((new DateTimeImmutable())->format("Y"));
            $yearOfBirth = intval($this->dateOfBirth->format("Y"));
            
            return $currentYear - $yearOfBirth;
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

        public final function setStatus(RegistrationStatus $status) : void {
            $this->status = $status;
        }

        public function __toString() : string {
            return $this->name . ", " . $this->getAge() . ", " . $this->cpf;
        }
    }
?>
