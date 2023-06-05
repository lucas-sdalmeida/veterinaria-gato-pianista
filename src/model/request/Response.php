<?php
    namespace pw2s3\clinicaveterinaria\model\request;

    final class Response {
        private readonly int $code;
        private array $bodyContent = [];

        public function __construct(int $code) {
            $this->code = $code;
        }

        public function addContent(string $contentName, $content) {
            $this->bodyContent[$contentName] = $content;
        }

        public function getBody() : array {
            return array_slice($this->bodyContent, 0);
        }

        public function getCode() : int {
            return $this->code;
        }
    }
?>
