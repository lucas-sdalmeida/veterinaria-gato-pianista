<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\account;
    
    use lucassdalmeida\gatopianista\veterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;
    use InvalidArgumentException;

    class UserAccount {
        private ?int $id;
        private readonly string $username;
        private string $password;
        private readonly UserRole $role;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(string $username, string $password, UserRole $role,
                                    ?DateTimeImmutable $registrationDate=null, ?RegistrationStatus $status=null,
                                    ?int $id=null) {
            $this->username = $username;
            $this->password = $password;
            $this->role = $role;
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
            $this->id = $id;
        }

        public final function hasAccessOver(UserRole $needed) : bool {
            return $this->role->compareAccessLevel($needed) >= 0;
        }

        public final function isActiveAccount() : bool {
            return $this->status == RegistrationStatus::ACTIVE;
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function getUsername() : string {
            return $this->username;
        }

        public final function getPassword() : string {
            return $this->password;
        }

        public final function setPassword(string $password) : void {
            if (empty($password))
                throw new InvalidArgumentException("The password cannot be null!");
            $this->password = $password;
        }

        public final function getRole() : UserRole {
            return $this->role;
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
    }
?>
