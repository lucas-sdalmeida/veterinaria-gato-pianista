<?php
    namespace pw2s3\clinicaveterinaria\model\application;

    use pw2s3\clinicaveterinaria\domain\entities\account\UserAccount;
    use pw2s3\clinicaveterinaria\domain\entities\account\UserRole;
    use pw2s3\clinicaveterinaria\model\auth\Session;
    use pw2s3\clinicaveterinaria\model\auth\Token;
    use InvalidArgumentException;

    final class UserSession extends Session {
        private readonly UserAccount $account;

        public function __construct(UserAccount $account, Token $token) {
            parent::__construct($token);
            $this->account = $account;
        }

        public function hasEnoughtAccessLevel(mixed $requiredAccessLevel): bool {
            if (!$requiredAccessLevel instanceof UserRole)
                throw new InvalidArgumentException("The required access level must be a UserRole!");
            return $this->account->hasGreaterOrEqualRoleThan($requiredAccessLevel);
        }

        public function getAccount() : UserAccount {
            return $this->account;
        }
    }
?>
