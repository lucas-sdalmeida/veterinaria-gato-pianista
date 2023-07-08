<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\account;
    
    use pw2s3\clinicaveterinaria\domain\util\RegistrationStatus;
    use DateTimeImmutable;

    class UserAccount {
        private ?int $id;
        private readonly string $username;
        private readonly string $password;
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

        public final function hasGreaterOrEqualRoleThan(UserAccount|UserRole $other) : bool {
            return $this->role->compareAccessLevel($other instanceof UserAccount ? $other->role : $other) >= 0;
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
