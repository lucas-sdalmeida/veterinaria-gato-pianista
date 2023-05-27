<?php
    namespace domain\entities\tutor;

    use DateTimeImmutable;
    use php\util\CPF;
    use domain\util\IllegalOperationException;

    class Tutor {
        private ?int $id = null;
        private readonly string $name;
        private readonly CPF $cpf;
        private readonly string $phoneNumber;
        private readonly DateTimeImmutable $dateOfBirth;
        private readonly DateTimeImmutable $registrationDate;

        public function __construct(string $name, string|CPF $cpf, string $phoneNumber,
                                    DateTimeImmutable $dateOfBirth, 
                                    ?DateTimeImmutable $registrationDate=null) {
            $this->name = $name;
            $this->cpf = is_string($cpf) ? CPF::of($cpf) : $cpf;
            $this->phoneNumber = $phoneNumber;
            $this->dateOfBirth = $dateOfBirth;
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function setId(int $id) {
            if ($this->id !== null)
                throw new IllegalOperationException(
                    "The tutor id cannot be changed once set!"
                );
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

        public final function getAge() : int {
            $currentYear = intval((new DateTimeImmutable())->format("yyyy"));
            $yearOfBirth = intval($this->dateOfBirth->format("yyyy"));
            
            return $currentYear - $yearOfBirth;
        }

        public final function getDateOfBirth() : DateTimeImmutable {
            return $this->dateOfBirth;
        }

        public final function getRegistrationDate() : DateTimeImmutable {
            return $this->registrationDate;
        }
    }
?>
