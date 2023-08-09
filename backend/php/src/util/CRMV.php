<?php
    namespace lucassdalmeida\gatopianista\veterinaria\util;

    use DomainException;

    final class CRMV {
        private static array $instances = [];
        private readonly string $inscriptionCode;

        private function __construct(string $inscriptionCode) {
            $this->inscriptionCode = $inscriptionCode;
        }

        public static function of(string $inscriptionCode) : CRMV {
            if (!static::isValidCRMV($inscriptionCode))
                throw new DomainException("This is not a valid CRMV inscription number!");
            if (!array_key_exists($inscriptionCode, static::$instances))
                static::$instances[$inscriptionCode] = new CRMV($inscriptionCode);
            return static::$instances[$inscriptionCode];
        }

        public static function isValidCRMV(string $inscriptionCode) {
            return preg_match('/^CRMV-[A-Z]{2}\d+$/', $inscriptionCode) > 0;
        }

        public final function getInscriptionCode() : string {
            return $this->inscriptionCode;
        }

        public function __toString() : string {
            return $this->inscriptionCode;
        }
    }
?>
