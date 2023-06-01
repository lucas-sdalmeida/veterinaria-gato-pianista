<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\account;

    use pw2s3\clinicaveterinaria\domain\util\IllegalOperationException;
    use DateTimeImmutable;

    class UserAccount {
        private ?int $id = null;
        private readonly string $username;
        private readonly string $password;
        private readonly UserRole $role;
        private readonly DateTimeImmutable $registrationDate;

        public function __construct(string $username, string $password, UserRole $role,
                                    ?DateTimeImmutable $registrationDate=null) {
            $this->username = $username;
            $this->setPassword($password);
            $this->role = $role;
            $this->registrationDate = $registrationDate ?? new DateTimeImmutable();
        }

        public final function hasGreaterOrEqualAccessLevelThan(UserAccount $other) : bool {
            return $this->role->hasGreaterOrEqualAccessLevelThan($other->role);
        }

        public final function validadePassword(string $password) : bool {
            return $this->password == static::encodePassword($password);
        }

        private static function encodePassword(string $password) : string {
            // W.I.P

            return "";
        }

        public final function getId() : ?int {
            return $this->id;
        }

        public final function setId(int $id) : void {
            if ($this->id != null)
                throw new IllegalOperationException("Unable to change id once set!");
            $this->id = $id;
        }

        public final function getUsername() : string {
            return $this->username;
        }

        private final function setPassword(string $password) : void {
            $this->password = static::encodePassword($password);
        }

        public final function getRole() : UserRole {
            return $this->role;
        }

        public final function getRegistrationDate() : DateTimeImmutable {
            return $this->registrationDate;
        }
    }
?>
