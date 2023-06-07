<?php
    namespace pw2s3\clinicaveterinaria\model\services\util;

    final class Notification {
        private array $messages = [];

        public function addMessage(string $message) : void {
            $this->messages[] = $message;
        }

        public function getMessagesAsString() : string {
            $string = "";

            foreach($this->messages as $message)
                $string .= $message;

            return $string;
        }

        public function hasMessages() : bool {
            return count($this->messages) > 0;
        }
    }
?>
