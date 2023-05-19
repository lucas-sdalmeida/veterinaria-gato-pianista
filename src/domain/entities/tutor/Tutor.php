<?php
    namespace domain\entities\tutor;

    use DateTime;
    use DateTimeZone;
    use php\util\CPF;
    use domain\util\IllegalOperationException;

    class Tutor {
        private readonly ?int $id;
        private readonly string $name;
        private readonly CPF $cpf;
        private readonly string $phoneNumber;
        private readonly DateTime $registrationDateTime;

        public function __construct(string $name, string|CPF $cpf, string $phoneNumber) {
            $this->name = $name;
            $this->cpf = is_string($cpf) ? CPF::of($cpf) : $cpf;
            $this->phoneNumber = $phoneNumber;
            $this->registrationDateTime = new DateTime(timezone: 
                                            new DateTimeZone("America/Sao_Paulo"));
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

        public final function getRegistrationDateTime() : DateTime {
            return $this->registrationDateTime;
        }
    }
?>
