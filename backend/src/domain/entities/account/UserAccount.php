<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\account;
    
    use lucassdalmeida\gatopianista\veterinaria\domain\util\RegistrationStatus;
    use lucassdalmeida\gatopianista\veterinaria\domain\entities\account\password\Password;
    use DateTimeImmutable;

    class UserAccount {
        private readonly int $id;
        private readonly string $username;
        private Password $password;
        private readonly UserRole $role;
        private readonly DateTimeImmutable $registrationDate;
        private RegistrationStatus $status;

        public function __construct(int $id, string $username, Password $password, UserRole $role,
                                    ?DateTimeImmutable $registrationDate=null, ?RegistrationStatus $status=null) {
            $this->id = $id;
            $this->username = $username;
            $this->password = $password;
            $this->role = $role;
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
            $this->status = $status ?? RegistrationStatus::ACTIVE;
        }

        public final function hasAccessOver(UserRole $needed) : bool {
            return $this->isActiveAccount() && $this->role->compareAccessLevel($needed) >= 0;
        }

        public final function isActiveAccount() : bool {
            return $this->status == RegistrationStatus::ACTIVE;
        }

        public final function getId() : int {
            return $this->id;
        }

        public final function getUsername() : string {
            return $this->username;
        }

        public final function getPassword() : Password {
            return $this->password;
        }

        public final function setPassword(Password $password) : void {
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
    }
?>
