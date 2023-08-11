<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\doctor;

    use lucassdalmeida\gatopianista\veterinaria\util\CPF;
    use lucassdalmeida\gatopianista\veterinaria\util\CRMV;
    use lucassdalmeida\gatopianista\veterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;
    use DomainException;

    class Doctor {
        private readonly int $id;
        private readonly string $name;
        private readonly CPF $cpf;
        private readonly CRMV $crmv;
        private string $phoneNumber;
        private readonly DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $hiringDate;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(int $id, string $name, string|CPF $cpf, string|CRMV $crmv,string $phoneNumber, 
                                    DateTimeImmutable $dateOfBirth, DateTimeImmutable $hiringDate, 
                                    ?DateTimeImmutable $registrationDate=null, ?RegistrationStatus $status=null) {
            $this->id = $id;
            $this->name = $name;
            $this->cpf = is_string($cpf) ? CPF::of($cpf) : $cpf;
            $this->crmv = is_string($cpf) ? CRMV::of($crmv) : $crmv;
            $this->phoneNumber = $phoneNumber;
            $this->setDateOfBirth($dateOfBirth);
            $this->hiringDate = $hiringDate;
            $this->$registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
        }

        public final function getAge() : int {
            $today = new DateTimeImmutable();
            $intervalBetweenDateOfBirthAndToday = $today->diff($this->dateOfBirth);

            return $intervalBetweenDateOfBirthAndToday->y;
        }

        public final function activateDoctor() : void {
            $this->status = RegistrationStatus::ACTIVE;
        }

        public final function inactivateDoctor() : void {
            $this->status = RegistrationStatus::INACTIVE;
        }

        public final function getId() : int {
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

        public final function getCRMV() : CRMV {
            return $this->crmv;
        }

        public final function getPhoneNumber() : string {
            return $this->phoneNumber;
        }

        public final function setPhoneNumber(string $phoneNumber) : void {
            $this->phoneNumber = $phoneNumber;
        }

        public final function getDateOfBirth() : DateTimeImmutable {
            return $this->dateOfBirth;
        }

        public final function setDateOfBirth(DateTimeImmutable $dateOfBirth) : void {
            $today = new DateTimeImmutable();

            if ($dateOfBirth > $today)
                throw new DomainException("It is impossible a doctor to be born after today!");

            $interval = $today->diff($dateOfBirth);

            if ($interval->y < 18)
                throw new DomainException("A doctor must be of legal age!");

            $this->dateOfBirth = $dateOfBirth;
        }

        public final function getHiringDate() : DateTimeImmutable {
            return $this->hiringDate;
        }

        public final function setHiringDate(DateTimeImmutable $hiringDate) : void {
            $intervalBetweenDateOfBirth = $hiringDate->diff($this->dateOfBirth);

            if ($intervalBetweenDateOfBirth->y > $this->getAge())
                throw new DomainException("Cannot hire someone before they are born!");

            $this->hiringDate = $hiringDate;
        }

        public final function getRegistrationDate() : DateTimeImmutable {
            return $this->registrationDate;
        }

        public final function getStatus() : RegistrationStatus {
            return $this->status;
        }

        public function __toString() : string {
            return $this->name . ", " . $this->getAge() . ", " . $this->cpf . ", " . 
                    $this->crmv;
        }
    }
?>
