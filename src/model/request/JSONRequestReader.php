<?php
    namespace pw2s3\clinicaveterinaria\model\request;

    final class JSONRequestReader {
        public function readRequest() : Request {
            $uri = $_SERVER["REQUEST_URI"];
            $method = HTTPMethod::from($_SERVER["REQUEST_METHOD"]);
            $request = new Request($uri, $method);
            $parameters = array_merge($this->readQueryParameters(), $this->readBodyParameters());

            foreach($parameters as $paramName => $value)
                $request->addParameter($paramName, $value);
            
            return $request;
        }

        private function readQueryParameters() : array {
            $uriQueryParameters = $_SERVER["QUERY_STRING"];

            if ($uriQueryParameters == null || empty($uriQueryParameters))
                return [];

            $queryBlocks = explode("&", $uriQueryParameters);
            $parameters = [];

            foreach ($queryBlocks as $block) {
                $param = explode("=", $block);
                $parameters[$param[0]] = $param[1];
            }

            return $parameters;
        }

        private function readBodyParameters() : array {
            $parameters = json_decode(file_get_contents("php://input"), true);

            if ($parameters == null)
                return [];

            return $parameters;
        }
    }
?>
