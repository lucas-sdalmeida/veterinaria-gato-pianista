<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\application;

    use lucassdalmeida\gatopianista\veterinaria\domain\entities\account\UserAccount;
    use lucassdalmeida\gatopianista\veterinaria\domain\entities\account\UserRole;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Token;
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
