<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\appointment;

    use InvalidArgumentException;

    enum AppointmentType : string {
        case EXAMINATION = "Examination";
        case SURGERY = "Surgery";

        public static function fromString(string $type) : AppointmentType {
            foreach (static::cases() as $case)
                if (strtolower($case->value) === strtolower($type)) return $case;
            
            throw new InvalidArgumentException(
                "The argument <$type> is not a valid appointment type"
            );
        }
    }
?>
