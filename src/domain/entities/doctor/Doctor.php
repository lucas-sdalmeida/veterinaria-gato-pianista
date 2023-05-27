<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\doctor;

    use pw2s3\clinicaveterinaria\php\util\CPF;
    use pw2s3\clinicaveterinaria\php\util\CRMV;
    use pw2s3\clinicaveterinaria\domain\util\IllegalOperationException;
    use DateTimeImmutable;

    class Doctor {
        private ?int $id = null;
        private readonly string $name;
        private readonly CPF $cpf;
        private readonly CRMV $crmv;
        private readonly string $phoneNumber;
        private readonly DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $hiringDate;
        private readonly DateTimeImmutable $registrationDate;

        public function __construct(string $name, string|CPF $cpf, string|CRMV $crmv,
                                    string $phoneNumber, DateTimeImmutable $dateOfBirth,
                                    DateTimeImmutable $hiringDate, 
                                    ?DateTimeImmutable $registrationDate=null) {
            $this->name = $name;
            $this->cpf = is_string($cpf) ? CPF::of($cpf) : $cpf;
            $this->crmv = is_string($cpf) ? CRMV::of($crmv) : $crmv;
            $this->phoneNumber = $phoneNumber;
            $this->dateOfBirth = $dateOfBirth;
            $this->hiringDate = $hiringDate;
            $this->$registrationDate = $registrationDate ?? new DateTimeImmutable();
        }

        public final function getId() : int {
            return $this->id;
        }

        public final function setId(int $id) : void {
            if ($this->id != null)
                throw new IllegalOperationException("Unable to change id once set!");
            $this->id = $id;
        }

        public final function getName() : string {
            return $this->name;
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

        public final function getAge() : int {
            $currentYear = intval((new DateTimeImmutable())->format("yyyy"));
            $yearOfBirth = intval($this->dateOfBirth->format("yyyy"));

            return $currentYear - $yearOfBirth;
        }

        public final function getDateOfBirth() : DateTimeImmutable {
            return $this->dateOfBirth;
        }

        public final function getHiringDate() : DateTimeImmutable {
            return $this->hiringDate;
        }

        public final function getRegistrationDate() : DateTimeImmutable {
            return $this->registrationDate;
        }

        public function __toString() : string {
            return $this->name . ", " . $this->getAge() . ", " . $this->cpf . ", " . 
                    $this->crmv;
        }
    }
?>
