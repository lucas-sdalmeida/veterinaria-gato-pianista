<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\appointment;

    use InvalidArgumentException;

    enum AppointmentType : string {
        case Examination = "Examination";
        case Surgery = "Surgery";

        public function fromString(string $type) : AppointmentType {
            foreach (static::cases() as $case)
                if (strtolower($case->value) === strtolower($type)) return $case;
            
            throw new InvalidArgumentException(
                "The argument <$type> is not a valid appointment type"
            );
        }
    }
?>
