<?php
    namespace pw2s3\clinicaveterinaria\model\request;

use InvalidArgumentException;

    final class Request {
        private readonly string $uri;
        private readonly HTTPMethod $method;
        private array $parameters = [];

        public function __construct(string $uri, HTTPMethod|string $method) {
            $this->uri =$uri;
            $this->method = is_string($method) ? HTTPMethod::from(strtoupper($method)) : $method;
        }

        public function hasParameters() : bool {
            return count($this->parameters) > 0;
        }

        public function hasParameterByName(string $parameterName) : bool {
            return array_key_exists($parameterName, $this->parameters);
        }

        public function getParameterByName(string $parameterName) : mixed {
            if (!$this->hasParameterByName($parameterName))
                throw new InvalidArgumentException("Such parameter has not been sent!");
            return $this->parameters[$parameterName];
        }

        public function getAllParameters() : array {
            return array_slice($this->parameters, 0);
        }

        public function addParameter(string $parameterName, mixed $value) : void {
            $this->parameters[$parameterName] = $value;
        }

        public function getURI() : string {
            return $this->uri;
        }

        public function getMethod() : HTTPMethod {
            return $this->method;
        }
    }
?>
