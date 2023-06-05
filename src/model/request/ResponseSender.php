<?php
    namespace pw2s3\clinicaveterinaria\model\request;

    final class JSONResponseSender {
        public function sendResponse(Response $response) : never {
            http_response_code($response->getCode());
            $this->setHeaders(array_merge(
                [ "status" => $response->getCode() ],
                $response->getBody()
            ));
            exit;
        }

        private function setHeaders() {
            header("Content-Type: application/json");
        }
    }
?>
