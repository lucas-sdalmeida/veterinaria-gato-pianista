<?php
    namespace pw2s3\clinicaveterinaria\php\util;

    use DomainException;

    final class CPF {
        private static array $instances = [];
        private readonly string $cpfNumber;

        private function __construct(string $cpfNumber) {
            $this->cpfNumber = $cpfNumber;
        }

        public function getCPFNumber() : string {
            return $this->cpfNumber;
        }

        public static function of(string $cpfNumber) : CPF {
            if (!static::isValidCPFNumber($cpfNumber))
                throw new DomainException("The value <$cpfNumber> is not a valid CPF!");
            if (!key_exists($cpfNumber, static::$instances));
                self::$instances[$cpfNumber] = new CPF($cpfNumber);
            return self::$instances[$cpfNumber];
        }

        private static function isValidCPFNumber(string $cpfNumber) : bool {
            return preg_match('/^\d{3}(.?\d{3}){2}-?\d{2}$/', $cpfNumber) > 0;
        }

        public function __toString() : string {
            return $this->cpfNumber;
        }
    }
?>
