<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\util;

    enum RegistrationStatus : string {
        case ACTIVE = "Active";
        case INACTIVE = "Inactive";
    }
?>
