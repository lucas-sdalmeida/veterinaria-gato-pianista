<?php
    namespace pw2s3\clinicaveterinaria\model\auth;

    use pw2s3\clinicaveterinaria\domain\entities\account\UserAccount;
    use DateTimeImmutable;
    use InvalidArgumentException;

    abstract class Session {
        private readonly Token $token;

        public function __construct(Token $token) {
            $this->token = $token;
        }

        public abstract function hasEnoughtAccessLevel(mixed $requiredAccessLevel) : bool;
    }
?>
