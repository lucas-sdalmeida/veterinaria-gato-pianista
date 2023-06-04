<?php
    namespace pw2s3\clinicaveterinaria\domain\util;

    enum RegistrationStatus : string {
        case ACTIVE = "Active";
        case INACTIVE = "Inactive";
    }
?>
