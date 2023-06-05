<?php
    namespace pw2s3\clinicaveterinaria\model\services;

    use pw2s3\clinicaveterinaria\model\request\JSONRequestReader;
    use pw2s3\clinicaveterinaria\model\request\Request;

    abstract class ServicesRequestReader {
        private readonly JSONRequestReader $jsonRequestReader;

        public function __construct() {
            $this->jsonRequestReader = new JSONRequestReader();
        }

        public final function readRequest() : Request {
            return $this->upgradeRequest($this->jsonRequestReader->readRequest());
        }

        public final function upgradeRequest(Request $request) : Request {
            $restParameters = $this->readRestParameters();
            $updgradedRequest = new Request($request->getURI(), $request->getMethod());

            foreach($request->getAllParameters() as $paramName => $value)
                $updgradedRequest->addParameter($paramName, $value);

            foreach($restParameters as $paramName => $value)
                $updgradedRequest->addParameter($paramName, $value);

            return $updgradedRequest;
        }

        protected abstract function readRestParameters() : array;
    }
?>
