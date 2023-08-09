<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\request;

    final class Response {
        private int $code;
        private array $bodyContent = [];

        public function __construct(?int $code=200) {
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

        public function setCode(int $code) : void {
            $this->code = $code;
        }
    }
?>
